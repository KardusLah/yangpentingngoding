<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'bocahkardus999@email.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('12312312'),
                'no_hp' => '08123456789',
                'level' => 'admin',
                'aktif' => 1,
            ]
        );
        // Tambahkan data lain sesuai kebutuhan
    }
}