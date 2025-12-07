<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController; // <--- JANGAN LUPA IMPORT INI

Route::get('/', function () {
    return view('welcome');
});

// Route Lupa Password (Bawaan Breeze sudah aman, tapi jika pakai custom):
Route::get('/lupa-password', [NoteController::class, 'showForgotForm'])->name('password.custom_forgot');
Route::post('/lupa-password', [NoteController::class, 'processForgot'])->name('password.custom_process');
Route::get('/reset-password-baru', [NoteController::class, 'showResetForm'])->name('password.custom_reset');
Route::post('/reset-password-baru', [NoteController::class, 'processReset'])->name('password.custom_update');

Route::middleware(['auth'])->group(function () {

    // 1. Redirect Cerdas saat Login
    Route::get('/dashboard-check', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    })->name('dashboard.check');

    // 2. DASHBOARD & CATATAN
    Route::get('/dashboard', [NoteController::class, 'index'])->name('dashboard');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{id}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{id}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');
    
    // Fitur Tambahan Catatan
    Route::post('/notes/{id}/favorite', [NoteController::class, 'toggleFavorite'])->name('notes.favorite');
    Route::get('/trash', [NoteController::class, 'trash'])->name('trash');
    Route::post('/trash/{id}/restore', [NoteController::class, 'restore'])->name('notes.restore');
    Route::delete('/trash/{id}/force', [NoteController::class, 'forceDelete'])->name('notes.force');
    Route::get('/notes/{id}/pdf', [NoteController::class, 'exportPdf'])->name('notes.pdf');

    // 3. MANAJEMEN KATEGORI (INI YANG BIKIN ERROR SEBELUMNYA)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Sampah Kategori
    Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force', [CategoryController::class, 'forceDelete'])->name('categories.force');

    // 4. PROFIL USER
    Route::get('/profile-settings', [NoteController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile-settings', [NoteController::class, 'updateProfile'])->name('profile.update');

    // 5. ADMIN PANEL
    Route::middleware(['auth'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.deleteUser');
        
        Route::post('/users/{id}/favorite', [AdminController::class, 'toggleFavoriteUser'])->name('admin.users.favorite');

        Route::get('/users/trash', [AdminController::class, 'trashUsers'])->name('admin.users.trash');
        Route::post('/users/{id}/restore', [AdminController::class, 'restoreUser'])->name('admin.users.restore');
        Route::delete('/users/{id}/force', [AdminController::class, 'forceDeleteUser'])->name('admin.users.force');
    });
    
});

require __DIR__.'/auth.php';