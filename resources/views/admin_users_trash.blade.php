<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10 font-sans">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-black text-pop-hibiscus">🚷 Penjara User (Kicked)</h1>
                <a href="{{ route('admin.dashboard') }}" class="bg-white border-2 border-pop-hibiscus text-pop-hibiscus font-bold px-6 py-2 rounded-full hover:bg-pop-hibiscus hover:text-white transition">
                    ⬅️ Kembali
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-soft overflow-hidden border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-red-50 text-red-400 uppercase text-xs font-bold">
                        <tr>
                            <th class="p-4">User Info</th>
                            <th class="p-4">Waktu Kick</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deletedUsers as $user)
                        <tr class="border-b border-gray-100 hover:bg-red-50/30">
                            <td class="p-4">
                                <div class="font-black text-gray-700">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                            </td>
                            <td class="p-4 text-xs text-gray-500 font-mono">
                                {{ $user->deleted_at->diffForHumans() }}
                            </td>
                            <td class="p-4 flex justify-end gap-2">
                                <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-pop-lime text-pop-dark px-3 py-1 rounded-lg text-xs font-bold hover:bg-green-400">
                                        😇 Ampuni
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.force', $user->id) }}" method="POST" onsubmit="return confirm('Yakin? Hapus selamanya!')">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-100 text-red-500 px-3 py-1 rounded-lg text-xs font-bold hover:bg-red-600 hover:text-white">
                                        💀 Musnahkan
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-10 text-center text-gray-400 font-bold">
                                Kosong! Belum ada user nakal yang di-kick.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>