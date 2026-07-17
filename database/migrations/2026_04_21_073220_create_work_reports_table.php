<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_reports', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('nik');
            $table->string('jabatan');
            $table->date('tanggal');
            $table->integer('nomor_unit');
            $table->integer('hm_unit');
            $table->text('trouble');
            $table->text('activity');
            $table->enum('shift', ['day', 'night']);
            $table->enum('status', ['ready', 'continue']);
            $table->time('jam_mulai');
            $table->time('jam_berakhir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_reports');
    }
};
