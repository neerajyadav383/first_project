<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ClosingStatement;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RenewalReport;
use App\Models\Downline;
use App\Models\RoiIncome;
use App\Models\IncomeReport;
use App\Models\MatchingIncome;
use App\Models\TopupReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TopupIdController extends Controller
{
    public function topupId()
    {
        return view('admin.topupId');
    }

    public function addTopupId(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        $user = User::where('userid', $request->topup_id)->first();
        if ($user == null) {
            return "Invalid User ID.||||";
        } else {
            if ($user->status == 0) {
                // die('Inactive');
                if (Auth::user()->topup_wallet >= $request->renewal_amt) {

                    $user_id = $user->id;
                    $renewal_amt = $request->renewal_amt;
                    $data = array(
                        'join_amt' => $renewal_amt,
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    Downline::where('downline_id', $user_id)->update($data);

                    if ($user->activation_timestamp == null || $user->activation_timestamp == '') {
                        $user->status = 1;
                        $user->activation_timestamp = date('Y-m-d H:i:s');
                        $user->join_amt = $request->renewal_amt;
                        $user->updated_at = date('Y-m-d H:i:s');
                        $user->save();
                    } else {
                        $user->status = 1;
                        $user->join_amt = $request->renewal_amt;
                        $user->updated_at = date('Y-m-d H:i:s');
                        $user->save();
                    }
                    $authuser = Auth::user();
                    $authuser->topup_wallet -= $request->renewal_amt;
                    $authuser->save();

                    $data = array(
                        'user_id'    => $user->id,
                        'topup_type' => 'TOPUP',
                        'amount'     => $request->renewal_amt,
                        'topupby_id' => $authuser->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    TopupReport::create($data);



                    $sponsor_id = $user->sponsor_id;
                    if ($sponsor_id != '' || $sponsor_id != null) {
                        while (1) {
                            if ($sponsor_id == $user->placement_id) {
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




                    return "ID has been activated successfully.||||" . $user_id;
                } else {
                    return "Insufficient topup wallet amount.||||";
                }
            } else {
                return "Already Active.||||";
            }
        }
        return view('admin.topupId');
    }

    public function distributeIncome(Request $request)
    {
        //ROI & DIRECT INCOME
        $id                 = $request->id;
        $user_id            = $request->id;
        $renewal_amt        = $request->renewal_amt;

        $user       = User::where('id', $id)->first();
        $sponsor_id = $user->sponsor_id;
        $userDI     = User::where('id', $sponsor_id)->first();
        $userDI->direct_mems   += 1;
        $userDI->save();

        if ($renewal_amt == 12000) {
            $amount             = 600;
            $direct_income      = ($renewal_amt * 2) / 100;
            $date               = date('Y-m-d');
            $start_date         = date('Y-m-d', strtotime($date . ' +1 day'));
            $pay_date           = date('Y-m-d', strtotime($start_date . ' +1 month'));
            $end_date           = date('Y-m-d', strtotime($date . ' +36 months'));

            $data               = array(
                'user_id'       => $user_id,
                'income_type'   => 'ROI INCOME',
                'amount'        => $amount,
                'start_date'    => $start_date,
                'end_date'      => $end_date,
                'pay_date'      => $pay_date,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            );
            RoiIncome::create($data);

            if ($userDI != null) {
                if ($userDI->status == 1) {

                    $data               = array(
                        'user_id'       => $user_id,
                        'income_type'   => 'DIRECT INCOME',
                        'amount'        => $direct_income,
                        'start_date'    => $start_date,
                        'end_date'      => $end_date,
                        'pay_date'      => $pay_date,
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s'),
                    );
                    RoiIncome::create($data);
                }
            }
        }

        $this->distributeMatchingIncome($user_id, $renewal_amt);
    }

    public function distributeMatchingIncome($user_id, $renewal_amt)
    {
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


            if ($renewal_amt == 12000) {
                if ($upline->placement == 'Left') {
                    $left     = 1;
                    $right    = 0;
                } else {
                    $left     = 0;
                    $right    = 1;
                }

                if ($matchingIncomeUser == null) {
                    // die('yes');
                    $data = array(
                        'user_id'    => $upline_id,
                        'left2'       => $left,
                        'right2'      => $right,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    MatchingIncome::create($data);
                } else {
                    $left          += $matchingIncomeUser->left2;
                    $right         += $matchingIncomeUser->right2;

                    $pair_amt       = ($left > $right) ? $right : $left;
                    $matching_amt   = ($pair_amt * 1200);

                    if ($matching_amt > 0) {
                        if ($upline->placement == 'Left') {
                            $matchingIncomeUser->left2         -= ($pair_amt - $left);
                            $matchingIncomeUser->right2        -= $pair_amt;
                        } else {
                            $matchingIncomeUser->left2         -= $pair_amt;
                            $matchingIncomeUser->right2        -= ($pair_amt - $right);
                        }
                        $matchingIncomeUser->save();

                        $userDI = User::where('id', $upline_id)->first();
                        if ($userDI->status == 1) {
                            $amount = checkMaxIncome($userDI->id, $matching_amt);

                            if ($amount > 0) {

                                $userDI->matching      += $amount;
                                $userDI->total_earning += $amount;
                                $userDI->updated_at     = date('Y-m-d H:i:s');
                                $userDI->save();

                                $data = array(
                                    'user_id'           => $upline_id,
                                    'income_type'       => 'BINARY INCOME',
                                    'amount'            => $amount,
                                    // 'by_id' 		    => '',
                                    // 'level'		    => '1',
                                    'created_at'        => date('Y-m-d H:i:s'),
                                    'updated_at'        => date('Y-m-d H:i:s'),
                                );
                                IncomeReport::create($data);
                            }
                        }
                    } else {
                        $matchingIncomeUser->left2       = $left;
                        $matchingIncomeUser->right2      = $right;
                        $matchingIncomeUser->save();
                    }
                }
            } else {
                if ($upline->placement == 'Left') {
                    $left     = 1;
                    $right    = 0;
                } else {
                    $left     = 0;
                    $right    = 1;
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
                    $matching_amt   = ($pair_amt * 200);

                    if ($matching_amt > 0) {
                        if ($upline->placement == 'Left') {
                            $matchingIncomeUser->left         -= ($pair_amt - $left);
                            $matchingIncomeUser->right        -= $pair_amt;
                        } else {
                            $matchingIncomeUser->left         -= $pair_amt;
                            $matchingIncomeUser->right        -= ($pair_amt - $right);
                        }
                        $matchingIncomeUser->save();

                        $userDI = User::where('id', $upline_id)->first();
                        if ($userDI->status == 1) {
                            $amount = checkMaxIncome($userDI->id, $matching_amt);

                            if ($amount > 0) {

                                $userDI->matching      += $amount;
                                $userDI->total_earning += $amount;
                                $userDI->updated_at     = date('Y-m-d H:i:s');
                                $userDI->save();

                                $data = array(
                                    'user_id'           => $upline_id,
                                    'income_type'       => 'BINARY INCOME',
                                    'amount'            => $amount,
                                    // 'by_id' 		    => '',
                                    // 'level'		    => '1',
                                    'created_at'        => date('Y-m-d H:i:s'),
                                    'updated_at'        => date('Y-m-d H:i:s'),
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
            }


            #REWARD & AWARD..***************..***************************..******************************
            // distribute_reward($upline_id);
        }
    }

    public function renewalYourId(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        $user = User::where('userid', $request->topup_id)->first();
        if ($user == null) {
            return "Invalid User ID.||||";
        } else {
            if ($user->status != 0) {
                // die('Inactive');
                if (Auth::user()->topup_wallet >= $request->renewal_amt) {
                    // $join_amt = $user->join_amt + $request->renewal_amt;
                    $user_id = $user->id;

                    $dd = Downline::where('downline_id', $user_id)->first();

                    $renewal_amt = $dd->join_amt + $request->renewal_amt;
                    $data = array(
                        'join_amt' => $renewal_amt,
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    Downline::where('downline_id', $user_id)->update($data);

                    if ($user->activation_timestamp == null || $user->activation_timestamp == '') {
                        $user->status = 1;
                        $user->activation_timestamp = date('Y-m-d H:i:s');
                        $user->join_amt = $renewal_amt;
                        $user->updated_at = date('Y-m-d H:i:s');
                        $user->save();
                    } else {
                        $user->status = 1;
                        $user->join_amt = $renewal_amt;
                        $user->updated_at = date('Y-m-d H:i:s');
                        $user->save();
                    }
                    $authuser = Auth::user();
                    $authuser->topup_wallet -= $request->renewal_amt;
                    $authuser->save();

                    $data = array(
                        'user_id'    => $user->id,
                        'topup_type' => 'RENEWAL',
                        'amount' => $request->renewal_amt,
                        'topupby_id' => $authuser->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    TopupReport::create($data);

                    $data = array(
                        'status' => 'Expired',
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    RoiIncome::where('user_id', $user->id)->update($data);

                    return "ID has been activated successfully.||||" . $user->id;
                } else {
                    return "Insufficient topup wallet amount.||||";
                }
            } else {
                return "TopUp required on your ID.||||";
            }
        }
        return view('admin.topupId');
    }

    public function closingStatement()
    {
        $user  = Auth::user();
        if ($user->hasRole('admin')) {
            $closing_statement = ClosingStatement::with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        } else {
            $closing_statement = $user->closingStatement()->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        }
        // echo '<pre>';
        // print_r($renewalYourId);
        // echo '</pre>';
        // die();
        return view('admin.closingStatement', ['closing_statement' => $closing_statement]);
    }

    public function search_closing_statement(Request $request)
    {
        // $request->from_date = '2021-11-14';
        // $request->to_date = '2021-11-16';
        $user  = Auth::user();
        if ($user->hasRole('admin')) {
            $closing_statement = ClosingStatement::where('created_at', '>=', $request->from_date)
                ->where('created_at', '<=', $request->to_date)
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        } else {
            $closing_statement = $user->closingStatement()
                ->where('created_at', '>=', $request->from_date)
                ->where('created_at', '<=', $request->to_date)
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        }
        // echo '<pre>';
        // print_r($closing_statement->toArray());
        // echo '</pre>';
        // die();
        return response()->json(['closing_statement' => $closing_statement]);
    }

    public function test()
    {
        $income_incomes = IncomeReport::all();
        foreach ($income_incomes as $key => $income_income) {
            if ($income_income->income_type == 'DIRECT INCOME') {
                $user = User::where('id', $income_income->user_id)->first();
                $user->direct += $income_income->amount;
                $user->save();
            } elseif ($income_income->income_type == 'MATCHING INCOME') {
                $user = User::where('id', $income_income->user_id)->first();
                $user->matching += $income_income->amount;
                $user->save();
            } elseif ($income_income->income_type == 'DIRECT TEAM MATCHING INCOME') {
                $user = User::where('id', $income_income->user_id)->first();
                $user->direct_team_matching += $income_income->amount;
                $user->save();
            }
        }
    }
}
