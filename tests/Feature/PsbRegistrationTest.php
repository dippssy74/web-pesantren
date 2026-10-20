<?php

namespace Tests\Feature;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PsbRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_wali_santri_can_view_registration_form(): void
    {
        $wali = User::factory()->create(['role' => 'wali_santri']);

        $response = $this->actingAs($wali)->get('/wali/pendaftaran/baru');

        $response->assertStatus(200);
        $response->assertSee('Formulir Santri Baru');
        $response->assertSee('Nomor Induk Kependudukan (NIK)');
    }

    public function test_wali_santri_can_submit_registration_with_files(): void
    {
        Storage::fake('public');

        $wali = User::factory()->create(['role' => 'wali_santri']);

        $fileKk = UploadedFile::fake()->create('kartu_keluarga.pdf', 500, 'application/pdf');
        $fileIjazah = UploadedFile::fake()->image('ijazah.jpg', 600, 800);

        $payload = [
            'nama_lengkap' => 'Muhammad Bilal Al-Banjari',
            'nik' => '3201123456789012',
            'tempat_tanggal_lahir' => 'Banjarmasin, 10 Oktober 2012',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Sungai Jingah No. 15, Banjarmasin',
            'nama_wali' => 'H. Syarifuddin',
            'no_hp_wali' => '081234567890',
            'file_kk' => $fileKk,
            'file_ijazah' => $fileIjazah,
        ];

        $response = $this->actingAs($wali)->post('/wali/pendaftaran', $payload);

        $response->assertRedirect('/wali/dashboard');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('registrations', [
            'user_id' => $wali->id,
            'nama_lengkap' => 'Muhammad Bilal Al-Banjari',
            'nik' => '3201123456789012',
            'status' => 'menunggu_verifikasi',
        ]);

        $registration = Registration::where('nik', '3201123456789012')->first();
        $this->assertNotNull($registration->file_kk);
        $this->assertNotNull($registration->file_ijazah);

        Storage::disk('public')->assertExists($registration->file_kk);
        Storage::disk('public')->assertExists($registration->file_ijazah);
    }

    public function test_registration_validation_fails_for_invalid_nik_and_oversized_file(): void
    {
        Storage::fake('public');

        $wali = User::factory()->create(['role' => 'wali_santri']);

        // NIK kurang dari 16 digit & file lebih dari 2048 KB (3000 KB)
        $oversizedFile = UploadedFile::fake()->create('big_kk.pdf', 3000, 'application/pdf');

        $payload = [
            'nama_lengkap' => 'Santri Test',
            'nik' => '12345', // Invalid: not 16 digits
            'tempat_tanggal_lahir' => 'Jakarta, 1 Januari 2012',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Alamat test',
            'nama_wali' => 'Nama Wali',
            'no_hp_wali' => '081234567890',
            'file_kk' => $oversizedFile,
        ];

        $response = $this->actingAs($wali)->post('/wali/pendaftaran', $payload);

        $response->assertSessionHasErrors(['nik', 'file_kk']);
    }

    public function test_admin_can_view_psb_management_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $wali = User::factory()->create(['role' => 'wali_santri']);

        Registration::create([
            'user_id' => $wali->id,
            'no_pendaftaran' => 'PSB-2026-9999',
            'nama_lengkap' => 'Santri Terdaftar',
            'nik' => '3201123456780009',
            'tempat_tanggal_lahir' => 'Bogor, 12 Mei 2012',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Pesantren',
            'nama_wali' => 'Wali Santri',
            'no_hp_wali' => '081234567890',
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($admin)->get('/admin/psb');

        $response->assertStatus(200);
        $response->assertSee('Penerimaan Santri Baru (PSB)');
        $response->assertSee('PSB-2026-9999');
        $response->assertSee('Santri Terdaftar');
    }

    public function test_admin_can_update_registration_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $wali = User::factory()->create(['role' => 'wali_santri']);

        $registration = Registration::create([
            'user_id' => $wali->id,
            'no_pendaftaran' => 'PSB-2026-7777',
            'nama_lengkap' => 'Calon Lulus',
            'nik' => '3201123456780007',
            'tempat_tanggal_lahir' => 'Bogor, 12 Mei 2012',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Pesantren',
            'nama_wali' => 'Wali Santri',
            'no_hp_wali' => '081234567890',
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($admin)->patch("/admin/psb/{$registration->id}/status", [
            'status' => 'lulus_berkas',
        ]);

        $response->assertRedirect();
        $this->assertEquals('lulus_berkas', $registration->fresh()->status);
    }

    public function test_wali_santri_cannot_access_admin_psb_page(): void
    {
        $wali = User::factory()->create(['role' => 'wali_santri']);

        $response = $this->actingAs($wali)->get('/admin/psb');

        $response->assertStatus(403);
    }

    public function test_wali_santri_cannot_view_others_registration_detail(): void
    {
        $wali1 = User::factory()->create(['role' => 'wali_santri']);
        $wali2 = User::factory()->create(['role' => 'wali_santri']);

        $registration = Registration::create([
            'user_id' => $wali1->id,
            'no_pendaftaran' => 'PSB-2026-1111',
            'nama_lengkap' => 'Santri Wali 1',
            'nik' => '3201123456780011',
            'tempat_tanggal_lahir' => 'Bogor, 12 Mei 2012',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Pesantren',
            'nama_wali' => 'Wali 1',
            'no_hp_wali' => '081234567890',
            'status' => 'menunggu_verifikasi',
        ]);

        // Wali 2 mencoba membuka detail pendaftaran milik Wali 1
        $response = $this->actingAs($wali2)->get("/wali/pendaftaran/{$registration->id}");

        $response->assertStatus(403);
    }
}
