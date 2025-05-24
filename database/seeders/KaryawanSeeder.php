<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Karyawan;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'bocahkardus999@email.com')->first();

        if ($adminUser) {
            Karyawan::updateOrCreate(
                ['id_user' => $adminUser->id],
                [
                    'nama_karyawan' => 'Admin',
                    'alamat' => 'Alamat Admin',
                    'foto' => null,
                    'no_hp' => '08123456789',
                    'jabatan' => 'administrasi',
                ]
            );
            // Tambahkan log untuk debug
            echo "Karyawan admin dibuat/diupdate dengan id_user: " . $adminUser->id . PHP_EOL;
        } else {
            echo "User admin tidak ditemukan!" . PHP_EOL;
        }
    }
}
