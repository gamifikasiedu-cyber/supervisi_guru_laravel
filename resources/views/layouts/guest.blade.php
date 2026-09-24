<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Login' }} — Supervisi Guru</title>
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-600 via-indigo-700 to-slate-900 p-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-white text-xl font-bold leading-snug">{{ \App\Models\Setting::value('school_name') ?: 'Sistem Informasi Supervisi Guru' }}</h1>
            <p class="text-indigo-200 text-sm mt-1">Supervisi Akademik Guru</p>
        </div>
        <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8">
            {{ $slot }}
        </div>
    </div>
</div>
</body>
</html>
