<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spp_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan'); // 1-12
            $table->unsignedSmallInteger('tahun'); // e.g., 2026
            $table->unsignedBigInteger('nominal');
            $table->enum('status', ['belum_bayar', 'lunas', 'cicilan'])->default('belum_bayar');
            $table->timestamp('paid_at')->nullable();
            $table->string('metode_bayar')->nullable(); // tunai, transfer, e-wallet
            $table->string('bukti_transfer')->nullable(); // path file bukti
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            // Unique: satu tagihan per santri per bulan per tahun
            $table->unique(['santri_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_payments');
    }
};
