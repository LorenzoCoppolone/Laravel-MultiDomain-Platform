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
        Schema::create('insegnamenti', function (Blueprint $table) {
            $table->id();
            $table->string('nome_insegnamento');
            
            // Foreign Key personalizzata verso la colonna codice_corso di corso_di_laurea
            $table->string('corso_di_laurea_codice');
            $table->foreign('corso_di_laurea_codice')
                  ->references('codice_corso')
                  ->on('corsidilaurea')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insegnamenti');
    }
};
