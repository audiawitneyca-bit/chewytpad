<nav x-data="{ open: false }" class="bg-white border-b-4 border-pop-candy/50 sticky top-0 z-50 shadow-soft">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard.check') }}" class="text-2xl font-black text-pop-hibiscus tracking-tighter flex items-center gap-2">
                        <span class="bg-pop-lime text-pop-hibiscus w-10 h-10 rounded-full flex items-center justify-center text-xl border-2 border-pop-hibiscus">C</span>
                        <span class="hidden md:block">CHEWYT<span class="text-pop-hibiscus font-black">PAD.</span></span>
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    <div @click="open = ! open">
                        <button class="flex items-center text-sm font-bold font-sans text-pop-dark hover:text-pop-hibiscus hover:bg-pop-candy/30 px-3 py-2 rounded-full transition duration-150 ease-in-out cursor-pointer">
                            @if(Auth::user()->role == 'admin')
                                👑 {{ Auth::user()->name }} (Admin)
                            @else
                                🌸 {{ Auth::user()->name }}
                            @endif

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </div>

                    <div x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 z-50 mt-2 w-48 rounded-2xl shadow-xl bg-white border-2 border-pop-candy ring-1 ring-black ring-opacity-5 py-1 origin-top-right"
                            style="display: none;">
                        
                        <!-- MENU KHUSUS ADMIN (SWITCH ROLE) -->
                        @if(Auth::user()->role == 'admin')
                            <div class="px-4 py-2 text-xs text-gray-400 font-bold uppercase border-b border-gray-100">Switch Mode</div>
                            
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm leading-5 text-pop-dark hover:bg-pop-lime hover:font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-pop-candy font-bold' : '' }}">
                                🎛️ AdminChewytpad
                            </a>

                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm leading-5 text-pop-dark hover:bg-pop-candy hover:font-bold transition {{ request()->routeIs('dashboard') ? 'bg-pop-candy font-bold' : '' }}">
                                📝 AdminAsUser
                            </a>
                            
                            <div class="border-t border-gray-100 my-1"></div>
                        @endif

                        <!-- MENU GLOBAL (SEKARANG MUNCUL UNTUK ADMIN & USER) -->
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm leading-5 text-pop-dark hover:bg-pop-candy transition">
                            ⚙️ Edit Profil
                        </a>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="block px-4 py-2 text-sm leading-5 text-red-500 font-bold hover:bg-red-50 transition rounded-b-xl">
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>