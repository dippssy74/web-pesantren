<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'no_pendaftaran',
        'nama_lengkap',
        'nik',
        'tempat_tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'nama_wali',
        'no_hp_wali',
        'status',
        'file_kk',
        'file_ijazah',
    ];

    /**
     * Get the user that owns the registration.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique registration number.
     */
    public static function generateNoPendaftaran(): string
    {
        $year = date('Y');
        $prefix = "PSB-{$year}-";
        $latest = static::where('no_pendaftaran', 'like', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $lastNumber = (int) substr($latest->no_pendaftaran, -4);
            $newNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Get human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'lulus_berkas' => 'Lulus Berkas',
            'ditolak' => 'Ditolak',
            default => 'Menunggu Verifikasi',
        };
    }
}
