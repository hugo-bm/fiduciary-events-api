<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddIndexesToAuditLogsTable
 *
 * Adds performance indexes to audit_logs table.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('created_at', 'idx_audit_logs_created_at');
            $table->index('user_id', 'idx_audit_logs_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex('idx_audit_logs_created_at');
            $table->dropIndex('idx_audit_logs_user_id');
        });
    }
};
