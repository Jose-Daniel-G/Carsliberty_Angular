<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombres' , 100);
            $table->string('apellidos' , 100);
            $table->integer('cc')->unique();
            $table->string('genero' , 10);
            $table->integer('celular');
            $table->string('direccion' , 150);
            $table->integer('contacto_emergencia')->nullable();
            $table->string('observaciones' , 255)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
