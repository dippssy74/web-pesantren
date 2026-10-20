<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KoperasiProduct extends Model
{
    protected $fillable = [
        'kode_produk',
        'nama',
        'kategori',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
        'satuan',
        'foto',
        'is_active',
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'stok' => 'integer',
        'stok_minimum' => 'integer',
        'is_active' => 'boolean',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function transactionItems(): HasMany
    {
        return $this->hasMany(KoperasiTransactionItem::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getIsStokRendahAttribute(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    public function getMarginAttribute(): float
    {
        return $this->harga_jual - $this->harga_beli;
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeStokRendah($query)
    {
        return $query->whereColumn('stok', '<=', 'stok_minimum');
    }

    // ─── Static Methods ───────────────────────────────────────────────────────

    public static function generateKodeProduk(): string
    {
        $last = self::orderByDesc('id')->first();
        $num = $last ? str_pad($last->id + 1, 4, '0', STR_PAD_LEFT) : '0001';
        return "KOP-{$num}";
    }
}
