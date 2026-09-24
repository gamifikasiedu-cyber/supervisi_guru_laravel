<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
            <h3 class="font-semibold text-slate-800">Pra Observasi / Telaah RPP</h3>
            <div class="flex items-center gap-2">
                @if(auth()->user()->hasRole('admin', 'supervisor', 'kepala_sekolah', 'pengawas'))
                <div x-data="{ open: false }" class="relative inline-block text-left">
                    <button type="button" @click="open = ! open"
                        class="text-sm px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200 inline-flex items-center gap-1.5">
                        💬 Konferensi-Wawancara
                        <span class="text-[10px]">▼</span>
                    </button>
                    <div x-cloak x-show="open" @click.outside="open = false" x-transition
                        class="absolute right-0 z-30 mt-1 w-48 origin-top-right rounded-xl bg-white py-1 shadow-xl ring-1 ring-slate-900/5 text-sm">
                        <a href="{{ route('konferensis.index') }}"
                            class="flex items-center gap-2.5 px-4 py-2 text-slate-600 hover:bg-slate-50">
                            <span class="w-4 text-center">📋</span><span>Lihat Konferensi</span>
                        </a>
                        <a href="{{ route('konferensis.create') }}"
                            class="flex items-center gap-2.5 px-4 py-2 text-slate-600 hover:bg-slate-50">
                            <span class="w-4 text-center">＋</span><span>Isi Konferensi</span>
                        </a>
                    </div>
                </div>
                @endif
                <a href="{{ route('pre-observations.create') }}" class="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">+ Isi Baru</a>
            </div>
        </div>
        <livewire:tables.pre-observation-table />
    </div>
</div>
