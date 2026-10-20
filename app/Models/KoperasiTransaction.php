<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KoperasiTransaction extends Model
{
    protected $fillable = [
        'no_transaksi',
        'santri_id',
        'total_nominal',
        'metode_bayar',
        'wallet_transaction_id',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'total_nominal' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(KoperasiTransactionItem::class);
    }

    public function walletTransaction(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getMetodeBayarLabelAttribute(): string
    {
        return match ($this->metode_bayar) {
            'ewallet' => 'E-Wallet',
            'tunai'   => 'Tunai',
            default   => $this->metode_bayar,
        };
    }

    // ─── Static Methods ───────────────────────────────────────────────────────

    public static function generateNoTransaksi(): string
    {
        $tahun = date('Y');
        $last = self::whereYear('created_at', $tahun)->orderByDesc('id')->first();
        $num = $last ? str_pad((int) substr($last->no_transaksi, -6) + 1, 6, '0', STR_PAD_LEFT) : '000001';
        return "KOP-{$tahun}-{$num}";
    }
}
