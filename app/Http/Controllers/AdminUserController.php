<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    /**
     * Liste les nouveaux comptes et les demandes de réinitialisation.
     */
   // app/Http/Controllers/AdminUserController.php

public function indexPending(): View
{
    // On récupère TOUS les comptes, classés par les plus récents en premier
    $users = User::with('direction') // Eager loading pour éviter les requêtes inutiles
                 ->orderBy('created_at', 'desc')
                 ->get();
                 
    return view('profile.pending', compact('users'));
}


    /**
     * Affiche le formulaire d'examen avant validation.
     */
    public function editBeforeValidation(string $id): View
    {
        // Correction : On utilise 'id' (UUID)
        $user = User::where('id', $id)->firstOrFail();
        $directions = Direction::orderBy('nom')->get();

        return view('profile.edit-validation', compact('user', 'directions'));
    }

    /**
     * Valide définitivement le compte et assigne le rôle.
     */
    public function confirmValidation(Request $request, string $id): RedirectResponse
    {
        // Correction : On utilise 'id' (UUID)
        $user = User::where('id', $id)->firstOrFail();

        $request->validate([
            'role' => ['required', 'in:admin_rh,admin_direction_generale,admin_direction,chef_service,agent'],
            // Correction de l'exception unique : on ignore l'ID actuel de l'utilisateur
            'username' => ['required', 'string', 'max:191', 'unique:users,username,' . $user->id],
            'direction_id' => ['required', 'exists:directions,id'],
        ]);

        $user->update([
            'username' => $request->username,
            'role' => $request->role,
            'direction_id' => $request->direction_id,
            'est_valide' => true,
            'a_demande_reset' => false,
            'doit_changer_password' => true,
        ]);

        return redirect()->route('users.pending')->with('status', "Le compte de {$user->username} a été activé.");
    }

    /**
     * Traite la notification d'oubli de mot de passe (Action publique).
     */
    public function forgotPasswordRequest(Request $request)
    {
        $request->validate([
            'username' => 'required|string|exists:users,username',
        ]);
        
        $user = User::where('username', $request->username)->first();
        $user->update(['a_demande_reset' => true]);

        return back()->with('status', 'Votre demande a été envoyée à l\'administrateur.');
    }

    /**
     * Réinitialisation forcée par l'administrateur.
     */
    public function resetPassword(string $id): RedirectResponse
    {
        // Correction : On utilise 'id' (UUID)
        $user = User::where('id', $id)->firstOrFail();
        
        $user->update([
            'password' => Hash::make('12345678'),
            'a_demande_reset' => false,
            'doit_changer_password' => true
        ]);

        return redirect()->route('users.pending')->with('status', "Mot de passe réinitialisé pour {$user->username}. Nouveau pass : 12345678");
    }


    // app/Http/Controllers/AdminUserController.php

/**
 * Bloquer ou Débloquer un compte utilisateur.
 */
public function toggleStatus(string $id): RedirectResponse
{
    $user = User::findOrFail($id);
    
    // Si l'utilisateur est valide, on le bloque (0), sinon on le valide (1)
    $user->update([
        'est_valide' => !$user->est_valide
    ]);

    $status = $user->est_valide ? 'activé' : 'bloqué';
    return back()->with('status', "Le compte de {$user->username} a été {$status}.");
}

/**
 * Supprimer définitivement un compte.
 */
public function destroy(string $id): RedirectResponse
{
    $user = User::findOrFail($id);
    
    // Optionnel : Empêcher de se supprimer soi-même
    if ($user->id === auth()->id()) {
        return back()->withErrors(['Erreur' => 'Vous ne pouvez pas supprimer votre propre compte.']);
    }

    $user->delete();

    return redirect()->route('users.pending')->with('status', "Le compte a été supprimé définitivement.");
}

}
