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
        Schema::table('work_reports', function (Blueprint $table) {

            // 🔥 Ubah tipe data
            $table->string('nomor_unit')->change();

            // 🔥 Tambah kolom baru setelah hm_unit
            $table->string('no_wo')->after('hm_unit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_reports', function (Blueprint $table) {

            // balik ke integer
            $table->integer('nomor_unit')->change();

            // hapus kolom
            $table->dropColumn('no_wo');
        });
    }
};
