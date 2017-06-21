<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

//use Laracasts\TestDummy\Factory as TestDummy;

class UsersSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->truncate();

        $adminUser = new User([
            'user_name' => Purifier::clean(env('ADMIN_USERNAME'), 'generalFields'),
            'first_name' => Purifier::clean(env('ADMIN_FIRSTNAME'), 'generalFields'),
            'last_name' => Purifier::clean(env('ADMIN_LASTNAME'), 'generalFields'),
            'email' => Purifier::clean(env('ADMIN_EMAIL'), 'generalFields'),
            'password' => Purifier::clean(bcrypt(env('ADMIN_PASSWORD')), 'generalFields'),
            'bitcoin_address' => Purifier::clean(env('ADMIN_BITCOINADDRESS'), 'generalFields'),
            'is_admin' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $adminUser->save();
    }
}
