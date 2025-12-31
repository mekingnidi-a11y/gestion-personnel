<?php

namespace App\Http\Controllers;

use App\Models\Bureau;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BureauController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Tout le monde voit tout, filtrage visuel dans la vue
        $bureaux = Bureau::with('service.direction')->orderBy('nom')->get();
        return view('bureaux.index', compact('bureaux'));
    }

public function create()
{
    $user = Auth::user();
    
    // On utilise "with('direction')" pour être sûr que les données sont là
    $services = ($user->role === 'admin_rh') 
        ? Service::with('direction')->orderBy('nom')->get() 
        : Service::with('direction')->where('direction_id', $user->direction_id)->get();
        
    return view('bureaux.create', compact('services'));
}

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'nom' => 'required|string|max:255',
            'service_id' => 'required|exists:services,id',
        ]);

        $service = Service::find($request->service_id);
        if ($user->role !== 'admin_rh' && $service->direction_id !== $user->direction_id) {
            abort(403);
        }

        Bureau::create($request->all());
        return redirect()->route('bureaux.index')->with('success', 'Bureau créé.');
    }

    public function show(Bureau $bureau)
    {
        $bureau->load('service.direction');
        return view('bureaux.show', compact('bureau'));
    }

    public function edit(Bureau $bureau)
    {
        $user = Auth::user();
        if ($user->role !== 'admin_rh' && $bureau->service->direction_id !== $user->direction_id) {
            abort(403);
        }

        $services = ($user->role === 'admin_rh') 
            ? Service::all() 
            : Service::where('direction_id', $user->direction_id)->get();

        return view('bureaux.edit', compact('bureau', 'services'));
    }

    public function update(Request $request, Bureau $bureau)
    {
        $user = Auth::user();
        if ($user->role !== 'admin_rh' && $bureau->service->direction_id !== $user->direction_id) {
            abort(403);
        }

        $bureau->update($request->only(['nom', 'localisation', 'capacite']));
        return redirect()->route('bureaux.index')->with('success', 'Bureau mis à jour.');
    }

    public function destroy(Bureau $bureau)
    {
        if (Auth::user()->role !== 'admin_rh') {
            abort(403);
        }
        $bureau->delete();
        return redirect()->route('bureaux.index')->with('success', 'Bureau supprimé.');
    }
}
