<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\User;
use App\Models\Direction;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord dynamique selon le rôle.
     * Note : Les filtres de direction sont appliqués automatiquement via les Global Scopes des modèles.
     */
    public function index()
    {
        $user = Auth::user();
        $stats = [];

        // --- CAS 1 : ADMINISTRATEUR RH (Vue Centrale) ---
        if ($user->role === 'admin_rh') {
            // L'Admin RH voit tout car le GlobalScope ne s'applique pas à lui
            $stats['total_agents'] = Agent::count();
            
            $stats['en_attente'] = Agent::whereNull('date_premiere_prise_service')
                ->whereHas('affectationActuelle')
                ->count();

            $stats['sans_matricule'] = Agent::whereNotNull('date_premiere_prise_service')
                ->where(function($q) {
                    $q->whereNull('matricule')->orWhere('matricule', '');
                })->count();

            $stats['total_users'] = User::count();
            $stats['users_en_attente'] = User::where('est_valide', 0)->orWhere('a_demande_reset', 1)->count();
            $stats['total_directions'] = Direction::count();
        } 
        
        // --- CAS 2 : ADMIN DIRECTION OU DIRECTION GÉNÉRALE ---
        else if (in_array($user->role, ['admin_direction', 'admin_direction_generale'])) {
            // IMPORTANT : Ici, Agent::count() ne renverra QUE les agents de sa direction
            // grâce au GlobalScope ajouté dans le modèle Agent.
            
            $stats['total_agents'] = Agent::count();

            // Nouvelles arrivées pour sa direction (filtré automatiquement)
            $stats['en_attente'] = Agent::whereNull('date_premiere_prise_service')
                ->whereHas('affectationActuelle')
                ->count();

            // Ses agents installés sans matricule (filtré automatiquement)
            $stats['sans_matricule'] = Agent::whereNotNull('date_premiere_prise_service')
                ->where(function($q) {
                    $q->whereNull('matricule')->orWhere('matricule', '');
                })->count();

            // Nombre de services (Filtrage manuel car le modèle Service n'a peut-être pas encore de scope)
            $stats['total_services'] = Service::where('code_direction', $user->code_direction)->count();
        }

        // --- CAS 3 : AUTRES RÔLES (Chef de service, Agent) ---
        else {
            // Vue simplifiée
            $stats['total_agents'] = Agent::count(); 
        }

        return view('dashboard', compact('stats'));
    }
}
