<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function label(): string
    {
        return $this->tahun_ajaran.' - '.$this->semester;
    }

    public static function fromSession(): ?self
    {
        $id = session('active_period_id');

        return $id ? static::find($id) : null;
    }

    public static function current(): ?self
    {
        foreach (static::where('is_active', true)->orderByDesc('start_date')->get() as $period) {
            if ($period->start_date->lte(now()) && $period->end_date->gte(now())) {
                return $period;
            }
        }

        return static::where('is_active', true)->orderByDesc('start_date')->first();
    }

    public function scopeForLogin(Builder $query): Builder
    {
        return $query->orderByDesc('start_date');
    }
}
