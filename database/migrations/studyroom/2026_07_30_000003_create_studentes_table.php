<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('studenti', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cognome');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('username', 50)->unique();
            $table->boolean('is_banned')->default(false);
            
            // Immagine profilo gestita come BLOB (contenuto + mimetype)
            $table->binary('immagine_profilo')->nullable();
            $table->string('immagine_profilo_mime')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE studenti MODIFY immagine_profilo MEDIUMBLOB");
    }

    public function down(): void
    {
        Schema::dropIfExists('studenti');
    }
};