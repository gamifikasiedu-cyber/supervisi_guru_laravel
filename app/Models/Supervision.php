<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'teacher_id',
        'supervisor_id',
        'subject_id',
        'class_name',
        'schedule_date',
        'status',
        'approval_status',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'approved_at' => 'datetime',
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

    // Relasi ke User (Guru)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relasi ke User (Supervisor)
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // Relasi ke Subject (Mata Pelajaran)
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Kepala Sekolah yang approve
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Observasi terkait
    public function observations()
    {
        return $this->hasMany(Observation::class);
    }
}
