<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Fonction;
use App\Models\Service;
use App\Models\Affectation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PriseServiceController extends Controller
{
    /**
     * Liste des arrivées (Agents affectés non synchronisés)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        // On récupère les agents non installés localement
        $query = Agent::where('est_synchronise', 0)->with(['affectationActuelle.direction']);

        if ($user->role !== 'admin_rh') {
            $query->whereHas('affectationActuelle', function($q) use ($user) {
                // On filtre par la direction de l'utilisateur connecté
                $q->where('direction_id', $user->direction_id)
                  ->orWhere('code_direction', $user->direction_id);
            });
        }

        $agents = $query->orderBy('created_at', 'desc')->get();
        return view('agents.pending_affectations', compact('agents'));
    }

    /**
     * Formulaire d'installation locale
     */
    public function create(Agent $agent)
    {
        $affectation = $agent->affectationActuelle;

        if (!$affectation) {
            return redirect()->route('agents.index')->with('error', "Aucune affectation trouvée.");
        }

        // On utilise la direction déjà présente dans l'affectation créée lors de l'envoi
        $directionId = $affectation->direction_id ?? $affectation->code_direction;

        $fonctions = Fonction::where('direction_id', $directionId)->get();
        $services = Service::where('direction_id', $directionId)->get();

        return view('agents.prise_service', compact('agent', 'fonctions', 'services'));
    }

    /**
     * Finalisation de l'installation (Mise à jour de l'affectation existante)
     */
    public function store(Request $request, Agent $agent)
    {
        $request->validate([
            'date_premiere_prise_service' => 'required|date',
            'code_fonction' => 'required|exists:fonctions,id',
            'code_service' => 'required|exists:services,id',
            'code_bureau' => 'nullable',
            'matricule' => 'nullable|unique:agents,matricule,' . $agent->id,
        ]);

        try {
            DB::beginTransaction();

            // 1. Mise à jour de l'Agent (Passage en statut actif)
            $updateData = [
                'date_premiere_prise_service' => $request->date_premiere_prise_service,
                'est_synchronise' => 1,
                'date_synchronisation' => now(),
                'statut' => 'actif'
            ];
            
            if ($request->filled('matricule')) { 
                $updateData['matricule'] = $request->matricule; 
            }
            
            $agent->update($updateData);

            // 2. Mise à jour de l'Affectation existante
            $affectation = $agent->affectationActuelle;
            if (!$affectation) {
                throw new \Exception("L'enregistrement d'affectation est introuvable.");
            }

            $fonction = Fonction::findOrFail($request->code_fonction);

            // On complète la ligne existante créée lors de l'affectation initiale
            $affectation->update([
                'direction_id'  => $affectation->code_direction, // Déclenche le mutateur pour synchroniser
                'service_id'    => $request->code_service,
                'code_service'  => $request->code_service, // Pour la cohérence SQL
                'bureau_id'     => $request->code_bureau,
                'code_bureau'   => $request->code_bureau,  // Pour la cohérence SQL
                'code_fonction' => $request->code_fonction,
                'fonction'      => $fonction->intitule,
                'date_debut'    => $request->date_premiere_prise_service,
            ]);



            DB::commit();
            return redirect()->route('agents.index')->with('success', "Installation locale terminée avec succès.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors("Erreur lors de la finalisation : " . $e->getMessage());
        }
    }
}
