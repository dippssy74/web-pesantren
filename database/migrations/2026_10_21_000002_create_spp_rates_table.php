<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spp_rates', function (Blueprint $table) {
            $table->id();
            $table->string('kelas'); // Kelas yang dikenai tarif ini
            $table->unsignedBigInteger('nominal'); // Nominal SPP per bulan
            $table->string('tahun_ajaran'); // e.g., "2026/2027"
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_rates');
    }
};
