<?php

use App\Models\Downline;
use App\Models\IncomeReport;
use App\Models\Reward;
use App\Models\RoiIncome;
use App\Models\User;
use Carbon\Carbon;

if (!function_exists('uploadFile')) {
	function uploadFile($file, $userid)
	{
		$curr_time     = strtotime(date('Y-m-d H:i:s'));
		$filename     = $file->getClientOriginalName();
		$file->move(public_path() . '/assets/img/profiles/' . $userid . '/' . $curr_time . '/', $filename);
		$destinationPath = 'assets/img/profiles/' . $userid . '/' . $curr_time . '/' . $filename;
		return $destinationPath;
	}
}

if (!function_exists('checkMaxIncome')) {
	function checkMaxIncome($user_id, $amount)
	{
		$user = User::where('id', $user_id)->first();
		$join_amt = $user->join_amt;
		if ($join_amt == 12000) {
			$maxAmount = 10000;
		} else {
			$maxAmount = 1000;
		}
		$date = date('w');
		if ($user->earning_date != $date) {
			$user->earning_date  = $date;
			$user->total_earning = 0;
			$user->save();
			if ($amount > $maxAmount) {
				$amount = $maxAmount;
			}
		} else {
			if ($user->total_earning >= $maxAmount) {
				$amount = 0;
			} elseif (($user->total_earning+$amount) > $maxAmount) {
				$amount = $maxAmount-$user->total_earning;
			}
		}
		return $amount;
	}
}

if (!function_exists('distribute_reward')) {
	function distribute_reward($user_id)
	{
		$left_downline = User::where('placement_id', $user_id)
			->where('placement', 'Left')
			->first();

		$left = 0;
		if ($left_downline != null) {
			$left = $left_downline->join_amt;

			$downline = Downline::where('user_id', $left_downline->id)->get()->sum('join_amt');
			$left = $downline + $left;
		}

		$right_downline = User::where('placement_id', $user_id)
			->where('placement', 'Right')
			->first();
		$right = 0;
		if ($right_downline != null) {
			if ($right_downline->join_amt > 0) {
				$right       = 1;
			}
			$downline = Downline::where('user_id', $right_downline->id)->get()->sum('join_amt');
			$right = $downline + $right;
		}

		$pair = ($left > $right) ? $right : $left;
		$date = date('Y-m-d H:i:s');
		if ($pair >= 50000000) {
			$data = array(
				'user_id'		=> $user_id,
				'rank'			=> 'TATA SAFARI',
				'created_at' 	=> $date,
				'updated_at' 	=> $date,
			);

			$reward = Reward::where('user_id', $user_id)->where('rank', $data['rank'])->first();
			if ($reward == null) {
				Reward::create($data);
			}
		}
		if ($pair >= 20000000) {
			$data = array(
				'user_id'		=> $user_id,
				'rank'			=> 'KWID CAR',
				'created_at' 	=> $date,
				'updated_at' 	=> $date,
			);

			$reward = Reward::where('user_id', $user_id)->where('rank', $data['rank'])->first();
			if ($reward == null) {
				Reward::create($data);
			}
		}
		if ($pair >= 7500000) {
			$data = array(
				'user_id'		=> $user_id,
				'rank'			=> 'BULLET',
				'created_at' 	=> $date,
				'updated_at' 	=> $date,
			);

			$reward = Reward::where('user_id', $user_id)->where('rank', $data['rank'])->first();
			if ($reward == null) {
				Reward::create($data);
			}
		}
		if ($pair >= 2500000) {
			$data = array(
				'user_id'		=> $user_id,
				'rank'			=> 'BIKE',
				'created_at' 	=> $date,
				'updated_at' 	=> $date,
			);

			$reward = Reward::where('user_id', $user_id)->where('rank', $data['rank'])->first();
			if ($reward == null) {
				Reward::create($data);
			}
		}
		if ($pair >= 1200000) {
			$data = array(
				'user_id'		=> $user_id,
				'rank'			=> 'LAPTOP',
				'created_at' 	=> $date,
				'updated_at' 	=> $date,
			);

			$reward = Reward::where('user_id', $user_id)->where('rank', $data['rank'])->first();
			if ($reward == null) {
				Reward::create($data);
			}
		}
		if ($pair >= 500000) {
			$data = array(
				'user_id'		=> $user_id,
				'rank'			=> 'MINI LAPTOP',
				'created_at' 	=> $date,
				'updated_at' 	=> $date,
			);

			$reward = Reward::where('user_id', $user_id)->where('rank', $data['rank'])->first();
			if ($reward == null) {
				Reward::create($data);
			}
		}
		if ($pair >= 200000) {
			$data = array(
				'user_id'		=> $user_id,
				'rank'			=> 'MOBILE',
				'created_at' 	=> $date,
				'updated_at' 	=> $date,
			);

			$reward = Reward::where('user_id', $user_id)->where('rank', $data['rank'])->first();
			if ($reward == null) {
				Reward::create($data);
			}
		}
	}
}
