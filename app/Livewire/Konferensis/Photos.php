<?php

namespace App\Livewire\Konferensis;

use App\Models\PreObservationKonferensi;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Photos extends Component
{
    public PreObservationKonferensi $konferensi;

    public ?int $preview = null;

    public function mount(PreObservationKonferensi $konferensi): void
    {
        $this->konferensi = $konferensi;
    }

    public function photos(): array
    {
        return collect(array_values(array_filter((array) $this->konferensi->dokumentasi_foto)))
            ->filter(fn ($p) => Storage::disk('public')->exists($p))
            ->map(fn ($p) => ['path' => $p, 'url' => Storage::url($p)])
            ->values()
            ->all();
    }

    public function openPreview(int $index): void
    {
        $this->preview = isset($this->photos()[$index]) ? $index : null;
    }

    public function closePreview(): void
    {
        $this->preview = null;
    }

    public function step(int $dir): void
    {
        $photos = $this->photos();
        if (! $photos || $this->preview === null) {
            return;
        }
        $this->preview = ($this->preview + $dir + count($photos)) % count($photos);
    }

    public function render()
    {
        return view('livewire.konferensis.photos', [
            'photos' => $this->photos(),
        ])->layout('layouts.app', ['title' => 'Foto Dokumentasi']);
    }
}
