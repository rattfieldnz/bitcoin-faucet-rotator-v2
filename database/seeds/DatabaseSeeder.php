<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $this->call(UsersSeeder::class);
        $this->call(FaucetsTableSeeder::class);
        $this->call(PaymentProcessorsTableSeeder::class);
        $this->call(FaucetPaymentProcessorsTableSeeder::class);
        $this->call(LanguagesSeeder::class);
        $this->call(MainMetaTableSeeder::class);
        $this->call(TwitterConfigTableSeeder::class);
        $this->call(AdBlockSeeder::class);
        $this->call(LaratrustSeeder::class);
        $this->call(SocialLinksTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        Model::reguard();
    }
}
