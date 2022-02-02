<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BeneficiaryDetail;
use App\Models\PayoutDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
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
        $this->bankAccount = '';
        $this->ifsc = '';
    }

    public function beneficiary()
    {
        $Auser = Auth::user();
        $user = User::where('id', $Auser->id)->with('state', 'city', 'bank_details', 'bank_details.banks')->first();
        // $user  = Auth::user();

        if ($user->state == null || $user->city == null || $user->name == '' || $user->email == '' || $user->mobile == '' || $user->pincode == '' || $user->address == '') {
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', "Update your profile first for withdrawal.");

            return redirect('edit_profile');
        }

        if ($user->hasRole('admin')) {
            $beneficiaryDetail = BeneficiaryDetail::with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        } else {
            $beneficiaryDetail = $user->beneficiaryDetails()->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        }
        // echo '<pre>';
        // print_r($user->toArray());
        // echo '</pre>';
        // die();
        return view('admin.beneficiary', ['beneficiaryDetail' => $beneficiaryDetail, 'user' => $user]);
    }

    public function withdrawalReport()
    {
        $user = Auth::user()->with('state', 'city', 'bank_details', 'bank_details.banks')->first();
        $user  = Auth::user();

        if ($user->hasRole('admin')) {
            $payoutDetail = PayoutDetail::with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        } else {
            $payoutDetail = $user->payoutDetails()->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        }
        // echo '<pre>';
        // print_r($user->toArray());
        // echo '</pre>';
        // die();
        return view('admin.withdrawalReport', ['payoutDetail' => $payoutDetail]);
    }

    public function addBeneId(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        $user = Auth::user();
        $user = User::where('id', $user->id)->with('state', 'city', 'bank_details', 'bank_details.banks')->first();
        // echo '<pre>';
        // print_r($user->toArray());
        // echo '</pre>';
        // die();

        $this->bankAccount = $request->bankAccount;
        $this->ifsc = $request->ifsc;
        $address = urlencode($user->address);
        $a1 = $user->name;
        $a2 = $request->bankAccount;
        $a2max = strlen($a2);
        $a2min = $a2max - 4;
        $a3 = strtoupper(substr($a1, 0, 2)) . substr($a2, $a2min, 4) . mt_rand(100, 9999);
        $a4 = str_replace(' ', '', $a3);
        $a4 = urlencode($a4);
        if (strlen($a4) >= 6) {
            $this->beneId = $a4;
        }

        $this->urls = array(
            'addBene' => '/rest/spvaig/AddBeneficiary?Usermobile=' . $this->Usermobile . '&authkey=' . $this->authkey . '&env=' . $this->env . '&beneId=' . $this->beneId . '&name=' . $user->name . '&email=' . $user->email . '&phone=' . $user->mobile . '&bankAccount=' . $request->bankAccount . '&ifsc=' . $request->ifsc . '&address1=' . $address . '&city=' . $user->city->name . '&state=' . $user->state->name . '&pincode=' . $user->pincode,
            'getBene' => '/rest/spvaig/GetBeneficiary?Usermobile=' . $this->Usermobile . '&authkey=' . $this->authkey . '&env=' . $this->env . '&beneId=',
            'getBeneId' => '/rest/spvaig/GetBeneficiaryId?Usermobile=' . $this->Usermobile . '&authkey=' . $this->authkey . '&env=' . $this->env . '&bankAccount=' . $request->bankAccount . '&ifsc=' . $request->ifsc,
        );

        $res22 = $this->addBeneficiary();
        // echo '<pre>';
        // print_r($res22);
        // echo '</pre>';
        // die();
        $isaray = is_array($res22);

        if ($isaray == '1') {
            if(count($res22)==1){
                session()->flash('alert-class', 'text-danger');
                session()->flash('message', 'Server Issue!');
                return redirect('bene_report');
            }
            $res = $res22['main_data'];
            if ($res['message'] == 'Beneficiary added successfully') {
                $address1 = urldecode($address);
                $beneficiary_details = new BeneficiaryDetail;
                $beneficiary_details->user_id = $user->id;
                $beneficiary_details->beneId = $this->beneId;
                $beneficiary_details->name = $user->name;
                $beneficiary_details->email = $user->email;
                $beneficiary_details->phone = $user->mobile;
                $beneficiary_details->bankAccount = $request->bankAccount;
                $beneficiary_details->ifsc = $request->ifsc;
                $beneficiary_details->address1 = $address;
                $beneficiary_details->city = $user->city->name;
                $beneficiary_details->state = $user->state->name;
                $beneficiary_details->pincode = $user->pincode;
                $beneficiary_details->date = date('Y-m-d');
                $beneficiary_details->time = date('H:i:s');
                $beneficiary_details->created_at = date('Y-m-d H:i:s');
                $beneficiary_details->updated_at = date('Y-m-d H:i:s');
                $beneficiary_details->save();

                session()->flash('alert-class', 'text-success');
                session()->flash('message', $res['message']);
                return redirect('bene_report');
            } elseif ($res['message'] == "Entered bank Account is already registered") {
                $resBId22 = $this->getBeneficiaryId();
                $resBID33 = $resBId22['main_data'];
                if ($resBID33['message'] == 'beneId retrieved successfully') {
                    $resBID44 = $resBID33['data'];
                    $beneId = $resBID44['beneId'];
                    $result = $this->getBeneficiary($beneId);
                    $result22 = $result['main_data'];
                    if ($result22['message'] == "Details of beneficiary") {
                        $result2 = $result22['data'];
                        $beneId = $result2['beneId'];
                        $name = $result2['name'];
                        $email = $result2['email'];
                        $phone = $result2['phone'];
                        $address1 = urldecode($result2['address1']);
                        $city = $result2['city'];
                        $state = $result2['state'];
                        $pincode = $result2['pincode'];
                        $bankAccount = $result2['bankAccount'];
                        $ifsc = $result2['ifsc'];

                        $beneficiary_details_exist = BeneficiaryDetail::where('user_id', $user->id)->where('bankAccount', $bankAccount)->where('ifsc', $ifsc)->first();
                        
                        if ($beneficiary_details_exist == null) {
                            $beneficiary_details = new BeneficiaryDetail;
                            $beneficiary_details->user_id = $user->id;
                            $beneficiary_details->beneId = $beneId;
                            $beneficiary_details->name = $name;
                            $beneficiary_details->email = $email;
                            $beneficiary_details->phone = $phone;
                            $beneficiary_details->bankAccount = $bankAccount;
                            $beneficiary_details->ifsc = $ifsc;
                            $beneficiary_details->address1 = $address1;
                            $beneficiary_details->city = $city;
                            $beneficiary_details->state = $state;
                            $beneficiary_details->pincode = $pincode;
                            $beneficiary_details->date = date('Y-m-d');
                            $beneficiary_details->time = date('H:i:s');
                            $beneficiary_details->created_at = date('Y-m-d H:i:s');
                            $beneficiary_details->updated_at = date('Y-m-d H:i:s');
                            $beneficiary_details->save();

                            session()->flash('alert-class', 'text-success');
                            session()->flash('message', 'Beneficiary details inserted successfully.');
                            return redirect('bene_report');
                        } else {
                            session()->flash('alert-class', 'text-success');
                            session()->flash('message', 'Already inserted.');
                            return redirect('bene_report');
                        }
                    } else {
                        session()->flash('alert-class', 'text-success');
                        session()->flash('message', $result['message']);
                        return redirect('bene_report');
                    }
                } else {
                    session()->flash('alert-class', 'text-success');
                    session()->flash('message', $resBID33['message']);
                    return redirect('bene_report');
                }
            } else {
                session()->flash('alert-class', 'text-success');
                session()->flash('message', $res['message']);
                return redirect('bene_report');
            }
        } else {
            session()->flash('alert-class', 'text-success');
            session()->flash('message', $res22);
            return redirect('bene_report');
        }
    }

    public function addBeneficiary()
    {
        try {
            $response = $this->post_helper('addBene');
            $response = json_decode($response, TRUE);
            return $response;
            error_log('beneficiary created');
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            return $msg;
            error_log('error in creating beneficiary');
            error_log($msg);
            die();
        }
    }

    public function getBeneficiaryId()
    {
        try {
            $finalUrl = $this->baseUrls . $this->urls['getBeneId'];
            $response = $this->get_helper($finalUrl);
            $response = json_decode($response, TRUE);
            //        print_r($response);
            return $response;
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            return $msg;
            die();
        }
    }

    public function getBeneficiary($beneId)
    {
        try {
            $finalUrl = $this->baseUrls . $this->urls['getBene'] . $beneId;
            $response = $this->get_helper($finalUrl);
            $response = json_decode($response, TRUE);
            return $response;
        } catch (Exception $ex) {
            $msg = $ex->getMessage();
            return $msg;
            if (strstr($msg, 'Beneficiary does not exist'))
                return false;
            error_log('error in getting beneficiary details');
            error_log($msg);
            die();
        }
    }

    public function post_helper($action)
    {
        $finalUrl = $this->baseUrls . $this->urls[$action];
        $finalUrl = str_replace(" ", '%20', $finalUrl);
        //echo $finalUrl.'<br><br';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        //    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //    if (!is_null($data))
        //        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $r = curl_exec($ch);
        //die($r);
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
  
  
  public function manual_payment()
    {
        $authuser = Auth::user();
        if ($authuser->hasRole('admin')) {
            $user = User::where('wallet', '>', '0')->get();
            return view('admin.manual_payment', ['reports' => $user]);
        } else {
            return redirect('dashboard');
        }
    }

    public function post_manual_payment(Request $request)
    {

        $amount     = $request->amount;
        if ($amount != "" && $amount > 0) {
            $user       = User::where('id', $request->m_id)->first();
            if ($user != null) {
                $wallet = $user->wallet;
                if ($wallet >= $amount) {
                    $user->wallet   -= $amount;
                    $user->save();

                    $payout_details = new PayoutDetail;
                    $payout_details->user_id        = $user->id;
                    $payout_details->referenceId    = 'MANUAL';
                    $payout_details->bankAccount    = 'MANUAL';
                    $payout_details->ifsc           = 'MANUAL';
                    $payout_details->beneId         = 'MANUAL';
                    $payout_details->amount         = $amount;
                    $payout_details->status         = 'SUCCESS';
                    $payout_details->utr            = 'MANUAL';
                    $payout_details->addedOn        = date('Y-m-d H:i:s');
                    $payout_details->processedOn    = date('Y-m-d H:i:s');
                    $payout_details->transferMode   = 'MANUAL';
                    $payout_details->acknowledged   = 'MANUAL';
                    $payout_details->created_at     = date('Y-m-d H:i:s');
                    $payout_details->updated_at     = date('Y-m-d H:i:s');
                    $payout_details->save();

                    session()->flash('alert-class', 'text-success');
                    session()->flash('message', 'Amount transfer successful');
                    return redirect('manual_payment');
                } else {
                    session()->flash('alert-class', 'text-danger');
                    session()->flash('message', 'The amount should not exceed the wallet amount');
                    return redirect('manual_payment');
                }
            }
        }
        session()->flash('alert-class', 'text-danger');
        session()->flash('message', 'Something wrong please try again');
        return redirect('manual_payment');
    }
}
