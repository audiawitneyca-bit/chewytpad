<div class="bg-white rounded-[2rem] shadow-sm hover:shadow-soft cursor-grab active:cursor-grabbing transition duration-300 border border-gray-100 flex flex-col relative overflow-hidden group mb-4" data-id="{{ $note->id }}">
    
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

    <!-- Area Konten -->
    <div class="pt-20 px-6 pb-4 flex-grow relative z-10">
        <!-- Judul -->
        <h3 class="text-xl font-black text-pop-dark mb-2 leading-tight break-words">{{ $note->title }}</h3>
        <!-- Isi Singkat -->
        <p class="text-gray-500 font-medium line-clamp-3 text-sm whitespace-pre-line">{{ $note->content }}</p>
    </div>

    <!-- Footer Action (PDF, Edit, Delete) -->
    <div class="flex justify-between items-center px-6 pb-6 pt-4 border-t border-dashed border-gray-200 mt-auto relative z-10">
        <span class="text-[10px] font-bold text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
        
        <div class="flex gap-2 opacity-100 group-hover:opacity-100 transition-opacity duration-300">
            <a href="{{ route('notes.pdf', $note->id) }}" class="w-8 h-8 rounded-full bg-pop-candy flex items-center justify-center text-sm hover:bg-pop-hibiscus hover:text-white transition" title="PDF">📄</a>
            <a href="{{ route('notes.edit', $note->id) }}" class="w-8 h-8 rounded-full bg-pop-lime flex items-center justify-center text-sm hover:bg-green-400 transition" title="Edit">✏️</a>
            <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('Buang ke sampah sementara?')">
                @csrf @method('DELETE')
                <button class="w-8 h-8 rounded-full bg-red-100 text-red-500 flex items-center justify-center text-sm hover:bg-red-500 hover:text-white transition">🗑️</button>
            </form>
        </div>
    </div>
</div>