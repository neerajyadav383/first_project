<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminrole = Role::where('slug', 'admin')->first();

        $admin               = new User();
        $admin->userid       = 'E0077';
        $admin->sponsor_id   = '';
        $admin->placement_id = '';
        $admin->name         = 'Admin';
        $admin->email        = 'admin@gmail.com';
        $admin->mobile       = '1234567890';
        $admin->password     = Hash::make('00');
        $admin->trans_pass   = 7700;
        $admin->photo        = 'assets/img/user.png';
        $admin->status       = 1;
        $admin->activation_timestamp = date('Y-m-d H:i:s');
        $admin->join_amt     = 12000;
        $admin->created_at   = date('Y-m-d H:i:s');
        $admin->updated_at   = date('Y-m-d H:i:s');
        $admin->save();
        $admin->roles()->attach($adminrole);
    }
}
