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
        Schema::create('recensioni', function (Blueprint $table) {
            $table->id();
            $table->float('voto');
            $table->string('commento', 255);
            
            // Foreign keys con onDelete a cascata per il materiale e lo studente
            $table->foreignId('materiale_id')->constrained('materiali')->onDelete('cascade');
            $table->foreignId('studente_id')->constrained('studenti')->onDelete('cascade');
            
            // Vincolo di unicità composto
            $table->unique(['materiale_id', 'studente_id'], 'unique_recensione');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recensioni');
    }
};
