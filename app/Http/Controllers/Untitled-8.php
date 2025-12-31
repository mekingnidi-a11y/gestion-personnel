<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Pointage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PointageController extends Controller
{
    /**
     * Interface de saisie quotidienne (Feuille d'appel)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $date = $request->get('date', date('Y-m-d'));

        // 1. On récupère les agents du périmètre
        $query = Agent::where('est_synchronise', 1)
            ->with(['affectationActuelle', 'pointages' => function($q) use ($date) {
                $q->where('date_pointage', $date);
            }]);

        if ($user->role !== 'admin_rh') {
            $query->whereHas('affectationActuelle', function($q) use ($user) {
                $q->where('direction_id', $user->direction_id);
            });
        }

        $agents = $query->orderBy('nom')->get();

        return view('pointages.index', compact('agents', 'date'));
    }

    /**
     * Enregistrement en masse des pointages
     */
    public function storeBulk(Request $request)
    {
        $date = $request->date_pointage;
        $user = Auth::user();

        try {
            DB::beginTransaction();

            foreach ($request->pointages as $agentId => $data) {
                // On met à jour ou on crée le pointage pour cet agent à cette date
                Pointage::updateOrCreate(
                    [
                        'agent_id' => $agentId,
                        'date_pointage' => $date,
                    ],
                    [
                        'direction_id'  => $user->direction_id, // Ou celle de l'agent
                        'heure_arrivee' => $data['statut'] === 'present' ? $data['arrivee'] : null,
                        'heure_depart'  => $data['statut'] === 'present' ? $data['depart'] : null,
                        'statut'        => $data['statut'],
                        'motif'         => $data['motif'] ?? null,
                        'est_synchronise' => 0, // Prêt pour synchro future
                    ]
                );
            }

            DB::commit();
            return back()->with('success', "Pointages du " . Carbon::parse($date)->format('d/m/Y') . " enregistrés.");

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors("Erreur lors de l'enregistrement : " . $e->getMessage());
        }
    }

    /**
     * Rapports et Statistiques
     */
    public function rapports(Request $request)
    {
        $user = Auth::user();
        $debut = $request->get('debut', date('Y-m-01'));
        $fin = $request->get('fin', date('Y-m-t'));

        $stats = Pointage::whereBetween('date_pointage', [$debut, $fin])
            ->whereHas('agent.affectationActuelle', function($q) use ($user) {
                if($user->role !== 'admin_rh') $q->where('direction_id', $user->direction_id);
            })
            ->select('statut', DB::raw('count(*) as total'))
            ->groupBy('statut')
            ->get();

        return view('pointages.rapports', compact('stats', 'debut', 'fin'));
    }
}
