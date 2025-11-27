<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-pop-candy p-4 relative overflow-hidden">
        <!-- Dekorasi Latar Belakang (Sama kayak Login) -->
        <div class="absolute top-[-50px] left-[-50px] w-64 h-64 bg-pop-lime rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse"></div>
        <div class="absolute bottom-[-50px] right-[-50px] w-64 h-64 bg-pop-gum rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse" style="animation-delay: 1s"></div>

        <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-soft overflow-hidden relative z-10 border-4 border-white transform transition-all hover:scale-[1.01]">
            
            <!-- Header Register (Pink Hibiscus) -->
            <div class="bg-pop-hibiscus p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <h1 class="text-3xl font-black text-white tracking-tight">JOIN THE CLUB! ✨</h1>
                <p class="text-pink-100 text-sm mt-1">Siap jadi lebih produktif bareng ChewytPad?</p>
            </div>

            <div class="p-8">
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Nama Panggilan</label>
                        <input type="text" name="name" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-hibiscus placeholder-gray-300" placeholder="Nama keren kamu..." value="{{ old('name') }}" autofocus>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-hibiscus placeholder-gray-300" placeholder="email@kamu.com" value="{{ old('email') }}">
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Password</label>
                        <input type="password" name="password" required autocomplete="new-password" class="w-full bg-gray-50 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-hibiscus placeholder-gray-300" placeholder="••••••••">
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Ulangi Password</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-hibiscus placeholder-gray-300" placeholder="••••••••">
                    </div>

                    <!-- Tombol Daftar (Warna Lime) -->
                    <button type="submit" class="w-full bg-pop-lime text-pop-dark font-black py-4 rounded-2xl shadow-lg hover:bg-green-400 hover:scale-[1.02] transition transform duration-200">
                        DAFTAR SEKARANG 🚀
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm font-bold text-gray-400">Sudah punya akun?</p>
                    <a href="{{ route('login') }}" class="text-pop-hibiscus font-black hover:underline">Login aja di sini</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>