<?php

namespace App\Services\WorkReport;

use App\Models\User;
use App\Models\WorkReport;
use Illuminate\Support\Facades\DB;
use App\Services\Notification\NotificationService;
use RuntimeException;

class ApprovalService
{
    private NotificationService $notificationService;

    public function __construct(
        NotificationService $notificationService
    ) {
        $this->notificationService = $notificationService;
    }

    public function approveByForeman(
        WorkReport $workReport,
        User $foreman
    ): WorkReport {
        return DB::transaction(function () use ($workReport, $foreman) {

            // Pastikan report memang masih menunggu review Foreman
            $this->ensureStatus(
                $workReport,
                WorkReport::STATUS_AWAITING_FOREMAN_REVIEW
            );

            $workReport->update([
                'approval_status' => WorkReport::STATUS_AWAITING_FINAL_APPROVAL,
                'reviewed_by_user_id' => $foreman->id,
                'reviewed_at' => now(),
                'rejection_reason' => null,
            ]);

            $receivers = User::whereIn('role', [
                'Supervisor',
                'Dept. Head',
            ])->get();

            foreach ($receivers as $receiver) {

                $this->notificationService->send(
                    $receiver,
                    'Work Report Awaiting Final Approval',
                    "Work Report #{$workReport->id} dari {$workReport->nama} menunggu final approval.",
                    'approval',
                    $workReport,
                    'fas fa-user-check',
                    route('workreport.show', $workReport)
                );
            }

            $this->notificationService->send(
                $workReport->user,
                'Work Report Reviewed',
                'Work Report Anda telah direview oleh Foreman dan menunggu final approval.',
                'info',
                $workReport,
                'fas fa-check-circle',
                route('workreport.show', $workReport)
            );

            return $workReport->fresh();
        });
    }

    public function rejectByForeman(
        WorkReport $workReport,
        User $foreman,
        string $reason
    ): WorkReport {
        return DB::transaction(function () use (
            $workReport,
            $foreman,
            $reason
        ) {

            $this->ensureStatus(
                $workReport,
                WorkReport::STATUS_AWAITING_FOREMAN_REVIEW
            );

            $workReport->update([
                'approval_status' => WorkReport::STATUS_FOREMAN_REJECTED,
                'reviewed_by_user_id' => $foreman->id,
                'reviewed_at' => now(),
                'rejection_reason' => $reason,
            ]);

            $this->notificationService->send(

                $workReport->user,

                'Work Report Rejected',

                "Work Report Anda ditolak oleh Foreman.\n\nSilakan lihat alasan penolakan dan buat Work Report baru.",

                'reject',

                $workReport,

                'fas fa-times-circle',

                route('workreport.show', $workReport)

            );

            return $workReport->fresh();
        });
    }

    public function approveFinal(
        WorkReport $workReport,
        User $approver
    ): WorkReport {
        return DB::transaction(function () use (
            $workReport,
            $approver
        ) {

            $this->ensureStatus(
                $workReport,
                WorkReport::STATUS_AWAITING_FINAL_APPROVAL
            );

            $workReport->update([
                'approval_status' => WorkReport::STATUS_APPROVED,
                'approved_by_user_id' => $approver->id,
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            $this->notificationService->send(

                $workReport->user,

                'Work Report Approved',

                'Selamat! Work Report Anda telah disetujui.',

                'approval',

                $workReport,

                'fas fa-check-circle',

                route('workreport.show', $workReport)

            );

            return $workReport->fresh();
        });
    }

    public function rejectFinal(
        WorkReport $workReport,
        User $approver,
        string $reason
    ): WorkReport {
        return DB::transaction(function () use (
            $workReport,
            $approver,
            $reason
        ) {

            $this->ensureStatus(
                $workReport,
                WorkReport::STATUS_AWAITING_FINAL_APPROVAL
            );

            $workReport->update([
                'approval_status' => WorkReport::STATUS_FINAL_APPROVAL_REJECTED,
                'approved_by_user_id' => $approver->id,
                'approved_at' => now(),
                'rejection_reason' => $reason,
            ]);

            $this->notificationService->send(

                $workReport->user,

                'Work Report Rejected',

                'Work Report Anda ditolak pada tahap final. Silakan lihat alasan penolakan.',

                'reject',

                $workReport,

                'fas fa-times-circle',

                route('workreport.show', $workReport)

            );

            return $workReport->fresh();
        });
    }

    private function ensureStatus(
        WorkReport $workReport,
        string $expectedStatus
    ): void {
        if ($workReport->approval_status !== $expectedStatus) {
            throw new RuntimeException(
                'Report ini sudah diproses oleh pengguna lain.'
            );
        }
    }
}
