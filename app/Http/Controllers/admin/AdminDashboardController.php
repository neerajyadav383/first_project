<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Downline;
use App\Models\IncomeReport;
use App\Models\Offer;
use App\Models\PayoutDetail;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->Usermobile = env('usermobile');
        $this->authkey = env('authkey');
        $this->env = env('env');

        #config objs
        $this->baseUrls = 'https://spvaig.co.in';

        $this->header = array(
            'Usermobile: ' . $this->Usermobile,
            'authkey: ' . $this->authkey,
        );
        $this->urls = '';
        $this->beneId = '';
        $this->amount = '';
        $this->transferId = '';
    }

    public function dashboard()
    {
        $offer = Offer::where('status', 'ON')->orderBy('id', 'DESC')->first();
        if($offer==null){
            $offerstatus = 0;
        } else {
            $offerstatus = 1;
        }

        $user  = Auth::user();
        $left_downline = User::where('placement_id', $user->id)
            ->where('placement', 'Left')
            ->first();

        if ($left_downline == null) {
            $leftB = 0;
            $leftT = 0;
        } else {
            $downlines = User::where('id', $left_downline->id)->first()->downline;
            $leftT = count($downlines) + 1;
            $leftB = $downlines->sum('join_amt') + $left_downline->join_amt;
        }
        $right_downline = User::where('placement_id', $user->id)
            ->where('placement', 'Right')
            ->first();

        if ($right_downline == null) {
            $rightB = 0;
            $rightT = 0;
        } else {
            $downlines = User::where('id', $right_downline->id)->first()->downline;
            $rightT = count($downlines) + 1;
            $rightB = $downlines->sum('join_amt') + $right_downline->join_amt;
        }

        $virtual_powers = IncomeReport::where('user_id',$user->id)->where('income_type', 'VIRTUAL POWER')->get();
        foreach ($virtual_powers as $key => $virtual_power) {
            $left = $virtual_power->level - $virtual_power->level3;
            $right = $virtual_power->level2 - $virtual_power->level4;
            if($left>0){
                $leftB      += $left;
            }
            if($right>0){
                $rightB     += $right;
            }
        }
      
        $business = array(
            'leftT' => $leftT,
            'leftB' => $leftB,
            'rightT' => $rightT,
            'rightB' => $rightB,
        );

        $this->urls = array(
            'getBalance' => '/rest/spvaig/GetBal?Usermobile=' . $this->Usermobile . '&authkey=' . $this->authkey . '&env=' . $this->env
        );

        $payoutApiAmount = '';
        $res = $this->getBalance();
        // print_r($res);
        // die();
        $isaray = is_array($res);
        if ($isaray == '1') {
            $res22 = $res['main_data'];
            if ($res['msg'] == 'success') {
                //echo $res['message'];
                //        $res2 = $res22['data'];
                $payoutApiAmount = $res22['availableBalance'];
            } else {
                $payoutApiAmount = $res22['message'];
            }
        } else {
            if (isset($res['message'])) {
                $payoutApiAmount = $res['message'];
            }
        }

        // echo '<pre>';
        // print_r($payoutDetails);
        // echo '</pre>';
        // die();

        if ($user->hasRole('admin')) {
            $allUser = User::all();
            $activeUser = User::where('status', '1')->get();
            $directActiveUser = User::all()->where('status', '1')->where('sponsor_id', $user->id);
            $downlineDetails = array(
                'allUser' => count($allUser),
                'activeUser' => count($activeUser),
                'inactiveUser' => (count($allUser) - count($activeUser)),
                'directActiveUser' => count($directActiveUser),
            );
            $dashboard = DB::select("select sum(roi) as roi, sum(booster) as booster, sum(direct) as direct, sum(matching) as matching, sum(direct_team_matching) as direct_team_matching, sum(reward) as reward, (sum(roi)+sum(booster)+sum(direct)+sum(matching)+sum(direct_team_matching)+sum(reward)) as total_income, sum(wallet) as wallet, sum(topup_wallet) as topup_wallet from users");
            $dashboard2 = DB::select("select sum(roi) as roi, sum(booster) as booster, sum(direct) as direct, sum(matching) as matching, sum(direct_team_matching) as direct_team_matching, sum(reward) as reward, sum(total_amount) as total_income from closing_statements");
            foreach ($dashboard as $key => $dashboard) {
                # code...
            }
            foreach ($dashboard2 as $key => $dashboard2) {
                # code...
            }
            $withdrwalAmount = PayoutDetail::where('status', '!=', 'FAILED')->sum('amount');
            // echo '<pre>'; print_r($dashboard); echo '</pre>'; die();
        } else {
            $allUser = $user->downline()->get();

            $activeUser = 0;
            foreach ($allUser as $key => $downlineUser) {
                $getUser = $downlineUser->users()->where('status', '1')->first();
                if ($getUser != null) {
                    $activeUser++;
                }
            }

            $directActiveUser = User::all()->where('status', '1')->where('sponsor_id', $user->id);
            $downlineDetails = array(
                'allUser' => count($allUser),
                'activeUser' => $activeUser,
                'inactiveUser' => (count($allUser) - $activeUser),
                'directActiveUser' => count($directActiveUser),
            );
            $dashboard = $user;
            $dashboard2 = DB::select("select sum(roi) as roi, sum(booster) as booster, sum(direct) as direct, sum(matching) as matching, sum(direct_team_matching) as direct_team_matching, sum(reward) as reward, sum(total_amount) as total_income from closing_statements where user_id='$user->id'");
            foreach ($dashboard2 as $key => $dashboard2) {
                # code...
            }
            $withdrwalAmount = PayoutDetail::where('user_id', $user->id)->where('status', '!=', 'FAILED')->sum('amount');
            // echo '<pre>'; print_r($dashboard2); echo '</pre>'; die();
        }
        // echo '<pre>'; print_r($dashboard2); echo '</pre>'; die();
        return view('admin.dashboard', ['status' => $user->status, 'offer' => $offer, 'offerstatus' => $offerstatus, 'payoutApiAmount' => $payoutApiAmount, 'withdrwalAmount' => $withdrwalAmount, 'dashboard' => $dashboard, 'dashboard2' => $dashboard2, 'downlineDetails' => $downlineDetails, 'business' => $business]);
    }

    function getBalance()
    {
        try {
            $finalUrl = $this->baseUrls . $this->urls['getBalance'];
            $response = $this->get_helper($finalUrl);
            $response = json_decode($response, TRUE);
            //print_r($response);
            return $response;
            error_log(json_encode($response));
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            return $msg;
            error_log('error in getting transfer status');
            error_log($msg);
            die();
        }
    }


    function get_helper($finalUrl)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        //    curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $r = curl_exec($ch);

        if (curl_errno($ch)) {
            print('error in posting');
            print(curl_errno($ch) . ' , ' . curl_error($ch));
            die();
        }
        curl_close($ch);

        //    $rObj = json_decode($r, true);
        //    if ($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200')
        //        throw new Exception($rObj['message']);
        return $r;
    }

    public function checkUserForBooster()
    {
        # code...
    }
}
