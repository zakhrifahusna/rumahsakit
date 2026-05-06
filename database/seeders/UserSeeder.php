<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user')->insert([
            [
                'nama_user' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567890',
                'foto_user' => null,
                'roles' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_user' => 'Kepala RS User',
                'username' => 'kepala_rs',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567891',
                'foto_user' => null,
                'roles' => 'kepala_rs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_user' => 'Petugas User',
                'username' => 'petugas',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567892',
                'foto_user' => null,
                'roles' => 'petugas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_user' => 'Pasien User',
                'username' => 'pasien',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567893',
                'foto_user' => null,
                'roles' => 'pasien',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
