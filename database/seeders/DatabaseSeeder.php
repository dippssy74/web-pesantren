<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@pesantren.test'],
            [
                'name' => 'Administrator Pesantren',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Akun Wali Santri
        User::firstOrCreate(
            ['email' => 'wali@pesantren.test'],
            [
                'name' => 'H. Syarifuddin (Wali Santri)',
                'password' => bcrypt('password'),
                'role' => 'wali_santri',
            ]
        );

        // Akun Umum
        User::firstOrCreate(
            ['email' => 'umum@pesantren.test'],
            [
                'name' => 'Fulan bin Fulan (Umum)',
                'password' => bcrypt('password'),
                'role' => 'umum',
            ]
        );

        // Seeder Registrasi Santri (PSB Online)
        $this->call(RegistrationSeeder::class);
    }
}
