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
        Eloquent::unguard();
        Schema::disableForeignKeyConstraints();
        $this->call(UsersSeeder::class);
        $this->call(FaucetsTableSeeder::class);
        $this->call(PaymentProcessorsTableSeeder::class);
        $this->call(FaucetPaymentProcessorsTableSeeder::class);
        $this->call(LanguagesSeeder::class);
        $this->call(MainMetaTableSeeder::class);
        $this->call(TwitterConfigTableSeeder::class);
        $this->call(AdBlockSeeder::class);
        $this->call(LaratrustSeeder::class);
        $this->call(UserPermissionsSeeder::class);
        $this->call(SocialLinksTableSeeder::class);
        $this->call(AlertIconsSeeder::class);
        $this->call(AlertTypesSeeder::class);
        $this->call(AlertSeeder::class);
        $this->call(PrivacyPolicySeeder::class);
        Schema::enableForeignKeyConstraints();
        Eloquent::reguard();
    }
}
