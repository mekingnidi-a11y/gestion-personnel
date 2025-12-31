<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Direction;
use App\Models\Affectation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    /**
     * Liste des agents avec recherche et pagination de 50
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Agent::with(['affectationActuelle.direction', 'situationActuelle']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', "%{$search}%")
                  ->orWhere('prenom', 'LIKE', "%{$search}%")
                  ->orWhere('matricule', 'LIKE', "%{$search}%");
            });
        }

        // Pagination fixée à 50 par page pour correspondre à la vue avec scroll
        $agents = $query->orderBy('nom', 'asc')->paginate(50);
        
        // Nécessaire pour la liste déroulante de la modale d'affectation sur l'index
        $directions = Direction::orderBy('nom')->get();

        return view('agents.index', compact('agents', 'search', 'directions'));
    }

    /**
     * Formulaire de création (Réservé Admin RH)
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin_rh') {
            abort(403, "Action réservée à l'Admin RH.");
        }
        $directions = Direction::orderBy('nom')->get();
        return view('agents.create', compact('directions'));
    }

    /**
     * STOCKAGE INITIAL (Enrôlement / Recrutement)
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin_rh') { abort(403); }

        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'num_recrutement' => 'required|string',
            'date_recrutement' => 'required|date',
            'grade_recrutement' => 'required|string',
            'categorie_recrutement' => 'required|string',
            'diplome_recrutement' => 'required|string',
            'etablissement_recrutement' => 'nullable|string',
            
            // Validation si le switch affectation est coché
            'direction_id' => 'required_if:effectuer_affectation,on|nullable|exists:directions,id',
            'ref_acte_affectation' => 'required_if:effectuer_affectation,on|nullable|string',
        ], [
            'direction_id.required_if' => "La direction est obligatoire pour une affectation immédiate.",
            'ref_acte_affectation.required_if' => "La référence de l'acte d'affectation est requise."
        ]);

        try {
            DB::beginTransaction();

            // 1. Création de l'Agent (Table agents)
            $agentData = $request->except(['_token', 'effectuer_affectation', 'direction_id', 'ref_acte_affectation']);
            $agentData['est_synchronise'] = 0;
            $agentData['statut'] = 'actif';
            $agent = Agent::create($agentData);

            // 2. Création de la première ligne d'Historique Administrative
            $agent->evolutions()->create([
                'grade' => $request->grade_recrutement,
                'categorie' => $request->categorie_recrutement,
                'echelle' => $request->echelle_recrutement,
                'echelon' => $request->echelon_recrutement,
                'indice' => $request->indice_recrutement,
                'diplome_actuel' => $request->diplome_recrutement,
                'etablissement_diplome' => $request->etablissement_recrutement,
                'ref_acte_evolution' => $request->num_recrutement,
                'date_effet' => $request->date_recrutement,
                'est_actuel' => 1,
            ]);

            // 3. Affectation immédiate (Si cochée dans la vue create)
            if ($request->has('effectuer_affectation') && $request->filled('direction_id')) {
                Affectation::create([
                    'agent_id'     => $agent->id,
                    'direction_id' => $request->direction_id, 
                    'ref_acte'     => $request->ref_acte_affectation,
                    'date_debut'   => $request->date_recrutement,
                    'est_actuelle' => 1,
                ]);
            }

            DB::commit();
            return redirect()->route('agents.index')->with('success', 'Agent recruté avec succès.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors("Erreur lors de l'enregistrement : " . $e->getMessage());
        }
    }

    /**
     * AFFECTATION ULTERIEURE (Via le bouton + de l'index)
     */
    public function affecter(Request $request, Agent $agent)
    {
        if (Auth::user()->role !== 'admin_rh') { abort(403); }

        $request->validate([
            'direction_id' => 'required|exists:directions,id',
            'ref_acte_affectation' => 'required|string',
            'date_debut' => 'required|date',
        ]);

        try {
            // Le modèle Affectation gère automatiquement la clôture de l'ancienne via static::creating (boot)
            Affectation::create([
                'agent_id'     => $agent->id,
                'direction_id' => $request->direction_id,
                'ref_acte'     => $request->ref_acte_affectation,
                'date_debut'   => $request->date_debut,
                'est_actuelle' => 1,
            ]);

            return redirect()->route('agents.index')->with('success', 'Nouvelle affectation enregistrée.');
        } catch (\Exception $e) {
            return back()->withErrors("Erreur : " . $e->getMessage());
        }
    }

    /**
     * Voir la fiche agent
     */
    public function show(Agent $agent)
    {
        $agent->load(['affectations.direction', 'evolutions', 'situationActuelle', 'affectationActuelle']);
        return view('agents.show', compact('agent'));
    }

    /**
     * Mise à jour du matricule (Après premier salaire)
     */
    public function updateMatricule(Request $request, Agent $agent)
    {
        $request->validate([
            'matricule' => 'required|string|unique:agents,matricule,' . $agent->id,
        ]);

        $agent->update(['matricule' => $request->matricule]);

        return redirect()->route('agents.index')->with('success', 'Matricule attribué avec succès.');
    }


public function edit(Agent $agent)
{
    if (Auth::user()->role !== 'admin_rh') { abort(403); }
    return view('agents.edit', compact('agent'));
}

public function update(Request $request, Agent $agent)
{
    if (Auth::user()->role !== 'admin_rh') { abort(403); }

    $request->validate([
        'nom' => 'required|string|max:100',
        'prenom' => 'required|string|max:100',
        'sexe' => 'required|in:M,F',
        'date_naissance' => 'required|date',
        'num_recrutement' => 'required|string',
        'date_recrutement' => 'required|date',
        'grade_recrutement' => 'required|string',
        'categorie_recrutement' => 'required|string',
        'diplome_recrutement' => 'required|string',
    ]);

    try {
        DB::beginTransaction();

        // 1. Mise à jour des données de base de l'Agent
        $agent->update($request->all());

        // 2. Mise à jour de la ligne d'évolution administrative actuelle (Correction)
        // Comme c'est un "Edit", on modifie la ligne existante au lieu d'en créer une nouvelle
        $agent->evolutions()->where('est_actuel', 1)->update([
            'grade' => $request->grade_recrutement,
            'categorie' => $request->categorie_recrutement,
            'echelle' => $request->echelle_recrutement,
            'echelon' => $request->echelon_recrutement,
            'indice' => $request->indice_recrutement,
            'diplome_actuel' => $request->diplome_recrutement,
            'etablissement_diplome' => $request->etablissement_recrutement,
            'ref_acte_evolution' => $request->num_recrutement,
            'date_effet' => $request->date_recrutement,
        ]);

        DB::commit();
        return redirect()->route('agents.index')->with('success', 'Fiche agent et historique mis à jour.');

    } catch (\Exception $e) {
        DB::rollback();
        return back()->withInput()->withErrors("Erreur de mise à jour : " . $e->getMessage());
    }
}



}
