<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <form wire:submit="save">
            <div class="px-5 py-4 grid grid-cols-1 md:grid-cols-4 gap-3 border-b border-slate-100">
                <div>
                    <label class="text-sm font-medium">Guru</label>
                    <select wire:model="teacher_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                        <option value="">-- Pilih --</option>
                        @foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Mapel</label>
                    <select wire:model="subject_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                        <option value="">-- Pilih --</option>
                        @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium">Kelas</label>
                    <input wire:model="class_name" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                </div>
                <div>
                    <label class="text-sm font-medium">Tanggal</label>
                    <input type="date" wire:model="observation_date" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-2.5 py-1.5 border">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[1000px]">
                    <thead>
                        <tr class="text-left text-xs uppercase text-slate-400 border-b border-slate-100">
                            <th class="px-4 py-2.5 w-10">No</th>
                            <th class="px-4 py-2.5">Pertanyaan / Fokus</th>
                            <th class="px-4 py-2.5 w-44">Catatan Guru</th>
                            <th class="px-4 py-2.5 w-44">Catatan Supervisor</th>
                            <th class="px-4 py-2.5 w-44">Kesepakatan</th>
                            <th class="px-4 py-2.5 w-44">Tindak Lanjut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($items as $i => $it)
                            <tr>
                                <td class="px-4 py-2 text-slate-400">{{ $i + 1 }}</td>
                                <td class="px-4 py-2 text-slate-700">{{ $it['pertanyaan'] }}</td>
                                <td class="px-4 py-2"><textarea wire:model="items.{{ $i }}.catatan_guru" rows="2" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></textarea></td>
                                <td class="px-4 py-2"><textarea wire:model="items.{{ $i }}.catatan_supervisor" rows="2" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></textarea></td>
                                <td class="px-4 py-2"><textarea wire:model="items.{{ $i }}.kesepakatan" rows="2" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></textarea></td>
                                <td class="px-4 py-2"><textarea wire:model="items.{{ $i }}.tindak_lanjut" rows="2" class="block w-full rounded-lg border-slate-300 text-sm px-2 py-1.5 border"></textarea></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-slate-100">
                <label class="text-sm font-medium">Foto Dokumentasi (maks 5)</label>
                <input type="file" wire:model="photos" accept="image/*" multiple class="mt-1 block w-full text-sm">
                <div wire:loading wire:target="photos" class="text-xs text-indigo-600 mt-1">Mengunggah…</div>
                @error('photos') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                @if(count($existingPhotos))
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach($existingPhotos as $idx => $path)
                            <div class="relative">
                                <img src="{{ Storage::url($path) }}" class="w-24 h-24 object-cover rounded-lg border">
                                <button type="button" wire:click="removeExistingPhoto({{ $idx }})" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-rose-600 text-white text-xs">✕</button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="px-5 py-4 flex gap-2">
                <button class="px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
                <a href="{{ route('konferensis.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
