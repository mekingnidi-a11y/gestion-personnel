<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Pointage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PointageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $date = $request->get('date', date('Y-m-d'));

        // Liste de TOUS les agents de la direction
        $agents = Agent::where('est_synchronise', 1)
            ->whereHas('affectationActuelle', function($q) use ($user) {
                $q->where('direction_id', $user->direction_id);
            })
            ->with(['pointages' => function($q) use ($date) {
                $q->where('date_pointage', $date);
            }])
            ->orderBy('nom', 'asc')
            ->get();

        return view('pointages.index', compact('agents', 'date'));
    }

    public function storePointage(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:agents,id',
            'date_pointage' => 'required|date',
            'heure_arrivee' => 'nullable',
            'heure_depart' => 'nullable',
        ]);

        $pointage = Pointage::where('agent_id', $request->agent_id)
                            ->where('date_pointage', $request->date_pointage)
                            ->first();

        // Sécurité : Ne pas modifier si déjà existant
        $data = ['direction_id' => auth()->user()->direction_id, 'statut' => 'present'];
        
        if (!$pointage || !$pointage->heure_arrivee) {
            $data['heure_arrivee'] = $request->heure_arrivee;
        }
        if (!$pointage || !$pointage->heure_depart) {
            $data['heure_depart'] = $request->heure_depart;
        }

        Pointage::updateOrCreate(
            ['agent_id' => $request->agent_id, 'date_pointage' => $request->date_pointage],
            $data
        );

        return back()->with('success', 'Pointage enregistré.');
    }


    public function storeAbsence(Request $request)
{
    $request->validate([
        'agent_id' => 'required',
        'statut' => 'required|in:justifie,permission,conge',
        'date_debut' => 'required|date',
        'date_fin' => 'required|date|after_or_equal:date_debut',
        'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // 2Mo max
    ]);

    try {
        DB::beginTransaction();

        $path = null;
        if ($request->hasFile('document')) {
            // Stockage local dans storage/app/public/justificatifs
            $path = $request->file('document')->store('justificatifs', 'public');
        }

        $debut = \Carbon\Carbon::parse($request->date_debut);
        $fin = \Carbon\Carbon::parse($request->date_fin);

        while ($debut->lte($fin)) {
            if (!$debut->isWeekend()) {
                Pointage::updateOrCreate(
                    ['agent_id' => $request->agent_id, 'date_pointage' => $debut->toDateString()],
                    [
                        'direction_id' => auth()->user()->direction_id,
                        'statut' => $request->statut,
                        'motif' => $request->motif,
                        'piece_justificative' => $path, // On enregistre le chemin du fichier
                        'est_synchronise' => 0
                    ]
                );
            }
            $debut->addDay();
        }

        DB::commit();
        return back()->with('success', 'Absence et document enregistrés.');
    } catch (\Exception $e) {
        DB::rollback();
        return back()->withErrors("Erreur : " . $e->getMessage());
    }
}

}
