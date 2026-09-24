<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public string $school_name = '';

    public string $address = '';

    public string $npsn = '';

    public string $tahun_ajaran = '';

    public string $semester = '';

    public $logo;

    public function mount(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $this->school_name = Setting::value('school_name') ?? '';
        $this->address = Setting::value('address') ?? '';
        $this->npsn = Setting::value('npsn') ?? '';
        $this->tahun_ajaran = Setting::value('tahun_ajaran') ?? '';
        $this->semester = Setting::value('semester') ?? '';
    }

    public function save()
    {
        $this->validate([
            'school_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'npsn' => 'nullable|string|max:20',
            'tahun_ajaran' => 'nullable|string|max:50',
            'semester' => 'nullable|in:Ganjil,Genap',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        foreach (['school_name', 'address', 'npsn', 'tahun_ajaran', 'semester'] as $field) {
            Setting::set($field, $this->$field ?: null);
        }

        if ($this->logo) {
            Setting::set('logo', $this->logo->store('logos', 'public'));
        }

        session()->flash('success', 'Pengaturan disimpan.');
    }

    public function render()
    {
        return view('livewire.settings.manage', [
            'logo_url' => Setting::value('logo') ? \Illuminate\Support\Facades\Storage::url(Setting::value('logo')) : null,
        ])->layout('layouts.app', ['title' => 'Pengaturan']);
    }
}
