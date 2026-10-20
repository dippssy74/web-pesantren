<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    protected $fillable = [
        'no_donasi',
        'nama_donatur',
        'no_hp',
        'alamat',
        'jenis',
        'nominal',
        'tanggal',
        'metode',
        'keterangan',
        'bukti_transfer',
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

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'infaq'   => 'Infaq',
            'sedekah' => 'Sedekah',
            'wakaf'   => 'Wakaf',
            'zakat'   => 'Zakat',
            'lainnya' => 'Lainnya',
            default   => $this->jenis,
        };
    }

    public function getMetodeLabelAttribute(): string
    {
        return match ($this->metode) {
            'tunai'    => 'Tunai',
            'transfer' => 'Transfer Bank',
            'ewallet'  => 'E-Wallet',
            default    => $this->metode,
        };
    }

    // ─── Static Methods ───────────────────────────────────────────────────────

    public static function generateNoDonasi(): string
    {
        $tahun = date('Y');
        $bulan = date('m');
        $last = self::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->orderByDesc('id')->first();
        $num = $last ? str_pad((int) substr($last->no_donasi, -3) + 1, 3, '0', STR_PAD_LEFT) : '001';
        return "DON-{$tahun}{$bulan}-{$num}";
    }
}
