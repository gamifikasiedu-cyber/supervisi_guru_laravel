<div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 max-w-2xl">
        <form wire:submit="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium">Nama</label>
                    <input wire:model="name" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" wire:model="email" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">Password {{ $user ? '(kosongkan jika tidak diubah)' : '' }}</label>
                    <input type="password" wire:model="password" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                    @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-sm font-medium">NIP</label>
                    <input wire:model="nip" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-medium">Mata Pelajaran</label>
                    <input wire:model="mata_pelajaran" class="mt-1 block w-full rounded-lg border-slate-300 text-sm px-3 py-2 border">
                </div>
            </div>
            <div>
                <label class="text-sm font-medium">Peran</label>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach(\App\Models\User::ROLES as $key => $label)
                        <label class="flex items-center gap-1.5 text-sm bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 cursor-pointer">
                            <input type="checkbox" wire:model="roles" value="{{ $key }}" class="rounded text-indigo-600"> {{ $label }}
                        </label>
                    @endforeach
                </div>
                @error('roles') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">Simpan</button>
                <a href="{{ route('users.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 text-sm text-slate-600 hover:bg-slate-200">Batal</a>
            </div>
        </form>
    </div>
</div>
