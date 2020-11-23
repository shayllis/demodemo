<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'profile_id' => 1,
                'name' => 'Comum',
                'document' => '212.146.930-32',
                'email' => 'comum@mail.com',
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now
            ],

            [
                'profile_id' => 2,
                'name' => 'Lojista',
                'document' => '34.384.702/0001-38',
                'email' => 'lojista@mail.com',
                'email_verified_at' => $now,
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);
    }
}
