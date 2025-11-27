<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Area Admin!');
        }

        $totalUsers = User::where('role', 'user')->count();
        $totalNotes = Note::count(); 
        
        // 1. AMBIL USER FAVORIT (KESAYANGAN ADMIN)
        $favoriteUsers = User::where('role', 'user')
                             ->where('is_favorite', true)
                             ->withCount('notes')
                             ->get();

        // 2. AMBIL SEMUA USER DIURUTKAN DARI YANG PALING RAJIN (TOP CONTRIBUTORS)
        $users = User::where('role', 'user')
                     ->withCount('notes')
                     ->orderBy('notes_count', 'desc') // Urutkan dari terbanyak
                     ->get();

        $latestActivities = Note::with('user', 'category')->latest()->take(10)->get();

        return view('admin_dashboard', compact('users', 'totalUsers', 'totalNotes', 'latestActivities', 'favoriteUsers'));
    }

    // FITUR BARU: Toggle User Favorit
    public function toggleFavoriteUser($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $user = User::findOrFail($id);
        $user->is_favorite = !$user->is_favorite; // Switch true/false
        $user->save();

        return redirect()->back()->with('success', 'Status favorit user diperbarui! 🌟');
    }

    // ... (FUNGSI KICK, RESTORE, DLL BIARKAN SAMA SEPERTI SEBELUMNYA) ...
    public function destroyUser($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        User::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'User berhasil di-kick!');
    }

    public function trashUsers()
    {
        if (Auth::user()->role !== 'admin') abort(403);
        $deletedUsers = User::onlyTrashed()->get();
        return view('admin_users_trash', compact('deletedUsers'));
    }

    public function restoreUser($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        User::withTrashed()->where('id', $id)->restore();
        return redirect()->back()->with('success', 'User kembali aktif!');
    }

    public function forceDeleteUser($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);
        User::withTrashed()->where('id', $id)->forceDelete();
        return redirect()->back();
    }
}