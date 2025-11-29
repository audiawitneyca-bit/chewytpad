<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Category;
use App\Models\SearchHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF; 

class NoteController extends Controller
{
    // 1. Dashboard & Search
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $query = Note::where('user_id', $user_id);

        // Logika Pencarian
        if ($request->has('search') && $request->search != null) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                  ->orWhere('content', 'like', '%'.$keyword.'%')
                  ->orWhere('image_caption', 'like', '%'.$keyword.'%') // Cari di caption juga
                  ->orWhereHas('category', function($c) use ($keyword){
                      $c->where('name', 'like', '%'.$keyword.'%');
                  });
            });
            SearchHistory::create(['user_id' => $user_id, 'keyword' => $keyword]);
        }

        // Filter Kategori
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Filter Favorit
        if ($request->has('filter_favorite') && $request->filter_favorite == 'true') {
            $query->where('is_favorite', true);
        }

        // Filter Tanggal
        if ($request->has('date_filter') && $request->date_filter != '') {
            $query->whereDate('created_at', $request->date_filter);
        }

        $notes = $query->latest()->get();
        $categories = Category::where('user_id', $user_id)->get();
        
        // Riwayat Pencarian
        $recentSearches = SearchHistory::where('user_id', $user_id)
                            ->latest()
                            ->take(5)
                            ->get()
                            ->unique('keyword');

        return view('dashboard', compact('notes', 'categories', 'recentSearches'));
    }

    // 2. Simpan Catatan (DENGAN GAMBAR & CAPTION)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi Gambar
            'image_caption' => 'nullable|string|max:255', // Validasi Caption
        ]);

        // Cek Kategori Baru
        if($request->filled('new_category_name')) {
             $color = $request->category_color ?? '#FFD9F8'; 
             $category = Category::create([
                 'user_id' => Auth::id(),
                 'name' => $request->new_category_name,
                 'slug' => \Str::slug($request->new_category_name),
                 'color' => $color
             ]);
             $cat_id = $category->id;
        } else {
            $cat_id = $request->category_id;
        }

        if(!$cat_id) {
            return redirect()->back()->with('error', 'Pilih kategori dulu bestie!');
        }

        // Upload Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('notes-images', 'public');
        }

        Note::create([
            'user_id' => Auth::id(),
            'category_id' => $cat_id,
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'image_caption' => $request->image_caption,
            'is_favorite' => $request->has('is_favorite')
        ]);

        return redirect()->back()->with('success', 'Catatan berhasil disimpan!');
    }

    // Halaman Edit
    public function edit($id)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::where('user_id', Auth::id())->get();
        return view('edit', compact('note', 'categories'));
    }

    // 3. Update Catatan
    public function update(Request $request, $id)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_caption' => 'nullable|string|max:255',
        ]);

        // Cek jika ada gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($note->image) {
                Storage::disk('public')->delete($note->image);
            }
            // Upload baru
            $imagePath = $request->file('image')->store('notes-images', 'public');
            $note->image = $imagePath;
        }

        $note->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image_caption' => $request->image_caption, // Update caption
        ]);

        return redirect()->route('dashboard')->with('success', 'Catatan berhasil diperbarui! ✨');
    }

    // 4. Hapus (Soft Delete)
    public function destroy($id)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        $note->delete(); 
        return redirect()->back()->with('success', 'Catatan dibuang ke tong sampah.');
    }

    // 5. Halaman Sampah
    public function trash()
    {
        $deletedNotes = Note::onlyTrashed()->where('user_id', Auth::id())->get();
        return view('trash', compact('deletedNotes'));
    }

    // 6. Restore
    public function restore($id)
    {
        Note::withTrashed()->where('id', $id)->restore();
        return redirect()->back()->with('success', 'Catatan dikembalikan!');
    }

    // 7. Force Delete (Hapus Permanen)
    public function forceDelete($id)
    {
        $note = Note::withTrashed()->where('id', $id)->first();
        
        // Hapus file gambar fisik
        if ($note->image) {
            Storage::disk('public')->delete($note->image);
        }
        
        $note->forceDelete();
        return redirect()->back()->with('error', 'Catatan musnah selamanya.');
    }

    // 8. Toggle Favorit
    public function toggleFavorite($id)
    {
        $note = Note::findOrFail($id);
        $note->is_favorite = !$note->is_favorite;
        $note->save();
        return redirect()->back();
    }

    // 9. Export PDF
    public function exportPdf($id)
    {
        $note = Note::findOrFail($id);
        $pdf = PDF::loadView('pdf_view', compact('note'));
        return $pdf->download('ChewytPad-'.$note->title.'.pdf');
    }

    // --- FITUR PROFIL ---
    
    public function editProfile()
    {
        return view('profile_edit', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Hanya USER yang boleh update foto profil (Admin tidak)
        if ($user->role === 'user' && $request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo = $path;
        }
        
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui! ✨');
    }

    // --- FITUR LUPA PASSWORD SIMPEL (UNTUK TUGAS) ---

    // 1. Tampilkan Form Input Email
    public function showForgotForm()
    {
        return view('auth.forgot-password-custom');
    }

    // 2. Cek Email & Alihkan ke Ganti Password
    public function processForgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Simpan email di session sementara biar sistem tau siapa yang mau diganti
        session(['reset_email' => $request->email]);

        return redirect()->route('password.custom_reset');
    }

    // 3. Tampilkan Form Password Baru
    public function showResetForm()
    {
        // Cek dulu, kalau gak ada email di session, tendang balik
        if (!session('reset_email')) {
            return redirect()->route('password.custom_forgot')->with('error', 'Masukkan email dulu!');
        }

        return view('auth.reset-password-custom');
    }

    // 4. Simpan Password Baru
    public function processReset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('reset_email');
        
        if (!$email) {
            return redirect()->route('password.custom_forgot');
        }

        // Update Password
        $user = User::where('email', $email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Hapus sesi
        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru. ✨');
    }
}