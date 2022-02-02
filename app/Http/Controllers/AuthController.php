<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\Role;
use App\Models\User;
use App\Models\Downline;
use Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function registerPost(Request $request)
    {
        // echo '<pre>'; print_r($request->all()); echo '<pre>';die();
        $result = $this->registrationUser($request);
        if ($result['success'] == 1) {
            $user = $result['message'];
            session()->flash('alert-class', 'text-success');
            session()->flash('message', "You have registered successfully. You can login to portal using your USER ID: " . $user->userid . " and PASSWORD: " . $result['pass'] . ".");
            if (isset($request->user_registration)) {
                return redirect("/user_registration");
            } else {
                return redirect("/login");
            }
        } else {
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', $result['message']);
            if (isset($request->user_registration)) {
                return redirect("/user_registration");
            } else {
                return redirect("/register");
            }
        }
    }

    public function registrationUser($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'mobile' => 'required', //|unique:users,mobile'
            'email' => 'required', //|unique:users,email
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'sponsor_id' => 'required|exists:users,userid',
        ]);
        if ($validator->fails()) {
            $success['success'] = 0;
            $success['message'] = $validator->errors()->first();
            return $success;
        }

        // $checkMobile = User::where('mobile', $request->mobile)->get();
        // if(count($checkMobile)>6){
        //     $success['success'] = 0;
        //     $success['message'] = 'This mobile already used 7 times';
        //     return $success;
        // }
        // $checkMobile = User::where('email', $request->email)->get();
        // if(count($checkMobile)>6){
        //     $success['success'] = 0;
        //     $success['message'] = 'This email already used 7 times';
        //     return $success;
        // }

        $userRole = Role::where('slug', 'user')->first();

        $placement = $request->placement;
        $sponsor = $request->sponsor_id;
        $pass = $request->password;
        $sponsorUser = User::where('userid', $sponsor)->first();
        $sponsor = $sponsorUser->id;

        $placement_id = $this->getLastID($sponsor, $placement);

        $trans_pass = mt_rand(1000, 9999);
        $data = array(
            'userid'    => '',
            'sponsor_id' => $sponsor,
            'placement_id' => $placement_id,
            'placement' => $request->placement,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'trans_pass' => $trans_pass,
            'photo' => 'assets/img/user.png',
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $user = User::create($data);
        $userId             = $user->id;
        $newUserID = 'E' . $userId . mt_rand(100, 999);
        $user->userid        = $newUserID;
        $user->save();
        $user->roles()->attach($userRole);
        $this->addDownline($userId, $userId);

        // // $message = urlencode('Dear Mr. '.$request->name.', Your Account has been Successfully Created. User Id - '.$newUserID.', Password - '.$request->password.' and Transaction Pass - '.$trans_pass.'. Web - https://E-Life.com/ AFGSST');
        // $message = urlencode('Dear Mr. ' . $request->name . ', Your Account has been Successfully Created. User Id - ' . $newUserID . ', Password - ' . $request->password . ' and Transaction Pass - ' . $trans_pass . '. Web - https://E-Life.com TRDCRT');
        // // $url = "http://mysmsshop.in/V2/http-api.php?apikey=AZ5Cxf6nE9cDX88w&senderid=TRXTRD&number=".$request->mobile."&message=".$message."&format=json";
        // $url = "http://mysmsshop.in/V2/http-api.php?apikey=i3lmzhDxdOgxv82M&senderid=TRDCRT&number=" . $request->mobile . "&message=" . $message . "&format=json8";
        // // create a new cURL resource
        // $ch = curl_init();
        // // set URL and other appropriate options
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_HEADER, 0);
        // // grab URL and pass it to the browser
        // curl_exec($ch);
        // // close cURL resource, and free up system resources
        // curl_close($ch);

        $success['success'] = 1;
        $success['message'] = $user;
        $success['pass'] = $pass;
        return $success;
    }

    public function getLastID($userid, $placement)
    {
        while (1) {
            $user = User::where('placement_id', $userid)->where('placement', $placement)->first();
            if ($user == null) {
                break;
            }
            // echo '<pre>';
            // print_r($user->userid);
            // echo '</pre>';
            // die();
            $userid = $user->id;
        }
        return $userid;
    }

    public function addDownline($newID, $userId)
    {
        $user = User::where('id', $userId)->first();
        $userId = $user->placement_id;
        if ($userId == '') {
            return;
        }
        $data = array(
            'user_id' => $userId,
            'downline_id' => $newID,
            'placement' => $user->placement,
            // 'join_amt' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $downline = Downline::create($data);

        $this->addDownline($newID, $userId);
    }

    public function loginPost(Request $request)
    {
        $result = $this->loginUser($request);
        if ($result['success'] == 1) {
            session()->flash('alert-class', 'text-success');
            session()->flash('message', $result['message']);
            return redirect("/dashboard");
        } else {
            session()->flash('alert-class', 'text-danger');
            session()->flash('message', $result['message']);
            return redirect("/login");
        }
    }

    public function loginUser($request)
    {
        $userId = $request->user_id;
        $password = $request->password;

        $credentials = array('userid' => $userId, 'password' => $password);

        if (Auth::attempt($credentials, false)) {
            $result['success'] = 1;
            $result['message'] = "User loged in successfully.";
            return $result;
        } else {
            $result['success'] = 0;
            $result['message'] = "Incorrect userid/password combination.";
            return $result;
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        session()->flash('message', 'Logout successful.');
        session()->flash('alert-class', 'text-success');
        return redirect('/login');
    }

    public function get_username(Request $request)
    {
        $userid = $request->userid;
        $user = User::select('name')->where('userid', $userid)->first();
        if ($user != null) {
            return response()->json(['success' => 1, 'user' => $user]);
        } else {
            return response()->json(['success' => 0]);
        }
    }

    public function loginMember(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // echo '</pre>';
        // die();
        Auth::loginUsingId($request->user_id);
        $user = Auth::user();
        // echo '<pre>';
        // print_r($user->userid);
        // echo '</pre>';
        // die();
        session()->flash('alert-class', 'text-success');
        session()->flash('message', 'User loged in successfully.');
        return redirect("/dashboard");
    }


    public function forgot_password()
    {
        return view('forgot_password');
    }


    public function forgot_password_post(Request $request)
    {
        $password = mt_rand(1000,999999);
        $user = User::where('userid', $request->user_id)->first();
        $user->password = Hash::make($password);
        $user->save();

        $mobile_last2 = substr($user->mobile, -2);

        if ($user != null) {
            // $message = urlencode('Your Account Forget Your User Id- '.$user->userid.' and Password- '.$password.'. TRDCRT');
            // // $url = "http://mysmsshop.in/V2/http-api.php?apikey=AZ5Cxf6nE9cDX88w&senderid=TRXTRD&number=".$request->mobile."&message=".$message."&format=json";
            // $url = "http://mysmsshop.in/V2/http-api.php?apikey=i3lmzhDxdOgxv82M&senderid=TRDCRT&number=" . $user->mobile . "&message=" . $message . "&format=json8";
            // // create a new cURL resource
            // $ch = curl_init();
            // // set URL and other appropriate options
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_HEADER, 0);
            // // grab URL and pass it to the browser
            // curl_exec($ch);
            // // close cURL resource, and free up system resources
            // curl_close($ch);

            session()->flash('message', 'Message sent on ********'.$mobile_last2.' successfully.');
            session()->flash('alert-class', 'text-white');
        } else {
            session()->flash('message', 'Invalid User Id.');
            session()->flash('alert-class', 'text-success');
        }

        return redirect("login");
    }
}
