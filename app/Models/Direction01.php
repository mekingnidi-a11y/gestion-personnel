<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Direction extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nom', 'type', 'code_direction_parent', 'missions', 
        'arret_creation', 'contact_email', 'contact_telephone',
        'est_synchronise', 'code_central'
    ];

    // Génération automatique de l'UUID
protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        // 1. Générer l'acronyme du nom actuel
        // "Direction des Systemes" -> DSI
        $words = explode(' ', strtoupper($model->nom));
        $acronym = "";
        foreach ($words as $w) {
            if (strlen($w) > 3) { // On ignore les petits mots comme 'des', 'de', 'le'
                $acronym .= substr($w, 0, 1);
            }
        }
        if(empty($acronym)) $acronym = substr(strtoupper($model->nom), 0, 3);

        // 2. Vérifier s'il y a un parent pour construire le code hiérarchique
        if ($model->code_direction_parent) {
            $parent = self::find($model->code_direction_parent);
            $model->code = $parent->code . '-' . $acronym;
        } else {
            $model->code = $acronym;
        }
    });
}


    /**
     * Relation : Obtenir la direction parente (ex: la DG d'une Direction Centrale)
     */
    public function parent()
    {
        return $this->belongsTo(Direction::class, 'code_direction_parent', 'code');
    }

    /**
     * Relation : Obtenir les sous-directions (ex: les DC d'une Direction Générale)
     */
    public function enfants()
    {
        return $this->hasMany(Direction::class, 'code_direction_parent', 'code');
    }
}
