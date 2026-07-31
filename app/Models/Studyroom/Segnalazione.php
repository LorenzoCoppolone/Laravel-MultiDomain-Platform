<?php

namespace App\Models\Studyroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Segnalazione extends Model
{
    protected $table = 'segnalazioni';

    protected $fillable = [
        'motivo',
        'segnalante_id',
        'materiale_segnalato_id',
        'amministratore_id',
    ];

    public function segnalante(): BelongsTo
    {
        return $this->belongsTo(Studente::class, 'segnalante_id');
    }

    public function materialeSegnalato(): BelongsTo
    {
        return $this->belongsTo(Materiale::class, 'materiale_segnalato_id');
    }

    public function amministratore(): BelongsTo
    {
        return $this->belongsTo(Amministratore::class, 'amministratore_id');
    }
}