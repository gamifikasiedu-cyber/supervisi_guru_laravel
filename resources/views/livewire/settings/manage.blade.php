<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 max-w-2xl">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-sm font-medium">Nama Sekolah</label>
                <input wire:model="school_name" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
            </div>
            <div>
                <label class="text-sm font-medium">Alamat</label>
                <textarea wire:model="address" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-sm font-medium">NPSN</label>
                    <input wire:model="npsn" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                </div>
                <div>
                    <label class="text-sm font-medium">Semester</label>
                    <select wire:model="semester" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                        <option value="">--</option>
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium">Tahun Ajaran</label>
                <input wire:model="tahun_ajaran" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border" placeholder="2026/2027">
            </div>
            <div>
                <label class="text-sm font-medium">Logo</label>
                <div class="flex items-center gap-3 mt-1">
                    @if($logo_url)<img src="{{ $logo_url }}" class="w-12 h-12 rounded-lg border object-contain">@endif
                    <input type="file" wire:model="logo" accept="image/*" class="text-sm">
                </div>
                <div wire:loading wire:target="logo" class="text-xs text-indigo-600 mt-1">Mengunggah…</div>
                @error('logo') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
        </form>
    </div>
</div>
