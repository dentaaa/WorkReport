<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkReportMember extends Model
{
    protected $fillable = [
        'work_report_id',
        'nama',
        'nik',
        'jabatan'
    ];

    public function workReport()
    {
        return $this->belongsTo(WorkReport::class);
    }
}
