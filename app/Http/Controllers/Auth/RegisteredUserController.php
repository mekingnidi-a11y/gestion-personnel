<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegisteredUserController extends Controller
{
    /**
     * Affiche le formulaire d'inscription.
     */
 // app/Http/Controllers/Auth/RegisteredUserController.php

public function create(): View
{
    // On trie par 'nom' car c'est le nom exact dans votre SQL
    $directions = \App\Models\Direction::orderBy('nom', 'asc')->get(); 
    return view('auth.register', compact('directions'));
}



    /**
     * Gère l'enregistrement de la demande.
     */
   // app/Http/Controllers/Auth/RegisteredUserController.php

// app/Http/Controllers/Auth/RegisteredUserController.php

public function store(Request $request): RedirectResponse
{
    $request->validate([
        'username' => ['required', 'string', 'max:191', 'unique:users'],
        'email' => ['nullable', 'email', 'max:191', 'unique:users'],
        // Correction ici : table 'directions', colonne 'id'
        'direction_id' => ['required', 'string', 'exists:directions,id'],
    ]);

    User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => null, 
        'direction_id' => $request->direction_id, // L'ID (UUID) provenant du select
        'role' => 'agent',
        'est_valide' => false,
        'doit_changer_password' => true,
    ]);

    return redirect()->route('login')->with('status', 'Votre demande d\'accès a été enregistrée. Votre compte est en cours de validation. Veuillez contacter un administrateur RH pour accélérer le traitement.');
}


}
