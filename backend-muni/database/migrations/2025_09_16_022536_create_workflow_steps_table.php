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
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gerencia_id')->constrained('gerencias')->comment('Gerencia que define esta etapa');
            $table->string('nombre_etapa')->comment('Nombre de la etapa (ej: Revisión técnica, Aprobación jefe)');
            $table->text('descripcion')->nullable()->comment('Descripción de qué se hace en esta etapa');
            $table->integer('orden')->comment('Orden secuencial de la etapa (1, 2, 3...)');
            $table->string('rol_requerido')->comment('Rol necesario para aprobar esta etapa');
            $table->integer('dias_limite')->default(5)->comment('Días límite para completar esta etapa');
            $table->boolean('es_opcional')->default(false)->comment('Si la etapa es opcional o requerida');
            $table->boolean('requiere_documento')->default(false)->comment('Si requiere subir un documento');
            $table->json('condiciones_aprobacion')->nullable()->comment('Condiciones específicas para aprobar');
            $table->boolean('activa')->default(true)->comment('Si la etapa está activa');
            $table->foreignId('created_by')->nullable()->constrained('users')->comment('Usuario que creó la etapa');
            $table->timestamps();
            
            // Índices
            $table->index(['gerencia_id', 'orden']);
            $table->index(['rol_requerido', 'activa']);
            $table->unique(['gerencia_id', 'orden'], 'unique_gerencia_orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
