<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin       = new Role();
        $admin->name = 'admin';
        $admin->slug = 'admin';
        $admin->save();
        
        $member       = new Role();
        $member->name = 'user';
        $member->slug = 'user';
        $member->save();
    }
}