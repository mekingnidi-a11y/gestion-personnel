<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class Agent extends Model {
    use SoftDeletes, HasUuids;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    
    // Liste stricte des colonnes pour éviter les injections de colonnes inexistantes
  protected $fillable = [
    'nom', 'prenom', 'sexe', 'date_naissance', 'lieu_naissance', 
    'nationalite', 'num_recrutement', 'date_recrutement', 'matricule',
    'grade_recrutement', 'categorie_recrutement', 'echelle_recrutement', 
    'echelon_recrutement', 'indice_recrutement', 'diplome_recrutement', 
    'etablissement_recrutement', 'statut',
    // AJOUTEZ CES LIGNES :
    'est_synchronise', 
    'date_synchronisation', 
    'date_premiere_prise_service'
];


    /**
     * RELATION : Historique des évolutions (Grades)
     */
    public function evolutions() { 
        return $this->hasMany(EvolutionAdministrative::class, 'agent_id')->orderBy('date_effet', 'desc'); 
    }

    /**
     * RELATION : Situation administrative actuelle
     */
    public function situationActuelle() { 
        return $this->hasOne(EvolutionAdministrative::class, 'agent_id')->where('est_actuel', 1); 
    }

    /**
     * RELATION : Historique des affectations
     */
    public function affectations() { 
        return $this->hasMany(Affectation::class, 'agent_id'); 
    }

    /**
     * RELATION : Direction actuelle
     */
    public function affectationActuelle() { 
        return $this->hasOne(Affectation::class, 'agent_id')->where('est_actuelle', 1); 
    }

    /**
     * SYNCHRONISATION : Met à jour la table Agent et crée une nouvelle ligne d'évolution
     */
    public function promouvoir(array $data)
    {
        // Clôturer l'ancienne situation
        $this->evolutions()->where('est_actuel', 1)->update(['est_actuel' => 0]);

        // Créer la nouvelle ligne d'historique
        $this->evolutions()->create([
            'grade' => $data['grade'],
            'categorie' => $data['categorie'],
            'ref_acte_evolution' => $data['ref_acte'],
            'date_effet' => $data['date_effet'],
            'est_actuel' => 1,
        ]);

        // Mettre à jour la fiche Agent (Situation de référence)
        return $this->update([
            'grade_recrutement' => $data['grade'],
            'categorie_recrutement' => $data['categorie'],
        ]);
    }

    public function getNomCompletAttribute() {
        return strtoupper($this->nom) . ' ' . ucfirst(strtolower($this->prenom));
    }
    /**
 * RELATION : Un agent possède plusieurs pointages (un par jour)
 */
public function pointages()
{
    return $this->hasMany(Pointage::class, 'agent_id');
}

}
