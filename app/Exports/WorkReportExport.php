<?php

namespace App\Exports;

use App\Models\WorkReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WorkReportExport implements FromCollection, WithHeadings
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->get()->map(function ($item) {
            return [
                'nama' => $item->nama,
                'nik' => $item->nik,
                'jabatan' => $item->jabatan,
                'tanggal' => $item->tanggal,
                'nomor_unit' => $item->nomor_unit,
                'hm_unit' => $item->hm_unit,
                'component' => $item->component,
                'no_wo' => $item->no_wo,
                'trouble' => $item->trouble,
                'activity' => $item->activity,
                'shift' => ucfirst($item->shift),
                'status' => ucfirst($item->status),
                'jam_mulai' => $item->jam_mulai,
                'jam_berakhir' => $item->jam_berakhir,
                'status_verifikasi' => ucfirst($item->status_verifikasi),
                'continue_note' => $item->continue_note,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIK',
            'Jabatan',
            'Tanggal',
            'Nomor Unit',
            'HM Unit',
            'Component',
            'No WO',
            'Trouble',
            'Activity',
            'Shift',
            'Status',
            'Jam Mulai',
            'Jam Berakhir',
            'Status Verifikasi',
            'Continue Note',
        ];
    }
}
