<?php

namespace App\Livewire\Auth;

use App\Models\Period;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public string $period_id = '';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'period_id' => 'nullable|integer|exists:periods,id',
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Email atau password salah.');

            return;
        }

        session()->regenerate();

        if ($this->period_id !== '') {
            session()->put('active_period_id', (int) $this->period_id);
        } else {
            session()->forget('active_period_id');
        }

        $this->redirect(route('dashboard'), navigate: false);
    }

    public function render()
    {
        return view('livewire.auth.login', [
            'periods' => Period::forLogin()->get(),
        ])->layout('layouts.guest', ['title' => 'Masuk']);
    }
}
