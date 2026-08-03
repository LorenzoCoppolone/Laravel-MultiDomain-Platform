<?php

namespace App\Models\Studyroom;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\StudyroomVerifyEmail;
class Studente extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'studenti';

    protected $fillable = [
        'nome',
        'cognome',
        'email',
        'password',
        'username',
        'immagine_profilo',
        'immagine_profilo_mimeType',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean',
        ];
    }

    public function segnalazioniFatte(): HasMany
    {
        return $this->hasMany(Segnalazione::class, 'segnalante_id');
    }

    public function uploadEffettuati(): HasMany
    {
        return $this->hasMany(Materiale::class, 'studente_id');
    }

    public function downloadEffettuati(): HasMany
    {
        return $this->hasMany(Download::class, 'studente_id');
    }

    public function preferiti(): HasMany
    {
        return $this->hasMany(Preferito::class, 'studente_id');
    }

    public function recensioni(): HasMany
    {
        return $this->hasMany(Recensione::class, 'studente_id');
    }


public function sendEmailVerificationNotification()
{
 $this->notify(new StudyroomVerifyEmail);
}

}