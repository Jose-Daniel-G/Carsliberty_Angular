<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();  // Clave primaria

            $table->unsignedBigInteger('cliente_id');  // Columna para la clave foránea de 'clientes'
            $table->unsignedBigInteger('agenda_id');  // Columna para la clave foránea de 'eventos'

            $table->boolean('asistio')->default(true);  // Indicar asistencia
            $table->integer('penalidad')->default(0);  // Penalidad por inasistencia
            $table->boolean('liquidado')->default(false);  // Indicar si la multa ha sido pagada
            $table->timestamp('fecha_pago_multa')->nullable();  // Fecha en la que se pagó la multa
            
            $table->timestamps();  // Timestamps

            // Definir las claves foráneas
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('agenda_id')->references('id')->on('agendas')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
