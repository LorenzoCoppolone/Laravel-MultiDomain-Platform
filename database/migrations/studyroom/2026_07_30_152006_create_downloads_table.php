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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys con onDelete a cascata per il materiale
            $table->foreignId('materiale_id')->constrained('materiali')->onDelete('cascade');
            $table->foreignId('studente_id')->constrained('studenti')->onDelete('cascade');
            
            // Vincolo di unicità composto
            $table->unique(['materiale_id', 'studente_id'], 'unique_download');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
