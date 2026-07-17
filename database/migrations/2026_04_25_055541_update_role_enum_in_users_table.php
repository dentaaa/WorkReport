<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users
            MODIFY role ENUM(
                'Mekanik',
                'Foreman',
                'Supervisor',
                'Dept. Head',
                'Trainer'
            ) DEFAULT 'Mekanik'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            DB::statement("ALTER TABLE users
            MODIFY role ENUM(
                'pegawai',
                'verifikator',
                'supervisor'
            ) DEFAULT 'pegawai'");
        });
    }
};
