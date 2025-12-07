<x-app-layout>
    <div class="min-h-screen bg-pop-candy py-10 font-sans">
        <div class="max-w-4xl mx-auto px-4">
            
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-black text-pop-hibiscus">Manajemen Kategori 📂</h1>
                <div class="flex gap-3">
                    <a href="{{ route('categories.trash') }}" class="bg-white text-pop-gum font-bold px-4 py-2 rounded-xl border-2 border-pop-gum hover:bg-pop-gum hover:text-white transition">
                        🗑️ Sampah Kategori
                    </a>
                    <a href="{{ route('dashboard') }}" class="bg-pop-lime text-pop-dark font-bold px-4 py-2 rounded-xl shadow-sm hover:bg-green-400 transition">
                        ⬅️ Kembali Dashboard
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-2 border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 font-bold text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-2 border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 font-bold text-center">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($categories as $category)
                <div class="bg-white rounded-[2rem] p-6 shadow-soft border-4 border-white relative" x-data="{ editMode: false }">
                    
                    <!-- MODE TAMPIL -->
                    <div x-show="!editMode">
                        <div class="flex justify-between items-center mb-4">
                            <span class="px-4 py-1 rounded-lg text-xs font-black uppercase tracking-widest border-2 border-white shadow-sm text-pop-dark" style="background-color: {{ $category->color }}">
                                {{ $category->name }}
                            </span>
                            <div class="text-xs font-bold text-gray-400">
                                {{ $category->notes->count() }} Catatan
                            </div>
                        </div>
                        
                        <div class="flex gap-2 mt-4">
                            <button @click="editMode = true" class="flex-1 bg-pop-lime text-pop-dark font-bold py-2 rounded-xl hover:bg-green-400 transition text-sm">
                                ✏️ Edit
                            </button>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="w-full bg-red-100 text-red-500 font-bold py-2 rounded-xl hover:bg-red-500 hover:text-white transition text-sm">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- MODE EDIT -->
                    <div x-show="editMode" style="display: none;">
                        <form action="{{ route('categories.update', $category->id) }}" method="POST">
                            @csrf @method('PUT')
                            
                            <div class="mb-3">
                                <label class="text-xs font-bold text-gray-400">Nama Kategori</label>
                                <input type="text" name="name" value="{{ $category->name }}" class="w-full bg-gray-50 border-none rounded-xl py-2 px-3 font-bold text-pop-dark focus:ring-2 focus:ring-pop-lime">
                            </div>

                            <div class="mb-4">
                                <label class="text-xs font-bold text-gray-400">Warna Label</label>
                                <input type="color" name="color" value="{{ $category->color }}" class="w-full h-10 rounded-xl cursor-pointer border-none">
                            </div>

                            <div class="flex gap-2">
                                <button type="button" @click="editMode = false" class="flex-1 bg-gray-200 text-gray-600 font-bold py-2 rounded-xl hover:bg-gray-300 text-sm">Batal</button>
                                <button type="submit" class="flex-1 bg-pop-hibiscus text-white font-bold py-2 rounded-xl hover:bg-pop-gum text-sm">Simpan</button>
                            </div>
                        </form>
                    </div>

                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>