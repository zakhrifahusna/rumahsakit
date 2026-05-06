<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class datapasienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersData = DB::table('user')->select(
            DB::raw('id as user_id, nama_user as nama_pasien, username as email, no_telepon as no_telp, foto_user as foto_pasien')
        )->get();
        
        $dataToInsert = $usersData->map(function($user) {
            return (array) $user;
        })->toArray();

        DB::table('datapasien')->insert($dataToInsert);
    }
}
