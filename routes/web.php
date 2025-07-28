<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CitoyenController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\CommentaireReclamationController;
use App\Http\Controllers\InteractionController;



Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication routes
Route::get('/auth', [AuthController::class, 'showAuthForm'])->name('auth.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    // Role-based dashboards
    Route::get('/citoyen/dashboard', [CitoyenController::class, 'index'])->name('citoyen.dashboard');
    Route::get('/agent/dashboard', [AgentController::class, 'index'])->name('agent.dashboard');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard1', function () {
        return view('admin.dashboard1');
    })->name('admin.dashboard1');
    Route::get('/admin/utilisateurs', function () {
        return view('admin.utilisateurs');
    })->name('admin.utilisateurs');
    Route::get('/admin/agents', [UtilisateurController::class, 'agents'])->name('admin.agents');
    Route::get('/agent/coéquipiers', [AgentController::class, 'coéquipiers'])->name('agent.coéquipiers');
    
    // Default dashboard (optional)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    // Agent routes
    Route::get('/agent/reclamations', [AgentController::class, 'reclamations'])->name('agent.reclamations');


    // Citoyen routes
    Route::get('/citoyen/reclamations', [CitoyenController::class, 'reclamations'])->name('citoyen.reclamations');
    Route::get('/citoyen/interactions', [InteractionController::class, 'index'])->name('citoyen.interactions');
    
    // Réclamations routes
    Route::post('/reclamations', [ReclamationController::class, 'store'])->name('reclamations.store');
    Route::get('/dashboard', [ReclamationController::class, 'index'])->name('dashboard');
    Route::delete('/reclamations/{reclamation}', [ReclamationController::class, 'destroy'])->name('reclamations.destroy');
    Route::put('/reclamations/{reclamation}', [ReclamationController::class, 'update'])->name('reclamations.update');
    Route::patch('/reclamations/{reclamation}/status', [ReclamationController::class, 'traitement_agent'])->name('reclamations.traitement');
    Route::post('/reclamations/{reclamation}/feedback', [ReclamationController::class, 'feedback'])->name('reclamations.feedback');

    // Interaction routes
    Route::post('/interactions', [InteractionController::class, 'store'])->name('interactions.store');
    Route::put('/interactions/{id_reclamation}', [InteractionController::class, 'update'])->name('interactions.update');
    Route::delete('/interactions/{id_reclamation}', [InteractionController::class, 'destroy'])->name('interactions.destroy');
    Route::get('/interactions/{id_reclamation}', [InteractionController::class, 'getInteraction'])->name('interactions.get');

    // Admin users management
    Route::prefix('admin')->group(function () {
        Route::resource('utilisateurs', UtilisateurController::class)->names([
            'index' => 'admin.utilisateurs.index',
            'create' => 'admin.utilisateurs.create',
            'store' => 'admin.utilisateurs.store',
            'edit' => 'admin.utilisateurs.edit',
            'update' => 'admin.utilisateurs.update',
            'destroy' => 'admin.utilisateurs.destroy'
        ]);
    });

    // Comments routes
    Route::get('/reclamations/{reclamation}/commentaires', [CommentaireReclamationController::class, 'index'])
        ->name('reclamations.commentaires.index');
    Route::post('/comments', [CommentaireReclamationController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentaireReclamationController::class, 'destroy'])->name('comments.destroy');

    //Mail routes
    Route::get('/test-email', function () {
        \Illuminate\Support\Facades\Mail::raw('Test email from Laravel', function ($message) {
            $message->to('nasseffadhlaoui@gmail.com')->subject('Test Email');
        });
        return 'Test email sent';
    });

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';