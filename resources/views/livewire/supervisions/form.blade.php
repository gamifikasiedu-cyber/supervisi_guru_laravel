<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 max-w-2xl">
        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Guru</label>
                    <select wire:model="teacher_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                        <option value="">-- Pilih guru --</option>
                        @foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                    </select>
                    @error('teacher_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Supervisor</label>
                    <select wire:model="supervisor_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                        <option value="">-- Pilih supervisor --</option>
                        @foreach($supervisors as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                    </select>
                    @error('supervisor_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Mata Pelajaran</label>
                    <select wire:model="subject_id" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                        <option value="">-- Pilih mapel --</option>
                        @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach
                    </select>
                    @error('subject_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Kelas</label>
                    <input wire:model="class_name" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border" placeholder="cth: XII RPL 1">
                    @error('class_name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Tanggal</label>
                    <input type="date" wire:model="schedule_date" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    @error('schedule_date') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select wire:model="status" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                        <option value="Scheduled">Terjadwal</option>
                        <option value="Completed">Selesai</option>
                        <option value="Cancelled">Batal</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="text-sm font-medium">Catatan</label>
                <textarea wire:model="notes" rows="2" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border"></textarea>
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
                <a href="{{ route('supervisions.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
