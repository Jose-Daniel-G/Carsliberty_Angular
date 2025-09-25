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
        Schema::create('picoyplaca', function (Blueprint $table) {
            $table->id();
            $table->string('dia'); // Días aplicables
            $table->time('horario_inicio'); // Hora de inicio de la restricción
            $table->time('horario_fin'); // Hora de fin de la restricción
            $table->string('placas_reservadas'); // Placas afectadas por el pico y placa
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picoyplaca');
    }
};
