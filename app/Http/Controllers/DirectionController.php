<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectionController extends Controller
{
    /**
     * Liste des directions : Tout le monde voit tout
     */
    public function index()
    {
        $directions = Direction::with('parent')->orderBy('nom')->get();
        return view('directions.index', compact('directions'));
    }

    /**
     * Formulaire d'ajout : SEUL l'Admin RH
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin_rh') {
            abort(403, "Seul l'Administrateur RH peut créer une direction.");
        }

        $parents = Direction::orderBy('nom')->get();
        return view('directions.create', compact('parents'));
    }

    /**
     * Enregistrement : SEUL l'Admin RH
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin_rh') {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|in:generale,centrale,departementale,rattache_cabinet',
            'code_direction_parent' => 'nullable|exists:directions,code',
            'missions' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_telephone' => 'nullable|string',
        ]);

        Direction::create($validated);

        return redirect()->route('directions.index')->with('success', 'Direction créée avec succès.');
    }

    public function show(Direction $direction)
    {
        $direction->load(['parent', 'enfants']);
        return view('directions.show', compact('direction'));
    }

    /**
     * Modification : Admin RH (tout) ou Admin DG/Dir (uniquement la leur)
     */
    public function edit(Direction $direction)
    {
        $user = Auth::user();

        // RÈGLE : Si pas Admin RH, l'ID de la direction doit être celui de l'utilisateur
        if ($user->role !== 'admin_rh' && $direction->id !== $user->direction_id) {
            abort(403, "Vous ne pouvez modifier que votre propre direction.");
        }

        $parents = Direction::where('id', '!=', $direction->id)->orderBy('nom')->get();
        return view('directions.edit', compact('direction', 'parents'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Direction $direction)
    {
        $user = Auth::user();

        if ($user->role !== 'admin_rh' && $direction->id !== $user->direction_id) {
            abort(403);
        }

        $rules = [
            'nom' => 'required|string|max:255',
            'missions' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_telephone' => 'nullable|string',
        ];

        // Seul l'Admin RH peut changer le type ou le parent d'une direction
        if ($user->role === 'admin_rh') {
            $rules['type'] = 'required|in:generale,centrale,departementale,rattache_cabinet';
            $rules['code_direction_parent'] = 'nullable|exists:directions,code';
        }

        $validated = $request->validate($rules);

        $direction->update($validated);

        return redirect()->route('directions.index')->with('success', 'Direction mise à jour.');
    }

    /**
     * Suppression : Uniquement Admin RH
     */
    public function destroy(Direction $direction)
    {
        if (Auth::user()->role !== 'admin_rh') {
            abort(403, "Action réservée à l'Administrateur RH.");
        }

        if ($direction->enfants()->exists()) {
            return back()->withErrors("Impossible de supprimer : des sous-directions y sont rattachées.");
        }

        $direction->delete();
        return redirect()->route('directions.index')->with('success', 'Direction supprimée.');
    }
}
