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
            Schema::create('horario_profesor_curso', function (Blueprint $table) {
                $table->id();
                $table->foreignId('horario_id')->constrained()->onDelete('cascade');
                $table->foreignId('curso_id')->constrained()->onDelete('cascade');
                $table->foreignId('profesor_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horario_profesor_curso');
    }
};
