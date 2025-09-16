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
        Schema::create('mesa_partes_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_parte_id')->constrained('mesa_partes')->onDelete('cascade');
            $table->string('nombre_original');
            $table->string('nombre_archivo');
            $table->string('ruta_archivo');
            $table->string('tipo_mime');
            $table->unsignedBigInteger('tamaño');
            $table->text('descripcion')->nullable();
            $table->foreignId('subido_por')->constrained('users');
            $table->timestamps();
            
            $table->index(['mesa_parte_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesa_partes_archivos');
    }
};
