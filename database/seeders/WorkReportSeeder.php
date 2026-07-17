<?php

namespace Database\Seeders;

use App\Models\WorkReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkReport::create([
            'nama' => 'Reza Pahlevi',
            'nik' => '42313',
            'jabatan' => 'mekanik',
            'tanggal' => '2026-06-01',
            'nomor_unit' => '43224',
            'hm_unit' => '54364',
            'trouble' => 'Proin laoreet odio porta dapibus aliquam. Nulla elit arcu, ullamcorper quis lorem ut, sagittis viverra ipsum. Curabitur porta enim at lectus blandit vehicula. Proin quis nisl neque. Suspendisse potenti. Cras sed purus posuere, tincidunt nisi ut, lacinia lorem. Etiam dolor eros, vestibulum nec felis sed, mattis faucibus quam. Phasellus rhoncus urna non nibh sodales molestie. Maecenas sagittis dictum nulla ac aliquet. Donec venenatis mi ac vestibulum tempus. Maecenas at neque libero. Proin eu purus condimentum, venenatis nulla non, consequat quam. Vestibulum commodo vitae nunc eget vestibulum. Quisque ut lobortis odio. Ut sed tristique nisi.',
            'activity' => 'Duis imperdiet ipsum erat, ut volutpat diam rutrum at. Phasellus dignissim varius vehicula. Nunc tincidunt, libero ut scelerisque feugiat, eros nisl ornare mauris, eget consectetur felis nisl at velit. Donec sagittis tempor lobortis. Proin et dolor vitae lorem venenatis lacinia ut ac quam. Nunc sapien nulla, consequat id ultricies eget, tempor id libero. Proin eget neque a magna scelerisque ullamcorper at et elit. Nam ut euismod ex.',
            'shift' => 'night',
            'status' => 'ready',
            'jam_mulai' => '13:20:25',
            'jam_berakhir' => '18:24:44',
        ]);
    }
}
