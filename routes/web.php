<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    MessageController, PointageController, AgentController, 
    PriseServiceController, DashboardController, AdminUserController, 
    ProfileController, DirectionController, ServiceController, 
    BureauController, FonctionController, AgentMatriculeController, 
    MutationInterneController
};
use App\Http\Controllers\Auth\{AuthenticatedSessionController, RegisteredUserController};

/* --- ROUTES PUBLIQUES (GUEST) --- */
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => view('auth.login'));
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    // Inscription (Une seule fois suffit)
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    
    Route::post('/forgot-password-notify', [AdminUserController::class, 'forgotPasswordRequest'])->name('password.request.admin');
});

/* --- ROUTES PROTÉGÉES (AUTH) --- */
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // --- ARRIVÉES & PRISE DE SERVICE ---
    Route::get('/arrivées', [PriseServiceController::class, 'index'])->name('prises-service.index');
    Route::get('/agents/{agent}/installation', [PriseServiceController::class, 'create'])->name('agents.prise-service.create');
    Route::post('/agents/{agent}/installation', [PriseServiceController::class, 'store'])->name('agents.prise-service.store');

    // --- GESTION RH : MATRICULES & MUTATIONS ---
    Route::prefix('gestion-rh')->group(function() {
        Route::get('/matricules', [AgentMatriculeController::class, 'index'])->name('agents.matricule.index');
        Route::patch('/agents/{agent}/attributer-matricule', [AgentMatriculeController::class, 'update'])->name('agents.matricule.update');
        Route::get('/mutations', [MutationInterneController::class, 'index'])->name('agents.mutation.index');
        Route::get('/agents/{agent}/mutation', [MutationInterneController::class, 'create'])->name('agents.mutation.create');
        Route::post('/agents/{agent}/mutation', [MutationInterneController::class, 'store'])->name('agents.mutation.store');
    });

    // --- AGENTS & ORGANIGRAMME ---
    Route::resource('agents', AgentController::class);
    Route::post('/agents/{agent}/affecter', [AgentController::class, 'affecter'])->name('agents.affecter');
    Route::resource('directions', DirectionController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('bureaux', BureauController::class)->parameters(['bureaux' => 'bureau']);
    Route::resource('fonctions', FonctionController::class);

    // --- ADMINISTRATION UTILISATEURS (VALIDATION) ---
    // Note : On utilise {user} (UUID) au lieu de {code}
    Route::get('/admin/users/en-attente', [AdminUserController::class, 'indexPending'])->name('users.pending');
    Route::get('/admin/users/{user}/edit-validation', [AdminUserController::class, 'editBeforeValidation'])->name('users.edit-validation');
    Route::patch('/admin/users/{user}/confirm-validation', [AdminUserController::class, 'confirmValidation'])->name('users.confirm-validation');
    Route::post('/admin/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

    // --- PROFIL & SÉCURITÉ ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/force-password-change', [ProfileController::class, 'showForcePasswordForm'])->name('profile.force-password.edit');
    Route::post('/force-password-update', [ProfileController::class, 'updatePasswordForce'])->name('profile.force-password.update');

    // --- POINTAGES ---
    Route::prefix('pointages')->group(function() {
        Route::get('/', [PointageController::class, 'index'])->name('pointages.index');
        Route::post('/store', [PointageController::class, 'storePointage'])->name('pointages.store');
        Route::post('/store-bulk', [PointageController::class, 'storeBulk'])->name('pointages.store-bulk');
        Route::post('/absences', [PointageController::class, 'storeAbsence'])->name('pointages.absences.store');
        Route::get('/rapports', [PointageController::class, 'rapports'])->name('pointages.rapports');
    });

    // --- MESSAGERIE ---
    Route::prefix('messagerie')->group(function() {
        Route::get('/', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/discussion/{user}', [MessageController::class, 'show'])->name('messages.show');
        Route::post('/envoyer', [MessageController::class, 'store'])->name('messages.store');
        Route::get('/download/{message}', [MessageController::class, 'download'])->name('messages.download');
    });

    // --- APIS DYNAMIQUES (JS) ---
    Route::get('/api/pointages/search-agent', [PointageController::class, 'searchAgent']);
    Route::get('/api/services/{serviceId}/bureaux', function($serviceId) {
        return \App\Models\Bureau::where('service_id', $serviceId)->get();
    });


// Administration des comptes
Route::patch('/admin/users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');
Route::delete('/admin/users/{user}/delete', [AdminUserController::class, 'destroy'])->name('users.delete');


});
