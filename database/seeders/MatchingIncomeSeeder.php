<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MatchingIncome;
use Illuminate\Support\Facades\Auth;

class MatchingIncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $matchingIncome             = new MatchingIncome();
        $matchingIncome->user_id    = 1;
        $matchingIncome->created_at = date('Y-m-d H:i:s');
        $matchingIncome->updated_at = date('Y-m-d H:i:s');
        $matchingIncome->save();
    }
}
