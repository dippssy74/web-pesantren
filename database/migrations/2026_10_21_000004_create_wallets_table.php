<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->unique()->constrained('santris')->cascadeOnDelete();
            $table->decimal('saldo', 15, 2)->default(0);
            $table->decimal('limit_harian', 15, 2)->default(50000); // Batas transaksi per hari
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
