<?php

use App\Helpers\Constants;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    public function run()
    {
        User::truncate();

        DB::table('users')->insert([
            'user_name' => Constants::ADMIN_SLUG,
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => Purifier::clean(env('ADMIN_EMAIL'), 'generalFields'),
            'password' => Purifier::clean(bcrypt(env('ADMIN_PASSWORD')), 'generalFields'),
            'bitcoin_address' => Purifier::clean(env('ADMIN_BITCOINADDRESS'), 'generalFields'),
            'slug' => Constants::ADMIN_SLUG,
            'is_admin' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // Seed a standard user
        if (env('APP_ENV') == 'local') {
            DB::table('users')->insert([
                'user_name' => 'bobisbob',
                'first_name' => 'Bob',
                'last_name' => 'Bob',
                'email' => 'robert.attfield00@gmail.com',
                'password' => bcrypt('P@ssw0rd55'),
                'bitcoin_address' => '17ptPaCkdHEKYrnmieMPB5jyamHY42P39S',
                'slug' => 'bobisbob',
                'is_admin' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
