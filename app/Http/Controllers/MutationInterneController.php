<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Affectation;
use App\Models\Fonction;
use App\Models\Service;
use App\Models\Bureau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutationInterneController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Agent::where('est_synchronise', 1)->with(['affectationActuelle.direction']);

        if ($user->role !== 'admin_rh') {
            $query->whereHas('affectationActuelle', function($q) use ($user) {
                $q->where('direction_id', $user->direction_id)
                  ->orWhere('code_direction', $user->direction_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('matricule', 'LIKE', "%{$search}%");
            });
        }

        $agents = $query->orderBy('nom', 'asc')->paginate(15);
        return view('agents.mutation_index', compact('agents'));
    }

    public function create(Agent $agent)
    {
        $affectation = $agent->affectationActuelle;
        if (!$affectation) {
            return redirect()->route('agents.mutation.index')->with('error', "Aucune affectation active.");
        }

        $directionId = $affectation->direction_id ?? $affectation->code_direction;
        $fonctions = Fonction::where('direction_id', $directionId)->get();
        $services = Service::where('direction_id', $directionId)->get();

        return view('agents.mutation_interne', compact('agent', 'fonctions', 'services'));
    }

    public function store(Request $request, Agent $agent)
    {
        // Validation sur les IDs UUID
        $request->validate([
            'ref_acte_mutation' => 'required|string',
            'date_mutation' => 'required|date',
            'code_fonction' => 'required|exists:fonctions,id',
            'code_service' => 'required|exists:services,id',
            'code_bureau' => 'nullable|exists:bureaux,id',
        ]);

        try {
            DB::beginTransaction();

            $ancienne = $agent->affectationActuelle;
            $fonction = Fonction::findOrFail($request->code_fonction);

            // Création de la nouvelle affectation (L'ancienne est fermée via le Boot du modèle)
            Affectation::create([
                'agent_id'      => $agent->id,
                'direction_id'  => $ancienne->direction_id,
                'code_direction'=> $ancienne->code_direction,
                'service_id'    => $request->code_service, // ID UUID
                'code_service'  => $request->code_service, // ID UUID (pour cohérence SQL)
                'bureau_id'     => $request->code_bureau,
                'code_bureau'   => $request->code_bureau,
                'code_fonction' => $request->code_fonction,
                'fonction'      => $fonction->intitule,
                'ref_acte'      => $request->ref_acte_mutation,
                'date_debut'    => $request->date_mutation,
                'est_actuelle'  => 1,
            ]);

            DB::commit();
            return redirect()->route('agents.show', $agent->id)->with('success', "Mutation réussie.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors("Erreur : " . $e->getMessage());
        }
    }
}
