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
            'user_name'=>env('ADMIN_USERNAME'),
            'first_name' =>env('ADMIN_FIRSTNAME'),
            'last_name' =>env('ADMIN_LASTNAME'),
            'email'=>env('ADMIN_EMAIL'),
            'password'=> bcrypt(env('ADMIN_PASSWORD')),
            'bitcoin_address' => env('ADMIN_BITCOINADDRESS'),
            'is_admin' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $adminUser->save();
    }
}
