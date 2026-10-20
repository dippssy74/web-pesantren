<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'santri_id',
        'saldo',
        'limit_harian',
        'is_active',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
        'limit_harian' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    // ─── Methods ──────────────────────────────────────────────────────────────

    /**
     * Tambah saldo wallet (topup).
     */
    public function topup(float $nominal, string $keterangan, int $createdBy, ?string $orderId = null): WalletTransaction
    {
        $sebelum = $this->saldo;
        $this->saldo += $nominal;
        $this->save();

        return $this->transactions()->create([
            'type' => 'topup',
            'nominal' => $nominal,
            'saldo_sebelum' => $sebelum,
            'saldo_sesudah' => $this->saldo,
            'keterangan' => $keterangan,
            'midtrans_order_id' => $orderId,
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Kurangi saldo wallet (debit / belanja).
     *
     * @throws \Exception jika saldo tidak cukup
     */
    public function debit(float $nominal, string $keterangan, int $createdBy, ?int $refId = null, ?string $refType = null): WalletTransaction
    {
        if ($this->saldo < $nominal) {
            throw new \Exception("Saldo tidak mencukupi. Saldo saat ini: Rp " . number_format($this->saldo, 0, ',', '.'));
        }

        $sebelum = $this->saldo;
        $this->saldo -= $nominal;
        $this->save();

        return $this->transactions()->create([
            'type' => 'debit',
            'nominal' => $nominal,
            'saldo_sebelum' => $sebelum,
            'saldo_sesudah' => $this->saldo,
            'keterangan' => $keterangan,
            'reference_type' => $refType,
            'reference_id' => $refId,
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Total pengeluaran hari ini (untuk cek limit harian).
     */
    public function getPengeluaranHariIniAttribute(): float
    {
        return $this->transactions()
            ->where('type', 'debit')
            ->whereDate('created_at', today())
            ->sum('nominal');
    }
}
