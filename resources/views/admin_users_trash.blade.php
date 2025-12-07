<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-20 font-sans" x-data="{ showEditReasonModal: false, editUrl: '', currentReason: '', editUserName: '' }">
        
        <div class="bg-pop-hibiscus pb-20 pt-10 px-4 rounded-b-[3rem] shadow-soft relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            <div class="max-w-7xl mx-auto relative z-10 text-center">
                <span class="bg-pop-lime text-pop-dark text-xs font-black px-3 py-1 rounded-full uppercase tracking-widest mb-4 inline-block">
                    Trash Bin V3.0
                </span>
                <h1 class="text-4xl md:text-5xl font-black text-white mb-2 tracking-tight"> DAFTAR USER TER-KICK</h1>
                <p class="text-pink-200 font-medium text-lg mb-8">Mereka yang telah melanggar aturan komunitas.</p>

                <a href="{{ route('admin.dashboard') }}" class="bg-white text-pop-hibiscus font-black px-8 py-3 rounded-full hover:bg-pop-lime hover:text-pop-dark transition shadow-lg inline-flex items-center gap-2 border-4 border-transparent hover:border-white">
                    ⬅️ KEMBALI KE DASHBOARD
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
            
            @if($deletedUsers->count() > 0)
            <div class="flex justify-center mb-10">
                <div class="bg-white p-6 rounded-[2rem] shadow-lg border-b-8 border-pop-hibiscus flex items-center gap-4 transform hover:-translate-y-1 transition max-w-md">
                    <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center text-3xl">🚷</div>
                    <div>
                        <p class="text-gray-400 font-bold text-xs uppercase">Total User Di-Kick</p>
                        <h3 class="text-4xl font-black text-pop-dark">{{ $deletedUsers->count() }}</h3>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-soft overflow-hidden border border-gray-100">
                <div class="bg-pop-candy/30 p-6 border-b border-gray-100">
                    <h3 class="text-xl font-black text-pop-hibiscus">🗑️ Daftar User yang Di-Kick</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase text-xs">
                            <tr>
                                <th class="px-6 py-4 text-center">#</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Alasan Kick</th>
                                <th class="px-6 py-4 text-center">Waktu</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($deletedUsers as $index => $user)
                            <tr class="hover:bg-red-50/30 transition group">
                                <td class="px-6 py-4 text-center">
                                    <span class="font-black text-gray-300 text-lg">#{{ $index + 1 }}</span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="font-black text-pop-dark text-base flex items-center gap-2">
                                        {{ $user->name }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-gray-600 font-medium">{{ $user->email }}</div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="relative">
                                        <div class="bg-gray-50 p-4 pr-12 rounded-2xl border border-gray-200 group-hover:border-red-200 transition max-w-md">
                                            <p class="text-gray-700 italic text-sm leading-relaxed break-words whitespace-pre-wrap">"{{ $user->ban_reason ?? 'Tidak ada alasan' }}"</p>
                                        </div>
                                        
                                        <button 
                                            @click="showEditReasonModal = true; 
                                                    editUrl = '{{ route('admin.users.updateReason', $user->id) }}'; 
                                                    currentReason = $el.getAttribute('data-reason');
                                                    editUserName = '{{ addslashes($user->name) }}'"
                                            data-reason="{{ $user->ban_reason }}"
                                            class="absolute top-3 right-3 text-gray-400 hover:text-blue-500 transition hover:scale-110 p-2 bg-white rounded-full shadow-sm border border-gray-200 hover:border-blue-300" 
                                            title="Edit Alasan">
                                            ✏️
                                        </button>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block text-xs font-bold text-red-600 bg-red-100 px-3 py-1.5 rounded-full border border-red-200">
                                        {{ $user->deleted_at->diffForHumans() }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-2 items-end">
                                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                                            @csrf
                                            <button class="bg-green-100 hover:bg-green-500 text-green-600 hover:text-white px-4 py-2 rounded-xl text-sm font-bold transition flex items-center justify-center gap-2 w-28 shadow-sm hover:shadow-md border border-green-200 hover:border-green-500">
                                                <span class="text-base">♻️</span>
                                                Restore
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.users.force', $user->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Data user dan semua catatannya akan dihapus PERMANEN. Lanjutkan?')">
                                            @csrf @method('DELETE')
                                            <button class="bg-red-100 hover:bg-red-500 text-red-500 hover:text-white px-4 py-2 rounded-xl text-sm font-bold transition flex items-center justify-center gap-2 w-28 shadow-sm hover:shadow-md border border-red-200 hover:border-red-500">
                                                <span class="text-base">🔥</span>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-gray-400">
                                    <div class="text-6xl mb-4 grayscale opacity-50">🗑️</div>
                                    <h3 class="text-lg font-bold text-gray-500">Tong sampah kosong!</h3>
                                    <p class="text-sm">Tidak ada user nakal hari ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div x-show="showEditReasonModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div x-show="showEditReasonModal" @click="showEditReasonModal = false" class="fixed inset-0 bg-pop-hibiscus/60 backdrop-blur-sm transition-opacity"></div>
            
            <div x-show="showEditReasonModal" class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl p-8 relative z-10 border-4 border-white transform transition-all">
                
                <div class="text-center mb-6">
                    <div class="text-5xl mb-2">✏️</div>
                    <h2 class="text-2xl font-black text-pop-hibiscus mb-1">Edit Alasan</h2>
                    <p class="text-sm text-gray-500 font-medium">Update alasan kick untuk <span class="font-black text-pop-hibiscus" x-text="editUserName"></span></p>
                </div>

                <form :action="editUrl" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-pop-hibiscus uppercase mb-2 ml-2">Alasan Baru</label>
                        <textarea name="ban_reason" rows="4" x-model="currentReason" required class="w-full bg-gray-50 border-2 border-pop-lime/50 rounded-2xl px-4 py-3 focus:border-pop-lime focus:ring-0 transition font-medium text-gray-700 placeholder-gray-300 resize-none"></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="showEditReasonModal = false" class="w-1/3 bg-gray-100 text-gray-500 font-bold py-3 rounded-xl hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="w-2/3 bg-pop-hibiscus hover:bg-pop-hibiscus/90 text-white font-bold py-3 rounded-xl shadow-lg transition flex items-center justify-center gap-2">
                            💾 Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>