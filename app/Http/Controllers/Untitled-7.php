<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AgentController, 
    PriseServiceController, 
    DashboardController, 
    AdminUserController, 
    ProfileController, 
    DirectionController, 
    ServiceController, 
    BureauController, 
    FonctionController
};
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES (GUEST)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => view('auth.login'));
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    
    Route::post('/forgot-password-notify', [AdminUserController::class, 'forgotPasswordRequest'])
        ->name('password.request.admin');
});

/*
|--------------------------------------------------------------------------
| ROUTES PROTÉGÉES (AUTH)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // --- DASHBOARD & LOGOUT ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // --- ARRIVÉES (PRISE DE SERVICE LOCALE) ---
    // Ces routes gèrent la synchronisation entre le RH Central et la Direction locale
    Route::get('/arrivées', [PriseServiceController::class, 'index'])->name('prises-service.index');
    Route::get('/agents/{agent}/installation', [PriseServiceController::class, 'create'])->name('prises-service.create');
    Route::post('/agents/{agent}/installation', [PriseServiceController::class, 'store'])->name('prises-service.store');

    // --- GESTION DU PERSONNEL (AGENTS) ---
    Route::resource('agents', AgentController::class);
    Route::post('/agents/{agent}/affecter', [AgentController::class, 'affecter'])->name('agents.affecter');
    
    // Routes pour les pages spécifiques liées aux agents
    Route::get('/gestion-rh/matricules', [PriseServiceController::class, 'matriculeIndex'])->name('agents.matricule.index');
    Route::post('/agents/{agent}/attribuer-matricule', [AgentController::class, 'updateMatricule'])->name('agents.matricule.update');
    
    Route::get('/gestion-rh/mutations', [PriseServiceController::class, 'mutationIndex'])->name('agents.mutation.index');

    // --- STRUCTURES ADMINISTRATIVES ---
    Route::resource('directions', DirectionController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('bureaux', BureauController::class)->parameters(['bureaux' => 'bureau']);
    Route::resource('fonctions', FonctionController::class);

    // --- ADMINISTRATION DES UTILISATEURS (VALIDATION) ---
    Route::get('/admin/users/en-attente', [AdminUserController::class, 'indexPending'])->name('users.pending');
    Route::patch('/admin/users/{code}/confirm-validation', [AdminUserController::class, 'confirmValidation'])->name('users.confirm-validation');

    // --- PROFIL & SÉCURITÉ ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/force-password-change', [ProfileController::class, 'showForcePasswordForm'])->name('profile.force-password.edit');
    Route::post('/force-password-update', [ProfileController::class, 'updatePasswordForce'])->name('profile.force-password.update');

    // --- APIS DYNAMIQUES (POUR LES FORMULAIRES) ---
    Route::get('/api/services/{serviceCode}/bureaux', fn($code) => \App\Models\Bureau::where('code_service', $code)->get());
    Route::get('/api/directions/{directionCode}/fonctions', fn($code) => \App\Models\Fonction::where('code_direction', $code)->get());
});
