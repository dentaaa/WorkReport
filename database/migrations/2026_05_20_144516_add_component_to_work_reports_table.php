<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_reports', function (Blueprint $table) {

            $table->enum('component', [
                'PM Service',
                'TA (Technical Analysis)',
                'Engine',
                'Fuel System',
                'Cooling System',
                'Air Conditioning',
                'Electrical System',
                'Swing',
                'Clutch / Converter',
                'Transmission',
                'Differential',
                'Final Drive',
                'Air & Brake System',
                'Axle',
                'Hydraulic System',
                'Steering System',
                'Suspension',
                'Attachment',
                'Bucket & Linkage',
                'Tyre & Rim',
                'Undercarriage',
                'Cabin',
                'Frame & Structure'
            ])
                ->nullable()
                ->after('hm_unit');
        });
    }

    public function down(): void
    {
        Schema::table('work_reports', function (Blueprint $table) {
            $table->dropColumn('component');
        });
    }
};
