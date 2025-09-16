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
        Schema::create('custom_workflow_transitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_workflow_id');
            $table->unsignedBigInteger('from_step_id')->nullable(); // null para paso inicial
            $table->unsignedBigInteger('to_step_id');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('condicion')->nullable(); // condición para la transición
            $table->json('reglas')->nullable(); // reglas específicas de la transición
            $table->boolean('automatica')->default(false); // si se ejecuta automáticamente
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('custom_workflow_id')->references('id')->on('custom_workflows')->onDelete('cascade');
            $table->foreign('from_step_id')->references('id')->on('custom_workflow_steps')->onDelete('cascade');
            $table->foreign('to_step_id')->references('id')->on('custom_workflow_steps')->onDelete('cascade');
            $table->index(['custom_workflow_id', 'from_step_id', 'to_step_id'], 'cwf_transitions_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_workflow_transitions');
    }
};
