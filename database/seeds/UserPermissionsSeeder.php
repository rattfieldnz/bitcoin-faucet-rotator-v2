<?php

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $standardUser = User::where('slug', '=', 'bobisbob')->first();
        $initialPermissions = [
            Permission::where('name', 'read-users')->first(),
            Permission::where('name', 'read-faucets')->first(),
            Permission::where('name', 'create-user-faucets')->first(),
            Permission::where('name', 'read-user-faucets')->first(),
            Permission::where('name', 'update-user-faucets')->first(),
            Permission::where('name', 'soft-delete-user-faucets')->first(),
            Permission::where('name', 'permanent-delete-user-faucets')->first(),
            Permission::where('name', 'restore-user-faucets')->first(),
            Permission::where('name', 'read-payment-processors')->first(),
        ];

        foreach ($initialPermissions as $permission) {
            $standardUser->attachPermission($permission);
            $this->command->info('Assigning permission "' . $permission->name . '"" to user "' . $standardUser->user_name . '"');
        }
    }
}
