<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model {
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function boot() {
        parent::boot();
        static::creating(fn($m) => $m->id = (string) Str::uuid());
    }

    // Relations pour l'historique
    public function affectations() { return $this->hasMany(Affectation::class)->orderBy('date_debut', 'desc'); }
    public function evolutions() { return $this->hasMany(EvolutionAdministrative::class)->orderBy('date_effet', 'desc'); }

    // Raccourcis pour la situation au jour J
    public function affectationActuelle() { return $this->hasOne(Affectation::class)->where('est_actuelle', true); }
    public function situationActuelle() { return $this->hasOne(EvolutionAdministrative::class)->where('est_actuel', true); }
}
