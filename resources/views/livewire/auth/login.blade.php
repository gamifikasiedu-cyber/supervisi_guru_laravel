<div>
    <h2 class="text-lg font-bold text-slate-800">Selamat datang kembali</h2>
    <p class="text-sm text-slate-500 mb-5">Masuk untuk mengelola supervisi akademik</p>

    <!-- KODE TAMBAHAN UNTUK UJI COBA SINKRONISASI -->
    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs rounded-lg font-medium">
        ✨ Uji Coba Sinkronisasi dari VS Code Berhasil! coba lagi agus iki d hhassd
    </div>

    <form wire:submit="login" class="space-y-4">
        <div>
            <label class="text-sm font-medium text-slate-700">Email</label>
            <input type="email" wire:model="email" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2.5 border focus:border-indigo-500 focus:ring-indigo-500" placeholder="nama@sekolah.id" autofocus>
            @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Password</label>
            <input type="password" wire:model="password" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2.5 border focus:border-indigo-500 focus:ring-indigo-500" placeholder="••••••••">
            @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>
        @if($periods->isNotEmpty())
            <div>
                <label class="text-sm font-medium text-slate-700">Periode Akademik</label>
                <select wire:model="period_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2.5 border focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Tanpa periode --</option>
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}">{{ $p->tahun_ajaran }} · {{ $p->semester }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <label class="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" wire:model="remember" class="rounded border-slate-300 text-indigo-600"> Ingat saya
        </label>
        <button type="submit" wire:loading.attr="disabled" class="w-full py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 disabled:opacity-50 transition">
            <span wire:loading.remove>Masuk</span><span wire:loading>Memproses…</span>
        </button>
    </form>
</div>