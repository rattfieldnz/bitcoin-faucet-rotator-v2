<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $user = User::where('is_admin', '=', true)->first();

        if (env('APP_ENV') == 'local') {
            $standardUser = User::where('slug', '=', 'bobisbob')->first();
        }

        $this->command->info('Truncating Role and Permission tables');
        try {
            $this->truncateLaratrustTables();
        } catch (Illuminate\Database\QueryException $e) {
        }

        $config = config('laratrust_seeder.role_structure');
        $userPermission = config('laratrust_seeder.permission_structure');
        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

        foreach ($config as $key => $modules) {
            // Create a new
            $key = Purifier::clean($key, 'generalFields');
            $role = Role::create([
                'name' => $key,
                'display_name' => ucwords(str_replace("_", " ", $key)),
                'description' => ucwords(str_replace("_", " ", $key))
            ]);

            $this->command->info('Creating Role '. strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {
                $permissions = explode(',', $value);

                foreach ($permissions as $p => $perm) {
                    $permissionValue = Purifier::clean($mapPermission->get($perm), 'generalFields');
                    $module = Purifier::clean($module, 'generalFields');

                    $permission = Permission::firstOrCreate([
                        'name' => $permissionValue . '-' . $module,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    ]);

                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);

                    if (!$role->hasPermission($permission->name)) {
                        $role->attachPermission($permission);
                    } else {
                        $this->command->info($key . ': ' . $p . ' ' . $permissionValue . ' already exist');
                    }
                }
            }
        }

        $this->command->info('Attaching \'owner\' role to user that has \'is_admin\' set to true.');
        $user->attachRole(Role::where('name', '=', 'owner')->first());

        if (env('APP_ENV') == 'local') {
            $standardUser->attachRole(Role::where('name', '=', 'user')->first());
        }

        // creating user with permissions
        try {
            if (!empty($userPermission)) {
                foreach ($userPermission as $key => $modules) {
                    foreach ($modules as $module => $value) {
                        $permissions = explode(',', $value);
                        foreach ($permissions as $p => $perm) {
                            $permissionValue = Purifier::clean($mapPermission->get($perm), 'generalFields');
                            $module = Purifier::clean($module, 'generalFields');

                            $permission = Permission::firstOrCreate([
                                'name' => $permissionValue . '-' . $module,
                                'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                                'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                            ]);

                            $this->command->info('Creating Permission to ' . $permissionValue . ' for ' . $module);
                            $user->attachPermission($permission);
                            $this->command->info('Assigning permission "' . $permission->name . '"" to user "' . $user->user_name . '"');
                        }
                    }
                }
            }
        } catch (Illuminate\Database\QueryException $e) {
        }
    }

    /**
     * Truncates all the laratrust tables and the users table
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();

        if (DB::table('role_user')->exists()) {
            DB::table('role_user')->truncate();
        }
        Role::truncate();
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
