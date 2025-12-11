<x-app-layout>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>

    <div class="min-h-screen bg-pop-candy font-sans pb-20" x-data="{ showModal: false }">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
            
            <div class="bg-pop-hibiscus rounded-[2.5rem] p-8 mb-10 text-white relative overflow-hidden shadow-soft border-4 border-white transition hover:shadow-lg">
                <div class="absolute top-0 right-0 w-64 h-64 bg-pop-gum rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-pulse"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-pop-lime rounded-full mix-blend-multiply filter blur-2xl opacity-50"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-end gap-6">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black mb-2 tracking-tight">Catat Ide Liar Kamu! ✨</h1>
                        <p class="text-pink-200 font-bold text-lg">Catat sekarang, komentari nanti.</p>
                    </div>

                    <form action="{{ route('dashboard') }}" method="GET" class="w-full md:w-1/2 relative flex items-center">
                        <input type="text" name="search" placeholder="Cari resep, tugas, atau curhatan..." class="w-full bg-white/20 border-2 border-white/50 text-white placeholder-white/80 rounded-2xl py-4 pl-6 pr-28 focus:bg-white focus:text-pop-hibiscus focus:ring-0 focus:border-pop-lime transition-all font-bold h-16 text-lg" value="{{ request('search') }}">
                        <div class="absolute right-2 top-1/2 transform -translate-y-1/2 flex items-center gap-1">
                            @if(request('search'))
                                <a href="{{ route('dashboard') }}" class="bg-red-400 text-white w-10 h-10 rounded-xl flex items-center justify-center font-bold hover:bg-red-500 transition shadow-md text-lg leading-none">✕</a>
                            @endif
                            <button type="submit" class="bg-pop-lime text-pop-hibiscus w-12 h-12 rounded-xl flex items-center justify-center font-bold hover:scale-110 transition shadow-md text-xl leading-none">🔍</button>
                        </div>
                    </form>
                </div>

                @if(isset($recentSearches) && $recentSearches->count() > 0)
                <div class="mt-6 flex flex-wrap gap-2 relative z-10">
                    <span class="text-xs font-bold pt-2 text-white/80 uppercase tracking-wider">Baru dicari:</span>
                    @foreach($recentSearches as $history)
                        <a href="{{ route('dashboard', ['search' => $history->keyword]) }}" class="bg-white/20 px-3 py-1 rounded-lg text-xs font-bold hover:bg-pop-lime hover:text-pop-hibiscus transition border border-white/30">
                            {{ $history->keyword }}
                        </a>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="flex flex-wrap gap-4 mb-8 justify-center md:justify-start">
                <button @click="showModal = true" class="bg-pop-lime text-pop-dark font-black px-8 py-4 rounded-2xl shadow-glow hover:transform hover:-translate-y-1 transition flex items-center gap-2 text-lg hover:bg-green-300">
                    <span>➕</span> Buat Catatan Baru
                </button>

                <a href="{{ route('categories.index') }}" class="bg-white text-pop-gum font-bold px-6 py-4 rounded-2xl hover:bg-pop-candy transition border-2 border-pop-candy flex items-center gap-2 shadow-sm">
                    ⚙️ Atur Kategori
                </a>

                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap gap-4">
                     <select name="category_id" onchange="this.form.submit()" class="bg-white border-2 border-white text-pop-hibiscus font-bold pl-6 pr-16 py-4 rounded-2xl shadow-sm focus:ring-2 focus:ring-pop-lime cursor-pointer hover:bg-gray-50">
                        <option value="">📂 Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    @php
                        $isFav = request('filter_favorite') == 'true';
                    @endphp
                    <button type="submit" name="filter_favorite" value="{{ $isFav ? 'false' : 'true' }}" 
                        class="px-6 py-4 rounded-2xl shadow-sm border-2 font-bold transition flex items-center gap-2
                        {{ $isFav ? 'bg-pop-hibiscus text-white border-pop-dark' : 'bg-white text-pop-gum border-pop-gum hover:bg-pop-gum hover:text-white' }}">
                        {{ $isFav ? '❤️ Favorit Aktif' : '🤍 Lihat Favorit' }}
                    </button>
                </form>

                <a href="{{ route('trash') }}" class="bg-white text-pop-gum font-bold px-8 py-4 rounded-2xl hover:bg-pop-gum hover:text-white transition border-2 border-pop-gum flex items-center gap-2 shadow-sm">
                    🗑️ Sampah Catatan
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($notes as $note)
                <div x-data="{ showCommentModal: {{ (session('open_modal') == $note->id) ? 'true' : 'false' }} }" class="h-full">
                    
                    <div class="bg-white rounded-[2rem] shadow-sm hover:shadow-soft hover:-translate-y-2 transition duration-300 border border-gray-100 flex flex-col h-full relative overflow-hidden group">
                        
                        <div class="absolute top-4 left-4 z-20 max-w-[70%]">
                            <span class="inline-block px-4 py-1 rounded-lg text-xs font-black uppercase tracking-widest border-2 border-white shadow-sm text-pop-dark break-words whitespace-normal" style="background-color: {{ $note->category->color }}">
                                {{ $note->category->name }}
                            </span>
                        </div>

                        <div class="absolute top-4 right-4 z-20">
                            <form action="{{ route('notes.favorite', $note->id) }}" method="POST">
                                @csrf 
                                <button class="text-2xl hover:scale-125 transition drop-shadow-sm">{{ $note->is_favorite ? '❤️' : '🤍' }}</button>
                            </form>
                        </div>

                        <a href="{{ route('notes.show', $note->id) }}" class="pt-20 px-6 pb-4 flex-grow relative z-10 cursor-pointer block">
                            @if($note->image)
                                <div class="mb-3 overflow-hidden rounded-2xl border-2 border-gray-100">
                                    <div class="w-full aspect-video bg-gray-50">
                                        <img src="{{ asset('storage/' . $note->image) }}" 
                                             class="w-full h-full object-contain transform hover:scale-105 transition duration-500"
                                             alt="Cover Note"
                                             style="max-height: 160px;">
                                    </div>
                                </div>
                            @endif
                            <h3 class="text-2xl font-black text-pop-dark mb-2 leading-tight break-words">{{ $note->title }}</h3>
                            <p class="text-gray-500 font-medium line-clamp-3 text-sm whitespace-pre-line">{{ $note->content }}</p>
                            <span class="text-xs text-blue-400 font-bold hover:underline mt-2 inline-block">Baca Selengkapnya ↗</span>
                        </a>

                        <div class="flex justify-between items-center px-6 pb-6 pt-4 border-t border-dashed border-gray-100 mt-auto relative z-10">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                                <button @click="showCommentModal = true" class="bg-blue-50 text-blue-500 px-3 py-1 rounded-lg text-xs font-bold hover:bg-blue-100 transition border border-blue-100 flex items-center gap-1 group-hover:bg-blue-500 group-hover:text-white">
                                    💬 {{ $note->comments->count() }}
                                </button>
                            </div>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('notes.pdf', $note->id) }}" class="w-8 h-8 rounded-full bg-pop-candy flex items-center justify-center text-sm hover:bg-pop-hibiscus hover:text-white transition">📄</a>
                                <a href="{{ route('notes.edit', $note->id) }}" class="w-8 h-8 rounded-full bg-pop-lime flex items-center justify-center text-sm hover:bg-green-400 transition">✏️</a>
                                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('Buang ke sampah sementara?')">
                                    @csrf @method('DELETE')
                                    <button class="w-8 h-8 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-sm hover:bg-red-500 hover:text-white transition">🗑️</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div x-show="showCommentModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4 py-4">
                        <div class="fixed inset-0 bg-pop-hibiscus/60 backdrop-blur-sm transition-opacity" @click="showCommentModal = false"></div>
                        
                        <div class="bg-gray-50 w-full max-w-lg h-[80vh] rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col border-4 border-white transform transition-all">
                            
                            <div class="p-5 border-b border-gray-200 bg-white shadow-sm flex justify-between items-center flex-none z-20">
                                <div>
                                    <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-widest text-pop-dark border border-black/5 mb-1 inline-block" style="background-color: {{ $note->category->color }}">
                                        {{ $note->category->name }}
                                    </span>
                                    <h2 class="text-lg font-black text-pop-dark truncate max-w-[250px] leading-tight">{{ $note->title }}</h2>
                                </div>
                                <button @click="showCommentModal = false" class="w-8 h-8 bg-gray-100 rounded-full font-bold hover:bg-red-100 hover:text-red-500 transition flex items-center justify-center text-sm">✕</button>
                            </div>

                            <div class="flex-grow p-5 overflow-y-auto space-y-4 custom-scrollbar bg-white">
                                
                                <div class="text-center mb-4">
                                    <span class="bg-white border border-gray-200 text-gray-400 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                        Komentar
                                    </span>
                                </div>

                                @forelse($note->comments as $comment)
                                    <div x-data="{ isEditing: false, editContent: '{{ $comment->content }}' }" class="flex flex-col {{ $comment->user_id == Auth::id() ? 'items-end' : 'items-start' }}">
                                        
                                        <div class="flex items-center gap-2 mb-1 px-1 {{ $comment->user_id == Auth::id() ? 'flex-row-reverse' : '' }}">
                                            <span class="font-bold text-xs text-pop-dark">{{ $comment->user->name }}</span>
                                            <span class="text-[9px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            
                                            @if($comment->user_id == Auth::id())
                                                <div class="flex gap-1 opacity-0 hover:opacity-100 transition duration-200 group-hover:opacity-100" x-show="!isEditing">
                                                    <span class="text-[9px] text-gray-300">|</span>
                                                    <button @click="isEditing = true" class="text-[9px] text-blue-400 hover:underline">Edit</button>
                                                    <span class="text-[9px] text-gray-300">.</span>
                                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="text-[9px] text-red-400 hover:underline">Hapus</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>

                                        <div x-show="!isEditing" class="px-4 py-3 rounded-2xl text-sm max-w-[90%] shadow-sm border 
                                            {{ $comment->user_id == Auth::id() 
                                                ? 'bg-pop-lime text-pop-dark rounded-tr-none border-pop-lime' 
                                                : 'bg-gray-50 text-gray-600 rounded-tl-none border-gray-200' }}">
                                            {{ $comment->content }}
                                        </div>

                                        @if($comment->user_id == Auth::id())
                                            <div x-show="isEditing" class="w-[90%] max-w-[300px]">
                                                <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="flex flex-col gap-2 bg-white p-2 rounded-xl border border-blue-200 shadow-sm">
                                                    @csrf @method('PUT')
                                                    <textarea name="content" x-model="editContent" rows="2" class="w-full text-sm border-gray-200 rounded-lg bg-gray-50 p-2 resize-none" required></textarea>
                                                    <div class="flex justify-end gap-2">
                                                        <button type="button" @click="isEditing = false; editContent = '{{ $comment->content }}'" class="text-[10px] text-gray-500 font-bold bg-gray-100 px-2 py-1 rounded">Batal</button>
                                                        <button type="submit" class="bg-blue-500 text-white text-[10px] px-3 py-1 rounded font-bold hover:bg-blue-600">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="h-full flex flex-col items-center justify-center text-center opacity-40 mt-10">
                                        <div class="text-5xl mb-2">💬</div>
                                        <p class="text-sm font-bold text-gray-500">Belum ada komentar.</p>
                                        <p class="text-xs text-gray-400">Jadilah yang pertama!</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="p-4 bg-white border-t border-gray-200 flex-none z-20">
                                <form action="{{ route('notes.comment', $note->id) }}" method="POST" class="flex gap-2 relative">
                                    @csrf
                                    <input type="text" name="content" required placeholder="Tulis komentar..." class="flex-grow bg-white border-2 border-transparent focus:bg-gray-50 focus:border-pop-gum focus:ring-0 rounded-2xl px-4 py-3 shadow-inner text-sm transition placeholder-gray-400">
                                    <button type="submit" class="bg-pop-dark text-white font-bold px-4 rounded-2xl hover:bg-gray-800 transition shadow-md flex items-center justify-center transform active:scale-95">
                                        ➤
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 flex flex-col items-center justify-center py-20 text-center">
                    <div class="bg-white p-6 rounded-full shadow-soft mb-4 text-4xl border-4 border-pop-candy">😴</div>
                    <h3 class="text-xl font-black text-pop-hibiscus">Belum ada catatan!</h3>
                    <p class="text-gray-400">Yuk klik tombol (+) buat mulai nulis ide liarmu.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-pop-hibiscus/30 backdrop-blur-sm transition-opacity"></div>
            <div x-show="showModal" class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl p-8 relative z-10 transform transition-all border-4 border-white max-h-[90vh] overflow-y-auto custom-scrollbar">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-black text-pop-hibiscus">Tulis Ide Baru 🍬</h2>
                    <button @click="showModal = false" class="w-8 h-8 bg-gray-100 rounded-full font-bold hover:bg-red-100 hover:text-red-500 transition">✕</button>
                </div>

                <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Kategori</label>
                            <select name="category_id" class="w-full bg-pop-candy/30 border-none rounded-xl py-3 px-4 font-bold text-pop-dark focus:ring-2 focus:ring-pop-lime cursor-pointer">
                                <option value="" disabled selected>Pilih...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
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
                        <input type="text" name="title" required placeholder="Judul..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 font-black text-lg focus:ring-2 focus:ring-pop-lime">
                    </div>
                    <div class="mb-6">
                        <label class="text-xs font-bold text-gray-400 uppercase ml-2">Isi Catatan</label>
                        <textarea name="content" rows="4" required placeholder="Tulis..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 font-medium focus:ring-2 focus:ring-pop-lime"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="text-xs font-bold text-gray-400 uppercase ml-2">Upload Gambar</label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-500 border">
                    </div>
                    <div class="mb-6">
                        <label class="text-xs font-bold text-gray-400 uppercase ml-2">Keterangan</label>
                        <input type="text" name="image_caption" placeholder="Caption..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 font-bold text-sm text-pop-dark focus:ring-2 focus:ring-pop-lime">
                    </div>
                    <button type="submit" class="w-full bg-pop-lime text-pop-dark font-black py-4 rounded-xl shadow-lg hover:bg-green-400 transition hover:scale-[1.02]">
                        SIMPAN CATATAN ✨
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>