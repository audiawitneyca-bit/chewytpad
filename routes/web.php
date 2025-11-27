<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // 1. Redirect Cerdas saat Login
    Route::get('/dashboard-check', function () {
        // Kalau Admin, tawarkan mau ke mana, tapi default ke Admin Panel
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    })->name('dashboard.check');

    // UPDATE BAGIAN USER ROUTES JADI SEPERTI INI:
    
    // Dashboard
    Route::get('/dashboard', [NoteController::class, 'index'])->name('dashboard');
    
    // Profil User (BARU)
    Route::get('/profile-settings', [NoteController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile-settings', [NoteController::class, 'updateProfile'])->name('profile.update');

    // Catatan CRUD
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{id}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{id}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');
    
    // Fitur Lain
    Route::post('/notes/{id}/favorite', [NoteController::class, 'toggleFavorite'])->name('notes.favorite');
    Route::get('/trash', [NoteController::class, 'trash'])->name('trash'); // INI RUTE RESTORE/SAMPAH
    Route::post('/trash/{id}/restore', [NoteController::class, 'restore'])->name('notes.restore');
    Route::delete('/trash/{id}/force', [NoteController::class, 'forceDelete'])->name('notes.force');
    Route::get('/notes/{id}/pdf', [NoteController::class, 'exportPdf'])->name('notes.pdf');

    // 3. ADMIN PANEL
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.deleteUser');
        
        // Fitur Baru: Favoritkan User
        Route::post('/users/{id}/favorite', [AdminController::class, 'toggleFavoriteUser'])->name('admin.users.favorite');

        // Rute Sampah User
        Route::get('/users/trash', [AdminController::class, 'trashUsers'])->name('admin.users.trash');
        Route::post('/users/{id}/restore', [AdminController::class, 'restoreUser'])->name('admin.users.restore');
        Route::delete('/users/{id}/force', [AdminController::class, 'forceDeleteUser'])->name('admin.users.force');
    });
    
});

require __DIR__.'/auth.php';