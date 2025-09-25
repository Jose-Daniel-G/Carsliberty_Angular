<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->string('dia');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->unsignedBigInteger('profesor_id');
            // $table->unsignedBigInteger('curso_id');
        
            // Si las tablas y campos existen tal como los mencionas
            $table->foreign('profesor_id')->references('id')->on('profesors')->onDelete('cascade');            
            // $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');           
            
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
