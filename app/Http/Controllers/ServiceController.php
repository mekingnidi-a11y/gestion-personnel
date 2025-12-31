<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Direction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Liste des services avec filtrage par direction pour les admins locaux
     */
      /**
     * Liste des services : Tout le monde voit tout (2025)
     */
    public function index()
    {
        // On retire le filtre "where direction_id" pour que tout le monde voit tout
        $services = Service::with('direction')->orderBy('nom')->get();
        return view('services.index', compact('services'));
    }


    /**
     * Formulaire de création
     */
    public function create()
    {
        $user = Auth::user();
        
        // RH : toutes les directions | Autres : Uniquement leur direction propre
        $directions = ($user->role === 'admin_rh') 
            ? Direction::orderBy('nom')->get()
            : Direction::where('id', $user->direction_id)->get();

        return view('services.create', compact('directions'));
    }

    /**
     * Enregistrement du nouveau service
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'direction_id' => 'required|exists:directions,id',
            'missions' => 'nullable|string',
        ]);

        // Sécurité : L'admin local ne peut pas tricher sur l'ID de direction
        $finalDirectionId = ($user->role === 'admin_rh') 
            ? $request->direction_id 
            : $user->direction_id;

        // Note : Le code métier (ex: DSIC-ADA) est généré automatiquement par le modèle Service.php
        Service::create([
            'nom' => $request->nom,
            'direction_id' => $finalDirectionId,
            'missions' => $request->missions,
        ]);

        return redirect()->route('services.index')->with('success', 'Service créé avec succès.');
    }

    /**
     * Formulaire d'édition (C'est cette méthode qui manquait)
     */
    public function edit(Service $service)
    {
        $user = Auth::user();

        // Sécurité : Modification interdite si hors périmètre (sauf RH)
        if ($user->role !== 'admin_rh' && $service->direction_id !== $user->direction_id) {
            abort(403, "Vous n'avez pas le droit de modifier ce service.");
        }

        // Pour l'édition, on récupère les directions pour le RH, ou juste la sienne pour l'admin local
        $directions = ($user->role === 'admin_rh') 
            ? Direction::orderBy('nom')->get()
            : Direction::where('id', $user->direction_id)->get();

        return view('services.edit', compact('service', 'directions'));
    }

    /**
     * Mise à jour du service
     */
    public function update(Request $request, Service $service)
    {
        $user = Auth::user();

        if ($user->role !== 'admin_rh' && $service->direction_id !== $user->direction_id) {
            abort(403);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'missions' => 'nullable|string',
        ]);

        // On ne met à jour que le nom et les missions (la direction reste verrouillée)
        $service->update([
            'nom' => $request->nom,
            'missions' => $request->missions,
        ]);

        return redirect()->route('services.index')->with('success', 'Service mis à jour.');
    }

    /**
     * Suppression du service
     */
    public function destroy(Service $service)
    {
        if (Auth::user()->role !== 'admin_rh') {
            abort(403, "Seul l'Administrateur RH peut supprimer un service.");
        }

        // Optionnel : Vérifier si des bureaux sont liés avant suppression
        if ($service->bureaux()->exists()) {
            return back()->with('error', 'Impossible de supprimer : ce service possède des bureaux.');
        }

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service supprimé.');
    }

    /**
     * Affichage des détails
     */
    public function show(Service $service)
    {
        $service->load('direction');
        return view('services.show', compact('service'));
    }
}
