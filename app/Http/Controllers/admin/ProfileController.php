<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\City;
use App\Models\ClosingStatement;
use App\Models\Downline;
use App\Models\State;
use App\Models\RenewalReport;
use App\Models\RoiIncome;
use App\Models\IncomeReport;
use App\Models\MatchingIncome;

use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function profile()
    {
        $authUser = Auth::user();
        $user = User::where('id', $authUser->id)->with('state', 'city', 'bank_details', 'bank_details.banks')->first();
        // $user->bank_details->banks;
        // echo '<pre>';
        // print_r($user);
        // echo '</pre>';
        // die();

        $profileComplete = 0;
        if ($user->name) {
            $profileComplete += 5;
        }
        if ($user->email) {
            $profileComplete += 5;
        }
        if ($user->mobile) {
            $profileComplete += 5;
        }
        if ($user->state_id) {
            $profileComplete += 10;
        }
        if ($user->bank_id) {
            $profileComplete += 50;
        }
        if ($user->photo != 'assets/img/user.png') {
            $profileComplete += 25;
        }

        return view('admin.profile', ['user' => $user, 'profileComplete' => $profileComplete]);
    }

    public function editProfile()
    {
        $authUser = Auth::user();
        $user = User::where('id', $authUser->id)->with('state', 'city', 'bank_details', 'bank_details.banks')->first();
        if (isset($_GET['id']) && $_GET['id'] && $user->hasRole('admin')) {
            // echo "Hi"; die();
            $user = User::where('id', $_GET['id'])->with('state', 'city', 'bank_details', 'bank_details.banks')->first();
        }
        $states = State::all();

        $city = $user->city_id;
        $cities = [];
        if ($city != null) {
            $cities = City::where('id', $city)->first()->toArray();
        }

        $banks = Bank::all();
        // echo '<pre>';
        // print_r($user);
        // echo '</pre>';
        // die();

        return view('admin.editProfile', ['user' => $user, 'states' => $states, 'cities' => $cities, 'banks' => $banks]);
    }

    public function updateProfile(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        $validator = Validator::make($request->all(), [
            'state_id' => 'required',
            'city_id' => 'required',
            'pincode' => 'required',
            'address' => 'required',
        ]);
        if ($validator->fails()) {
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', $validator->errors()->first());

            return redirect('profile');
        }

        $user = Auth::user()->bank_details()->first();
        if ($user == null) {
            $data = array(
                'holder_name' => $request->holder_name,
                'bank_id' => $request->bank_id,
                'branch' => $request->branch,
                'ifsc' => $request->ifsc,
                'account_no' => $request->account_no,
                'account_type' => $request->account_type,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $bankDetail = BankDetail::create($data);
            $request->bank_details_id = $bankDetail->id;
        } else {
            $data = array(
                'holder_name' => $request->holder_name,
                'bank_id' => $request->bank_id,
                'branch' => $request->branch,
                'ifsc' => $request->ifsc,
                'account_no' => $request->account_no,
                'account_type' => $request->account_type,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            BankDetail::where('id', $request->bank_details_id)->update($data);
        }

        $user = Auth::user();

        if ($request->file('photo') != null) {
            $photo  = uploadFile($request->photo, $user->userid);
            $data = array(
                'photo' => $photo,
            );
            User::where('id', $user->id)->update($data);
        }
        if ($request->file('pan_file') != null) {
            $photo  = uploadFile($request->pan_file, $user->userid);
            $data = array(
                'pan_file' => $photo,
            );
            User::where('id', $user->id)->update($data);
        }
        if ($request->file('aadhar_file') != null) {
            $photo  = uploadFile($request->aadhar_file, $user->userid);
            $data = array(
                'aadhar_file' => $photo,
            );
            User::where('id', $user->id)->update($data);
        }

        $data = array(
            'state_id'          => $request->state_id,
            'city_id'           => $request->city_id,
            'pincode'           => $request->pincode,
            'address'           => $request->address,
            'bank_id'           => $request->bank_details_id,
            'pan'               => $request->pan,
            'aadhar'            => $request->aadhar,
            'updated_at'        => date('Y-m-d H:i:s'),
        );
        User::where('id', $user->id)->update($data);

        session()->flash('alert-class', 'text-success');
        session()->flash('message', "Updated successfully.");

        return redirect('profile');
    }

    public function updateUserProfile(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'mobile' => 'required',
        //     'email' => 'required',
        //     'pincode' => 'required',
        //     'address' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     session()->flash('alert-class', 'text-success');
        //     session()->flash('message', $validator->errors()->first());

        //     return redirect('users');
        // }

        $user = User::where('id', $request->old_id)->with('bank_details')->first();
        // echo '<pre>';
        // print_r($user->toArray());
        // echo '</pre>';
        // die();
        if ($user->bank_details == null) {
            $request->bank_details_id = NULL;
            if ($request->bank_id != '') {
                $data = array(
                    'holder_name' => $request->holder_name,
                    'bank_id' => $request->bank_id,
                    'branch' => $request->branch,
                    'ifsc' => $request->ifsc,
                    'account_no' => $request->account_no,
                    'account_type' => $request->account_type,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $bankDetail = BankDetail::create($data);
                $request->bank_details_id = $bankDetail->id;
            }
        } else {
            $data = array(
                'holder_name' => $request->holder_name,
                'bank_id' => $request->bank_id,
                'branch' => $request->branch,
                'ifsc' => $request->ifsc,
                'account_no' => $request->account_no,
                'account_type' => $request->account_type,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            BankDetail::where('id', $request->bank_details_id)->update($data);
        }

        $user = User::where('id', $request->old_id)->first();

        if ($request->file('photo') != null) {
            $photo  = uploadFile($request->photo, $user->userid);
            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'pincode' => $request->pincode,
                'address' => $request->address,
                'photo' => $photo,
                'bank_id' => $request->bank_details_id,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            User::where('id', $user->id)->update($data);
        } else {
            $data = array(
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'pincode' => $request->pincode,
                'address' => $request->address,
                'bank_id' => $request->bank_details_id,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            User::where('id', $user->id)->update($data);
        }

        session()->flash('alert-class', 'text-success');
        session()->flash('message', "Updated successfully.");

        return redirect('users');
    }

    public function getCities(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->get();
        return response()->json($cities);
    }

    public function changePassword()
    {
        $user = Auth::user();
        $success = 0;
        if (isset($_GET['id']) && $_GET['id'] && $user->hasRole('admin')) {
            // echo "Hi"; die();
            $user = User::where('id', $_GET['id'])->first();
            $success = 1;
        }
        return view('admin.changePassword', ['success' => $success, 'user' => $user]);
    }

    public function updateChangePassword(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        $user = Auth::user();
        if ($request->user_id) {
            if ($user->hasRole('admin')) {
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:users,userid',
                    'new_password' => 'required',
                    'confirm_password' => 'required|same:new_password',
                ]);
                if ($validator->fails()) {
                    $success['message'] = $validator->errors()->first();

                    session()->flash('alert-class', 'text-danger');
                    session()->flash('message', $success['message']);
                    return redirect("users");
                }

                $data = array(
                    'password' => Hash::make($request->new_password),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                User::where('userid', $request->user_id)->update($data);

                session()->flash('alert-class', 'text-success');
                session()->flash('message', "Updated successfully.");
            }
            return redirect('users');
        } else {
            $validator = Validator::make($request->all(), [
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            ]);
            if ($validator->fails()) {
                $success['message'] = $validator->errors()->first();

                session()->flash('alert-class', 'text-danger');
                session()->flash('message', $success['message']);
                return redirect("/change_password");
            }

            $data = array(
                'password' => Hash::make($request->new_password),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            User::where('id', $user->id)->update($data);

            session()->flash('alert-class', 'text-success');
            session()->flash('message', "Updated successfully.");

            return redirect('change_password');
        }
    }

    public function updateChangeTransPassword(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        $user = Auth::user();
        if ($request->user_id) {
            if ($user->hasRole('admin')) {
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:users,userid',
                    'new_transaction_password' => 'required',
                    'confirm_transaction_password' => 'required|same:new_transaction_password',
                ]);
                if ($validator->fails()) {
                    $success['message'] = $validator->errors()->first();

                    session()->flash('alert-class', 'text-danger');
                    session()->flash('message', $success['message']);
                    return redirect("/users");
                }

                $data = array(
                    'password' => Hash::make($request->new_trans_pass),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                User::where('id', $user->id)->update($data);

                session()->flash('alert-class', 'text-success');
                session()->flash('message', "Updated successfully.");
            }
            return redirect('users');
        } else {
            $validator = Validator::make($request->all(), [
                'new_transaction_password' => 'required',
                'confirm_transaction_password' => 'required|same:new_transaction_password',
            ]);
            if ($validator->fails()) {
                $success['message'] = $validator->errors()->first();

                session()->flash('alert-class', 'text-danger');
                session()->flash('message', $success['message']);
                return redirect("/change_password");
            }

            $data = array(
                'password' => Hash::make($request->new_trans_pass),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            User::where('id', $user->id)->update($data);

            session()->flash('alert-class', 'text-success');
            session()->flash('message', "Updated successfully.");

            return redirect('change_password');
        }
    }

    public function renewalYourId()
    {
        $user  = Auth::user();
        if ($user->hasRole('admin')) {
            $renewalYourId = renewalReport::with(array('rrUsers' => function ($q) {
                $q->select('id', 'userid');
            }))->get();
        } else {
            $renewalYourId = $user->renewalReport()->with(array('rrUsers' => function ($q) {
                $q->select('id', 'userid');
            }))->get();
        }
        // echo '<pre>';
        // print_r($renewalYourId);
        // echo '</pre>';
        // die();
        return view('admin.renewalYourId', ['renewal_reports' => $renewalYourId]);
    }

    public function addRenewalId(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        $user = Auth::user();

        $checkAlready = RenewalReport::where('user_id', $user->id)->where('status', 'Pending')->get();

        if (count($checkAlready) == 0) {
            $renewalYourId = new RenewalReport;

            $renewalYourId->user_id = $user->id;
            $renewalYourId->renewal_amt = $request->renewal_amt;
            $renewalYourId->created_at = date('Y-m-d H:i:s');
            $renewalYourId->updated_at = date('Y-m-d H:i:s');
            $renewalYourId->save();

            session()->flash('alert-class', 'text-success');
            session()->flash('message', "Added successfully.");
        } else {
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', "Your request already exists.");
        }

        return redirect('renewal_id');
    }

    public function approveRenewalReq(Request $request)
    {
        // echo '<pre>';print_r($request->all());echo '</pre>';die();
        $check = RenewalReport::where('id', $request->id)->where('status', 'Pending')->first();
        if ($check != null) {
            $data = array(
                'status' => 'Approved',
                'updated_at' => date('Y-m-d H:i:s'),
            );
            RenewalReport::where('id', $request->id)->update($data);

            $renewalReport = RenewalReport::where('id', $request->id)->first();
            $user_id = $renewalReport->user_id;
            $renewal_amt = $renewalReport->renewal_amt;
            $data = array(
                'join_amt' => $renewal_amt,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            Downline::where('downline_id', $user_id)->update($data);

            $activationUser = User::where('id', $user_id)->first();
            if ($activationUser->activation_timestamp == null || $activationUser->activation_timestamp == '') {
                $activationUser->status = 1;
                $activationUser->activation_timestamp = date('Y-m-d H:i:s');
                $activationUser->updated_at = date('Y-m-d H:i:s');
                $activationUser->save();
            } else {
                $activationUser->status = 1;
                $activationUser->updated_at = date('Y-m-d H:i:s');
                $activationUser->save();
            }
            // $data = array(
            //     'status' => 1,
            //     'updated_at' => date('Y-m-d H:i:s'),
            // );
            // User::where('id', $user_id)->update($data);

            return 'Approved successfully.';
        } else {
            return 'Something is wrong, please refresh your page before approving.';
        }
    }

    public function rejectRenewalReq(Request $request)
    {
        $user = Auth::user();
        $data = array(
            'status' => 'Rejected',
            'reason' => $request->reason,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        RenewalReport::where('id', $request->id)->update($data);

        return 'Rejected successfully.';
    }

    public function distributeIncome(Request $request)
    {
        //ROI & DIRECT INCOME
        $id = $request->id;
        $renewalReport = RenewalReport::where('id', $id)->first();
        $user_id = $renewalReport->user_id;
        $renewal_amt = $renewalReport->renewal_amt;
        $amount = ($renewal_amt * 2) / 100;
        $direct_income = ($renewal_amt * 10) / 100;
        $date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime($date . ' +1 day'));
        $end_date = date('Y-m-d', strtotime($date . ' +100 day'));
        // echo '<pre>';
        // print_r($user_id);
        // echo '</pre>';
        // die();

        $data = array(
            'user_id'    => $user_id,
            'income_type' => 'ROI INCOME',
            'amount' => $amount,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        RoiIncome::create($data);

        $user = User::where('id', $id)->first();
        $sponsor_id = $user->sponsor_id;
        $placement = $user->placement;

        $userDI = User::where('id', $sponsor_id)->first();
        $userDI->direct += $direct_income;
        $userDI->updated_at = date('Y-m-d H:i:s');
        $userDI->save();

        $data = array(
            'user_id'    => $sponsor_id,
            'income_type' => 'DIRECT INCOME',
            'amount' => $direct_income,
            'by_id' => $user_id,
            'level' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        IncomeReport::create($data);

        $this->distributeMatchingIncome($user_id, $renewal_amt);
    }

    public function distributeMatchingIncome($user_id, $renewal_amt)
    {
        //MATCHING & DIRECT TEAM MATCHING INCOME
        $user = User::where('id', $user_id)->first();
        $placement_id = $user->placement_id;
        $placement = $user->placement;
        $matchingIncomeUser = MatchingIncome::where('user_id', $user_id)->first();
        if ($matchingIncomeUser == null) {
            // die('yes');
            $data = array(
                'user_id'    => $user_id,
                'amount' => 0,
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
            $matchingIncomeUser = MatchingIncome::where('user_id', $upline_id)->first();
            // echo '<pre>';
            // print_r($matchingIncomeUser->amount);
            // echo '</pre>';
            // die();
            if ($matchingIncomeUser->amount == 0) {
                $data = array(
                    'amount'    => $renewal_amt,
                    'placement'    => $upline->placement,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                MatchingIncome::where('user_id', $upline_id)->update($data);
            } elseif ($matchingIncomeUser->placement == 'Left') {
                if ($matchingIncomeUser->amount > 0 && $upline->placement == 'Right') {

                    $left_amount = $matchingIncomeUser->amount;
                    $place = ($renewal_amt > $left_amount) ? 'Right' : 'Left';
                    $matching_amt = ($renewal_amt > $left_amount) ? $left_amount : $renewal_amt;
                    $rem_amt = ($renewal_amt > $left_amount) ? ($renewal_amt - $left_amount) : ($left_amount - $renewal_amt);

                    $data = array(
                        'amount'    => $rem_amt,
                        'placement'    => $place,
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    MatchingIncome::where('user_id', $upline_id)->update($data);

                    $mamt = ($matching_amt * 10) / 100;
                    $userDI = User::where('id', $upline_id)->first();
                    $userDI->matching += $mamt;
                    $userDI->updated_at = date('Y-m-d H:i:s');
                    $userDI->save();

                    $data = array(
                        'user_id'    => $upline_id,
                        'income_type' => 'MATCHING INCOME',
                        'amount' => $mamt,
                        // 'by_id' => '',
                        // 'level' => '1',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    IncomeReport::create($data);

                    $user = User::where('id', $upline_id)->first();
                    $sponsor_id = $user->sponsor_id;
                    $amount = ($mamt * 10) / 100;

                    $userDI = User::where('id', $sponsor_id)->first();
                    $userDI->direct_team_matching += $amount;
                    $userDI->updated_at = date('Y-m-d H:i:s');
                    $userDI->save();

                    $data = array(
                        'user_id'    => $sponsor_id,
                        'income_type' => 'DIRECT TEAM MATCHING INCOME',
                        'amount' => $amount,
                        'by_id' => $upline_id,
                        // 'level' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    IncomeReport::create($data);
                } else {
                    $machingIUpdate = MatchingIncome::where('user_id', $upline_id)->first();
                    $machingIUpdate->amount += $renewal_amt;
                    $machingIUpdate->placement = $upline->placement;
                    $machingIUpdate->updated_at = date('Y-m-d H:i:s');
                    $machingIUpdate->save();
                }
            } elseif ($matchingIncomeUser->placement == 'Right') {
                if ($matchingIncomeUser->amount > 0 && $upline->placement == 'Left') {
                    $right_amount = $matchingIncomeUser->amount;
                    $place = ($renewal_amt > $right_amount) ? 'Right' : 'Left';
                    $matching_amt = ($renewal_amt > $right_amount) ? $right_amount : $renewal_amt;
                    $rem_amt = ($renewal_amt > $right_amount) ? ($renewal_amt - $right_amount) : ($right_amount - $renewal_amt);

                    $data = array(
                        'amount'    => $rem_amt,
                        'placement'    => $place,
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    MatchingIncome::where('user_id', $upline_id)->update($data);

                    $mamt = ($matching_amt * 10) / 100;
                    $userDI = User::where('id', $upline_id)->first();
                    $userDI->matching += $mamt;
                    $userDI->updated_at = date('Y-m-d H:i:s');
                    $userDI->save();

                    $data = array(
                        'user_id'    => $upline_id,
                        'income_type' => 'MATCHING INCOME',
                        'amount' => $mamt,
                        // 'by_id' => '',
                        // 'level' => '1',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    IncomeReport::create($data);

                    $user = User::where('id', $upline_id)->first();
                    $sponsor_id = $user->sponsor_id;
                    $amount = ($mamt * 10) / 100;

                    $userDI = User::where('id', $sponsor_id)->first();
                    $userDI->direct_team_matching += $amount;
                    $userDI->updated_at = date('Y-m-d H:i:s');
                    $userDI->save();

                    $data = array(
                        'user_id'    => $sponsor_id,
                        'income_type' => 'DIRECT TEAM MATCHING INCOME',
                        'amount' => $amount,
                        'by_id' => $upline_id,
                        // 'level' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    IncomeReport::create($data);
                } else {
                    $machingIUpdate = MatchingIncome::where('user_id', $upline_id)->first();
                    $machingIUpdate->amount += $renewal_amt;
                    $machingIUpdate->placement = $upline->placement;
                    $machingIUpdate->updated_at = date('Y-m-d H:i:s');
                    $machingIUpdate->save();
                }
            }

            $placement = $upline->placement;
        }
    }

    public function virtual_power()
    {
        $user = Auth::user();
        $success = 0;
        if (isset($_GET['id']) && $_GET['id'] && $user->hasRole('admin')) {

            $user = User::where('id', $_GET['id'])->first();
            $matching_income = MatchingIncome::where('user_id', $_GET['id'])->first();
            if ($matching_income->placement == 'Right') {
                $left  = 0;
                $right = $matching_income->amount;
            } else {
                $left  = $matching_income->amount;
                $right = 0;
            }
            // echo '<pre>';
            // print_r($matching_income->toArray());
            // echo '</pre>';
            // die();
            return view('admin.virtual_power', ['left' => $left, 'right' => $right, 'matching_income' => $matching_income, 'user' => $user]);
        }
        redirect('dashboard');
    }

    public function post_virtual_power(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        $auth_user = Auth::user();
        if ($auth_user->hasRole('admin')) {
            $user = User::where('userid', $request->user_id)->first();
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,userid',
                'left' => 'required',
                'right' => 'required',
            ]);
            if ($validator->fails()) {
                $success['message'] = $validator->errors()->first();

                session()->flash('alert-class', 'text-danger');
                session()->flash('message', $success['message']);
                return redirect("users");
            }

            $upline_id = $user->id;
            $matchingIncomeUser = MatchingIncome::where('user_id', $upline_id)->first();

            // $place          = ($request->left > $request->right) ? 'Left' : 'Right';
            $matching_amt   = ($request->left > $request->right) ? $request->right : $request->left;
            $rem_left       = $request->left - $matching_amt;
            $rem_right      = $request->right - $matching_amt;
            // $rem_amt        = ($request->left > $request->right) ? ($request->left - $request->right) : ($request->right - $request->left);

            if ($matchingIncomeUser == null) {
                $data = array(
                    'user_id'       => $upline_id,
                    'left'          => $rem_left,
                    'right'         => $rem_right,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                );
                MatchingIncome::create($data);
            } else {
                $data = array(
                    'left'          => $rem_left,
                    'right'         => $rem_right,
                    'updated_at'    => date('Y-m-d H:i:s'),
                );
                MatchingIncome::where('user_id', $upline_id)->update($data);
            }

            $data = array(
                'user_id'           => $user->id,
                'income_type'       => 'VIRTUAL POWER',
                'amount'            => 0,
                'level'             => $request->left,
                'level2'            => $request->right,
                'level3'            => $request->left_old,
                'level4'            => $request->right_old,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            );
            IncomeReport::create($data);



            $matching_amt = ($matching_amt * 8) / 100;
            if ($matching_amt > 0) {
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

                $sponsor_id = $userDI->sponsor_id;
                $userDI = User::where('id', $sponsor_id)->first();
                if ($userDI != null) {
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
                }
            }

            // echo '<pre>';
            // print_r($matchingIncomeUser->amount);
            // echo '</pre>';
            // die();

            session()->flash('alert-class', 'text-success');
            session()->flash('message', "Updated successfully.");
        } else {
            return redirect('dashboard');
        }
        return redirect('users');
    }
}
