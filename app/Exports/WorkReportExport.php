<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class WorkReportExport implements
    FromCollection,
    WithHeadings,
    WithEvents,
    WithStyles
{
    protected $query;

    /**
     * Menyimpan range merge setiap Work Report.
     */
    protected array $mergeRanges = [];

    protected array $reportRowRanges = [];

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        $rows = collect();

        $reports = $this->query
            ->with([
                'members',
            ])
            ->get();

        $currentRow = 2;

        foreach ($reports as $report) {

            /*
            |--------------------------------------------------------------------------
            | Semua anggota dalam report
            |--------------------------------------------------------------------------
            */

            $members = $report->members;

            /*
             * Fallback:
             * Kalau karena data lama members kosong,
             * gunakan data utama WorkReport.
             */
            if ($members->isEmpty()) {
                $members = collect([
                    (object) [
                        'nama' => $report->nama,
                        'nik' => $report->nik,
                        'jabatan' => $report->jabatan,
                    ]
                ]);
            }

            $startRow = $currentRow;
            $endRow = $currentRow + $members->count() - 1;

            $this->reportRowRanges[] = [
                'start' => $startRow,
                'end' => $endRow,
            ];


            /*
            |--------------------------------------------------------------------------
            | Data anggota
            |--------------------------------------------------------------------------
            */

            foreach ($members as $index => $member) {

                $rows->push([

                    // A - No WO
                    $index === 0
                        ? $report->no_wo
                        : null,

                    // B - Nama
                    $member->nama,

                    // C - NIK
                    $member->nik,

                    // D - Jabatan
                    $member->jabatan,

                    // E - Tanggal
                    $index === 0 && $report->tanggal
                        ? Date::dateTimeToExcel(
                            \Carbon\Carbon::parse($report->tanggal)
                        )
                        : null,

                    // F - Nomor Unit
                    $index === 0
                        ? $report->nomor_unit
                        : null,

                    // G - HM Unit
                    $index === 0
                        ? $report->hm_unit
                        : null,

                    // H - Component
                    $index === 0
                        ? $report->component
                        : null,

                    // I - Trouble
                    $index === 0
                        ? $report->trouble
                        : null,

                    // J - Activity
                    $index === 0
                        ? $report->activity
                        : null,

                    // K - Shift
                    $index === 0
                        ? ucfirst($report->shift)
                        : null,

                    // L - Status
                    $index === 0
                        ? ucfirst($report->status)
                        : null,

                    // M - Jam Mulai
                    $index === 0
                        ? $this->timeToExcel($report->jam_mulai)
                        : null,

                    // N - Jam Berakhir
                    $index === 0
                        ? $this->timeToExcel($report->jam_berakhir)
                        : null,

                    // O - Continue Note
                    $index === 0
                        ? $report->continue_note
                        : null,

                    // P - Status Verifikasi
                    $index === 0
                        ? $report->approval_status
                        : null,
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Simpan informasi range untuk merge
            |--------------------------------------------------------------------------
            |
            | Hanya kolom D-P yang di-merge.
            |
            | A-C adalah data anggota sehingga TIDAK boleh di-merge.
            |--------------------------------------------------------------------------
            */

            if ($members->count() > 1) {

                foreach ([1, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16] as $columnNumber) {

                    $column = $this->columnLetter($columnNumber);

                    $this->mergeRanges[] =
                        "{$column}{$startRow}:{$column}{$endRow}";
                }
            }


            $currentRow = $endRow + 1;
        }

        return $rows;
    }


    public function headings(): array
    {
        return [
            'No WO',
            'Nama',
            'NIK',
            'Jabatan',
            'Tanggal',
            'Nomor Unit',
            'HM Unit',
            'Component',
            'Trouble',
            'Activity',
            'Shift',
            'Status',
            'Jam Mulai',
            'Jam Berakhir',
            'Continue Note',
            'Status Verifikasi',
        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [

            // Header
            1 => [
                'font' => [
                    'bold' => true,
                ],

                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],

                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'D9E1F2',
                    ],
                ],

                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => [
                            'rgb' => '000000',
                        ],
                    ],
                ],
            ],
        ];
    }


    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();


                /*
                |--------------------------------------------------------------------------
                | Merge kolom report
                |--------------------------------------------------------------------------
                */

                foreach ($this->mergeRanges as $range) {
                    $sheet->mergeCells($range);
                }


                /*
                |--------------------------------------------------------------------------
                | Border seluruh data
                |--------------------------------------------------------------------------
                */

                $highestRow = $sheet->getHighestRow();

                foreach ($this->reportRowRanges as $range) {

                    $this->setReportRowHeight(
                        $sheet,
                        $range['start'],
                        $range['end']
                    );
                }

                $sheet
                    ->getStyle("A1:P{$highestRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);


                /*
                |--------------------------------------------------------------------------
                | Alignment
                |--------------------------------------------------------------------------
                */

                // Data anggota
                $sheet
                    ->getStyle("B2:D{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);


                // Data Work Report
                $sheet
                    ->getStyle("A2:A{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet
                    ->getStyle("E2:P{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);


                /*
                |--------------------------------------------------------------------------
                | Trouble & Activity
                |--------------------------------------------------------------------------
                */

                // Trouble & Activity
                $sheet
                    ->getStyle("I2:J{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setWrapText(true);


                /*
                |--------------------------------------------------------------------------
                | Continue Note
                |--------------------------------------------------------------------------
                */

                // Continue Note
                $sheet
                    ->getStyle("O2:O{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                    ->setWrapText(true);

                /*
                |--------------------------------------------------------------------------
                | Tanggal
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle("E2:E{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode('dd-mm-yyyy');

                /*
                |--------------------------------------------------------------------------
                | Tanggal
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle("M2:N{$highestRow}")
                    ->getNumberFormat()
                    ->setFormatCode('hh:mm:ss');

                /*
                |--------------------------------------------------------------------------
                | Freeze header
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A2');

                $sheet
                    ->getStyle("A2:P{$highestRow}")
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);


                /*
                |--------------------------------------------------------------------------
                | Column width
                |--------------------------------------------------------------------------
                */

                $widths = [

                    'A' => 15, // No WO
                    'B' => 20, // Nama
                    'C' => 15, // NIK
                    'D' => 18, // Jabatan
                    'E' => 13, // Tanggal
                    'F' => 15, // Nomor Unit
                    'G' => 12, // HM Unit
                    'H' => 22, // Component
                    'I' => 45, // Trouble
                    'J' => 45, // Activity
                    'K' => 12, // Shift
                    'L' => 15, // Status
                    'M' => 13, // Jam Mulai
                    'N' => 13, // Jam Berakhir
                    'O' => 35, // Continue Note
                    'P' => 25, // Status Verifikasi

                ];

                foreach ($widths as $column => $width) {
                    $sheet
                        ->getColumnDimension($column)
                        ->setWidth($width);
                }


                /*
                |--------------------------------------------------------------------------
                | Header row height
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(25);
            },
        ];
    }

    private function calculateTextLines($text, $columnWidth): int
    {
        if ($text === null || $text === '') {
            return 1;
        }

        $text = (string) $text;

        $lines = preg_split("/\r\n|\r|\n/", $text);

        $totalLines = 0;

        foreach ($lines as $line) {

            $length = mb_strlen($line);

            if ($length === 0) {
                $totalLines++;
                continue;
            }

            $estimatedLines = (int) ceil(
                $length / max(1, ($columnWidth * 1.15))
            );

            $totalLines += max(1, $estimatedLines);
        }

        return max(1, $totalLines);
    }

    private function setReportRowHeight(
        Worksheet $sheet,
        int $startRow,
        int $endRow
    ): void {

        $rowCount = $endRow - $startRow + 1;

        /*
    |--------------------------------------------------------------------------
    | Ambil isi dari merged cell
    |--------------------------------------------------------------------------
    */

        $texts = [
            'I' => $sheet->getCell("I{$startRow}")->getValue(),
            'J' => $sheet->getCell("J{$startRow}")->getValue(),
            'O' => $sheet->getCell("O{$startRow}")->getValue(),
        ];

        /*
    |--------------------------------------------------------------------------
    | Perkiraan jumlah baris teks
    |--------------------------------------------------------------------------
    */

        $lineCounts = [

            'I' => $this->calculateTextLines(
                $texts['I'],
                45
            ),

            'J' => $this->calculateTextLines(
                $texts['J'],
                45
            ),

            'O' => $this->calculateTextLines(
                $texts['O'],
                35
            ),
        ];

        /*
    |--------------------------------------------------------------------------
    | Ambil jumlah baris terbesar
    |--------------------------------------------------------------------------
    */

        $requiredLines = max(
            $lineCounts['I'],
            $lineCounts['J'],
            $lineCounts['O']
        );

        /*
    |--------------------------------------------------------------------------
    | Tinggi dasar
    |--------------------------------------------------------------------------
    */

        $baseHeight = 20;

        $totalHeight = max(
            $baseHeight,
            $requiredLines * 15
        );

        /*
    |--------------------------------------------------------------------------
    | Karena merged cell mencakup beberapa row,
    | bagi tinggi total ke seluruh row.
    |--------------------------------------------------------------------------
    */

        $heightPerRow = $totalHeight / $rowCount;

        for ($row = $startRow; $row <= $endRow; $row++) {

            $sheet
                ->getRowDimension($row)
                ->setRowHeight($heightPerRow);
        }
    }

    /**
     * Mengubah nomor kolom menjadi huruf Excel.
     *
     * 1  -> A
     * 2  -> B
     * ...
     * 16 -> P
     */
    private function columnLetter(int $columnNumber): string
    {
        $letter = '';

        while ($columnNumber > 0) {

            $remainder = ($columnNumber - 1) % 26;

            $letter = chr(65 + $remainder) . $letter;

            $columnNumber =
                intdiv($columnNumber - 1, 26);
        }

        return $letter;
    }

    // Mengubah format jam menjadi format Excel
    private function timeToExcel($time)
    {
        if (!$time) {
            return null;
        }

        $parts = explode(':', $time);

        if (count($parts) < 2) {
            return null;
        }

        $hours = (int) $parts[0];
        $minutes = (int) $parts[1];
        $seconds = isset($parts[2])
            ? (int) $parts[2]
            : 0;

        return (
            $hours * 3600 +
            $minutes * 60 +
            $seconds
        ) / 86400;
    }
}
