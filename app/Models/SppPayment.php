<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SppPayment extends Model
{
    protected $fillable = [
        'santri_id',
        'bulan',
        'tahun',
        'nominal',
        'status',
        'paid_at',
        'metode_bayar',
        'bukti_transfer',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'nominal' => 'integer',
        'bulan' => 'integer',
        'tahun' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'belum_bayar' => 'Belum Bayar',
            'lunas'       => 'Lunas',
            'cicilan'     => 'Cicilan',
            default       => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'lunas'       => 'green',
            'cicilan'     => 'yellow',
            'belum_bayar' => 'red',
            default       => 'gray',
        };
    }

    public function getBulanNamaAttribute(): string
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        return $bulan[$this->bulan] ?? '-';
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeLunas($query)
    {
        return $query->where('status', 'lunas');
    }

    public function scopeBelumBayar($query)
    {
        return $query->where('status', 'belum_bayar');
    }

    public function scopePeriode($query, int $bulan, int $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }
}
