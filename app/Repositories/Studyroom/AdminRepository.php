<?php
namespace App\Repositories\Studyroom;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Repositories\Repository;
use App\Models\Studyroom\Segnalazione;
class AdminRepository extends Repository {

/**
 * Materiali segnalati che recupera l'admin.
 * @return \Illuminate\Contracts\Pagination\Paginator Paginatore di Laravel.
 */
    public static function trovaSegnalazioni(): Paginator 
    {
    // Usiamo DB::table per evitare che il Modello Eloquent vada in panico col groupBy
    return DB::table('segnalazioni')
        // Usiamo la colonna esatta che mi hai mostrato nello screen!
        ->join('materiali', 'segnalazioni.materiale_segnalato_id', '=', 'materiali.id')
        ->select([
            'materiali.id as idMateriale',
            'materiali.titolo as titoloMateriale',
            DB::raw('COUNT(segnalazioni.id) as numeroSegnalazioni')
        ])
        ->groupBy('materiali.id', 'materiali.titolo')
        ->orderByDesc('numeroSegnalazioni')
        ->simplePaginate(10);
    }
}