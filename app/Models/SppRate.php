<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SppRate extends Model
{
    protected $fillable = [
        'kelas',
        'nominal',
        'tahun_ajaran',
        'is_active',
        'keterangan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'nominal' => 'integer',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(SppPayment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
