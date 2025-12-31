<?php

namespace App\Policies;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgentPolicy
{
    use HandlesAuthorization;

    /**
     * Lecture : Qui peut voir la fiche détaillée de l'agent
     */
    public function view(User $user, Agent $agent)
    {
        if ($user->role === 'admin_rh') return true;

        // On utilise le perimetre_codes que nous avons ajouté au modèle User
        $mesCodesAccessibles = $user->perimetre_codes;
        
        return $agent->affectations()
            ->whereIn('code_direction', $mesCodesAccessibles)
            ->exists();
    }

    /**
     * Modification : L'Admin DG ne peut modifier QUE si l'agent est 
     * directement dans sa DG (pas dans les directions centrales dépendantes)
     */
    public function update(User $user, Agent $agent)
    {
        if ($user->role === 'admin_rh') return true;

        $actuelle = $agent->affectationActuelle;
        if (!$actuelle) return false;

        // Règle stricte : Le code_direction de l'user doit être IDENTIQUE 
        // à celui de l'affectation de l'agent pour avoir le droit d'écriture
        return $user->code_direction === $actuelle->code_direction;
    }

    /**
     * Suppression (uniquement Admin RH selon les standards ministériels)
     */
    public function delete(User $user, Agent $agent)
    {
        return $user->role === 'admin_rh';
    }
}
