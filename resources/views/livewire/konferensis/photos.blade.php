<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 md:p-5">
        <div class="flex justify-between items-center flex-wrap gap-2 mb-1">
            <div>
                <h3 class="font-semibold text-slate-800">Foto Dokumentasi Konferensi</h3>
                <p class="text-sm text-slate-500">{{ $konferensi->teacher->name ?? '-' }} · {{ $konferensi->class_name ?? '-' }} · {{ $konferensi->observation_date?->format('d/m/Y') }}</p>
            </div>
            <div class="flex gap-2">
                @if(count($photos))
                    <a href="{{ route('konferensis.photos-download', $konferensi) }}" class="text-sm px-3 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">⬇ Unduh Semua (ZIP)</a>
                @endif
                <a href="{{ route('konferensis.index') }}" class="text-sm px-3 py-1.5 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200">← Kembali</a>
            </div>
        </div>

        @if(! count($photos))
            <p class="py-10 text-center text-slate-400 text-sm">Belum ada foto dokumentasi. Tambahkan lewat halaman Edit.</p>
        @else
            <p class="text-xs text-slate-400 mb-3">Klik foto untuk preview. {{ count($photos) }} foto.</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($photos as $i => $f)
                    <div class="relative group">
                        <button wire:click="openPreview({{ $i }})" class="block w-full">
                            <img src="{{ $f['url'] }}" alt="Dokumentasi {{ $i + 1 }}" loading="lazy"
                                class="w-full aspect-square object-cover rounded-xl border border-slate-200 group-hover:ring-2 group-hover:ring-indigo-400">
                        </button>
                        <a href="{{ route('konferensis.photo-download', [$konferensi, $i]) }}"
                            class="absolute bottom-2 right-2 text-xs px-2.5 py-1 rounded-lg bg-slate-900/70 text-white hover:bg-slate-900">⬇ Unduh</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if($preview !== null && isset($photos[$preview]))
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/90 p-4" wire:click.self="closePreview">
            <button wire:click="closePreview" class="absolute top-4 right-4 text-white/70 hover:text-white text-2xl" aria-label="Tutup">✕</button>
            @if(count($photos) > 1)
                <button wire:click="step(-1)" class="absolute left-2 md:left-6 text-white/70 hover:text-white text-3xl px-2" aria-label="Sebelumnya">‹</button>
                <button wire:click="step(1)" class="absolute right-2 md:right-6 text-white/70 hover:text-white text-3xl px-2" aria-label="Berikutnya">›</button>
            @endif
            <div class="max-w-4xl w-full text-center" wire:click.stop>
                <img src="{{ $photos[$preview]['url'] }}" alt="Preview" class="max-h-[75vh] mx-auto rounded-xl object-contain">
                <div class="mt-3 flex items-center justify-center gap-3">
                    <span class="text-white/70 text-sm">{{ $preview + 1 }} / {{ count($photos) }}</span>
                    <a href="{{ route('konferensis.photo-download', [$konferensi, $preview]) }}"
                        class="text-sm px-4 py-1.5 rounded-lg bg-white text-slate-800 font-medium hover:bg-slate-100">⬇ Unduh Foto Ini</a>
                </div>
            </div>
        </div>
    @endif
</div>
