<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        
        $this->call([
            BankSeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            BankDetailSeeder::class,
            MatchingIncomeSeeder::class,
        ]);

        // OR TRY IT
         
        // $this->call(BankSeeder::class);
        // $this->call(StateSeeder::class);
        // $this->call(CitySeeder::class);
        // $this->call(RoleSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(BankDetailSeeder::class);
        // $this->call(MatchingIncomeSeeder::class);
    }
}
