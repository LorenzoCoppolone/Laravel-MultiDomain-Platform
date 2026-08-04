<?php
namespace App\Models\Studyroom;

use App\Enums\studyroom\tag;

class Appunto extends Materiale
{

   protected $fillable = [
        'titolo',
        'tipo',
        'file_mimeType',
        'file_Contenuto',
        'insegnamento_id',
        'studente_id',
        'tag',
    ];

    protected $casts = [
            'tag' => tag::class,
        ];
}

