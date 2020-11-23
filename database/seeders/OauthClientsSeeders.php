<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OauthClientsSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            [
                'id' => '9208bf87-975f-473f-8458-e3e3b7025394',
                'name' => 'Demo Client',
                'secret' => 'zJFCS3LVIA5J1VrDAn1DAR0kQTijc2Iv9oaTBY02',
                'redirect' => 'http://localhost/callback',
                'personal_access_client' => 1,
                'password_client' => 1,
                'revoked' => 0,
            ],
        ]);
    }
}
