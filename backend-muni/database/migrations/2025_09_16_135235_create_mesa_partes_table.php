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
        Schema::create('mesa_partes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_registro')->unique();
            $table->foreignId('tipo_documento_id')->nullable()->constrained('tipo_documentos');
            $table->timestamp('fecha_recepcion');
            $table->date('fecha_documento')->nullable();
            $table->text('asunto');
            
            // Información del remitente
            $table->string('remitente_nombre');
            $table->string('remitente_documento')->nullable();
            $table->string('remitente_telefono')->nullable();
            $table->string('remitente_email')->nullable();
            $table->text('remitente_direccion')->nullable();
            $table->string('remitente_institucion')->nullable();
            
            // Gestión del documento
            $table->foreignId('gerencia_destino_id')->nullable()->constrained('gerencias');
            $table->enum('estado', ['recibido', 'en_revision', 'derivado', 'observado', 'rechazado'])->default('recibido');
            $table->boolean('urgente')->default(false);
            $table->text('observaciones')->nullable();
            
            // Auditoría
            $table->foreignId('recepcionado_por')->constrained('users');
            $table->timestamp('fecha_derivacion')->nullable();
            $table->foreignId('derivado_por')->nullable()->constrained('users');
            $table->text('motivo_derivacion')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['estado', 'fecha_recepcion']);
            $table->index(['gerencia_destino_id', 'estado']);
            $table->index(['urgente', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesa_partes');
    }
};
