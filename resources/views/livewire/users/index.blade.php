<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
            <h3 class="font-semibold text-slate-800">Pengguna</h3>
            <div class="flex items-center gap-2 flex-wrap">
                <form method="POST" action="{{ route('users.import') }}" enctype="multipart/form-data" class="flex items-center gap-2">
                    @csrf
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="text-xs max-w-[180px]">
                    <button class="text-sm px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">⬆ Impor</button>
                </form>
                <a href="{{ route('users.export') }}" class="text-sm px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Excel</a>
                <form method="POST" action="{{ route('users.delete-all') }}" onsubmit="return confirm('Hapus SEMUA pengguna kecuali akun Anda?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="text-sm px-3 py-1.5 rounded-lg bg-rose-100 text-rose-700 hover:bg-rose-200">Hapus Semua</button>
                </form>
                <a href="{{ route('users.create') }}" class="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">+ Tambah</a>
            </div>
        </div>
        <livewire:tables.user-table />
    </div>
</div>
