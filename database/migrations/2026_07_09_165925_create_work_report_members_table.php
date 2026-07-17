<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_report_members', function (Blueprint $table) {

            $table->id();

            $table->foreignId('work_report_id')
                ->constrained('work_reports')
                ->cascadeOnDelete();

            $table->string('nama');

            $table->unsignedInteger('nik');

            $table->string('jabatan');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_report_members');
    }
};
