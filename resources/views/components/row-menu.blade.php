@props(['items' => []])

<details class="relative inline-block text-left">
    <summary
        class="inline-flex items-center gap-1 pl-2.5 pr-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:border-indigo-300 hover:text-indigo-600 text-xs font-semibold cursor-pointer list-none select-none"
        style="list-style:none;">⋮<span>Aksi</span></summary>

    <div
        class="absolute right-0 z-30 mt-1 w-44 origin-top-right rounded-xl bg-white py-1 shadow-xl ring-1 ring-slate-900/5 text-sm">
        @foreach($items as $item)
            @if(($item['method'] ?? 'GET') === 'GET')
                <a href="{{ $item['url'] }}"
                    class="flex items-center gap-2.5 px-4 py-2 text-slate-600 hover:bg-slate-50">
                    <span class="w-4 text-center">{{ $item['icon'] ?? '›' }}</span><span>{{ $item['label'] }}</span>
                </a>
            @else
                <form method="POST" action="{{ $item['url'] }}"
                    @if(! empty($item['confirm'])) onsubmit="return confirm('{{ $item['confirm'] }}')" @endif>
                    @csrf
                    @method($item['method'])
                    <button type="submit"
                        class="flex w-full items-center gap-2.5 px-4 py-2 {{ ! empty($item['danger']) ? 'text-rose-600 hover:bg-rose-50' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="w-4 text-center">{{ $item['icon'] ?? '›' }}</span><span>{{ $item['label'] }}</span>
                    </button>
                </form>
            @endif
        @endforeach
    </div>
</details>
