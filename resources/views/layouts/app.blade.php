@php
    $user = auth()->user();
    $roles = $user ? $user->role_list : [];
    $isGuruOnly = in_array('guru', $roles) && ! array_intersect($roles, ['admin', 'supervisor', 'kepala_sekolah', 'pengawas']);
    $isAdmin = in_array('admin', $roles);
    $isSupervisorArea = (bool) array_intersect($roles, ['admin', 'supervisor', 'kepala_sekolah', 'pengawas']);
    $canReview = (bool) array_intersect($roles, ['admin', 'supervisor', 'kepala_sekolah', 'pengawas']);
    $period = \App\Models\Period::fromSession();
    $notifItems = \App\Models\Supervision::query()
        ->when($isGuruOnly, fn ($q) => $q->where('teacher_id', $user->id))
        ->when($period, fn ($q) => $q->where('period_id', $period->id))
        ->whereDate('schedule_date', '>=', now()->toDateString())
        ->whereNotIn('status', ['Completed', 'completed', 'Cancelled', 'cancelled'])
        ->orderBy('schedule_date')->limit(8)->get();
    $schoolLogo = \App\Models\Setting::value('logo') ? \Illuminate\Support\Facades\Storage::url(\App\Models\Setting::value('logo')) : null;
    $navLink = function ($label, $icon, $href, $active, $badge = null) {
        $cls = $active
            ? 'bg-indigo-50 text-indigo-600 font-semibold'
            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700';
        $iconCls = $active ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400';
        return '<a href="'.$href.'" class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition '.$cls.'">'
            .'<span class="w-8 h-8 rounded-lg flex items-center justify-center text-base shrink-0 '.$iconCls.'">'.$icon.'</span>'
            .'<span class="flex-1 truncate">'.$label.'</span>'
            .($badge ? '<span class="text-[10px] font-bold bg-rose-500 text-white rounded-full min-w-[20px] h-5 px-1.5 flex items-center justify-center">'.$badge.'</span>' : '')
            .'</a>';
    };
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — Supervisi Guru</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @livewireStyles
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        [x-cloak] { display: none !important; }
        #print-area { display: none; }
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area {
                display: block;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 12px;
                color: #000;
                background: #fff;
            }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="antialiased bg-[#f4f6fb] text-slate-800">
<div class="min-h-screen lg:flex">
    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-60 shrink-0 bg-white min-h-screen sticky top-0 h-screen border-r border-slate-100">
        <div class="px-5 py-6 flex items-center gap-2.5">
            @if($schoolLogo)
                <img src="{{ $schoolLogo }}" alt="Logo sekolah" class="w-9 h-9 rounded-xl object-contain bg-white ring-1 ring-slate-200 shrink-0">
            @else
                <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-extrabold shrink-0">S</div>
            @endif
            <div class="min-w-0">
                <p class="font-extrabold text-[17px] leading-tight truncate">Supervisi</p>
                <p class="text-[11px] text-slate-400 -mt-0.5">Guru App</p>
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto px-4 pb-3 space-y-1">
            {!! $navLink('Dashboard', '▦', route('dashboard'), request()->routeIs('dashboard')) !!}
            {!! $navLink('Jadwal Supervisi', '◷', route('supervisions.index'), request()->routeIs('supervisions.*'), $notifItems->count() ?: null) !!}
            {!! $navLink($canReview ? 'Dokumen' : 'Dokumen Saya', '▤', route('documents.index'), request()->routeIs('documents.*')) !!}
            @if($isSupervisorArea)
                {!! $navLink('Pra Observasi', '◎', route('pre-observations.index'), request()->routeIs('pre-observations.*')) !!}
                {!! $navLink('Pemantauan', '◉', route('pemantauan.index'), request()->routeIs('pemantauan.*')) !!}
                {!! $navLink('Pasca Supervisi', '⧉', route('post-supervisions.index'), request()->routeIs('post-supervisions.*')) !!}
            @endif
            @if($isAdmin)
                {!! $navLink('Pengguna', '◍', route('users.index'), request()->routeIs('users.*')) !!}
                {!! $navLink('Mata Pelajaran', '≡', route('subjects.index'), request()->routeIs('subjects.*')) !!}
                {!! $navLink('Periode', '◐', route('periods.index'), request()->routeIs('periods.*')) !!}
                {!! $navLink('Pengaturan', '⚙', route('settings.index'), request()->routeIs('settings.*')) !!}
            @endif
        </nav>
        <div class="px-4 pb-3">
            <div class="rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-500 p-4 text-white text-center">
                <p class="text-xs opacity-80">Periode Aktif</p>
                <p class="font-bold text-sm mt-0.5">{{ $period ? $period->tahun_ajaran : '—' }}</p>
                <p class="text-xs opacity-80">{{ $period ? $period->semester : 'Belum dipilih' }}</p>
                @if($isAdmin)
                    <a href="{{ route('periods.index') }}" class="inline-block mt-2.5 text-xs font-semibold bg-white text-indigo-600 rounded-lg px-4 py-1.5">Kelola</a>
                @endif
            </div>
            <div class="flex items-center gap-2.5 mt-3 px-1">
                <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="min-w-0 flex-1">
                    <p class="text-[13px] font-semibold truncate">{{ $user->name }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ implode(', ', $user->role_labels) }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button title="Keluar" class="text-slate-300 hover:text-rose-500 text-lg leading-none">⍈</button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Mobile top bar -->
    <div class="lg:hidden bg-white border-b border-slate-100 px-4 py-3 flex items-center gap-2.5 sticky top-0 z-30">
        <button onclick="document.getElementById('mobile-drawer').classList.remove('hidden')" class="text-xl text-slate-600 px-1" aria-label="Menu">☰</button>
        @if($schoolLogo)
            <img src="{{ $schoolLogo }}" alt="Logo sekolah" class="w-8 h-8 rounded-lg object-contain bg-white ring-1 ring-slate-200 shrink-0">
        @else
            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-extrabold shrink-0">S</div>
        @endif
        <p class="font-extrabold flex-1 truncate">Supervisi</p>
        <span class="text-xs font-medium bg-slate-100 rounded-lg px-2.5 py-1.5 text-slate-500">🔔{{ $notifItems->count() ? ' '.$notifItems->count() : '' }}</span>
    </div>

    <!-- Mobile drawer -->
    <div id="mobile-drawer" class="hidden fixed inset-0 z-40 lg:hidden">
        <div class="absolute inset-0 bg-slate-900/50" onclick="document.getElementById('mobile-drawer').classList.add('hidden')"></div>
        <aside class="absolute inset-y-0 left-0 w-64 bg-white flex flex-col shadow-2xl overflow-y-auto">
            <div class="px-5 py-5 flex items-center gap-2.5">
                @if($schoolLogo)
                    <img src="{{ $schoolLogo }}" alt="Logo sekolah" class="w-9 h-9 rounded-xl object-contain bg-white ring-1 ring-slate-200 shrink-0">
                @else
                    <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-extrabold shrink-0">S</div>
                @endif
                <p class="font-extrabold text-[17px] flex-1">Supervisi</p>
                <button onclick="document.getElementById('mobile-drawer').classList.add('hidden')" class="text-slate-400 text-xl px-1" aria-label="Tutup">✕</button>
            </div>
            <nav class="flex-1 px-4 pb-3 space-y-1">
                {!! $navLink('Dashboard', '▦', route('dashboard'), request()->routeIs('dashboard')) !!}
                {!! $navLink('Jadwal Supervisi', '◷', route('supervisions.index'), request()->routeIs('supervisions.*'), $notifItems->count() ?: null) !!}
                {!! $navLink($canReview ? 'Dokumen' : 'Dokumen Saya', '▤', route('documents.index'), request()->routeIs('documents.*')) !!}
                @if($isSupervisorArea)
                    {!! $navLink('Pra Observasi', '◎', route('pre-observations.index'), request()->routeIs('pre-observations.*')) !!}
                    {!! $navLink('Pemantauan', '◉', route('pemantauan.index'), request()->routeIs('pemantauan.*')) !!}
                    {!! $navLink('Pasca Supervisi', '⧉', route('post-supervisions.index'), request()->routeIs('post-supervisions.*')) !!}
                @endif
                @if($isAdmin)
                    {!! $navLink('Pengguna', '◍', route('users.index'), request()->routeIs('users.*')) !!}
                    {!! $navLink('Mata Pelajaran', '≡', route('subjects.index'), request()->routeIs('subjects.*')) !!}
                    {!! $navLink('Periode', '◐', route('periods.index'), request()->routeIs('periods.*')) !!}
                    {!! $navLink('Pengaturan', '⚙', route('settings.index'), request()->routeIs('settings.*')) !!}
                @endif
                {!! $navLink('Profil Saya', 'ⓘ', route('profile.edit'), request()->routeIs('profile.*')) !!}
            </nav>
            <div class="p-4 border-t border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[13px] font-semibold truncate">{{ $user->name }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ implode(', ', $user->role_labels) }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-xs px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-500 font-medium">Keluar</button>
                    </form>
                </div>
            </div>
        </aside>
    </div>

    <!-- Main -->
    <div class="flex-1 min-w-0 px-4 md:px-8 py-5 md:py-7 max-w-[1200px] mx-auto w-full">
        <div class="flex items-center gap-3 mb-5">
            <h1 class="text-xl md:text-2xl font-extrabold flex-1">{{ $title ?? 'Dashboard' }}</h1>
            @if($period)
                <span class="hidden sm:inline-block text-xs font-medium bg-white rounded-lg px-3 py-2 shadow-sm text-slate-500">{{ $period->start_date?->format('d-m-Y') }}</span>
                <span class="hidden sm:inline-block text-xs font-medium bg-white rounded-lg px-3 py-2 shadow-sm text-slate-500">{{ $period->end_date?->format('d-m-Y') }}</span>
            @endif
            <span class="text-xs font-medium bg-white rounded-lg px-3 py-2 shadow-sm text-slate-500" title="Jadwal mendatang">🔔{{ $notifItems->count() ? ' '.$notifItems->count() : '' }}</span>
        </div>

        @if(session('success') || session('error'))
            <div class="mb-4 px-4 py-3 rounded-2xl text-sm bg-white shadow-sm border-l-4 {{ session('success') ? 'border-emerald-400 text-emerald-700' : 'border-rose-400 text-rose-700' }}">
                {{ session('success') ?? session('error') }}
            </div>
        @endif

        <main>{{ $slot }}</main>
    </div>
</div>
@livewireScripts
</body>
</html>
