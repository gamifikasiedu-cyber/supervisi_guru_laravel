<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
            <h3 class="font-semibold text-slate-800">Pemantauan</h3>
            <div class="flex gap-2">
                <a href="{{ route('pemantauan.rekapitulasi') }}" class="text-sm px-3 py-1.5 rounded-lg bg-violet-100 text-violet-700 hover:bg-violet-200">Rekapitulasi</a>
                <a href="{{ route('pemantauan.create') }}" class="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">+ Isi Pemantauan</a>
            </div>
        </div>
        <livewire:tables.instrument-table />
    </div>
</div>
