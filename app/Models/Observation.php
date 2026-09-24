<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'supervision_id',
        'teacher_id',
        'supervisor_id',
        'subject_id',
        'class_name',
        'observation_date',
        'score_planning',
        'score_delivery',
        'score_management',
        'score_assessment',
        'total_score',
        'observation_notes',
        'feedback',
        'recommendations',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'observation_date' => 'date',
        'approved_at' => 'datetime',
        'score_planning' => 'decimal:2',
        'score_delivery' => 'decimal:2',
        'score_management' => 'decimal:2',
        'score_assessment' => 'decimal:2',
        'total_score' => 'decimal:2',
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

    // Relasi ke supervisi
    public function supervision()
    {
        return $this->belongsTo(Supervision::class);
    }

    // Guru yang diamati
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Supervisor yang mengisi
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    // Mata pelajaran
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Kepala Sekolah yang approve
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
