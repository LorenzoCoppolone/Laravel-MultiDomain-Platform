<?php
namespace App\Models\Studyroom;

use App\Enums\studyroom\tag;

class Appunto extends Materiale
{

   protected $fillable = [
        'titolo',
        'tipo',
        'file_MimeType',
        'file_Contenuto',
        'insegnamento_id',
        'studente_id',
        'tag',
    ];

    protected $casts = [
            'file_Contenuto' => 'binary',
            'tag' => tag::class,
        ];
}

