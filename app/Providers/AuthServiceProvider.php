<?php

namespace App\Providers;

use App\Models\Agent;
use App\Policies\AgentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Le mapping des policies pour l'application.
     */
    protected $policies = [
        Agent::class => AgentPolicy::class,
    ];

    /**
     * Enregistrement des services d'authentification / autorisation.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate pour les accès aux menus "Super Admin" (Directions, Paramètres)
        Gate::define('admin-rh', function ($user) {
            return $user->role === 'admin_rh' && $user->est_valide;
        });

        // Gate pour l'accès aux fonctions administratives (Validation comptes, etc.)
        Gate::define('access-admin-tools', function ($user) {
            return in_array($user->role, ['admin_rh', 'admin_direction_generale', 'admin_direction']) 
                   && $user->est_valide;
        });

        // Logique spécifique pour l'initiation d'une Mutation
        Gate::define('initier-mutation', function ($user, Agent $agent) {
            if ($user->role === 'admin_rh') return true;

            $actuelle = $agent->affectationActuelle;
            if (!$actuelle) return false;

            // Un Admin Direction ne peut muter que SES agents (en interne ou vers l'extérieur)
            return $user->code_direction === $actuelle->code_direction;
        });
    }
}
