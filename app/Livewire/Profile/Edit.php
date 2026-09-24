<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public function mount(): void
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function saveInfo()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore(auth()->id())],
        ]);

        $user = auth()->user();
        $user->update(['name' => $this->name, 'email' => $this->email]);
        if ($user->isDirty('email')) {
            $user->forceFill(['email_verified_at' => null])->save();
        }

        session()->flash('success', 'Profil diperbarui.');
    }

    public function deleteAccount()
    {
        $this->validate(['password' => 'required|current_password']);

        $user = auth()->user();
        Auth::logout();
        $user->delete();
        session()->invalidate();
        session()->regenerateToken();

        return $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.profile.edit')->layout('layouts.app', ['title' => 'Profil Saya']);
    }
}
