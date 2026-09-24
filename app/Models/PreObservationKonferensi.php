<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PreObservationKonferensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'teacher_id',
        'supervisor_id',
        'subject_id',
        'class_name',
        'observation_date',
        'items',
        'dokumentasi_foto',
    ];

    protected $casts = [
        'observation_date' => 'date',
        'items' => 'array',
        'dokumentasi_foto' => 'array',
    ];

    public function scopeByPeriod(Builder $query, ?Period $period): Builder
    {
        if (! $period) {
            return $query;
        }

        return $query->where('period_id', $period->id);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Menambahkan atribut sementara `dokumentasi_urls` (URL publik tiap foto)
     * agar mudah dipakai di frontend Inertia.
     */
    public function appendDokumentasiUrls(): static
    {
        $paths = array_values(array_filter((array) ($this->dokumentasi_foto ?? [])));
        $this->setAttribute('dokumentasi_urls', array_map(
            fn ($p) => Storage::disk('public')->exists($p) ? Storage::url($p) : null,
            $paths
        ));

        // Buang URL null (file hilang di disk) agar tidak broken image.
        $this->setAttribute('dokumentasi_urls', array_values(array_filter($this->dokumentasi_urls)));

        return $this;
    }
}
