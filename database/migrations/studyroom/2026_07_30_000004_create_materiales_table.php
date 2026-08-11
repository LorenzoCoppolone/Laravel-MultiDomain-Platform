<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materiali', function (Blueprint $table) {
           $table->id();
            $table->string('titolo');
            $table->string('tipo'); // Colonna discriminante per il Single Table Inheritance
            
            // File mappato come BLOB (puoi usare longBinary() se i file sono molto grandi)
            $table->binary('file_Contenuto');
            $table->string('file_mimeType');
            
            $table->string('tag')->nullable(); // solo per appunto

            
            // Foreign Keys con eliminazione a cascata
            $table->foreignId('insegnamento_id')->constrained('insegnamenti')->onDelete('cascade');
            $table->foreignId('studente_id')->constrained('studenti')->onDelete('cascade');
            
            $table->timestamps();
        });

        DB::statement("ALTER TABLE materiali MODIFY file_Contenuto MEDIUMBLOB");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiali');
    }
};
