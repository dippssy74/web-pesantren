<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koperasi_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique(); // e.g., KOP-2026-001234
            $table->foreignId('santri_id')->nullable()->constrained('santris')->nullOnDelete();
            $table->decimal('total_nominal', 15, 2);
            $table->enum('metode_bayar', ['ewallet', 'tunai']);
            $table->foreignId('wallet_transaction_id')->nullable()->constrained('wallet_transactions')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('koperasi_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('koperasi_transaction_id')->constrained('koperasi_transactions')->cascadeOnDelete();
            $table->foreignId('koperasi_product_id')->constrained('koperasi_products');
            $table->integer('qty');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('koperasi_transaction_items');
        Schema::dropIfExists('koperasi_transactions');
    }
};
