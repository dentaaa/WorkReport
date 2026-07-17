<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class WorkReport extends Model
{
    public const STATUS_AWAITING_FOREMAN_REVIEW = 'Awaiting Foreman Review';

    public const STATUS_FOREMAN_REJECTED = 'Foreman Rejected';

    public const STATUS_AWAITING_FINAL_APPROVAL = 'Awaiting Final Approval';

    public const STATUS_FINAL_APPROVAL_REJECTED = 'Final Approval Rejected';

    public const STATUS_APPROVED = 'Approved';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public static function statusMeta(string $status): array
    {
        return match ($status) {

            self::STATUS_AWAITING_FOREMAN_REVIEW => [
                'label' => 'Awaiting Foreman Review',
                'class' => 'bg-warning',
                'icon'  => 'fas fa-clock',
            ],

            self::STATUS_AWAITING_FINAL_APPROVAL => [
                'label' => 'Awaiting Final Approval',
                'class' => 'bg-info',
                'icon'  => 'fas fa-user-check',
            ],

            self::STATUS_APPROVED => [
                'label' => 'Approved',
                'class' => 'bg-success',
                'icon'  => 'fas fa-check-circle',
            ],

            self::STATUS_FOREMAN_REJECTED => [
                'label' => 'Foreman Rejected',
                'class' => 'bg-danger',
                'icon'  => 'fas fa-times-circle',
            ],

            self::STATUS_FINAL_APPROVAL_REJECTED => [
                'label' => 'Final Rejected',
                'class' => 'bg-danger',
                'icon'  => 'fas fa-ban',
            ],

            default => [
                'label' => $status,
                'class' => 'bg-secondary',
                'icon'  => 'fas fa-question-circle',
            ],
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusMeta($this->approval_status)['label'];
    }

    public function getStatusClassAttribute(): string
    {
        return self::statusMeta($this->approval_status)['class'];
    }

    public function getStatusIconAttribute(): string
    {
        return self::statusMeta($this->approval_status)['icon'];
    }

    public function photos()
    {
        return $this->hasMany(WorkReportPhoto::class);
    }

    public function members()
    {
        return $this->hasMany(WorkReportMember::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function reviewer()
    // {
    //     return $this->belongsTo(User::class, 'reviewed_by_user_id');
    // }

    // public function approver()
    // {
    //     return $this->belongsTo(User::class, 'approved_by_user_id');
    // }

    public function reviewer()
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by_user_id'
        );
    }

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by_user_id'
        );
    }
}
