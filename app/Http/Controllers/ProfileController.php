<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Affiche le formulaire de modification du profil classique.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Met à jour les informations du profil.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * [NOUVEAU 2025] Affiche le formulaire de création de mot de passe (Force Change).
     */
    public function showForcePasswordForm(Request $request): View
    {
        return view('profile.force-password-change', [
            'user' => $request->user(),
        ]);
    }

    /**
     * [NOUVEAU 2025] Enregistre le nouveau mot de passe et active le compte.
     */
public function updatePasswordForce(Request $request): RedirectResponse
{
    $user = $request->user();
    
    // On définit les règles de base
    $rules = [
        'password' => ['required', Password::defaults(), 'confirmed'],
    ];

    // On n'ajoute la vérification du mot de passe actuel QUE si le champ n'est pas vide en base
    if (!is_null($user->password)) {
        $rules['current_password'] = ['required', 'current_password'];
    }

    $validated = $request->validateWithBag('updatePassword', $rules);

    $user->update([
        'password' => Hash::make($validated['password']),
        'doit_changer_password' => 0, // L'utilisateur peut maintenant naviguer normalement
    ]);

    return redirect()->route('dashboard')->with('success', 'Votre mot de passe a été créé avec succès.');
}

    /**
     * Supprime le compte utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
