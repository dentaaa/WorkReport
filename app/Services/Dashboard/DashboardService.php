<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\WorkReport;

class DashboardService
{
    public function summary(User $user): array
    {
        if ($user->isAdmin()) {
            return $this->adminSummary();
        }

        if ($user->isForeman()) {
            return $this->foremanSummary();
        }

        if ($user->isDeptHead() || $user->isSupervisor()) {
            return $this->deptHeadSummary();
        }

        return $this->mechanicSummary($user);
    }

    public function recentWorkReports(User $user)
    {
        if ($user->isAdmin()) {

            return WorkReport::with('user')
                ->latest()
                ->take(15)
                ->get();
        }

        if ($user->isForeman()) {

            return WorkReport::with('user')->where(
                'approval_status',
                WorkReport::STATUS_AWAITING_FOREMAN_REVIEW
            )
                ->latest()
                ->take(50)
                ->get();
        }

        if ($user->isDeptHead() || $user->isSupervisor()) {

            return WorkReport::with('user')->where(
                'approval_status',
                WorkReport::STATUS_AWAITING_FINAL_APPROVAL
            )
                ->latest()
                ->take(50)
                ->get();
        }

        return WorkReport::with('user')->where(
            'user_id',
            $user->id
        )
            ->latest()
            ->take(10)
            ->get();
    }

    private function adminSummary(): array
    {
        return [

            'totalWorkReports' => WorkReport::count(),

            'totalUsers' => User::count(),

            'pendingReview' => WorkReport::where(
                'approval_status',
                WorkReport::STATUS_AWAITING_FOREMAN_REVIEW
            )->count(),

            'pendingApproval' => WorkReport::where(
                'approval_status',
                WorkReport::STATUS_AWAITING_FINAL_APPROVAL
            )->count(),

            'approved' => WorkReport::where(
                'approval_status',
                WorkReport::STATUS_APPROVED
            )->count(),

            'rejected' => WorkReport::whereIn(
                'approval_status',
                [
                    WorkReport::STATUS_FOREMAN_REJECTED,
                    WorkReport::STATUS_FINAL_APPROVAL_REJECTED
                ]
            )->count(),

        ];
    }

    private function foremanSummary(): array
    {
        return [
            'pendingReview' => WorkReport::where(
                'approval_status',
                WorkReport::STATUS_AWAITING_FOREMAN_REVIEW
            )->count(),

            'approvedToday' => WorkReport::whereDate(
                'reviewed_at',
                today()
            )
                ->where(
                    'approval_status',
                    WorkReport::STATUS_AWAITING_FINAL_APPROVAL
                )
                ->count(),

            'rejectedToday' => WorkReport::whereDate(
                'reviewed_at',
                today()
            )
                ->where(
                    'approval_status',
                    WorkReport::STATUS_FOREMAN_REJECTED
                )
                ->count(),
        ];
    }

    private function deptHeadSummary(): array
    {
        return [
            'pendingApproval' => WorkReport::where(
                'approval_status',
                WorkReport::STATUS_AWAITING_FINAL_APPROVAL
            )->count(),

            'approvedToday' => WorkReport::whereDate(
                'approved_at',
                today()
            )
                ->where(
                    'approval_status',
                    WorkReport::STATUS_APPROVED
                )
                ->count(),

            'rejectedToday' => WorkReport::whereDate(
                'approved_at',
                today()
            )
                ->where(
                    'approval_status',
                    WorkReport::STATUS_FINAL_APPROVAL_REJECTED
                )
                ->count(),
        ];
    }

    private function mechanicSummary(User $user): array
    {
        return [
            'submitted' => WorkReport::where(
                'user_id',
                $user->id
            )->count(),

            'approved' => WorkReport::where(
                'user_id',
                $user->id
            )
                ->where(
                    'approval_status',
                    WorkReport::STATUS_APPROVED
                )
                ->count(),

            'rejected' => WorkReport::where(
                'user_id',
                $user->id
            )
                ->whereIn(
                    'approval_status',
                    [
                        WorkReport::STATUS_FOREMAN_REJECTED,
                        WorkReport::STATUS_FINAL_APPROVAL_REJECTED
                    ]
                )
                ->count(),
        ];
    }
}
