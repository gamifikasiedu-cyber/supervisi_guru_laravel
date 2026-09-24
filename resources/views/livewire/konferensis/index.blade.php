<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
            <h3 class="font-semibold text-slate-800">Konferensi-Wawancara</h3>
            <a href="{{ route('konferensis.create') }}" class="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">+ Isi Baru</a>
        </div>
        <livewire:tables.konferensi-table />
    </div>
</div>
