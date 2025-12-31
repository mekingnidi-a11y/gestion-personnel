<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Validation des champs entrants
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['nullable', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])->first();

        // 2. Vérifications de sécurité
        if (!$user) {
            return back()->withErrors(['username' => 'Identifiant inconnu.'])->onlyInput('username');
        }

        if (!$user->est_valide) {
            return back()->withErrors(['username' => 'Votre compte est en attente de validation par un administrateur.'])->onlyInput('username');
        }

        // 3. CAS SPÉCIFIQUE : Pas de mot de passe (Nouveau compte / Reset Admin)
        if (is_null($user->password)) {
            // On connecte l'utilisateur sans vérifier le mot de passe (puisqu'il n'existe pas)
            Auth::login($user); 
            // On le redirige immédiatement vers une page pour qu'il le définisse
            return redirect()->route('profile.force-password.edit')
                ->with('status', 'Veuillez définir votre mot de passe pour finaliser l\'accès.');
        }

        // 4. CAS NORMAL : Tentative de connexion avec mot de passe
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            
            // Si l'admin a forcé le changement (même si un mot de passe existe)
            if (Auth::user()->doit_changer_password) {
                return redirect()->route('profile.force-password.edit');
            }

            return redirect()->intended('/dashboard');
        }

        // 5. Si on arrive ici, le mot de passe est faux
        return back()->withErrors(['password' => 'Mot de passe incorrect.'])->onlyInput('username');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
