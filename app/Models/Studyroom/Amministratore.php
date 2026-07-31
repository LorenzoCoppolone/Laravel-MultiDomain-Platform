<?php

namespace App\Models\Studyroom;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Amministratore extends Authenticatable
{
    use Notifiable;

    // Specifichiamo il nome esatto della tabella
    protected $table = 'amministratori';

    // I campi che puoi riempire (ho aggiunto 'matricola' come esempio)
    protected $fillable = [
        'nome',
        'email',
        'password',
    ];

    // I campi da nascondere (es. quando restituisci JSON)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Laravel 11 consiglia questo approccio per il casting delle password
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}