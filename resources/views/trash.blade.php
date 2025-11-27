<x-app-layout>
    <div class="min-h-screen bg-gray-100 font-sans py-10">
        <div class="max-w-6xl mx-auto px-4">
            
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-black text-pop-dark">Tong Sampah 🗑️</h1>
                    <p class="text-gray-500">Kembalikan catatan yang terhapus di sini.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-white text-pop-hibiscus font-bold px-6 py-3 rounded-full shadow-sm hover:bg-pop-candy transition">
                    ⬅️ Kembali ke Dashboard
                </a>
            </div>

            <!-- Grid Sampah -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($deletedNotes as $note)
                <div class="bg-white rounded-3xl p-6 opacity-70 hover:opacity-100 transition duration-300 border-2 border-dashed border-gray-300 hover:border-pop-lime">
                    <h3 class="text-xl font-black text-gray-600 mb-2">{{ $note->title }}</h3>
                    <p class="text-sm text-gray-400 mb-4 line-clamp-3">{{ $note->content }}</p>
                    
                    <div class="flex gap-3 mt-4">
                        <!-- TOMBOL RESTORE -->
                        <form action="{{ route('notes.restore', $note->id) }}" method="POST" class="w-full">
                            @csrf
                            <button class="w-full bg-pop-lime text-pop-dark font-black py-2 rounded-xl hover:bg-green-400 transition">
                                ♻️ Restore
                            </button>
                        </form>

                        <!-- HAPUS PERMANEN -->
                        <form action="{{ route('notes.force', $note->id) }}" method="POST" onsubmit="return confirm('Yakin hapus selamanya?')">
                            @csrf @method('DELETE')
                            <button class="w-full bg-red-100 text-red-500 font-bold py-2 rounded-xl hover:bg-red-500 hover:text-white transition">
                                💀 Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-20">
                    <p class="text-gray-400 font-bold text-lg">Sampah kosong! Bersih kinclong ✨</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>