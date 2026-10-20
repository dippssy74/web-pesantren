<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Santri extends Model
{
    protected $fillable = [
        'nis',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'kelas',
        'kamar',
        'asrama',
        'tahun_masuk',
        'status',
        'wali_id',
        'registration_id',
        'catatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_masuk' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function wali(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_id');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function sppPayments(): HasMany
    {
        return $this->hasMany(SppPayment::class);
    }

    public function koperasiTransactions(): HasMany
    {
        return $this->hasMany(KoperasiTransaction::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'laki_laki' ? 'Laki-laki' : 'Perempuan';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'aktif'  => 'Aktif',
            'alumni' => 'Alumni',
            'keluar' => 'Keluar',
            default  => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'aktif'  => 'green',
            'alumni' => 'blue',
            'keluar' => 'red',
            default  => 'gray',
        };
    }

    public function getUmurAttribute(): int
    {
        return $this->tanggal_lahir->age;
    }

    // ─── Methods ──────────────────────────────────────────────────────────────

    /**
     * Generate Nomor Induk Santri otomatis.
     */
    public static function generateNis(): string
    {
        $tahun = date('Y');
        $last = self::where('nis', 'like', "NIS-{$tahun}-%")->orderByDesc('nis')->first();

        if ($last) {
            $lastNum = (int) substr($last->nis, -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }

        return "NIS-{$tahun}-{$newNum}";
    }

    /**
     * Check apakah SPP bulan tertentu sudah lunas.
     */
    public function isSppLunas(int $bulan, int $tahun): bool
    {
        return $this->sppPayments()
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('status', 'lunas')
            ->exists();
    }

    /**
     * Hitung total tunggakan SPP.
     */
    public function getTotalTunggakanAttribute(): int
    {
        return $this->sppPayments()
            ->where('status', 'belum_bayar')
            ->sum('nominal');
    }

    /**
     * Scope: hanya santri aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
