<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-20 font-sans" x-data="{ showKickModal: false, kickUrl: '', kickUserName: '' }">
        
        <div class="bg-pop-hibiscus pb-20 pt-10 px-4 rounded-b-[3rem] shadow-soft relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            <div class="max-w-7xl mx-auto relative z-10 text-center">
                <span class="bg-pop-lime text-pop-dark text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest mb-4 inline-block">Control Panel v3.0</span>
                <h1 class="text-4xl md:text-5xl font-black text-white mb-2 tracking-tight">PUSAT KONTROL ADMIN</h1>
                <p class="text-pop-candy font-medium text-lg mb-6">Pantau siapa yang paling rajin mencatat di sini.</p>

                <a href="{{ route('admin.users.trash') }}" class="bg-white text-pop-hibiscus font-black px-8 py-3 rounded-full hover:bg-pop-lime hover:text-pop-dark transition shadow-lg inline-flex items-center gap-2 border-4 border-transparent hover:border-white">
                   🚷 LIHAT USER YANG DI-KICK
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-[2rem] shadow-lg border-b-8 border-pop-hibiscus flex items-center gap-4 transform hover:-translate-y-1 transition">
                    <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center text-3xl">👥</div>
                    <div>
                        <p class="text-gray-400 font-bold text-xs uppercase">Total User</p>
                        <h3 class="text-4xl font-black text-pop-dark">{{ $totalUsers }}</h3>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] shadow-lg border-b-8 border-pop-lime flex items-center gap-4 transform hover:-translate-y-1 transition">
                    <div class="w-16 h-16 bg-lime-100 rounded-2xl flex items-center justify-center text-3xl">📝</div>
                    <div>
                        <p class="text-gray-400 font-bold text-xs uppercase">Total Catatan</p>
                        <h3 class="text-4xl font-black text-pop-dark">{{ $totalNotes }}</h3>
                    </div>
                </div>

                <div class="bg-pop-dark p-6 rounded-[2rem] shadow-lg border-b-8 border-gray-600 flex items-center gap-4 text-white transform hover:-translate-y-1 transition">
                    <div class="w-16 h-16 bg-gray-700 rounded-2xl flex items-center justify-center text-3xl animate-pulse">🟢</div>
                    <div>
                        <p class="text-gray-400 font-bold text-xs uppercase">Status Sistem</p>
                        <h3 class="text-2xl font-black">ONLINE</h3>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="bg-white rounded-[2rem] shadow-soft overflow-hidden border border-gray-100 h-fit">
                    <div class="bg-pop-candy/30 p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-xl font-black text-pop-hibiscus">📡 Mata-Mata Aktivitas</h3>
                        <span class="text-xs bg-pop-hibiscus text-white px-2 py-1 rounded-lg animate-pulse">Live</span>
                    </div>
                    
                    <div class="p-6 space-y-6 max-h-[600px] overflow-y-auto">
                        @forelse($latestActivities as $activity)
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex-shrink-0 flex items-center justify-center font-black text-gray-400 text-sm border-2 border-white shadow-sm">
                                {{ substr($activity->user->name, 0, 1) }}
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-2xl rounded-tl-none w-full text-sm hover:bg-pop-lime/20 transition">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-black text-pop-dark">{{ $activity->user->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <p class="text-gray-600 font-medium italic">"Menambahkan catatan baru"</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-400 py-10">Belum ada aktivitas... sepi amat.</div>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-8">
                    
                    @if($favoriteUsers->count() > 0)
                    <div class="bg-yellow-50 rounded-[2rem] shadow-soft border-4 border-yellow-200 p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 text-6xl opacity-10">⭐</div>
                        <h3 class="text-xl font-black text-yellow-600 mb-4 flex items-center gap-2">
                            <span>⭐</span> User Kesayangan Admin
                        </h3>
                        <div class="flex flex-wrap gap-4">
                            @foreach($favoriteUsers as $fav)
                            <div class="bg-white p-3 rounded-2xl shadow-sm border-2 border-yellow-100 flex items-center gap-3 pr-6">
                                <div class="w-10 h-10 bg-yellow-200 rounded-full flex items-center justify-center font-black text-yellow-700">
                                    {{ substr($fav->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-sm">{{ $fav->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $fav->notes_count }} Catatan</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-[2rem] shadow-soft overflow-hidden border border-gray-100">
                        <div class="bg-pop-candy/30 p-6 border-b border-gray-100">
                            <h3 class="text-xl font-black text-pop-hibiscus">🏆 Papan Peringkat Warga</h3>
                            <p class="text-xs text-gray-500">Diurutkan dari yang paling rajin nulis.</p>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-xs">
                                    <tr>
                                        <th class="px-6 py-4 text-center">Rank</th>
                                        <th class="px-6 py-4">User</th>
                                        <th class="px-6 py-4 text-center">Kontribusi</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($users as $index => $user)
                                    <tr class="hover:bg-gray-50 transition group">
                                        <td class="px-6 py-4 text-center">
                                            @if($index == 0) 
                                                <span class="text-2xl">🥇</span>
                                            @elseif($index == 1)
                                                <span class="text-2xl">🥈</span>
                                            @elseif($index == 2)
                                                <span class="text-2xl">🥉</span>
                                            @else
                                                <span class="font-black text-gray-300 text-lg">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="font-black text-pop-dark text-base flex items-center gap-2">
                                                {{ $user->name }}
                                                @if($user->is_favorite)
                                                    <span class="text-xs bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full border border-yellow-200">Kesayangan</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <span class="bg-pop-lime text-pop-dark font-bold px-4 py-1 rounded-full text-xs border border-black/10">
                                                {{ $user->notes_count }} Catatan
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                                            <form action="{{ route('admin.users.favorite', $user->id) }}" method="POST">
                                                @csrf
                                                <button class="w-8 h-8 rounded-full flex items-center justify-center transition {{ $user->is_favorite ? 'bg-yellow-400 text-white' : 'bg-gray-100 text-gray-300 hover:bg-yellow-200 hover:text-yellow-600' }}" title="{{ $user->is_favorite ? 'Hapus dari Favorit' : 'Jadikan Favorit' }}">
                                                    ⭐
                                                </button>
                                            </form>

                                            <button 
                                                @click="showKickModal = true; kickUrl = '{{ route('admin.deleteUser', $user->id) }}'; kickUserName = '{{ $user->name }}'"
                                                class="bg-red-100 text-red-500 px-3 py-1 rounded-lg text-xs font-bold hover:bg-red-500 hover:text-white transition h-8 flex items-center gap-1">
                                                ⛔ Kick
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div x-show="showKickModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div x-show="showKickModal" @click="showKickModal = false" class="fixed inset-0 bg-pop-hibiscus/60 backdrop-blur-sm transition-opacity"></div>
            
            <div x-show="showKickModal" class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 relative z-10 border-4 border-white transform transition-all">
                
                <div class="text-center mb-6">
                    <div class="text-6xl mb-4">🚨</div>
                    <h2 class="text-2xl font-black text-red-500 mb-2">Kick User?</h2>
                    <p class="text-gray-500">
                        Kamu akan menendang <span class="font-black text-pop-dark" x-text="kickUserName"></span>.
                        <br>Berikan alasan yang jelas ya!
                    </p>
                </div>

                <form :action="kickUrl" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-2">Alasan Kick</label>
                        <textarea name="ban_reason" rows="3" required placeholder="Contoh: Melanggar aturan komunitas, spam, dll..." class="w-full bg-red-50 border-2 border-red-100 rounded-2xl px-4 py-3 focus:border-red-400 focus:ring-0 transition font-medium text-pop-dark placeholder-red-200"></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="showKickModal = false" class="w-1/3 bg-gray-100 text-gray-500 font-bold py-3 rounded-xl hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="w-2/3 bg-red-500 text-white font-bold py-3 rounded-xl shadow-lg hover:bg-red-600 transition flex items-center justify-center gap-2">
                            👋 Bye-bye
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>