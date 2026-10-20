<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KasTransaction extends Model
{
    protected $fillable = [
        'type',
        'kategori',
        'nominal',
        'tanggal',
        'keterangan',
        'bukti_foto',
        'reference_type',
        'reference_id',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran';
    }

    public function getTypeColorAttribute(): string
    {
        return $this->type === 'pemasukan' ? 'green' : 'red';
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopePemasukan($query)
    {
        return $query->where('type', 'pemasukan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('type', 'pengeluaran');
    }

    public function scopePeriode($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopeBulanIni($query)
    {
        return $query->whereYear('tanggal', now()->year)
                     ->whereMonth('tanggal', now()->month);
    }

    // ─── Static Methods ───────────────────────────────────────────────────────

    /**
     * Hitung saldo kas saat ini.
     */
    public static function getSaldoKas(): float
    {
        $pemasukan = self::where('type', 'pemasukan')->sum('nominal');
        $pengeluaran = self::where('type', 'pengeluaran')->sum('nominal');
        return $pemasukan - $pengeluaran;
    }
}
