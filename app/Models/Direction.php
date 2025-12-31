<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Important
use Illuminate\Support\Str;

class Direction extends Model
{
    use HasFactory, HasUuids; // HasUuids génère l'ID automatiquement

    protected $primaryKey = 'id'; // L'ID est maintenant la clé primaire
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'code', 'nom', 'type', 'code_direction_parent', 'missions', 
        'arret_creation', 'contact_email', 'contact_telephone',
        'est_synchronise', 'code_central'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // On garde votre logique de code métier
            $model->generateHierarchicalCode();
        });

        static::updating(function ($model) {
            if ($model->isDirty('code_direction_parent') || $model->isDirty('nom')) {
                $model->generateHierarchicalCode();
            }
        });
    }

    public function generateHierarchicalCode()
    {
        $nomNettoye = str_replace("'", " ", strtoupper($this->nom));
        $words = explode(' ', $nomNettoye);
        $acronym = "";
        $ignore = ['DES', 'DE', 'LA', 'LES', 'DU', 'ET', 'LE', 'AUX'];

        foreach ($words as $w) {
            if (!in_array($w, $ignore) && strlen($w) > 1) {
                $acronym .= substr($w, 0, 1);
            }
        }

        if(empty($acronym)) $acronym = substr(str_replace(' ', '', $nomNettoye), 0, 3);

        // Attention : on cherche toujours par le 'code' pour la hiérarchie métier
        if ($this->code_direction_parent) {
            $parent = self::where('code', $this->code_direction_parent)->first();
            $newCode = $parent ? $parent->code . '-' . $acronym : $acronym;
        } else {
            $newCode = $acronym;
        }

        $count = self::where('code', 'like', $newCode . '%')
                     ->where('id', '!=', $this->id) 
                     ->count();
                     
        $this->code = ($count > 0) ? $newCode . ($count + 1) : $newCode;
    }

    public function parent()
    {
        // La liaison se fait encore via le champ code_direction_parent
        return $this->belongsTo(Direction::class, 'code_direction_parent', 'code');
    }

    public function enfants()
    {
        return $this->hasMany(Direction::class, 'code_direction_parent', 'code');
    }
}
