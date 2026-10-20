<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('no_pendaftaran')->unique();
            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->string('tempat_tanggal_lahir');
            $table->string('jenis_kelamin');
            $table->text('alamat');
            $table->string('nama_wali');
            $table->string('no_hp_wali');
            $table->enum('status', ['menunggu_verifikasi', 'lulus_berkas', 'ditolak'])->default('menunggu_verifikasi');
            $table->string('file_kk')->nullable();
            $table->string('file_ijazah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
