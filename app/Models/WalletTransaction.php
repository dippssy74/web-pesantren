<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends Model
{
    protected $fillable = [
        'wallet_id',
        'type',
        'nominal',
        'saldo_sebelum',
        'saldo_sesudah',
        'keterangan',
        'reference_type',
        'reference_id',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_status',
        'created_by',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'saldo_sebelum' => 'decimal:2',
        'saldo_sesudah' => 'decimal:2',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'topup'  => 'Top Up',
            'debit'  => 'Pengeluaran',
            'refund' => 'Refund',
            default  => $this->type,
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match ($this->type) {
            'topup'  => 'green',
            'debit'  => 'red',
            'refund' => 'blue',
            default  => 'gray',
        };
    }

    public function getMidtransStatusLabelAttribute(): string
    {
        return match ($this->midtrans_status) {
            'pending'    => 'Menunggu Pembayaran',
            'settlement' => 'Berhasil',
            'cancel'     => 'Dibatalkan',
            'deny'       => 'Ditolak',
            'expire'     => 'Kadaluarsa',
            'failure'    => 'Gagal',
            default      => $this->midtrans_status ?? '-',
        };
    }
}
