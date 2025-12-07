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
                </form>

                <a href="{{ route('trash') }}" class="bg-white text-pop-gum font-bold px-8 py-4 rounded-2xl hover:bg-pop-gum hover:text-white transition border-2 border-pop-gum flex items-center gap-2 shadow-sm">
                    🗑️ Sampah
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

                        <div class="pt-20 px-6 pb-4 flex-grow relative z-10 cursor-pointer" @click="showCommentModal = true">
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
                        </div>

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
                        
                        <div class="bg-white w-full max-w-lg h-[90vh] rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col border-4 border-white transform transition-all">
                            
                            <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-white to-gray-50 flex-none">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-block px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider text-pop-dark border-2 border-white shadow-sm break-words whitespace-normal min-w-[80px] text-center" style="background-color: {{ $note->category->color }}">
                                            {{ $note->category->name }}
                                        </span>
                                        <h2 class="text-xl font-black text-pop-dark leading-tight break-words flex-1">{{ $note->title }}</h2>
                                    </div>
                                    <button @click="showCommentModal = false" class="w-8 h-8 bg-gray-100 hover:bg-red-100 hover:text-red-500 rounded-full font-bold transition flex items-center justify-center text-sm flex-shrink-0 ml-2">✕</button>
                                </div>
                            </div>

                            <div class="flex-grow p-6 overflow-y-auto space-y-4 custom-scrollbar bg-gradient-to-b from-white to-gray-50/30">
                                <div class="text-center mb-6">
                                    <span class="bg-white border border-gray-200 text-gray-400 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider shadow-sm">
                                        💬 KOMENTAR ({{ $note->comments->count() }})
                                    </span>
                                </div>

                                <div class="space-y-4">
                                    @forelse($note->comments as $comment)
                                        <div x-data="{ isEditing: false, editContent: '{{ addslashes($comment->content) }}' }" 
                                             class="flex flex-col {{ $comment->user_id == Auth::id() ? 'items-end' : 'items-start' }}">
                                            
                                            <div class="flex items-center gap-2 mb-1 {{ $comment->user_id == Auth::id() ? 'flex-row-reverse' : '' }}">
                                                <div class="w-7 h-7 rounded-full bg-gradient-to-br {{ $comment->user_id == Auth::id() ? 'from-pop-lime to-green-400' : 'from-blue-100 to-blue-200' }} flex items-center justify-center text-xs font-black text-pop-dark flex-shrink-0">
                                                    {{ substr($comment->user->name, 0, 1) }}
                                                </div>
                                                <div class="flex items-center gap-2 {{ $comment->user_id == Auth::id() ? 'flex-row-reverse' : '' }}">
                                                    <span class="font-bold text-sm text-pop-dark">{{ $comment->user->name }}</span>
                                                    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>
                                                
                                                @if($comment->user_id == Auth::id())
                                                    <div class="flex gap-2 opacity-0 hover:opacity-100 transition-opacity group-hover:opacity-100" x-show="!isEditing">
                                                        <button @click="isEditing = true" 
                                                                class="text-xs text-blue-500 hover:text-blue-700 transition-colors p-1 hover:bg-blue-50 rounded">
                                                            ✏️ Edit
                                                        </button>
                                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition-colors p-1 hover:bg-red-50 rounded">
                                                                ✕ Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div x-show="!isEditing" class="px-4 py-3 rounded-2xl text-sm w-full max-w-[90%] shadow-sm border leading-relaxed break-words
                                                {{ $comment->user_id == Auth::id() 
                                                    ? 'bg-gradient-to-r from-pop-lime to-green-300 text-pop-dark rounded-tr-none border-green-300' 
                                                    : 'bg-gradient-to-r from-gray-50 to-blue-50 text-gray-700 rounded-tl-none border-gray-200' }}">
                                                {{ $comment->content }}
                                            </div>
                                            
                                            @if($comment->user_id == Auth::id())
                                                <div x-show="isEditing" class="px-4 py-3 rounded-2xl text-sm w-full max-w-[90%] shadow-sm border leading-relaxed break-words bg-white border-blue-300">
                                                    <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="space-y-3">
                                                        @csrf @method('PUT')
                                                        <textarea x-model="editContent" name="content" rows="2" 
                                                                  class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-0 focus:border-blue-400 resize-none" 
                                                                  style="min-height: 60px;"></textarea>
                                                        <div class="flex gap-2 justify-end mt-2">
                                                            <button type="button" @click="isEditing = false; editContent = '{{ addslashes($comment->content) }}'" 
                                                                    class="text-xs text-gray-500 hover:text-gray-700 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition font-medium">
                                                                Batal
                                                            </button>
                                                            <button type="submit" 
                                                                    class="text-xs bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition font-bold">
                                                                Update Komentar
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="h-full flex flex-col items-center justify-center text-center opacity-40 py-16">
                                            <div class="text-5xl mb-4">💭</div>
                                            <p class="text-base font-bold text-gray-500 mb-1">Belum ada komentar</p>
                                            <p class="text-xs text-gray-400">Jadilah yang pertama menulis komentar!</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="p-5 bg-white border-t border-gray-100 shadow-inner flex-none">
                                <div class="px-4 pb-4">
                                    <form action="{{ route('notes.comment', $note->id) }}" method="POST" class="flex gap-2 w-full">
                                        @csrf
                                        <input type="text" name="content" required placeholder="Tulis komentar..." 
                                               class="flex-grow bg-gray-50 border-2 border-gray-200 focus:border-pop-gum focus:ring-0 rounded-xl px-4 py-2.5 shadow-sm text-xs transition placeholder-gray-400 font-medium mr-2">
                                        <button type="submit" 
                                                class="bg-pop-hibiscus text-white font-bold px-4 py-2.5 rounded-xl hover:bg-pop-gum transition shadow-md flex items-center justify-center gap-1 flex-shrink-0 whitespace-nowrap text-xs">
                                            Kirim
                                        </button>
                                    </form>
                                </div>
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
                        <input type="text" name="title" required placeholder="Judul yang aesthetic..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 font-black text-lg focus:ring-2 focus:ring-pop-lime">
                    </div>

                    <div class="mb-6">
                        <label class="text-xs font-bold text-gray-400 uppercase ml-2">Isi Catatan</label>
                        <textarea name="content" rows="4" required placeholder="Tulis detailnya di sini bestie..." class="w-full bg-gray-50 border-none rounded-xl py-3 px-4 font-medium focus:ring-2 focus:ring-pop-lime"></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="text-xs font-bold text-gray-400 uppercase ml-2">Upload Gambar (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-pop-lime file:text-pop-dark hover:file:bg-green-400 cursor-pointer">
                    </div>

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