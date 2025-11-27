<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Category;
use App\Models\SearchHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Penting untuk gambar
use PDF; 

class NoteController extends Controller
{
    // 1. Dashboard
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $query = Note::where('user_id', $user_id);

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

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('filter_favorite') && $request->filter_favorite == 'true') {
            $query->where('is_favorite', true);
        }

        $notes = $query->latest()->get();
        $categories = Category::where('user_id', $user_id)->get();
        $recentSearches = SearchHistory::where('user_id', $user_id)->latest()->take(5)->get()->unique('keyword');

        return view('dashboard', compact('notes', 'categories', 'recentSearches'));
    }

    // 2. Simpan Catatan (Dengan Gambar)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'image_caption' => 'nullable|string|max:255',
        ]);

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

    // Edit Page
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
        
        // Cek jika ada gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($note->image) {
                Storage::disk('public')->delete($note->image);
            }
            $imagePath = $request->file('image')->store('notes-images', 'public');
            $note->image = $imagePath;
        }

        $note->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image_caption' => $request->image_caption,
        ]);

        return redirect()->route('dashboard')->with('success', 'Catatan berhasil diperbarui! ✨');
    }

    // 4. Hapus
    public function destroy($id)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        $note->delete(); 
        return redirect()->back()->with('success', 'Catatan dibuang ke tong sampah.');
    }

    // 5. Trash
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

    // 7. Force Delete
    public function forceDelete($id)
    {
        $note = Note::withTrashed()->where('id', $id)->first();
        if ($note->image) {
            Storage::disk('public')->delete($note->image);
        }
        $note->forceDelete();
        return redirect()->back()->with('error', 'Catatan musnah selamanya.');
    }

    // 8. Toggle Favorite
    public function toggleFavorite($id)
    {
        $note = Note::findOrFail($id);
        $note->is_favorite = !$note->is_favorite;
        $note->save();
        return redirect()->back();
    }

    // 9. PDF Export
    public function exportPdf($id)
    {
        $note = Note::findOrFail($id);
        $pdf = PDF::loadView('pdf_view', compact('note'));
        return $pdf->download('ChewytPad-'.$note->title.'.pdf');
    }

    // Profil
    public function editProfile() { return view('profile_edit', ['user' => Auth::user()]); }
    public function updateProfile(Request $request) {
        $request->validate([ 'name' => 'required', 'email' => 'required' ]);
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) { $user->password = bcrypt($request->password); }
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Profil berhasil diperbarui!');
    }
}