<?php

namespace App\Models\Studyroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Download extends Model
{
    protected $table = 'downloads';

    protected $fillable = [
        'materiale_id',
        'studente_id',
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
