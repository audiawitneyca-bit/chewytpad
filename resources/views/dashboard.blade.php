<x-app-layout>
    <!-- Setup Data AlpineJS -->
    <div class="min-h-screen bg-pop-candy font-sans pb-20" x-data="{ showModal: false }">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
            
            <!-- 1. HERO SECTION & SEARCH -->
            <div class="bg-pop-hibiscus rounded-[2.5rem] p-8 mb-10 text-white relative overflow-hidden shadow-soft border-4 border-white">
                <!-- Dekorasi Bulat -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-pop-gum rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-pulse"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-pop-lime rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-end gap-6">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black mb-2 tracking-tight">Catat Ide Liar Kamu! ✨</h1>
                        <p class="text-pink-200 font-bold text-lg">Jangan biarkan idemu hilang ditelan bumi.</p>
                    </div>

                    <!-- SEARCH BAR AESTHETIC (FIXED POSISI TOMBOL DI DALAM) -->
                    <form action="{{ route('dashboard') }}" method="GET" class="w-full md:w-1/2 relative flex items-center">
                        
                        <!-- Input Search -->
                        <!-- pr-28: Memberi ruang kosong di kanan untuk tombol-tombol -->
                        <input type="text" 
                               name="search" 
                               placeholder="Cari resep, tugas, atau curhatan..." 
                               class="w-full bg-white/20 border-2 border-white/50 text-white placeholder-white/80 rounded-2xl py-4 pl-6 pr-28 focus:bg-white focus:text-pop-hibiscus focus:ring-0 focus:border-pop-lime transition-all font-bold h-16 text-lg" 
                               value="{{ request('search') }}">
                        
                        <!-- GROUP TOMBOL (POSISI ABSOLUTE DI KANAN TENGAH) -->
                        <div class="absolute right-2 top-1/2 transform -translate-y-1/2 flex items-center gap-1">
                            
                            <!-- TOMBOL CLEAR (X) -->
                            @if(request('search'))
                                <a href="{{ route('dashboard') }}" 
                                   class="bg-red-400 text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold hover:bg-red-500 transition shadow-md text-lg leading-none" 
                                   title="Hapus Pencarian">
                                    ✕
                                </a>
                            @endif

                            <!-- TOMBOL CARI (Kaca Pembesar) -->
                            <button type="submit" class="bg-pop-lime text-pop-hibiscus w-12 h-12 rounded-xl flex items-center justify-center font-bold hover:scale-110 transition shadow-md text-xl leading-none">
                                🔍
                            </button>
                        </div>

                    </form>
                </div>

                <!-- Recent Search Bubble -->
                @if(isset($recentSearches) && $recentSearches->count() > 0)
                <div class="mt-6 flex flex-wrap gap-2">
                    <span class="text-xs font-bold pt-2 text-white/80 uppercase tracking-wider">Baru dicari:</span>
                    @foreach($recentSearches as $history)
                        <a href="{{ route('dashboard', ['search' => $history->keyword]) }}" class="bg-white/20 px-3 py-1 rounded-lg text-xs font-bold hover:bg-pop-lime hover:text-pop-hibiscus transition border border-white/30">
                            {{ $history->keyword }}
                        </a>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- 2. ACTION BUTTONS -->
            <div class="flex flex-wrap gap-4 mb-8 justify-center md:justify-start">
                <button @click="showModal = true" class="bg-pop-lime text-pop-dark font-black px-8 py-4 rounded-2xl shadow-glow hover:transform hover:-translate-y-1 transition flex items-center gap-2 text-lg hover:bg-green-300">
                    <span>➕</span> Buat Catatan Baru
                </button>

                <a href="{{ route('categories.index') }}" class="bg-white text-pop-gum font-bold px-6 py-4 rounded-2xl hover:bg-pop-candy transition border-2 border-pop-candy flex items-center gap-2 shadow-sm">
                    ⚙️ Atur Kategori
                </a>

                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap gap-4">
                    <!-- FILTER KATEGORI -->
                    <select name="category_id" onchange="this.form.submit()" class="bg-white border-2 border-white text-pop-hibiscus font-bold pl-6 pr-16 py-4 rounded-2xl shadow-sm focus:ring-2 focus:ring-pop-lime cursor-pointer hover:bg-gray-50">
                        <option value="">📂 Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <!-- TOMBOL FAVORIT -->
                    @php
                        $isFavFilterActive = request('filter_favorite') == 'true';
                        $favBg = $isFavFilterActive ? 'bg-pop-hibiscus text-white border-pop-dark' : 'bg-white text-pop-gum border-pop-gum';
                    @endphp

                    <button type="submit" 
                            name="filter_favorite" 
                            value="{{ $isFavFilterActive ? 'false' : 'true' }}" 
                            class="font-bold px-8 py-4 rounded-2xl shadow-sm transition border-2 {{ $favBg }} hover:bg-pop-gum hover:text-white">
                        {{ $isFavFilterActive ? '❤️ Favorit Aktif' : '🤍 Lihat Favorit' }}
                    </button>
                </form>

                <!-- TOMBOL RESTORE -->
                <a href="{{ route('trash') }}" class="bg-white text-pop-gum font-bold px-8 py-4 rounded-2xl hover:bg-pop-gum hover:text-white transition border-2 border-pop-gum flex items-center gap-2 shadow-sm">
                    🗑️ Lihat Sampah (Restore)
                </a>
            </div>

            <!-- 3. CATATAN GRID -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($notes as $note)
                <div class="bg-white rounded-[2rem] shadow-sm hover:shadow-soft hover:-translate-y-2 transition duration-300 border border-gray-100 flex flex-col h-full relative overflow-hidden group">
                    
                    <!-- Pita Kategori -->
                    <div class="absolute top-4 left-4 px-4 py-1 rounded-lg text-xs font-black uppercase tracking-widest border-2 border-white shadow-sm text-pop-dark z-20" style="background-color: {{ $note->category->color }}">
                        {{ $note->category->name }}
                    </div>

                    <!-- Tombol Love -->
                    <div class="absolute top-4 right-4 z-20">
                        <form action="{{ route('notes.favorite', $note->id) }}" method="POST">
                            @csrf 
                            <button class="text-2xl hover:scale-125 transition drop-shadow-sm">{{ $note->is_favorite ? '❤️' : '🤍' }}</button>
                        </form>
                    </div>

                    <!-- KONTEN UTAMA (pt-20 agar judul turun aman) -->
                    <div class="pt-20 px-6 pb-4 flex-grow relative z-10">
                        <h3 class="text-2xl font-black text-pop-dark mb-2 leading-tight break-words">{{ $note->title }}</h3>
                        <p class="text-gray-500 font-medium line-clamp-4 text-sm whitespace-pre-line">{{ $note->content }}</p>
                    </div>

                    <!-- Footer Action -->
                    <div class="flex justify-between items-center px-6 pb-6 pt-4 border-t border-dashed border-gray-200 mt-auto relative z-10">
                        <span class="text-xs font-bold text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                        
                        <div class="flex gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <a href="{{ route('notes.pdf', $note->id) }}" class="w-8 h-8 rounded-full bg-pop-candy flex items-center justify-center text-sm hover:bg-pop-hibiscus hover:text-white transition" title="PDF">📄</a>
                            <a href="{{ route('notes.edit', $note->id) }}" class="w-8 h-8 rounded-full bg-pop-lime flex items-center justify-center text-sm hover:bg-green-400 transition" title="Edit">✏️</a>
                            <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('Buang ke sampah sementara?')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-sm hover:bg-red-500 hover:text-white transition">🗑️</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                @php
                    $isFavActive = request('filter_favorite') == 'true';
                    $isSearchActive = request('search');
                    
                    if ($isSearchActive) {
                        $message = 'Waduh, gak ketemu nih! 🧐';
                        $submessage = 'Coba cari kata kunci lain ya.';
                        $emoji = '🔍';
                    } elseif ($isFavActive) {
                        $message = 'Belum ada catatan favorit! ❤️';
                        $submessage = 'Kasih hati di salah satu kartu catatanmu ya!';
                        $emoji = '💔';
                    } else {
                        $message = 'Belum ada catatan! Yuk klik tombol (+) buat mulai nulis.';
                        $submessage = 'Simpan ide liarmu sekarang juga!';
                        $emoji = '😴';
                    }
                @endphp

                <div class="col-span-3 flex flex-col items-center justify-center py-20 text-center">
                    <div class="bg-white p-6 rounded-full shadow-soft mb-4 text-4xl border-4 border-pop-candy">{{ $emoji }}</div>
                    <h3 class="text-xl font-black text-pop-hibiscus">{{ $message }}</h3>
                    <p class="text-gray-400">{{ $submessage }}</p>
                    
                    @if($isSearchActive)
                        <a href="{{ route('dashboard') }}" class="mt-4 text-pop-gum font-bold underline hover:text-pop-hibiscus">Kembali ke Semua Catatan</a>
                    @endif
                </div>
                @endforelse
            </div>
        </div>

        <!-- MODAL POPUP (Form Input) -->
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-pop-hibiscus/30 backdrop-blur-sm transition-opacity"></div>

            <div x-show="showModal" class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl p-8 relative z-10 transform transition-all border-4 border-white max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-black text-pop-hibiscus">Tulis Ide Baru 🍬</h2>
                    <button @click="showModal = false" class="w-8 h-8 bg-gray-100 rounded-full font-bold hover:bg-red-100 hover:text-red-500 transition">✕</button>
                </div>

                <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Kategori</label>
                            <select name="category_id" class="w-full bg-pop-candy/30 border-none rounded-xl py-3 px-4 font-bold text-pop-dark focus:ring-2 focus:ring-pop-lime">
                                <option value="" disabled selected>Pilih...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Atau Baru</label>
                            <input type="text" name="new_category_name" placeholder="Cth: Skincare" class="w-full bg-pop-candy/30 border-none rounded-xl py-3 px-4 font-bold focus:ring-2 focus:ring-pop-lime">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-xs font-bold text-gray-400 uppercase ml-2">Judul</label>
                        <input type="text" name="title" required placeholder="Judul yang aesthetic..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 font-black text-lg focus:ring-2 focus:ring-pop-lime">
                    </div>

                    <div class="mb-6">
                        <label class="text-xs font-bold text-gray-400 uppercase ml-2">Isi Catatan</label>
                        <textarea name="content" rows="5" required placeholder="Tulis detailnya di sini bestie..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 font-medium focus:ring-2 focus:ring-pop-lime"></textarea>
                    </div>

                    <!-- INPUT GAMBAR BARU -->
                    <div class="mb-2">
                        <label class="text-xs font-bold text-gray-400 uppercase ml-2">Upload Gambar (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 font-bold text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-pop-lime file:text-pop-dark hover:file:bg-green-400 cursor-pointer">
                    </div>

                    <!-- INPUT CAPTION GAMBAR -->
                    <div class="mb-6">
                        <label class="text-xs font-bold text-gray-400 uppercase ml-2">Keterangan Gambar (Opsional)</label>
                        <input type="text" name="image_caption" placeholder="Contoh: Foto bahan-bahan kue..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 font-bold text-sm text-pop-dark focus:ring-2 focus:ring-pop-lime">
                    </div>

                    <button type="submit" class="w-full bg-pop-lime text-pop-dark font-black py-4 rounded-xl shadow-lg hover:bg-green-400 transition hover:scale-[1.02]">
                        SIMPAN CATATAN ✨
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>