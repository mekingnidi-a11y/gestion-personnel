<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Affectation extends Model
{
    use HasUuids;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

  protected $fillable = [
    'agent_id', 
    'direction_id',
    'code_direction',
    'service_id',     // Requis
    'code_service',   // Requis
    'bureau_id',      // Requis
    'code_bureau',    // Requis
    'code_fonction', 
    'fonction', 
    'ref_acte', 
    'date_debut', 
    'date_fin', 
    'est_actuelle'
];


    /**
     * MUTATEUR : Synchronise automatiquement code_direction avec direction_id
     */
    public function setDirectionIdAttribute($value)
    {
        $this->attributes['direction_id'] = $value;
        $this->attributes['code_direction'] = $value;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($affectation) {
            // Sécurité : assure que code_direction est rempli si direction_id est présent
            if (empty($affectation->code_direction) && !empty($affectation->direction_id)) {
                $affectation->code_direction = $affectation->direction_id;
            }

            // ARCHIVAGE AUTOMATIQUE : On ferme l'ancienne affectation de l'agent
            static::where('agent_id', $affectation->agent_id)
                ->where('est_actuelle', 1)
                ->update([
                    'est_actuelle' => 0,
                    'date_fin' => $affectation->date_debut
                ]);
        });
    }

    public function direction() { return $this->belongsTo(Direction::class, 'direction_id'); }
    public function agent() { return $this->belongsTo(Agent::class, 'agent_id'); }
}
