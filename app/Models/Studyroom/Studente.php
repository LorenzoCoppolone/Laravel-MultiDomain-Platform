<?php

namespace App\Models\Studyroom;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\StudyroomVerifyEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\Paginator;

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






/**
 * Trova i preferiti dello studente con paginazione semplice.
 * 
 * @param int $id_studente L'ID dello studente.
 * @return Paginator
 */
    public static function trovaPreferiti(int $id_studente): Paginator
    {
        return DB::table('preferiti AS p')
            ->select([
                'materiale.id AS idMateriale',
                'materiale.titolo AS titoloMateriale',
                'i.nome_insegnamento AS insegnamento', 
                'c.nome_corso AS corso_di_laurea',
                DB::raw('COUNT(DISTINCT d.id) AS numeroDownload'),
                DB::raw('COUNT(DISTINCT r.id) AS numeroRecensioni'),
                DB::raw('AVG(r.voto) AS mediaValutazione'),
                DB::raw("
                    CASE
                        WHEN materiale.tipo = 'appunto' THEN 'APPUNTO'
                        WHEN materiale.tipo = 'esame' THEN 'ESAME'
                        ELSE 'ALTRO'
                    END AS tipologia
                ")
            ])
            ->join('materiali AS materiale', 'p.materiale_id', '=', 'materiale.id')
            ->join('insegnamenti AS i', 'materiale.insegnamento_id', '=', 'i.id')
            ->join('corsidilaurea AS c', 'i.corso_di_laurea_codice', '=', 'c.codice_corso')
            ->leftJoin('downloads AS d', 'materiale.id', '=', 'd.materiale_id')
            ->leftJoin('recensioni AS r', 'materiale.id', '=', 'r.materiale_id')
            ->where('p.studente_id', $id_studente)
            ->groupBy([
                'materiale.id', 
                'materiale.titolo', 
                'materiale.tipo', 
                'i.nome_insegnamento', 
                'c.nome_corso'
        ])->simplePaginate(10);
    }

}