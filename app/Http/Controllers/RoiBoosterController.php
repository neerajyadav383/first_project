<?php

namespace App\Http\Controllers;

use App\Models\Downline;
use App\Models\RoiIncome;
use App\Models\User;
use App\Models\IncomeReport;
use App\Models\MatchingIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoiBoosterController extends Controller
{
    public function testing_double_intry()
    {
        die();
        $AllUsers = User::where('activation_timestamp', 'LIKE', '2021-12-19%')->orWhere('activation_timestamp', 'LIKE', '2021-12-20%')->get();
        foreach ($AllUsers as $AllUser) {
            $AllUser->join_amt;
            //ROI & DIRECT INCOME
            $id                 = $AllUser->id;
            $user_id            = $AllUser->id;
            $renewal_amt        = $AllUser->join_amt;
            $amount             = ($renewal_amt * 1) / 100;
            $direct_income      = ($renewal_amt * 5) / 100;
            $date               = date('Y-m-d');
            $start_date         = date('Y-m-d', strtotime($date . ' +1 day'));
            $end_date           = date('Y-m-d', strtotime($date . ' +200 day'));

            $data               = array(
                'user_id'       => $user_id,
                'income_type'   => 'ROI INCOME',
                'amount'        => $amount,
                'start_date'    => $start_date,
                'end_date'      => $end_date,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            );
            RoiIncome::create($data);

            $user       = User::where('id', $id)->first();
            $sponsor_id = $user->sponsor_id;

            $userDI     = User::where('id', $sponsor_id)->first();
            if ($userDI != null) {
                if ($userDI->status == 1) {
                    $amount                 = checkMaxIncome($userDI->id, $direct_income);

                    $userDI->direct        += $amount;
                    $userDI->total_earning += $amount;
                    $userDI->direct_mems   += 1;
                    $userDI->updated_at     = date('Y-m-d H:i:s');
                    $userDI->save();

                    $data = array(
                        'user_id'           => $sponsor_id,
                        'income_type'       => 'DIRECT INCOME',
                        'amount'            => $amount,
                        'by_id'             => $user_id,
                        'level'             => '1',
                        'created_at'        => date('Y-m-d H:i:s'),
                        'updated_at'        => date('Y-m-d H:i:s'),
                    );
                    IncomeReport::create($data);
                }
            }



            $user_id;
            $renewal_amt;
            //MATCHING & DIRECT TEAM MATCHING INCOME
            $user = User::where('id', $user_id)->first();

            $matchingIncomeUser = MatchingIncome::where('user_id', $user_id)->first();
            if ($matchingIncomeUser == null) {
                // die('yes');
                $data = array(
                    'user_id'    => $user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                MatchingIncome::create($data);
            }

            $uplines = DB::select("SELECT T2.id, T2.name, T2.placement_id, T2.placement
                                    FROM (
                                        SELECT
                                            @r AS _id,
                                            (SELECT @r := placement_id FROM users WHERE id = _id) AS placement_id,
                                            @l := @l + 1 AS lvl
                                        FROM
                                            (SELECT @r := $user_id, @l := 0) vars,
                                            users h
                                        WHERE @r <> 0) T1
                                    JOIN users T2
                                    ON T1._id = T2.id
                                    ORDER BY T2.id DESC");

            // echo '<pre>';
            // print_r($uplines);
            // echo '</pre>';
            // die();
            foreach ($uplines as $key => $upline) {
                $upline_id = $upline->placement_id;
                if ($upline_id == "") {
                    break;
                }
                $matchingIncomeUser = MatchingIncome::where('user_id', $upline_id)->first();

                if ($upline->placement == 'Left') {
                    $left     = $renewal_amt;
                    $right    = 0;
                } else {
                    $left     = 0;
                    $right    = $renewal_amt;
                }

                if ($matchingIncomeUser == null) {
                    // die('yes');
                    $data = array(
                        'user_id'    => $upline_id,
                        'left'       => $left,
                        'right'      => $right,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    MatchingIncome::create($data);
                } else {
                    $left          += $matchingIncomeUser->left;
                    $right         += $matchingIncomeUser->right;

                    $pair_amt       = ($left > $right) ? $right : $left;
                    $matching_amt   = ($pair_amt * 8) / 100;

                    if ($matching_amt > 0) {
                        if ($upline->placement == 'Left') {
                            $matchingIncomeUser->left         -= ($pair_amt - $left);
                            $matchingIncomeUser->right         -= $pair_amt;
                        } else {
                            $matchingIncomeUser->left         -= $pair_amt;
                            $matchingIncomeUser->right         -= ($pair_amt - $right);
                        }
                        $matchingIncomeUser->save();

                        $userDI = User::where('id', $upline_id)->first();
                        if ($userDI->status == 1 && $userDI->left_direct > 0 && $userDI->right_direct > 0) {
                            $amount = checkMaxIncome($userDI->id, $matching_amt);

                            if ($amount > 0) {

                                $userDI->matching      += $amount;
                                $userDI->total_earning += $amount;
                                $userDI->updated_at     = date('Y-m-d H:i:s');
                                $userDI->save();

                                $data = array(
                                    'user_id'           => $upline_id,
                                    'income_type'       => 'MATCHING INCOME',
                                    'amount'            => $amount,
                                    // 'by_id' 		    => '',
                                    // 'level'		    => '1',
                                    'created_at'        => date('Y-m-d H:i:s'),
                                    'updated_at'        => date('Y-m-d H:i:s'),
                                );
                                IncomeReport::create($data);
                            }
                        }

                        $user = User::where('id', $upline_id)->first();
                        $sponsor_id = $user->sponsor_id;
                        $userDI = User::where('id', $sponsor_id)->first();
                        if ($userDI == null) {
                            break;
                        }
                        if ($userDI->status == 1 && $userDI->left_direct > 0 && $userDI->right_direct > 0) {
                            $amount = ($matching_amt * 10) / 100;

                            $amount = checkMaxIncome($userDI->id, $amount);

                            if ($amount > 0) {

                                $userDI->direct_team_matching  += $amount;
                                $userDI->total_earning += $amount;
                                $userDI->updated_at             = date('Y-m-d H:i:s');
                                $userDI->save();

                                $data = array(
                                    'user_id'                   => $sponsor_id,
                                    'income_type'               => 'DIRECT TEAM MATCHING INCOME',
                                    'amount'                    => $amount,
                                    'by_id'                     => $upline_id,
                                    'level'                     => 1,
                                    'created_at'                => date('Y-m-d H:i:s'),
                                    'updated_at'                => date('Y-m-d H:i:s'),
                                );
                                IncomeReport::create($data);
                            }
                        }
                    } else {
                        $matchingIncomeUser->left       = $left;
                        $matchingIncomeUser->right      = $right;
                        $matchingIncomeUser->save();
                    }
                }
                #REWARD & AWARD..***************..***************************..******************************
                distribute_reward($upline_id);
            }
        }


        die();

        die();
        $AllUsers = User::all();
        //echo '<pre>';
        //print_r($AllUsers->toArray());
        //echo '</pre>';
        //die();
        foreach ($AllUsers as $AllUser) {
            $user = User::where('id', $AllUser->id)->first();

            $sponsor_id = $user->sponsor_id;
            if ($sponsor_id == '' && $sponsor_id == null) {
                continue;
            }

            while (1) {
                if ($sponsor_id == $user->placement_id) {
                    echo $sponsor_id . $user->placement . '<br>';
                    if ($user->placement == "Left") {
                        $user = User::where('id', $sponsor_id)->first();
                        $user->left_direct += 1;
                        $user->save();
                    } elseif ($user->placement == "Right") {
                        $user = User::where('id', $sponsor_id)->first();
                        $user->right_direct += 1;
                        $user->save();
                    }
                    break;
                }
                $user = User::where('id', $user->placement_id)->first();
            }
        }

        die();
        $messages = DB::table('income_reports')
            ->where('income_type', 'ROI INCOME')
            ->where('created_at', 'LIKE', '2021-11-18%')
            ->where('user_id', '>', '1229')
            ->groupBy('user_id')
            ->having(DB::raw('count(user_id)'), '>', 1)
            ->pluck('user_id');

        foreach ($messages as $key => $message) {
            $IncomeReport = IncomeReport::where('user_id', $message)
                ->where('income_type', 'ROI INCOME')
                ->where('created_at', 'LIKE', '2021-11-18%')
                ->first();

            $user                    = User::where('id', $IncomeReport->user_id)->first();
            $user->roi              -= $IncomeReport->amount;
            $user->total_earning    -= $IncomeReport->amount;
            $user->save();

            $roiIncome = RoiIncome::where('user_id', $IncomeReport->user_id)
                ->where('income_type', 'ROI INCOME')
                ->where('pay_date', '2021-11-18')
                ->first();

            $roiIncome->count -= 1;
            $roiIncome->save();
        }

        echo '<pre>';
        print_r('success');
        echo '</pre>';
        die();

        // $message = IncomeReport::orderBy('user_id', 'ASC');

        // $messages = DB::table(DB::raw("({$message->toSql()}) as sub"))
        //     ->where('income_type', 'ROI INCOME')
        //     ->where('created_at', 'LIKE', '2021-11-18%')
        //     ->where('user_id', '>', '1229')
        //     ->groupBy('user_id')
        //     ->havingRaw('COUNT(*)>1')
        //     ->get();



        // DB::enableQueryLog();
        // $incomereport = DB::select("SELECT COUNT(*), user_id, amount FROM `income_reports` WHERE income_type='ROI INCOME' AND created_at LIKE '2021-11-18%' GROUP BY user_id HAVING COUNT(*)>1 ORDER BY user_id");
        // $quries = DB::getQueryLog();
        // dd($quries);
    }
    public function roi()
    {
        $date = date('Y-m-d');
        $next_pay_date = date('Y-m-d', strtotime($date . ' +1 month'));
        $roiUsers = RoiIncome::where('income_type', 'ROI INCOME')->where('status', 'Pending')->where('start_date', '<=', $date)->where('end_date', '>', $date)->where('pay_date', '<=', $date)->get();
        // echo '<pre>';
        // print_r($roiUsers->toArray());
        // echo '</pre>';
        // die();

        foreach ($roiUsers as $roiUser) {
            $user = User::where('id', $roiUser->user_id)->first();
            if ($user->status == 1) {
                $amount_h = checkMaxIncome($user->id, $roiUser->amount);
                $user->roi += $amount_h;
                $user->total_earning += $amount_h;
                $user->save();

                $user = RoiIncome::where('id', $roiUser->id)->first();
                $user->count += 1;
                $user->pay_date = $next_pay_date;
                $user->updated_at = date('Y-m-d H:i:s');
                $user->save();

                $data = array(
                    'user_id'     => $roiUser->user_id,
                    'income_type' => $roiUser->income_type,
                    'amount'      => $amount_h,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                );
                IncomeReport::create($data);
            }
        }

        $roiUsers = RoiIncome::where('income_type', 'DIRECT INCOME')->where('status', 'Pending')->where('start_date', '<=', $date)->where('end_date', '>', $date)->where('pay_date', '<=', $date)->get();
        // echo '<pre>';
        // print_r($roiUsers->toArray());
        // echo '</pre>';
        // die();

        foreach ($roiUsers as $roiUser) {
            $user = User::where('id', $roiUser->user_id)->first();
            if ($user->status == 1) {
                $amount_h = checkMaxIncome($user->id, $roiUser->amount);
                $user->roi += $amount_h;
                $user->total_earning += $amount_h;
                $user->save();

                $user = RoiIncome::where('id', $roiUser->id)->first();
                $user->count += 1;
                $user->pay_date = $next_pay_date;
                $user->updated_at = date('Y-m-d H:i:s');
                $user->save();

                $data = array(
                    'user_id'     => $roiUser->user_id,
                    'income_type' => $roiUser->income_type,
                    'amount'      => $amount_h,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                );
                IncomeReport::create($data);
            }
        }
        echo 'ROI Closing Successfull!';
    }

    public function booster()
    {
        $date = date('Y-m-d');
        $next_pay_date = date('Y-m-d', strtotime($date . ' +1 month'));
        $roiUsers = RoiIncome::where('income_type', 'ROYALTY INCOME')->where('status', 'Pending')->where('start_date', '<=', $date)->where('end_date', '>', $date)->where('pay_date', '<=', $date)->get();
        // echo '<pre>';
        // print_r($roiUsers->toArray());
        // echo '</pre>';
        // die();

        foreach ($roiUsers as $roiUser) {
            $user = User::where('id', $roiUser->user_id)->first();
            if ($user->status == 1) {
                $amount_h = checkMaxIncome($user->id, $roiUser->amount);
                $user->direct_team_matching += $amount_h;
                $user->total_earning += $amount_h;
                $user->save();

                $user = RoiIncome::where('id', $roiUser->id)->first();
                $user->count += 1;
                $user->pay_date = $next_pay_date;
                $user->updated_at = date('Y-m-d H:i:s');
                $user->save();

                $data = array(
                    'user_id'     => $roiUser->user_id,
                    'income_type' => $roiUser->income_type,
                    'amount'      => $amount_h,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                );
                IncomeReport::create($data);
            }
        }


        $roiUsers = RoiIncome::where('income_type', 'REFERAL ROYALTY INCOME')->where('status', 'Pending')->where('start_date', '<=', $date)->where('end_date', '>', $date)->where('pay_date', '<=', $date)->get();
        
        foreach ($roiUsers as $roiUser) {
            $user = User::where('id', $roiUser->user_id)->first();
            if ($user->status == 1) {
                $amount_h = checkMaxIncome($user->id, $roiUser->amount);
                $user->direct_team_matching += $amount_h;
                $user->total_earning += $amount_h;
                $user->save();

                $user = RoiIncome::where('id', $roiUser->id)->first();
                $user->count += 1;
                $user->pay_date = $next_pay_date;
                $user->updated_at = date('Y-m-d H:i:s');
                $user->save();

                $data = array(
                    'user_id'     => $roiUser->user_id,
                    'income_type' => $roiUser->income_type,
                    'amount'      => $amount_h,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                );
                IncomeReport::create($data);
            }
        }

        return 'Royalty Closing Successfull!';
    }

    public function closing()
    {
        $users = User::all();
        // echo '<pre>';
        // print_r($users);
        // echo '</pre>';
        // die();
        foreach ($users as $key => $user) {
            $total_amount = ($user->roi + $user->booster + $user->direct + $user->matching + $user->direct_team_matching + $user->reward);
            if ($total_amount > 0) {
                $tds = ($total_amount * 10) / 100;
                $avail_amount = $total_amount - $tds;
                $closing_statement = new ClosingStatement;
                $closing_statement->user_id = $user->id;
                $closing_statement->roi = $user->roi;
                $closing_statement->booster = $user->booster;
                $closing_statement->direct = $user->direct;
                $closing_statement->matching = $user->matching;
                $closing_statement->direct_team_matching = $user->direct_team_matching;
                $closing_statement->reward = $user->reward;
                $closing_statement->total_amount = $total_amount;
                $closing_statement->tds = $tds;
                $closing_statement->avail_amount = $avail_amount;
                $closing_statement->created_at = date('Y-m-d H:i:s');
                $closing_statement->updated_at = date('Y-m-d H:i:s');
                $closing_statement->save();

                $userUpdate = User::where('id', $user->id)->first();
                $userUpdate->roi = 0;
                $userUpdate->booster = 0;
                $userUpdate->direct = 0;
                $userUpdate->matching = 0;
                $userUpdate->direct_team_matching = 0;
                $userUpdate->reward = 0;
                $userUpdate->wallet += $avail_amount;
                $userUpdate->updated_at = date('Y-m-d H:i:s');
                $userUpdate->save();
            }
        }
        return 'Closing successfully!';
    }

    public function checkBooster(Request $request)
    {
        die();
        // $date = date('Y-m-d');
        // $predate = date('Y-m-d', strtotime('-7 day', strtotime($date)));
        // echo '<pre>';
        // print_r($predate);
        // echo '</pre>';
        // die();
        // 2222222222222222222%%%%%%%%%%%%%%%%%% booster start
        $date = date('Y-m-d');
        $predate = date('Y-m-d', strtotime('-3 day', strtotime($date)));
        $predate = $predate . ' 00:00:00';
        $users = User::doesntHave('roiIncomes', 'or', function ($q) {
            $q->where('income_type', 'BOOSTER INCOME');
        })->where('direct_mems', '>', '1')
            ->where('activation_timestamp', '!=', '')
            ->where('activation_timestamp', '>', $predate)->get();
        // echo '<pre>';
        // print_r($users->toArray());
        // echo '</pre>';
        // die();
        foreach ($users as $user) {
            $booster = 0;
            $leftUsers = Downline::where('user_id', $user->id)->where('placement', 'Left')->get();
            foreach ($leftUsers as $leftUserDownline) {
                $check = User::where('id', $leftUserDownline->downline_id)->where('sponsor_id', $user->id)->where('join_amt', '>=', $user->join_amt)->first();
                if ($check != null) {
                    $booster = 1;
                    break;
                }
            }

            if ($booster == 1) {
                $booster = 0;
                $rightUsers = Downline::where('user_id', $user->id)->where('placement', 'Right')->get();
                foreach ($rightUsers as $leftUserDownline) {
                    $check = User::where('id', $leftUserDownline->downline_id)->where('sponsor_id', $user->id)->where('join_amt', '>=', $user->join_amt)->first();
                    if ($check != null) {
                        $booster = 1;
                        break;
                    }
                }
                if ($booster == 1) {
                    $date = date('Y-m-d');
                    $start_date = date('Y-m-d', strtotime($date . ' +1 day'));
                    $end_date = date('Y-m-d', strtotime($date . ' +100 day'));
                    $amount = ($user->join_amt * 2) / 100;
                    $data = array(
                        'user_id'    => $user->id,
                        'income_type' => 'BOOSTER INCOME',
                        'amount' => $amount,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    RoiIncome::create($data);
                }
            }
        }
        // 2222222222222222222%%%%%%%%%%%%%%%%%% booster start

        // 1111111111111111111%%%%%%%%%%%%%%%%%% booster start
        $date = date('Y-m-d');
        $predate = date('Y-m-d', strtotime('-7 day', strtotime($date)));
        $predate = $predate . ' 00:00:00';
        $users = User::doesntHave('roiIncomes', 'or', function ($q) {
            $q->where('income_type', 'BOOSTER INCOME');
        })->where('direct_mems', '>', '1')
            ->where('activation_timestamp', '!=', '')
            ->where('activation_timestamp', '>', $predate)->get();
        // echo '<pre>';
        // print_r($users->toArray());
        // echo '</pre>';
        // die();
        foreach ($users as $user) {
            $booster = 0;
            $leftUsers = Downline::where('user_id', $user->id)->where('placement', 'Left')->get();
            foreach ($leftUsers as $leftUserDownline) {
                $check = User::where('id', $leftUserDownline->downline_id)->where('sponsor_id', $user->id)->where('join_amt', '>=', $user->join_amt)->first();
                if ($check != null) {
                    $booster = 1;
                    break;
                }
            }

            if ($booster == 1) {
                $booster = 0;
                $rightUsers = Downline::where('user_id', $user->id)->where('placement', 'Right')->get();
                foreach ($rightUsers as $leftUserDownline) {
                    $check = User::where('id', $leftUserDownline->downline_id)->where('sponsor_id', $user->id)->where('join_amt', '>=', $user->join_amt)->first();
                    if ($check != null) {
                        $booster = 1;
                        break;
                    }
                }
                if ($booster == 1) {
                    $date = date('Y-m-d');
                    $start_date = date('Y-m-d', strtotime($date . ' +1 day'));
                    $end_date = date('Y-m-d', strtotime($date . ' +100 day'));
                    $amount = ($user->join_amt * 1) / 100;
                    $data = array(
                        'user_id'    => $user->id,
                        'income_type' => 'BOOSTER INCOME',
                        'amount' => $amount,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    RoiIncome::create($data);
                }
            }
        }
        // 1111111111111111111%%%%%%%%%%%%%%%%%% booster start

        return 'Check Booster Users Done!';
    }

    public function checkRetopup()
    {
        #
    }
}
