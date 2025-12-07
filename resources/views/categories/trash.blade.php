<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-10 font-sans">
        <div class="max-w-4xl mx-auto px-4">
            
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-black text-gray-600">Sampah Kategori 🗑️</h1>
                <a href="{{ route('categories.index') }}" class="bg-white border-2 border-gray-300 text-gray-600 font-bold px-4 py-2 rounded-xl hover:bg-gray-200 transition">
                    ⬅️ Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($deletedCategories as $category)
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border-2 border-dashed border-gray-300 opacity-75 hover:opacity-100 transition">
                    
                    <div class="flex justify-between items-center mb-4">
                        <span class="px-4 py-1 rounded-lg text-xs font-black uppercase tracking-widest border border-gray-200 text-gray-500 bg-gray-100 line-through">
                            {{ $category->name }}
                        </span>
                    </div>
                    
                    <div class="flex gap-2 mt-4">
                        <form action="{{ route('categories.restore', $category->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button class="w-full bg-pop-lime text-pop-dark font-bold py-2 rounded-xl hover:bg-green-400 transition text-sm">
                                ♻️ Restore
                            </button>
                        </form>
                        <form action="{{ route('categories.force', $category->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus permanen?')">
                            @csrf @method('DELETE')
                            <button class="w-full bg-red-100 text-red-500 font-bold py-2 rounded-xl hover:bg-red-600 hover:text-white transition text-sm">
                                💀 Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-2 text-center py-20 text-gray-400 font-bold">
                    Sampah kategori kosong. Bersih! ✨
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>