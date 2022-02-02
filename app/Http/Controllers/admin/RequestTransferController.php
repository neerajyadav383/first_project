<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestTransferController extends Controller
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

    public function addRequestTransfer(Request $request)
    {
        $user = Auth::user();

        //$week = date('w');
        //if($week==0){
            //session()->flash('alert-class', 'text-danger');
            //session()->flash('message', 'Withdrawal only Monday to Saturday');
            //return redirect('bene_report');
        //}
        $hour = date('G');
        if ($hour < 8 || $hour > 20) {
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', 'Withdrawal only 8:00 AM to 8:00 PM');
            return redirect('bene_report');
        }

        if($request->amount>$user->wallet){
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', 'Only '.$user->wallet.' available in your wallet');
            return redirect('bene_report');
        } elseif($request->amount>10000){
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', 'Maximum withdrawal limit is 10000');
            return redirect('bene_report');
        } elseif($request->amount<100){
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', 'Minimum withdrawal limit is 100');
            return redirect('bene_report');
        } else {
            $checkTodayWithdrawal = PayoutDetail::where('user_id', $user->id)->where('created_at', 'like', date('Y-m-d'))->get();
            if(count($checkTodayWithdrawal)>1){
                session()->flash('alert-class', 'text-danger');
                session()->flash('message', 'You can withdraw only 2 times in a day');
                return redirect('bene_report');
            }
        }
        
        $this->beneId = $request->bene_id;
        $this->amount = $request->amount;
        $this->transferId = $request->transferId;

        $this->amount = $this->amount - 10;

        $this->urls = array(
            'auth' => '/payout/v1/authorize',
            'addBene' => '/payout/v1/addBeneficiary',
            'requestTransfer' => '/rest/spvaig/RequestTransfer?Usermobile=' . $this->Usermobile . '&authkey=' . $this->authkey . '&env=' . $this->env . '&beneId=' . $this->beneId . '&amount=' . $this->amount . '&transferId=' . $this->transferId,
            'getTransferStatus' => '/rest/spvaig/RequestTransferStatus?Usermobile=' . $this->Usermobile . '&authkey=' . $this->authkey . '&env=' . $this->env . '&referenceId='
        );

        $res = $this->requestTransfer();
        $res22 = $res['main_data'];
        $res223 = $res['msg'];
        if (count($res22) > 3) {
            $res33 = $res22['data'];
            $utr = $res33['utr'];
            $referenceID = $res33['referenceId'];
            $ress = $this->getTransferStatus($referenceID);
            $ress1 = $ress['main_data'];
            $res2 = $ress1['data'];
            $res3 = $res2['transfer'];

            $referenceId = $referenceID;
            $bankAccount = $res3['bankAccount'];
            $ifsc = $res3['ifsc'];
            $beneId = $res3['beneId'];
            $amount = $res3['amount'];
            $amount = $amount + 10;
            $status = $res3['status'];
            $utr = $utr;
            $addedOn = $res3['addedOn'];
            $processedOn = $res3['processedOn'];
            $transferMode = $res3['transferMode'];
            $acknowledged = $res3['acknowledged'];

            $user->wallet -= $amount;
            $user->updated_at = date('Y-m-d H:i:s');
            $user->save();

            $payout_details = new PayoutDetail;
            $payout_details->user_id = $user->id;
            $payout_details->referenceId = $referenceId;
            $payout_details->bankAccount = $bankAccount;
            $payout_details->ifsc = $ifsc;
            $payout_details->beneId = $beneId;
            $payout_details->amount = $amount;
            $payout_details->status = $status;
            $payout_details->utr = $utr;
            $payout_details->addedOn = $addedOn;
            $payout_details->processedOn = $processedOn;
            $payout_details->transferMode = $transferMode;
            $payout_details->acknowledged = $acknowledged;
            $payout_details->created_at = date('Y-m-d H:i:s');
            $payout_details->updated_at = date('Y-m-d H:i:s');
            $payout_details->save();

            $isaray = is_array($res22);
            if ($isaray == '1') {
                session()->flash('alert-class', 'text-success');
                session()->flash('message', $res22['message']);
                return redirect('bene_report');
            } else {
                session()->flash('alert-class', 'text-success');
                session()->flash('message', $res22);
                return redirect('bene_report');
            }
        } else {
            if ($res223 == "success") {
                $res223 = $res22['message'];
                if($res223 == "Insufficient admin wallet amount"){
                    $res223 = "Technical issue, Please try after sometime.";
                }
                session()->flash('alert-class', 'text-danger');
                session()->flash('message', $res223);
                return redirect('bene_report');
            } else {
                if($res223 == "Insufficient admin wallet amount"){
                    $res223 = "Technical issue, Please try after sometime.";
                }
                session()->flash('alert-class', 'text-danger');
                session()->flash('message', $res223);
                return redirect('bene_report');
            }
        }
    }

    public function post_helper($action)
    {
        $finalUrl = $this->baseUrls . $this->urls[$action];
        $finalUrl = str_replace(" ", '%20', $finalUrl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //    if (!is_null($data))
        //        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $r = curl_exec($ch);
        if (curl_errno($ch)) {
            print('error in posting');
            print(curl_error($ch));
            die();
        }
        curl_close($ch);
        return $r;
    }

    public function get_helper($finalUrl)
    {
        $finalUrl = str_replace(" ", '%20', $finalUrl);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        //    curl_setopt($ch, CURLOPT_HEADER, true);
        //    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $r = curl_exec($ch);

        if (curl_errno($ch)) {
            print('error in posting');
            print(curl_error($ch));
            die();
        }
        curl_close($ch);

        //    $rObj = json_decode($r, true);
        //    if ($rObj['status'] != 'SUCCESS' || $rObj['subCode'] != '200')
        //        throw new Exception($rObj['message']);
        return $r;
    }

    public function requestTransfer()
    {
        try {
            $response = $this->post_helper('requestTransfer');
            $response = json_decode($response, TRUE);
            return $response;
            error_log('transfer requested successfully');
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            return $msg;
            error_log('error in requesting transfer');
            error_log($msg);
            die();
        }
    }

    public function getTransferStatus($referenceID)
    {
        try {
            $finalUrl = $this->baseUrls . $this->urls['getTransferStatus'] . $referenceID . '&transferId=' . $this->transferId;
            $response = $this->get_helper($finalUrl);
            $response = json_decode($response, TRUE);
            return $response;
            error_log(json_encode($response));
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            error_log('error in getting transfer status');
            error_log($msg);
            die();
        }
    }
}
