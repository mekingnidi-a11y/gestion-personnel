<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BureauController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\FonctionController;
use App\Http\Controllers\PriseServiceController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// 1. PAGE D'ACCUEIL
Route::get('/', function () {
    return view('welcome');
});

// 2. ROUTES PROTÉGÉES (AUTH)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // DASHBOARD
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // PROFIL UTILISATEUR
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // STRUCTURES (DIRECTIONS, SERVICES, BUREAUX)
    Route::resource('directions', DirectionController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('bureaux', BureauController::class);
    Route::resource('fonctions', FonctionController::class);

    // 3. GESTION DES AGENTS - ROUTES SPÉCIFIQUES (À mettre AVANT le resource agents)
    
    // Attribution de matricule (Page de recherche + Action)
    Route::get('/agents/attribution-matricule', [PriseServiceController::class, 'matriculeIndex'])
        ->name('agents.matricule.index');
    Route::post('/agents/{agent}/attribuer-matricule', [PriseServiceController::class, 'updateMatricule'])
        ->name('agents.matricule.update');

    // Arrivées / Installations (Agents affectés par DGARH en attente)
    Route::get('/affectations/en-attente', [PriseServiceController::class, 'pending'])
        ->name('affectations.en-attente');
    Route::get('/agents/{agent}/prise-service', [PriseServiceController::class, 'create'])
        ->name('agents.prise-service.create');
    Route::post('/agents/{agent}/prise-service', [PriseServiceController::class, 'store'])
        ->name('agents.prise-service.store');

    // Mutations Internes
    Route::get('/agents-actifs/mutation', [PriseServiceController::class, 'mutationIndex'])
        ->name('agents.mutation.index');
    Route::get('/agents/{agent}/muter', [PriseServiceController::class, 'editMutation'])
        ->name('agents.mutation.create');
    Route::post('/agents/{agent}/muter', [PriseServiceController::class, 'storeMutation'])
        ->name('agents.mutation.store');

    // 4. GESTION DES AGENTS - RESSOURCE GÉNÉRALE
    Route::resource('agents', AgentController::class);

    // 5. APIS LOCALES (Pour les formulaires dynamiques)
    Route::get('/api/services/{serviceCode}/bureaux', function($serviceCode) {
        return App\Models\Bureau::where('code_service', $serviceCode)->get();
    });
    
    Route::get('/api/directions/{directionCode}/fonctions', function($directionCode) {
        return App\Models\Fonction::where('code_direction', $directionCode)->get();
    });

    // Gestion des comptes
   // Routes du Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // [CORRECTION] Route pour les utilisateurs en attente (liée au ProfileController)
    Route::get('/users/en-attente', [ProfileController::class, 'editPassword'])
        ->name('profile.force-password');

    Route::post('/users/update-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.force-password.update');
});

// Changement de mot de passe forcé
Route::get('/modifier-mot-de-passe', [ProfileController::class, 'editPassword'])->name('password.force-change');
Route::post('/modifier-mot-de-passe', [ProfileController::class, 'updatePassword'])->name('password.update-forced');





// AUTHENTIFICATION
require __DIR__.'/auth.php';
