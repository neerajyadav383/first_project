<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\IncomeReport;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\State;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function userRegistration()
    {
        return view('admin.user_registration');
    }

    public function listUser()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $listUser = User::with(array('sponsorDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->with(array('placementDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();

            //SELECT t1.*,t2.userid AS spid,t2.name AS spname,t3.userid AS plid,t3.name AS plname FROM `users` AS t1, users AS t2, users AS t3 WHERE t1.`sponsor_id`=`t2`.`id` AND t1.`placement_id`=t3.id
        } else {
            return redirect("/dashboard");
        }

        return view('admin.users', ['listUser' => $listUser]);
    }

    public function wallet_update()
    {
        $user = Auth::user();
        if (isset($_GET['id']) && $_GET['id'] && $user->hasRole('admin')) {
            $user = User::where('id', $_GET['id'])->first();
            // echo '<pre>';
            // print_r($matching_income->toArray());
            // echo '</pre>';
            // die();
            return view('admin.wallet_update', ['user' => $user]);
        }
        return redirect('dashboard');
    }

    public function post_wallet_update(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $user = User::where('userid', $request->user_id)->first();
            $user->wallet = $request->wallet;
            $user->save();
            
            $data = array(
                'user_id'           => $user->id,
                'income_type'       => 'WALLET UPDATE BY ADMIN',
                'amount'            => ($request->wallet - $request->wallet_old),
                'level'             => $request->wallet_old,
                'level2'            => $request->wallet,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            );
            IncomeReport::create($data);

            return redirect('/users');
        }
        return redirect('dashboard');
    }

    public function inactiveUser()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $inactiveUser = User::where('status', '0')
                ->with(array('sponsorDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))
                ->with(array('placementDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        } else {
            return redirect("/dashboard");
        }

        // echo '<pre>';
        // print_r($inactiveUser->toArray());
        // echo '</pre>';
        // die();
        return view('admin.inactiveUsers', ['inactiveUser' => $inactiveUser]);
    }

    public function wallet_lock(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->wallet_lock = $request->wallet_status;
        $user->updated_at  = date('Y-m-d H:i:s');
        $user->save();
        return $request->wallet_status;
    }
}
