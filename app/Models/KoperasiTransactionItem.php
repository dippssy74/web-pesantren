<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KoperasiTransactionItem extends Model
{
    protected $fillable = [
        'koperasi_transaction_id',
        'koperasi_product_id',
        'qty',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'qty' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(KoperasiTransaction::class, 'koperasi_transaction_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(KoperasiProduct::class, 'koperasi_product_id');
    }
}
