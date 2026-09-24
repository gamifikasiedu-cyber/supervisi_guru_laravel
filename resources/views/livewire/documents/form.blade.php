<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 max-w-2xl">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="text-sm font-medium">Judul</label>
                <input wire:model="title" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                @error('title') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Jenis Dokumen</label>
                    <select wire:model="document_type" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                        <option value="">-- Pilih jenis --</option>
                        @foreach($types as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach
                    </select>
                    @error('document_type') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Mata Pelajaran (opsional)</label>
                    <select wire:model="subject_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                        <option value="">-- Tanpa mapel --</option>
                        @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium">Deskripsi (opsional)</label>
                <textarea wire:model="description" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border"></textarea>
            </div>
            <div>
                <label class="text-sm font-medium">File (.rar / .zip, maks 100MB) {{ $document ? '(kosongkan jika tidak diganti)' : '' }}</label>
                <input type="file" wire:model="file" accept=".rar,.zip" class="mt-1 block w-full text-sm">
                <div wire:loading wire:target="file" class="text-xs text-indigo-600 mt-1">Mengunggah…</div>
                @error('file') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
                <a href="{{ route('documents.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
