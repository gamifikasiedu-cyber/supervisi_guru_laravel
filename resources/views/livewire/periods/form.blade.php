<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 max-w-xl">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-sm font-medium">Tahun Ajaran (cth: 2026/2027)</label>
                <input wire:model="tahun_ajaran" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border" placeholder="2026/2027">
                @error('tahun_ajaran') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Semester</label>
                <select wire:model="semester" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
                <a href="{{ route('periods.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
