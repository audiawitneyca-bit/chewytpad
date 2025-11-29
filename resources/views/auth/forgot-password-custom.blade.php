<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-pop-candy p-4 relative overflow-hidden">
        <!-- Dekorasi -->
        <div class="absolute top-[-50px] left-[-50px] w-64 h-64 bg-pop-lime rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse"></div>
        <div class="absolute bottom-[-50px] right-[-50px] w-64 h-64 bg-pop-gum rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse"></div>

        <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-soft overflow-hidden relative z-10 border-4 border-white">
            
            <!-- Header -->
            <div class="bg-pop-hibiscus p-8 text-center relative">
                <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <h1 class="text-3xl font-black text-white tracking-tight">LUPA PASSWORD? 😱</h1>
                <p class="text-pink-100 text-sm mt-1">Santai, masukkan emailmu di bawah.</p>
            </div>

            <div class="p-8">
                <!-- Pesan Error -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('password.custom_process') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Email Terdaftar</label>
                        <input type="email" name="email" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-hibiscus" placeholder="email@kamu.com" autofocus>
                    </div>

                    <button type="submit" class="w-full bg-pop-lime text-pop-dark font-black py-4 rounded-2xl shadow-lg hover:bg-green-400 transition hover:scale-[1.02]">
                        LANJUT GANTI PASSWORD ➡️
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-pop-hibiscus font-black hover:underline text-sm">⬅️ Kembali Login</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>