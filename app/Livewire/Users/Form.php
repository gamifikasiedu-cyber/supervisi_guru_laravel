<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Form extends Component
{
    public ?User $user = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public array $roles = ['guru'];

    public string $nip = '';

    public string $mata_pelajaran = '';

    public function mount(?User $user = null): void
    {
        if ($user?->exists) {
            $this->user = $user;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->roles = $user->role_list;
            $this->nip = $user->nip ?? '';
            $this->mata_pelajaran = $user->mata_pelajaran ?? '';
        }
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email'.($this->user ? ','.$this->user->id : ''),
            'roles' => 'required|array|min:1',
            'roles.*' => 'in:admin,guru,supervisor,kepala_sekolah,pengawas',
            'nip' => 'nullable|string|max:30',
            'mata_pelajaran' => 'nullable|string|max:255',
        ];

        if (! $this->user || filled($this->password)) {
            $rules['password'] = 'required|string|min:8';
        }

        $this->validate($rules);

        if ($this->user) {
            $data = ['name' => $this->name, 'email' => $this->email, 'nip' => $this->nip ?: null, 'mata_pelajaran' => $this->mata_pelajaran ?: null];
            if (filled($this->password)) {
                $data['password'] = Hash::make($this->password);
            }
            $this->user->update($data);
            $this->user->setRoles($this->roles);
            session()->flash('success', 'Pengguna berhasil diperbarui.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => User::ROLE_GURU,
                'nip' => $this->nip ?: null,
                'mata_pelajaran' => $this->mata_pelajaran ?: null,
            ]);
            $user->setRoles($this->roles);
            session()->flash('success', 'Pengguna berhasil ditambahkan.');
        }

        return $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.users.form')->layout('layouts.app', ['title' => $this->user ? 'Edit Pengguna' : 'Tambah Pengguna']);
    }
}
