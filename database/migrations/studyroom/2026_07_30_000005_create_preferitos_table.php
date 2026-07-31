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
        Schema::create('preferiti', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('studente_id')->constrained('studenti')->onDelete('cascade');
            $table->foreignId('materiale_id')->constrained('materiali')->onDelete('cascade');
            
            $table->unique(['materiale_id', 'studente_id'], 'unique_preferito');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preferiti');
    }
};
