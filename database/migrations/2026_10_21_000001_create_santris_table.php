<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santris', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique(); // Nomor Induk Santri
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['laki_laki', 'perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat')->nullable();
            $table->string('kelas'); // e.g., "1 Ulya", "2 Tsanawiyah"
            $table->string('kamar')->nullable(); // Nomor/nama kamar asrama
            $table->string('asrama')->nullable(); // e.g., "Al-Fatih", "Ar-Razi"
            $table->year('tahun_masuk');
            $table->enum('status', ['aktif', 'alumni', 'keluar'])->default('aktif');
            $table->foreignId('wali_id')->nullable()->constrained('users')->nullOnDelete(); // wali santri
            $table->foreignId('registration_id')->nullable()->constrained('registrations')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};
