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

            $table->enum('approval_status', [
                'Awaiting Foreman Review',
                'Foreman Rejected',
                'Awaiting Final Approval',
                'Final Approval Rejected',
                'Approved',
            ])
                ->default('Awaiting Foreman Review')
                ->after('status');

            $table->text('rejection_reason')
                ->nullable()
                ->after('approval_status');

            $table->foreignId('reviewed_by_user_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')
                ->nullable()
                ->after('reviewed_by_user_id');

            $table->foreignId('approved_by_user_id')
                ->nullable()
                ->after('reviewed_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable()
                ->after('approved_by_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_reports', function (Blueprint $table) {

            $table->dropForeign(['reviewed_by_user_id']);
            $table->dropForeign(['approved_by_user_id']);

            $table->dropColumn([
                'approval_status',
                'rejection_reason',
                'reviewed_by_user_id',
                'reviewed_at',
                'approved_by_user_id',
                'approved_at',
            ]);
        });
    }
};
