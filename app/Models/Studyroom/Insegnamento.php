<?php

namespace App\Models\Studyroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insegnamento extends Model
{
    protected $table = 'insegnamenti';

    protected $fillable = [
        'nome_insegnamento',
        'corso_di_laurea_codice',
    ];

    public function materiali(): HasMany
    {
        return $this->hasMany(Materiale::class, 'insegnamento_id');
    }

    public function corsoDiLaurea(): BelongsTo
    {
        return $this->belongsTo(CorsoDiLaurea::class, 'corso_di_laurea_codice', 'codice_corso');
    }
}