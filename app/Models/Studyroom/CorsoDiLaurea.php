<?php

namespace App\Models\Studyroom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CorsoDiLaurea extends Model
{
    protected $table = 'corsidilaurea';

    protected $primaryKey = 'codice_corso';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'codice_corso',
        'nome_corso',
    ];

    public function insegnamenti(): HasMany
    {
        return $this->hasMany(Insegnamento::class, 'corso_di_laurea_codice', 'codice_corso');
    }
}