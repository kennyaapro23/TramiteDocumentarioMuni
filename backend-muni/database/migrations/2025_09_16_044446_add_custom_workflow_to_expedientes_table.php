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
        Schema::table('expedientes', function (Blueprint $table) {
            $table->unsignedBigInteger('custom_workflow_id')->nullable()->after('gerencia_id');
            $table->unsignedBigInteger('current_custom_step_id')->nullable()->after('custom_workflow_id');
            $table->json('custom_workflow_data')->nullable()->after('current_custom_step_id');
            $table->timestamp('custom_step_started_at')->nullable()->after('custom_workflow_data');
            $table->timestamp('custom_step_deadline')->nullable()->after('custom_step_started_at');
            $table->json('custom_step_history')->nullable()->after('custom_step_deadline');

            $table->foreign('custom_workflow_id')->references('id')->on('custom_workflows')->onDelete('set null');
            $table->foreign('current_custom_step_id')->references('id')->on('custom_workflow_steps')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('expedientes', function (Blueprint $table) {
            $table->dropForeign(['custom_workflow_id']);
            $table->dropForeign(['current_custom_step_id']);
            $table->dropColumn(['custom_workflow_id', 'current_custom_step_id', 'custom_workflow_data', 'custom_step_started_at', 'custom_step_deadline', 'custom_step_history']);
        });
    }
};
