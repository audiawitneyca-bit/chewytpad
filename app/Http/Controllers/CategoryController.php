<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return view('categories.index', compact('categories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string'
        ]);

        $category->update([
            'name' => $request->name,
            'color' => $request->color,
            'slug' => \Str::slug($request->name)
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui! ✨');
    }

    public function destroy($id)
    {
        $category = Category::where('user_id', Auth::id())->findOrFail($id);
        
        if($category->notes()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal! Masih ada catatan di kategori ini. Hapus atau pindahkan catatannya dulu.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Kategori masuk tong sampah.');
    }

    public function trash()
    {
        $deletedCategories = Category::onlyTrashed()->where('user_id', Auth::id())->get();
        return view('categories.trash', compact('deletedCategories'));
    }

    public function restore($id)
    {
        Category::withTrashed()->where('id', $id)->restore();
        return redirect()->back()->with('success', 'Kategori dikembalikan! ♻️');
    }

    public function forceDelete($id)
    {
        Category::withTrashed()->where('id', $id)->forceDelete();
        return redirect()->back()->with('error', 'Kategori dihapus selamanya.');
    }
}
