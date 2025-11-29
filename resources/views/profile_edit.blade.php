<x-app-layout>
    <div class="min-h-screen bg-pop-candy py-10 font-sans">
        <div class="max-w-2xl mx-auto px-4">
            
            <a href="{{ route('dashboard') }}" class="text-pop-hibiscus font-bold mb-4 inline-block hover:underline">⬅️ Kembali ke Dashboard</a>

            <div class="bg-white rounded-[2rem] shadow-soft p-8 md:p-12 border-4 border-white">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-black text-pop-hibiscus">Edit Profil ⚙️</h1>
                </div>
                <p class="text-gray-500 mb-8">Ganti info kamu biar makin kece.</p>

                @if(session('success'))
                    <div class="bg-pop-lime/30 text-pop-dark p-4 rounded-xl font-bold mb-6 text-center border-2 border-pop-lime">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- PENTING: enctype="multipart/form-data" -->
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- KHUSUS USER BIASA: Upload Foto Profil -->
                    @if(Auth::user()->role === 'user')
                    <div class="mb-8 p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 text-center">
                        <label class="block text-xs font-bold text-pop-gum uppercase mb-4">Foto Profil (Opsional)</label>
                        
                        <!-- Preview Foto Lama -->
                        <div class="flex justify-center mb-4">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-24 h-24 rounded-full object-cover border-4 border-pop-lime shadow-md">
                            @else
                                <div class="w-24 h-24 rounded-full bg-pop-candy flex items-center justify-center text-4xl border-4 border-white shadow-md">
                                    👤
                                </div>
                            @endif
                        </div>

                        <input type="file" name="profile_photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-pop-hibiscus file:text-white hover:file:bg-pop-gum cursor-pointer">
                        <p class="text-[10px] text-gray-400 mt-2">Format: JPG, PNG (Max 2MB). Gak wajib kok!</p>
                    </div>
                    @endif

                    <!-- Nama -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Nama Kamu</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 font-black text-xl focus:ring-2 focus:ring-pop-hibiscus">
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 font-bold text-gray-600 focus:ring-2 focus:ring-pop-hibiscus">
                    </div>

                    <!-- Password -->
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Password Baru (Opsional)</label>
                        <input type="password" name="password" placeholder="Isi cuma kalau mau ganti password..." class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-pop-hibiscus">
                    </div>

                    <button type="submit" class="w-full bg-pop-hibiscus text-white font-black py-4 rounded-2xl shadow-lg hover:bg-pop-gum hover:scale-[1.01] transition">
                        SIMPAN PERUBAHAN 💾
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>