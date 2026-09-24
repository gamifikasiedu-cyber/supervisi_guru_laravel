<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex justify-between items-center flex-wrap gap-2 mb-3">
            <h3 class="font-semibold text-slate-800">Dokumen Perangkat Ajar</h3>
            <a href="{{ route('documents.create') }}" class="text-sm px-3 py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">+ Unggah</a>
        </div>
        <livewire:tables.teaching-document-table />
    </div>
</div>
