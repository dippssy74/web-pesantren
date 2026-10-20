<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('no_donasi')->unique(); // e.g., DON-2026-001
            $table->string('nama_donatur')->default('Hamba Allah');
            $table->string('no_hp')->nullable();
            $table->string('alamat')->nullable();
            $table->enum('jenis', ['infaq', 'sedekah', 'wakaf', 'zakat', 'lainnya'])->default('infaq');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->enum('metode', ['tunai', 'transfer', 'ewallet'])->default('tunai');
            $table->text('keterangan')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
