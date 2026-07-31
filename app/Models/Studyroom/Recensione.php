<?php

namespace App\Models\Studyroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recensione extends Model
{
    protected $table = 'recensioni';

    protected $fillable = [
        'voto',
        'commento',
        'materiale_id',
        'studente_id',
    ];

    protected $casts = [
        'voto' => 'float',
    ];

    public function materiale(): BelongsTo
    {
        return $this->belongsTo(Materiale::class, 'materiale_id');
    }

    public function studente(): BelongsTo
    {
        return $this->belongsTo(Studente::class, 'studente_id');
    }
}