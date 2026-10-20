<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wali = User::where('role', 'wali_santri')->first();
        if (!$wali) {
            $wali = User::firstOrCreate(
                ['email' => 'wali@pesantren.test'],
                [
                    'name' => 'H. Syarifuddin (Wali Santri)',
                    'password' => bcrypt('password'),
                    'role' => 'wali_santri',
                ]
            );
        }

        $dummyRegistrations = [
            [
                'user_id' => $wali->id,
                'no_pendaftaran' => 'PSB-2026-0001',
                'nama_lengkap' => 'Muhammad Fatih Al-Ayyubi',
                'nik' => '3201123456780001',
                'tempat_tanggal_lahir' => 'Bogor, 12 Mei 2012',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Pesantren No. 45, Ciawi, Bogor',
                'nama_wali' => 'H. Syarifuddin',
                'no_hp_wali' => '081234567890',
                'status' => 'menunggu_verifikasi',
                'file_kk' => null,
                'file_ijazah' => null,
            ],
            [
                'user_id' => $wali->id,
                'no_pendaftaran' => 'PSB-2026-0002',
                'nama_lengkap' => 'Aisyah Humaira Putri',
                'nik' => '3201123456780002',
                'tempat_tanggal_lahir' => 'Bandung, 18 Agustus 2012',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Komplek Griya Indah Blok C No. 8, Bandung',
                'nama_wali' => 'Drs. H. Ahmad Farhan',
                'no_hp_wali' => '081398765432',
                'status' => 'lulus_berkas',
                'file_kk' => null,
                'file_ijazah' => null,
            ],
            [
                'user_id' => $wali->id,
                'no_pendaftaran' => 'PSB-2026-0003',
                'nama_lengkap' => 'Zaid bin Tsabit Ramadhan',
                'nik' => '3201123456780003',
                'tempat_tanggal_lahir' => 'Jakarta, 05 Januari 2011',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Tebet Timur Dalam No. 12, Jakarta Selatan',
                'nama_wali' => 'Rahmat Hidayat, S.T.',
                'no_hp_wali' => '085678901234',
                'status' => 'ditolak',
                'file_kk' => null,
                'file_ijazah' => null,
            ],
        ];

        foreach ($dummyRegistrations as $regData) {
            Registration::firstOrCreate(
                ['no_pendaftaran' => $regData['no_pendaftaran']],
                $regData
            );
        }
    }
}
