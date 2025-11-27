<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-pop-candy p-4 relative overflow-hidden">
        <!-- Dekorasi -->
        <div class="absolute top-10 left-10 w-32 h-32 bg-pop-lime rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-bounce"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-pop-hibiscus rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-pulse"></div>

        <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-soft overflow-hidden relative z-10 border-4 border-white">
            
            <!-- Header Login -->
            <div class="bg-pop-hibiscus p-8 text-center relative overflow-hidden">
                 <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <h1 class="text-3xl font-black text-white tracking-tight">WELCOME BACK! 👋</h1>
                <p class="text-pink-100 text-sm mt-1">Kangen nyatet ya?</p>
            </div>

            <div class="p-8">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-hibiscus" placeholder="email@kamu.com">
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Password</label>
                        <input type="password" name="password" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-hibiscus" placeholder="••••••••">
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-pop-hibiscus shadow-sm focus:border-pop-hibiscus focus:ring focus:ring-pop-hibiscus focus:ring-opacity-50">
                            <span class="ml-2 text-sm font-bold text-gray-500">Ingat Aku</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-bold text-pop-gum hover:text-pop-hibiscus hover:underline" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-pop-lime text-pop-dark font-black py-4 rounded-2xl shadow-lg hover:bg-green-400 hover:scale-[1.02] transition transform duration-200">
                        MASUK SEKARANG 🚀
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm font-bold text-gray-400">Belum punya akun?</p>
                    <a href="{{ route('register') }}" class="text-pop-hibiscus font-black hover:underline">Buat Akun Baru</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>