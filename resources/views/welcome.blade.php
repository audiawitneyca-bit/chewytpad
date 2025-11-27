<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChewytPad - Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="bg-pop-candy font-sans antialiased flex flex-col min-h-screen relative overflow-hidden">

    <!-- Dekorasi Latar Belakang -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-pop-lime rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-pop-gum rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen p-4">
        
        <!-- Logo Besar -->
        <div class="mb-10 transform hover:scale-105 transition duration-500">
            <div class="flex items-center justify-center gap-3">
                <span class="bg-pop-lime text-pop-hibiscus w-20 h-20 md:w-24 md:h-24 rounded-full flex items-center justify-center text-5xl md:text-6xl font-black border-4 border-white shadow-soft">C</span>
                <h1 class="text-6xl md:text-8xl font-black text-white drop-shadow-lg tracking-tighter">
                    CHEWYT<span class="text-pop-hibiscus">PAD.</span>
                </h1>
            </div>
            <p class="text-center text-pop-hibiscus font-bold text-xl mt-4 bg-white/50 py-2 px-6 rounded-full backdrop-blur-sm">
                Catatan Digital Gen-Z Paling Aesthetic ✨
            </p>
        </div>

        <!-- Card Pilihan -->
        <div class="bg-white/80 backdrop-blur-md border-2 border-white rounded-[3rem] p-8 md:p-12 w-full max-w-2xl shadow-soft text-center">
            <h2 class="text-3xl font-black text-pop-dark mb-8">Mau ngapain hari ini?</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pilihan Login -->
                <a href="{{ route('login') }}" class="group relative bg-pop-lime rounded-3xl p-6 transition hover:-translate-y-2 hover:shadow-glow cursor-pointer border border-transparent hover:border-white">
                    <div class="text-4xl mb-2 group-hover:scale-110 transition duration-300">🔑</div>
                    <h3 class="text-2xl font-black text-pop-dark">LOGIN</h3>
                    <p class="text-sm font-bold text-gray-600 mt-2">Sudah punya akun? Masuk sini!</p>
                </a>

                <!-- Pilihan Register -->
                <a href="{{ route('register') }}" class="group relative bg-pop-gum rounded-3xl p-6 transition hover:-translate-y-2 hover:shadow-soft cursor-pointer border border-transparent hover:border-white">
                    <div class="text-4xl mb-2 group-hover:scale-110 transition duration-300">✍️</div>
                    <h3 class="text-2xl font-black text-white">BUAT AKUN</h3>
                    <p class="text-sm font-bold text-white/80 mt-2">Pengguna baru? Daftar dulu dong.</p>
                </a>
            </div>

            <!-- Info Total User -->
            <div class="mt-8 text-pop-gum font-black text-lg">
                🚀 Bergabunglah dengan <span class="text-pop-hibiscus font-black">1000+</span> user keren lainnya!
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-pop-hibiscus font-bold text-sm opacity-70">
            &copy; 2025 ChewytPad Project.
        </div>
    </div>
</body>
</html>