<?php

namespace App\Http\Controllers;

use App\Models\Fonction;
use App\Models\Direction;
use Illuminate\Http\Request;

class FonctionController extends Controller
{
    public function index()
    {
        $fonctions = Fonction::with('direction')->orderBy('code_direction')->get();
        return view('parametres.fonctions.index', compact('fonctions'));
    }

    public function create()
    {
        $directions = Direction::orderBy('nom')->get();
        return view('parametres.fonctions.create', compact('directions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code_direction' => 'required|exists:directions,code',
            'intitule' => 'required|string|max:191',
            'niveau_hierarchique' => 'nullable|integer'
        ]);

        Fonction::create([
            'code_direction' => $request->code_direction,
            'intitule' => $request->intitule,
            'est_responsabilite' => $request->has('est_responsabilite') ? 1 : 0,
            'niveau_hierarchique' => $request->niveau_hierarchique ?? 0
        ]);

        return redirect()->route('fonctions.index')->with('success', 'Fonction créée avec succès.');
    }
}
