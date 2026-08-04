<?php
namespace App\Models\Studyroom;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
abstract class Materiale extends Model
{
    protected $table = 'materiali';

    protected $fillable = [
        'titolo',
        'tipo',
        'file_mimeType',
        'file_Contenuto',
        'insegnamento_id',
        'studente_id',
    ];


    /**
     * Gestione del Single Table Inheritance (STI) in Eloquent.
     */
    protected static function booted()
    {
        static::addGlobalScope('tipo', function ($builder) {
            if (static::class !== self::class) {
                $builder->where('tipo', static::getDiscriminatorMapValue());
            }
        });

        static::creating(function ($model) {
            if (empty($model->tipo)) {
                $model->tipo = static::getDiscriminatorMapValue();
            }
        });
    }

    protected static function getDiscriminatorMapValue(): string
    {
        return match (static::class) {
            Appunto::class => 'appunto',
            Esame::class => 'esame',
            default => 'Materiale',
        };
    }

    // Relazioni OneToMany (con cascade delete)
    public function segnalazioni(): HasMany
    {
        return $this->hasMany(Segnalazione::class, 'materiale_id');
    }

    public function recensioni(): HasMany
    {
        return $this->hasMany(Recensione::class, 'materiale_id');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class, 'materiale_id');
    }

    public function preferiti(): HasMany
    {
        return $this->hasMany(Preferito::class, 'materiale_id');
    }

    // Relazioni ManyToOne
    public function insegnamento(): BelongsTo
    {
        return $this->belongsTo(Insegnamento::class, 'insegnamento_id');
    }

    public function studente(): BelongsTo
    {
        return $this->belongsTo(Studente::class, 'studente_id');
    }


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
}
