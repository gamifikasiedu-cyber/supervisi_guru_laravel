<div>
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 max-w-5xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Informasi Akun</h3>
            <form wire:submit="saveInfo" class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input wire:model="name" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" wire:model="email" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
            </form>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="font-semibold text-slate-800 mb-1">Hapus Akun</h3>
            <p class="text-sm text-slate-500 mb-4">Tindakan ini permanen.</p>
            <form wire:submit="deleteAccount" class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Password saat ini</label>
                    <input type="password" wire:model="password" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button class="px-4 py-2 rounded-lg bg-rose-600 text-white text-sm font-medium hover:bg-rose-700">Hapus Akun</button>
            </form>
        </div>
    </div>
</div>
