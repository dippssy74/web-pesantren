<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koperasi_products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_produk')->unique();
            $table->string('nama');
            $table->string('kategori'); // e.g., "Makanan", "Minuman", "Alat Tulis", "Perlengkapan"
            $table->decimal('harga_beli', 15, 2)->default(0); // Modal
            $table->decimal('harga_jual', 15, 2); // Harga jual ke santri
            $table->integer('stok')->default(0);
            $table->integer('stok_minimum')->default(5); // Alert jika stok di bawah ini
            $table->string('satuan')->default('pcs'); // pcs, botol, bungkus, dll
            $table->string('foto')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('koperasi_products');
    }
};
