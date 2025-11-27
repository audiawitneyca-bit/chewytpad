<x-app-layout>
    <div class="min-h-screen bg-pop-candy py-12 font-sans">
        <div class="max-w-3xl mx-auto px-4">
            
            <!-- HEADER EDIT -->
            <div class="bg-white rounded-3xl shadow-soft p-6 mb-8 border-4 border-white relative overflow-hidden">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 w-48 h-10 bg-pop-lime opacity-70 rotate-3 rounded-full z-0"></div>
                
                <div class="flex justify-between items-center relative z-10">
                    <h1 class="text-3xl font-black text-pop-hibiscus flex items-center gap-2">
                        ✏️ EDIT IDE KAMU
                    </h1>
                    <a href="{{ route('dashboard') }}" class="font-bold text-pop-gum hover:text-pop-hibiscus hover:underline">
                        ⬅️ Kembali
                    </a>
                </div>
            </div>

            <!-- FORM EDIT (Style Note Pad) -->
            <div class="bg-white rounded-[2.5rem] shadow-lg p-8 relative border-4 border-pop-candy">
                
                <!-- enctype wajib untuk upload file -->
                <form action="{{ route('notes.update', $note->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Wajib untuk Update data -->

                    <!-- Input Kategori -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Ganti Kategori?</label>
                        <select name="category_id" class="w-full bg-pop-candy/30 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-lime">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $note->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Input Judul -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Judul Baru</label>
                        <input type="text" name="title" value="{{ old('title', $note->title) }}" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 font-black text-xl focus:ring-2 focus:ring-pop-hibiscus">
                    </div>

                    <!-- Input Konten -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Isi Catatan</label>
                        <textarea name="content" rows="10" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 font-medium focus:ring-2 focus:ring-pop-hibiscus">{{ old('content', $note->content) }}</textarea>
                    </div>

                    <!-- PREVIEW GAMBAR SAAT INI -->
                    @if($note->image)
                    <div class="mb-4 p-4 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-300">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Gambar Saat Ini</label>
                        <img src="{{ asset('storage/' . $note->image) }}" class="rounded-xl max-h-64 object-cover mx-auto shadow-sm border border-gray-200">
                    </div>
                    @endif

                    <!-- GANTI GAMBAR -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Ganti Gambar (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 font-bold text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-pop-lime file:text-pop-dark hover:file:bg-green-400 cursor-pointer">
                    </div>

                    <!-- EDIT CAPTION -->
                    <div class="mb-10">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Keterangan Gambar</label>
                        <input type="text" name="image_caption" value="{{ old('image_caption', $note->image_caption) }}" placeholder="Tulis keterangan fotonya..." class="w-full bg-gray-50 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-lime">
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('dashboard') }}" class="w-1/3 text-center bg-gray-200 text-pop-dark font-bold py-4 rounded-2xl hover:bg-gray-300 transition">
                            BATAL
                        </a>
                        <button type="submit" class="w-2/3 bg-pop-hibiscus text-white font-black text-lg py-4 rounded-2xl shadow-lg hover:bg-pop-gum hover:scale-[1.01] transition">
                            SIMPAN PERUBAHAN 💾
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>