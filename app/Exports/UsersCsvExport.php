<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersCsvExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $users = new Collection();

        $count = 1;
        foreach (User::all() as $u) {
            $users->add([
                'id' => $count,
                'user_name' => $u->user_name,
                'first_name' => $u->first_name,
                'last_name' => $u->last_name,
                'email' => $u->email,
                'bitcoin_address' => $u->bitcoin_address,
                'is_admin' => $u->is_admin,
                'last_login_at' => $u->last_login_at,
                'last_logout_at' => $u->last_logout_at,
                'last_login_ip' => $u->last_login_ip,
                'slug' => $u->slug
            ]);
            $count+=1;
        }

        return $users;
    }
}
