<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-pop-candy p-4 relative overflow-hidden">
        <div class="absolute top-[-50px] right-[-50px] w-64 h-64 bg-pop-lime rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse"></div>
        <div class="absolute bottom-[-50px] left-[-50px] w-64 h-64 bg-pop-gum rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse"></div>

        <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-soft overflow-hidden relative z-10 border-4 border-white">
            
            <!-- Header -->
            <div class="bg-pop-lime p-8 text-center relative">
                <h1 class="text-3xl font-black text-pop-dark tracking-tight">PASSWORD BARU 🔐</h1>
                <p class="text-pop-dark/70 text-sm mt-1 font-bold">Jangan sampai lupa lagi ya!</p>
            </div>

            <div class="p-8">
                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('password.custom_update') }}">
                    @csrf

                    <!-- Password Baru -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Password Baru</label>
                        <input type="password" name="password" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-lime" placeholder="••••••••">
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Ulangi Password</label>
                        <input type="password" name="password_confirmation" required class="w-full bg-gray-50 border-none rounded-2xl py-3 px-5 font-bold text-pop-dark focus:ring-2 focus:ring-pop-lime" placeholder="••••••••">
                    </div>

                    <button type="submit" class="w-full bg-pop-hibiscus text-white font-black py-4 rounded-2xl shadow-lg hover:bg-pop-gum transition hover:scale-[1.02]">
                        SIMPAN PASSWORD BARU ✨
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>