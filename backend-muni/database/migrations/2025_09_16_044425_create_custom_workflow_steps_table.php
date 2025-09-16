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
        Schema::create('custom_workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_workflow_id');
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->text('descripcion')->nullable();
            $table->integer('orden')->default(0);
            $table->string('tipo')->default('normal'); // normal, inicio, fin, decision, paralelo
            $table->json('configuracion')->nullable(); // configuración específica del paso
            $table->json('condiciones')->nullable(); // condiciones para ejecutar el paso
            $table->json('acciones')->nullable(); // acciones automáticas al ejecutar
            $table->boolean('requiere_aprobacion')->default(false);
            $table->integer('tiempo_estimado')->nullable(); // en minutos
            $table->string('responsable_tipo')->default('usuario'); // usuario, rol, gerencia
            $table->unsignedBigInteger('responsable_id')->nullable(); // ID del responsable
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('custom_workflow_id')->references('id')->on('custom_workflows')->onDelete('cascade');
            $table->index(['custom_workflow_id', 'orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_workflow_steps');
    }
};
