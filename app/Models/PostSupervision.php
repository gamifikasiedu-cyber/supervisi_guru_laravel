<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostSupervision extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'teacher_id',
        'supervisor_id',
        'subject_id',
        'class_name',
        'observation_date',
        'bagian',
        'jenis_observasi',
        'items',
        'total_skor',
        'max_skor',
        'score',
    ];

    protected $casts = [
        'observation_date' => 'date',
        'items' => 'array',
        'total_skor' => 'integer',
        'max_skor' => 'integer',
        'score' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (PostSupervision $record) {
            $cfg = \App\Http\Controllers\PostSupervisionController::config($record->bagian ?? '');

            if (! $cfg || empty($cfg['hasSkor'])) {
                $record->total_skor = null;
                $record->max_skor = null;
                $record->score = null;

                return;
            }

            $total = 0;
            $count = 0;
            foreach ((array) ($record->items ?? []) as $item) {
                $total += max(0, min(4, (int) (($item['skor'] ?? 0))));
                $count++;
            }
            $max = $count * 4;
            $record->total_skor = $total;
            $record->max_skor = $max;
            $record->score = $max > 0 ? round(($total / $max) * 100, 2) : 0;
        });
    }

    public function scopeByPeriod(Builder $query, ?Period $period): Builder
    {
        if (! $period) {
            return $query;
        }

        return $query->where('period_id', $period->id);
    }

    public function scopeByBagian(Builder $query, string $bagian): Builder
    {
        return $query->where('bagian', $bagian);
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
}
