<?php
namespace App\Repositories\Studyroom;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Repositories\Repository;

class StudenteRepository extends Repository {



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

    /**
     * Trova le recensioni scritte dallo studente con paginazione semplice.
     * 
     * @param int $id_studente L'ID dello studente.
     * @return Paginator
     */
    public static function trovaRecensioni(int $id_studente): Paginator
    {
        return DB::table('recensioni AS r')
            ->select([
                'r.id AS idRecensione',
                'materiale.id AS idMateriale',
                'materiale.titolo AS titoloMateriale',
                'r.voto',
                'r.commento'
            ])
            // Ci basta solo la join con i materiali per recuperare il titolo
            ->join('materiali AS materiale', 'r.materiale_id', '=', 'materiale.id')
            ->where('r.studente_id', $id_studente)
            // Ordiniamo per ID decrescente in modo da mostrare prima le recensioni più recenti
            ->orderByDesc('r.id') 
            ->simplePaginate(10);
    }

}