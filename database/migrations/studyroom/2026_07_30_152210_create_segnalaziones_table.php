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
        Schema::create('segnalazioni', function (Blueprint $table) {
            $table->id();
            $table->string('motivo');
            
            // Foreign Keys
            $table->foreignId('segnalante_id')->constrained('studenti')->onDelete('cascade');
            $table->foreignId('materiale_segnalato_id')->constrained('materiali')->onDelete('cascade');
            $table->foreignId('amministratore_id')->constrained('amministratori')->onDelete('cascade');
            
            // Vincolo di unicità composto
            $table->unique(['materiale_segnalato_id', 'segnalante_id'], 'unique_segnalazione');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segnalazioni');
    }
};
