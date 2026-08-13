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
                "),
                'materiale.tag as tag'
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
                'r.commento',
            ])
            // Ci basta solo la join con i materiali per recuperare il titolo
            ->join('materiali AS materiale', 'r.materiale_id', '=', 'materiale.id')
            ->where('r.studente_id', $id_studente)
            // Ordiniamo per ID decrescente in modo da mostrare prima le recensioni più recenti
            ->orderByDesc('r.id') 
            ->simplePaginate(10);
    }

     /**
    * Trova i preferiti dello studente con paginazione semplice.
    * 
    * @param int $id_studente L'ID dello studente.
    * @return Paginator
    */
    public static function trovaDownloads(int $id_studente): Paginator
    {
        return DB::table('downloads AS d')
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
                "),
                'materiale.tag as tag'
            ])
            ->join('materiali AS materiale', 'd.materiale_id', '=', 'materiale.id')
            ->join('insegnamenti AS i', 'materiale.insegnamento_id', '=', 'i.id')
            ->join('corsidilaurea AS c', 'i.corso_di_laurea_codice', '=', 'c.codice_corso')
            ->leftJoin('preferiti AS p', 'materiale.id', '=', 'p.materiale_id')
            ->leftJoin('recensioni AS r', 'materiale.id', '=', 'r.materiale_id')
            ->where('d.studente_id', $id_studente)
            ->groupBy([
                'materiale.id', 
                'materiale.titolo', 
                'materiale.tipo', 
                'i.nome_insegnamento', 
                'c.nome_corso'
        ])->simplePaginate(10);
    }


public static function materialiCaricatiUtente(int $id_studente): Paginator
{
    // Usiamo DB::table (o Materiale::query()) con la logica SQL esplicita
    return DB::table('materiali') 
        ->select([
            'materiali.id as idMateriale',
            'materiali.titolo as titoloMateriale',
            'insegnamenti.nome_insegnamento as insegnamento',
            'corsidilaurea.nome_corso as corso_di_laurea',
            'studenti.username as nome_studente',
            DB::raw("UPPER(materiali.tipo) as tipologia"), 
            DB::raw('COUNT(DISTINCT downloads.id) as numeroDownload'),
            DB::raw('COUNT(DISTINCT recensioni.id) as numeroRecensioni'),
            DB::raw('AVG(recensioni.voto) as mediaValutazione'),
            'materiali.tag as tag'
        ])
        ->join('studenti', 'materiali.studente_id', '=', 'studenti.id')
        ->join('insegnamenti', 'materiali.insegnamento_id', '=', 'insegnamenti.id')
        ->join('corsidilaurea', 'insegnamenti.corso_di_laurea_codice', '=', 'corsidilaurea.codice_corso')
        ->leftJoin('downloads', 'materiali.id', '=', 'downloads.materiale_id')
        ->leftJoin('recensioni', 'materiali.id', '=', 'recensioni.materiale_id')
        ->where('materiali.studente_id', $id_studente)
        ->groupBy(
            'materiali.id', 
            'materiali.titolo', 
            'insegnamenti.nome_insegnamento', 
            'corsidilaurea.nome_corso', 
            'studenti.username',
            'materiali.tipo'
        )
        ->orderByDesc('numeroDownload')
        ->orderByDesc('numeroRecensioni')
        ->simplePaginate(10); 
    }
}