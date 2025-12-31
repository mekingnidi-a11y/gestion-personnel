<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Carbon\Carbon;

class Pointage extends Model {
    use HasUuids;

    
protected $fillable = [
    'agent_id', 'direction_id', 'date_pointage', 
    'heure_arrivee', 'heure_depart', 'statut', 
    'motif', 'piece_justificative', 'minutes_travaillees', 'est_synchronise'
];


    protected static function boot() {
        parent::boot();
        
        // Calcul automatique de la durée brute avant sauvegarde
        static::saving(function ($pointage) {
            if ($pointage->heure_arrivee && $pointage->heure_depart && $pointage->statut === 'present') {
                $debut = Carbon::parse($pointage->heure_arrivee);
                $fin = Carbon::parse($pointage->heure_depart);
                $pointage->minutes_travaillees = $debut->diffInMinutes($fin);
            } else {
                $pointage->minutes_travaillees = 0;
            }
        });
    }

    public function agent() { return $this->belongsTo(Agent::class); }
}
