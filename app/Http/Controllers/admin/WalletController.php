<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WalletRequest;
use App\Models\User;
use App\Models\IncomeReport;
use App\Models\Investment;
use App\Models\RoiIncome;
use Illuminate\Support\Facades\Auth;
use Validator;

class WalletController extends Controller
{
    public function walletRequest()
    {
        $user  = Auth::user();
        if ($user->hasRole('admin')) {
            $walletRequestReport = WalletRequest::with(array('wrUsers' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        } else {
            $walletRequestReport = $user->walletRequest()->with(array('wrUsers' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        }
        // echo '<pre>';
        // print_r($renewalYourId);
        // echo '</pre>';
        // die();
        return view('admin.walletRequest', ['walletRequestReport' => $walletRequestReport]);
    }

    public function addWalletReq(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();

        $validator = Validator::make($request->all(), [
            'amount'            => 'required|numeric|min:10|max:900000',
            'transaction_id'    => 'required|unique:wallet_requests,trans_id',
        ]);
        if ($validator->fails()) {
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', $validator->errors()->first());
            return redirect('wallet_request');
        }

        $user = Auth::user();
        $photo  = uploadFile($request->screenshot, $user->userid);
        $walletRequest = new WalletRequest;
        $walletRequest->user_id = $user->id;
        $walletRequest->amount = $request->amount;
        $walletRequest->trans_id = $request->transaction_id;
        $walletRequest->screenshot = $photo;
        $walletRequest->created_at = date('Y-m-d H:i:s');
        $walletRequest->updated_at = date('Y-m-d H:i:s');
        $walletRequest->save();

        session()->flash('alert-class', 'text-success');
        session()->flash('message', "Added successfully.");


        return redirect('wallet_request');
    }

    public function approveWalletReq(Request $request)
    {
        // echo '<pre>';print_r($request->all());echo '</pre>';die();
        $check = WalletRequest::where('id', $request->id)->where('status', 'Pending')->first();
        if ($check != null) {
            $walletRequest = WalletRequest::where('id', $request->id)->first();
            $walletRequest->status = 'Approved';
            $walletRequest->updated_at = date('Y-m-d H:i:s');
            $walletRequest->save();

            $user_id = $walletRequest->user_id;
            $request_amt = $walletRequest->amount;
            $user = User::where('id', $user_id)->first();
            $user->topup_wallet += $request_amt;
            $user->updated_at = date('Y-m-d H:i:s');
            $user->save();

            $data = array(
                'user_id'    => $user_id,
                'income_type' => 'WALLET UPDATE',
                'amount' => $request_amt,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            IncomeReport::create($data);

            return 'Approved successfully.';
        } else {
            return 'Something is wrong, please refresh your page before approving.';
        }
    }

    public function rejectWalletReq(Request $request)
    {
        $data = array(
            'status' => 'Rejected',
            'reason' => $request->reason,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        WalletRequest::where('id', $request->id)->update($data);

        return 'Rejected successfully.';
    }


    //investment*****************************************************************
    //investment*****************************************************************

    public function investment()
    {
        $user  = Auth::user();
        if ($user->hasRole('admin')) {
            $walletRequestReport = Investment::with(array('user_details' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        } else {
            $walletRequestReport = $user->investment()->with(array('user_details' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        }
        // echo '<pre>';
        // print_r($walletRequestReport->toArray());
        // echo '</pre>';
        // die();
        return view('admin.investment', ['walletRequestReport' => $walletRequestReport]);
    }

    public function add_investment(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();

        $validator = Validator::make($request->all(), [
            'amount'            => 'required|numeric|min:10|max:900000',
            'transaction_id'    => 'required|unique:wallet_requests,trans_id',
        ]);
        if ($validator->fails()) {
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', $validator->errors()->first());
            return redirect('wallet_request');
        }

        $user = Auth::user();
        $photo  = uploadFile($request->screenshot, $user->userid);
        $walletRequest = new Investment;
        $walletRequest->user_id = $user->id;
        $walletRequest->amount = $request->amount;
        $walletRequest->trans_id = $request->transaction_id;
        $walletRequest->screenshot = $photo;
        $walletRequest->created_at = date('Y-m-d H:i:s');
        $walletRequest->updated_at = date('Y-m-d H:i:s');
        $walletRequest->save();

        session()->flash('alert-class', 'text-success');
        session()->flash('message', "Added successfully.");


        return redirect('investment');
    }

    public function approve_investment(Request $request)
    {
        // echo '<pre>';print_r($request->all());echo '</pre>';die();
        $check = Investment::where('id', $request->id)->where('status', 'Pending')->first();
        if ($check != null) {
            $walletRequest              = Investment::where('id', $request->id)->first();
            $walletRequest->status      = 'Approved';
            $walletRequest->updated_at  = date('Y-m-d H:i:s');
            $walletRequest->save();

            $user_id            = $walletRequest->user_id;
            $request_amt        = $walletRequest->amount;
            // $user               = User::where('id', $user_id)->first();
            // $user->topup_wallet += $request_amt;
            // $user->updated_at   = date('Y-m-d H:i:s');
            // $user->save();

            $date               = date('Y-m-d');
            $start_date         = date('Y-m-d', strtotime($date . ' +1 day'));
            $pay_date         = date('Y-m-d', strtotime($start_date . ' +1 month'));
            $direct_amount      = ($request_amt * 1) / 100;
            if ($request_amt < 100000) {
                $end_date           = date('Y-m-d', strtotime($date . ' +385 days'));
                $amount             = ($request_amt * 5) / 100;
            } else {
                $end_date           = date('Y-m-d', strtotime($date . ' +36 months'));
                $end_date           = date('Y-m-d', strtotime($end_date . ' +20 days'));
                $amount             = 5000;
            }

            $amount             = ($request_amt * 5) / 100;
            $data               = array(
                'user_id'       => $user_id,
                'income_type'   => 'ROYALTY INCOME',
                'amount'        => $amount,
                'start_date'    => $start_date,
                'end_date'      => $end_date,
                'pay_date'      => $pay_date,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            );
            RoiIncome::create($data);

            $user       = User::where('id', $user_id)->first();
            $sponsor_id = $user->sponsor_id;

            $userDI     = User::where('id', $sponsor_id)->first();
            if ($userDI != null) {
                $data               = array(
                    'user_id'       => $userDI->id,
                    'income_type'   => 'REFERAL ROYALTY INCOME',
                    'amount'        => $direct_amount,
                    'start_date'    => $start_date,
                    'end_date'      => $end_date,
                    'pay_date'      => $pay_date,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                );
                RoiIncome::create($data);
            }

            $data = array(
                'user_id'       => $user_id,
                'income_type'   => 'INVESTMENT',
                'amount'        => $request_amt,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            );
            IncomeReport::create($data);

            return 'Approved successfully.';
        } else {
            return 'Something is wrong, please refresh your page before approving.';
        }
    }

    public function reject_investment(Request $request)
    {
        $data = array(
            'status' => 'Rejected',
            'reason' => $request->reason,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        Investment::where('id', $request->id)->update($data);

        return 'Rejected successfully.';
    }
}
