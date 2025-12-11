<x-app-layout>
    <div class="min-h-screen bg-gray-50 font-sans pb-20 pt-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-pop-hibiscus font-bold transition">
                    ⬅️ Kembali ke Dashboard
                </a>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border-4 border-white relative">
                
                @if($note->image)
                    <div class="w-full h-64 md:h-96 bg-gray-100 relative group">
                        <img src="{{ asset('storage/' . $note->image) }}" class="w-full h-full object-cover object-center">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        
                        <div class="absolute bottom-0 left-0 p-8 w-full">
                            <span class="bg-pop-lime text-pop-dark text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest mb-3 inline-block shadow-lg">
                                {{ $note->category->name }}
                            </span>
                            <h1 class="text-3xl md:text-5xl font-black text-white leading-tight drop-shadow-md">
                                {{ $note->title }}
                            </h1>
                        </div>
                    </div>
                @else
                    <div class="p-8 bg-pop-hibiscus text-white relative overflow-hidden">
                         <div class="absolute top-0 right-0 w-64 h-64 bg-pop-gum rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
                         <span class="bg-white/20 text-white text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest mb-3 inline-block">
                            {{ $note->category->name }}
                        </span>
                        <h1 class="text-3xl md:text-5xl font-black relative z-10">{{ $note->title }}</h1>
                    </div>
                @endif

                <div class="p-8 md:p-12">
                    
                    <div class="flex items-center justify-between border-b border-gray-100 pb-6 mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xl">👤</div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">Dibuat {{ $note->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('notes.edit', $note->id) }}" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-blue-100 transition">Edit</a>
                            <a href="{{ route('notes.pdf', $note->id) }}" class="bg-gray-50 text-gray-600 px-4 py-2 rounded-xl font-bold text-sm hover:bg-gray-100 transition">PDF</a>
                        </div>
                    </div>

                    <div class="prose max-w-none text-gray-700 text-lg leading-relaxed whitespace-pre-line font-medium">
                        {{ $note->content }}
                    </div>

                    @if($note->image_caption)
                        <div class="mt-8 p-4 bg-gray-50 rounded-xl border-l-4 border-pop-gum text-gray-500 italic text-sm">
                            💡 Caption Gambar: {{ $note->image_caption }}
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</x-app-layout>