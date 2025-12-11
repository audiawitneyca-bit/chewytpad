<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Category;
use App\Models\SearchHistory;
use App\Models\User;
use App\Models\Comment; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF; 

class NoteController extends Controller
{
    // 1. Dashboard (Original List/Grid)
    public function index(Request $request)
    {
        $user_id = Auth::id();
        
        // Eager loading komentar agar efisien
        $query = Note::where('user_id', $user_id)->with(['category', 'comments.user']);

        // --- Logika Pencarian ---
        if ($request->has('search') && $request->search != null) {
            $keyword = $request->search;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                  ->orWhere('content', 'like', '%'.$keyword.'%')
                  ->orWhere('image_caption', 'like', '%'.$keyword.'%')
                  ->orWhereHas('category', function($c) use ($keyword){
                      $c->where('name', 'like', '%'.$keyword.'%');
                  });
            });
            SearchHistory::create(['user_id' => $user_id, 'keyword' => $keyword]);
        }

        // --- Filter ---
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('filter_favorite') && $request->filter_favorite == 'true') {
            $query->where('is_favorite', true);
        }

        if ($request->filled('date_filter')) {
            $query->whereDate('created_at', $request->date_filter);
        }

        // AMBIL DATA (NORMAL)
        $notes = $query->latest()->get(); 

        // Data Pendukung
        $categories = Category::where('user_id', $user_id)->get();
        $recentSearches = SearchHistory::where('user_id', $user_id)
                            ->latest()
                            ->take(5)
                            ->get()
                            ->unique('keyword');

        return view('dashboard', compact('notes', 'categories', 'recentSearches'));
    }

    // 2. Simpan Catatan
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_caption' => 'nullable|string|max:255',
        ]);

        // Handle Kategori Baru
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
            return redirect()->back()->with('error', 'Pilih kategori dulu!');
        }

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
            'is_favorite' => $request->has('is_favorite'),
        ]);

        return redirect()->back()->with('success', 'Catatan berhasil ditempel!');
    }

    // 3. Simpan Komentar (LOGIKA TETAP BUKA MODAL)
    public function storeComment(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);

        Comment::create([
            'user_id' => Auth::id(),
            'note_id' => $id,
            'content' => $request->content
        ]);

        // Kita kirim 'open_modal' session agar di view nanti modalnya otomatis terbuka lagi
        return redirect()->back()
                ->with('success', 'Komentar ditambahkan! 💬')
                ->with('open_modal', $id);
    }

    // --- FUNGSI STANDAR LAINNYA ---

    public function edit($id) {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::where('user_id', Auth::id())->get();
        return view('edit', compact('note', 'categories'));
    }

    public function update(Request $request, $id) {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        $request->validate(['image' => 'nullable|image|max:2048']);

        if ($request->hasFile('image')) {
            if ($note->image) Storage::disk('public')->delete($note->image);
            $note->image = $request->file('image')->store('notes-images', 'public');
        }

        $note->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image_caption' => $request->image_caption,
        ]);
        return redirect()->route('dashboard')->with('success', 'Catatan diperbarui!');
    }

    public function destroy($id) {
        Note::where('user_id', Auth::id())->findOrFail($id)->delete(); 
        return redirect()->back()->with('success', 'Dibuang ke sampah.');
    }

    public function trash() {
        $deletedNotes = Note::onlyTrashed()->where('user_id', Auth::id())->get();
        return view('trash', compact('deletedNotes'));
    }

    public function restore($id) {
        Note::withTrashed()->where('id', $id)->restore();
        return redirect()->back()->with('success', 'Catatan dikembalikan!');
    }

    public function forceDelete($id) {
        $note = Note::withTrashed()->where('id', $id)->first();
        if ($note->image) Storage::disk('public')->delete($note->image);
        $note->forceDelete();
        return redirect()->back();
    }

    public function toggleFavorite($id) {
        $note = Note::findOrFail($id);
        $note->is_favorite = !$note->is_favorite;
        $note->save();
        return redirect()->back();
    }

    public function exportPdf($id) {
        $note = Note::findOrFail($id);
        $pdf = PDF::loadView('pdf_view', compact('note'));
        return $pdf->download('ChewytPad-'.$note->title.'.pdf');
    }
    
    public function editProfile() { return view('profile_edit', ['user' => Auth::user()]); }
    
    public function updateProfile(Request $request) {
       $user = User::find(Auth::id());
       $request->validate(['name'=>'required','email'=>'required']);
       $user->name = $request->name;
       $user->email = $request->email;
       if($request->filled('password')) $user->password = bcrypt($request->password);
       $user->save();
       return redirect()->route('dashboard');
    }

    public function showForgotForm() { return view('auth.forgot-password-custom'); }
    public function processForgot(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);
        session(['reset_email' => $request->email]);
        return redirect()->route('password.custom_reset');
    }
    public function showResetForm() {
        if (!session('reset_email')) return redirect()->route('password.custom_forgot');
        return view('auth.reset-password-custom');
    }
    public function processReset(Request $request) {
        $request->validate(['password' => 'required|confirmed']);
        $user = User::where('email', session('reset_email'))->first();
        $user->password = bcrypt($request->password);
        $user->save();
        session()->forget('reset_email');
        return redirect()->route('login');
    }

    public function show($id)
    {
        $user_id = Auth::id();
        // Pastikan hanya pemilik yang bisa lihat (atau sesuaikan jika ingin publik)
        $note = Note::where('user_id', $user_id)->with(['category', 'comments.user'])->findOrFail($id);
        
        return view('show', compact('note'));
    }

    // Update Komentar
    public function updateComment(Request $request, $id)
    {
        $comment = Comment::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment->update([
            'content' => $request->content
        ]);

        // Kembalikan ke halaman sebelumnya dan buka modal note terkait
        return redirect()->back()
            ->with('success', 'Komentar berhasil diedit!')
            ->with('open_modal', $comment->note_id);
    }

    // Hapus Komentar
    public function destroyComment($id)
    {
        $comment = Comment::where('user_id', Auth::id())->findOrFail($id);
        $noteId = $comment->note_id; // Simpan ID note sebelum dihapus untuk redirect
        
        $comment->delete();

        return redirect()->back()
            ->with('success', 'Komentar dihapus.')
            ->with('open_modal', $noteId);
    }
}