<?php
namespace App\Models\Studyroom;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

abstract class Materiale extends Model
{
    protected $table = 'materiali';

    protected $fillable = [
        'titolo',
        'tipo',
        'file_MimeType',
        'file_Contenuto',
        'insegnamento_id',
        'studente_id',
    ];

    protected $casts = [
        'file_Contenuto' => 'binary',
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

}
