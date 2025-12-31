<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentMatriculeController extends Controller
{
    /**
     * Liste les agents sans matricule selon le périmètre de l'utilisateur
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // On cherche les agents qui n'ont pas encore de matricule
        $query = Agent::whereNull('matricule')
            ->where('est_synchronise', 1) // Uniquement ceux qui ont déjà pris service
            ->with(['affectationActuelle']);

        // Filtrage par périmètre (Logique identique à votre sidebar)
        if ($user->role !== 'admin_rh') {
            $query->whereHas('affectationActuelle', function($q) use ($user) {
                $q->where('direction_id', $user->direction_id)
                  ->orWhere('code_direction', $user->direction_id);
            });
        }

        // Recherche par nom/prénom
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%");
            });
        }

        $agents = $query->orderBy('created_at', 'desc')->get();

        return view('agents.matricule_assignment', compact('agents'));
    }

    /**
     * Enregistre le matricule saisi manuellement
     */
    public function update(Request $request, Agent $agent)
    {
        $request->validate([
            'matricule' => 'required|string|max:20|unique:agents,matricule,' . $agent->id,
        ]);

        // Mise à jour simple (Le modèle gère le UUID en interne pour les liaisons)
        $agent->update([
            'matricule' => $request->matricule
        ]);

        return back()->with('success', "Matricule attribué avec succès à {$agent->nom_complet}.");
    }
}
