<?php
namespace App\Repositories\Studyroom;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Repositories\Repository;

class MaterialeRepository extends Repository {

    // query per i materiali

    /**
     * Restituisce i materiali popolari ordinati per valutazione, recensioni e download,
     * gestendo la paginazione in automatico.
     */
    public static function trovaMaterialiPopolari(): Paginator
    {
    return DB::table('materiali AS materiale')
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
        ->leftJoin('downloads AS d', 'materiale.id', '=', 'd.materiale_id')
        ->leftJoin('recensioni AS r', 'materiale.id', '=', 'r.materiale_id')
        ->join('insegnamenti AS i', 'materiale.insegnamento_id', '=', 'i.id')
        ->join('corsidilaurea AS c', 'i.corso_di_laurea_codice', '=', 'c.codice_corso')
        ->groupBy([
            'materiale.id',
            'materiale.titolo',
            'materiale.tipo',
            'i.nome_insegnamento',
            'c.nome_corso',
        ])
        ->orderByDesc('mediaValutazione')
        ->orderByDesc('numeroRecensioni')
        ->orderByDesc('numeroDownload')
        ->simplePaginate(10);
    }

public static function ricercaFiltrata(array $filtri = []): Paginator
{
    $query = DB::table('materiali AS materiale')
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
        ->leftJoin('downloads AS d', 'materiale.id', '=', 'd.materiale_id')
        ->leftJoin('recensioni AS r', 'materiale.id', '=', 'r.materiale_id')
        ->join('insegnamenti AS i', 'materiale.insegnamento_id', '=', 'i.id')
        ->join('corsidilaurea AS c', 'i.corso_di_laurea_codice', '=', 'c.codice_corso')
        ->groupBy([
            'materiale.id', 
            'materiale.titolo', 
            'materiale.tipo', 
            'i.nome_insegnamento', 
            'c.nome_corso'
        ]);

    /* =========================================
       FILTRI DINAMICI
       ========================================= */
    $query->when(!empty($filtri['titolo']), function ($q) use ($filtri) {
        $q->where('materiale.titolo', 'LIKE', '%' . $filtri['titolo'] . '%');
    });

    $query->when(!empty($filtri['cdl']), function ($q) use ($filtri) {
        $q->where('i.corso_di_laurea_codice', $filtri['cdl']);
    });

    $query->when(!empty($filtri['insegnamento']), function ($q) use ($filtri) {
        $q->where('i.id', $filtri['insegnamento']);
    });

    $query->when(!empty($filtri['tipologia']), function ($q) use ($filtri) {
        $q->where('materiale.tipo', $filtri['tipologia']);
    });

    /* =========================================
       ORDINAMENTO DINAMICO
       ========================================= */
    if (!empty($filtri['criterio'])) {
        if ($filtri['criterio'] === 'download') {
            $query->orderByDesc('numeroDownload')->orderByDesc('mediaValutazione');
        } elseif ($filtri['criterio'] === 'valutazione') {
            $query->orderByDesc('mediaValutazione')->orderByDesc('numeroDownload');
        }
    } else {
        $query->orderByDesc('mediaValutazione')
              ->orderByDesc('numeroRecensioni')
              ->orderByDesc('numeroDownload');
    }

    return $query->simplePaginate(10);
    }

    /**
     * Recupera i dettagli completi di un singolo materiale.
     * Restituisce un oggetto con tutte le proprietà necessarie alla vista o null se non trovato.
     */
    public static function dettagliMateriale(int $id)
    {
        return DB::table('materiali AS materiale')
            ->select([
                'materiale.id AS idMateriale',
                'materiale.titolo AS titoloMateriale',
                'i.nome_insegnamento AS insegnamento', 
                'c.nome_corso AS corso_di_laurea',
                's.username AS nome_studente',
                'p.id AS preferito', // Per verificare se è nei preferiti    
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
            // Join base per corso e insegnamento
            ->join('insegnamenti AS i', 'materiale.insegnamento_id', '=', 'i.id')
            ->join('corsidilaurea AS c', 'i.corso_di_laurea_codice', '=', 'c.codice_corso')
            
            // Join con lo studente per prendere l'autore (adatta 'studente_email' con la tua chiave esterna reale)
            ->join('studenti AS s', 'materiale.studente_id', '=', 's.id') 
            
            // Left Join per le statistiche (possono essere 0, per questo usiamo LEFT)
            ->leftJoin('downloads AS d', 'materiale.id', '=', 'd.materiale_id')
            ->leftJoin('recensioni AS r', 'materiale.id', '=', 'r.materiale_id')
            ->leftJoin('preferiti AS p', 'materiale.id', '=', 'p.materiale_id')
            // Filtro per ID
            ->where('materiale.id', $id)
            
            ->groupBy([
                'materiale.id', 
                'materiale.titolo', 
                'materiale.tipo', 
                'i.nome_insegnamento', 
                'c.nome_corso',
                's.username', 
                'p.id' 
            ])
            ->first(); // <-- Estrae un singolo record
    }
}