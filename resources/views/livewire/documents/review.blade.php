<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 max-w-2xl space-y-4">
        <div>
            <h3 class="font-semibold text-slate-800">{{ $document->title }}</h3>
            <p class="text-sm text-slate-500">{{ $document->user->name ?? '-' }} · {{ $document->subject->name ?? '-' }} · {{ $document->file_size ?? '-' }}</p>
            @if($document->description)<p class="text-sm text-slate-600 mt-1">{{ $document->description }}</p>@endif
            <a href="{{ route('documents.download', $document) }}" class="inline-block mt-2 text-sm px-3 py-1.5 rounded-lg bg-sky-100 text-sky-700 hover:bg-sky-200">⬇ Unduh File</a>
        </div>
        <form wire:submit="save" class="space-y-4 border-t border-slate-100 pt-4">
            <div>
                <label class="text-sm font-medium">Status</label>
                <select wire:model="status" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    <option value="pending">Menunggu</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
                @error('status') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Catatan Review</label>
                <textarea wire:model="review_notes" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border"></textarea>
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">Simpan Review</button>
                <a href="{{ route('documents.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
