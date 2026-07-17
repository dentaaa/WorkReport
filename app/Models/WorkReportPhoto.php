<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkReportPhoto extends Model
{
    protected $fillable = ['work_report_id', 'file_path'];

    public function workReport()
    {
        return $this->belongsTo(WorkReport::class);
    }
}
