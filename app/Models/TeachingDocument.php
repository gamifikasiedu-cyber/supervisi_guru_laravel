<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingDocument extends Model
{
    use HasFactory;

    /**
     * Jenis dokumen perangkat ajar Pembelajaran Mendalam (Deep Learning).
     */
    public const TYPES = [
        'Pembelajaran Mendalam (Deep Learning)',
        'RPM / Modul Ajar Deep Learning',
        'ATP / Silabus Berbasis Deep Learning',
        'KKTP / KKM (Kriteria Ketercapaian Tujuan Pembelajaran)',
        'Prosem & Prota (Program Semester & Tahunan)',
        'Modul Projek / Kokurikuler (P5)',
    ];

    /**
     * Ekstensi paket yang diterima (1 paket RAR per unggahan).
     */
    public const PACKAGE_EXTENSIONS = ['rar', 'zip'];

    /**
     * Ukuran maksimal file dalam kilobyte (100MB).
     */
    public const MAX_FILE_KB = 102400;

    protected $fillable = [
        'period_id',
        'user_id',
        'subject_id',
        'title',
        'description',
        'document_type',
        'file_path',
        'file_name',
        'file_size',
        'status',
        'review_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
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

    // Guru yang mengunggah
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Mata pelajaran terkait
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Supervisor yang mereview
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
