<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            // Relaciones Llaves foráneas
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('zone_id')->nullable()->constrained('zones')->onDelete('set null');

            // Contenido del reporte
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('referencia_direccion')->nullable(); // Indicaciones escritas a mano

            // Ubicación geográfica para el mapa
            $table->decimal('latitud', 10, 8);
            $table->decimal('longitud', 11, 8);

            // Estado y seguimiento
            $table->enum('estado', ['pendiente', 'en_revision', 'en_proceso', 'resuelto', 'rechazado'])->default('pendiente');
            $table->text('notas_moderador')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
