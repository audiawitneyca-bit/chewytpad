<x-app-layout>
    <div class="min-h-screen bg-pop-candy py-10 font-sans">
        <div class="max-w-2xl mx-auto px-4">
            
            <a href="{{ route('dashboard') }}" class="text-pop-hibiscus font-bold mb-4 inline-block hover:underline">⬅️ Kembali ke Dashboard</a>

            <div class="bg-white rounded-[2rem] shadow-soft p-8 md:p-12">
                <h1 class="text-3xl font-black text-pop-hibiscus mb-2">Edit Profil ⚙️</h1>
                <p class="text-gray-500 mb-8">Ganti nama biar makin keren sesuai kepribadianmu.</p>

                @if(session('success'))
                    <div class="bg-pop-lime/30 text-pop-dark p-4 rounded-xl font-bold mb-6 text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Nama Kamu</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 font-black text-xl focus:ring-2 focus:ring-pop-hibiscus">
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 font-bold text-gray-600 focus:ring-2 focus:ring-pop-hibiscus">
                    </div>

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