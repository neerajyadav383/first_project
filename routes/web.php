<?php

use Illuminate\Support\Facades\Route;

//controller
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\NotificationController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\GenealogyController;
use App\Http\Controllers\admin\OfferController;
use App\Http\Controllers\admin\WalletController;
use App\Http\Controllers\admin\WithdrawalController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\RequestTransferController;
use App\Http\Controllers\admin\TopupIdController;
use App\Http\Controllers\RoiBoosterController;
//middleware
use App\Http\Middleware\UserCheck;
use App\Http\Middleware\VerifyAuth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'index']);


Route::get('/index.php', function () {
    return view('index');
});
Route::get('/about.php', function () {
    return view('about');
});
Route::get('/gallery.php', function () {
    return view('gallery');
});
Route::get('/product', function () {
    return view('product');
});
Route::get('/contact.php', function () {
    return view('contact');
});
Route::get('/plans', function () {
    return view('plans');
});

Route::post('/get_username', [AuthController::class, 'get_username'])->name('get_username');


// Route::middleware([UserCheck::class])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');

    Route::post('/register_post', [AuthController::class, 'registerPost'])->name('register_post');
    Route::post('/login_post', [AuthController::class, 'loginPost'])->name('login_post');
    
    Route::get('/forgot_password', [AuthController::class, 'forgot_password'])->name('forgot_password');
    Route::post('/forgot_password_post', [AuthController::class, 'forgot_password_post'])->name('forgot_password_post');
// });

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login_member', [AuthController::class, 'loginMember'])->name('login_member');


Route::get('/closing', [RoiBoosterController::class, 'closing'])->name('closing');
Route::get('/roi', [RoiBoosterController::class, 'roi'])->name('roi');
Route::post('/checkBooster', [RoiBoosterController::class, 'checkBooster'])->name('checkBooster');
Route::get('/booster', [RoiBoosterController::class, 'booster'])->name('booster');
Route::post('/checkRetopup', [RoiBoosterController::class, 'checkRetopup'])->name('checkRetopup');

Route::get('/testing_double_intry', [RoiBoosterController::class, 'testing_double_intry'])->name('testing_double_intry');

Route::middleware([VerifyAuth::class])->group(function () {
    //dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    //notification
    Route::get('/notification', [NotificationController::class, 'notification'])->name('notification');
    Route::post('/add_notification', [NotificationController::class, 'addNotification'])->name('add_notification');
    Route::get('/delete_notification/{id}', [NotificationController::class, 'deleteNotification']);
    Route::get('/notification/{id}', [NotificationController::class, 'notification']);
    Route::post('/update_notification', [NotificationController::class, 'updateNotification'])->name('update_notification');

    //OFFER
    Route::get('/offer', [OfferController::class, 'offer'])->name('offer');
    Route::post('/add_offer', [OfferController::class, 'add_offer'])->name('add_offer');
    Route::get('/delete_offer/{id}', [OfferController::class, 'delete_offer']);
    Route::get('/offer/{id}', [OfferController::class, 'offer']);
    Route::post('/update_offer', [OfferController::class, 'update_offer'])->name('update_offer');


    //profile
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/edit_profile', [ProfileController::class, 'editProfile'])->name('edit_profile');
    Route::post('/update_profile', [ProfileController::class, 'updateProfile'])->name('update_profile');
    Route::post('/get_cities', [ProfileController::class, 'getCities'])->name('get_cities');
    
    Route::get('/virtual_power', [ProfileController::class, 'virtual_power'])->name('virtual_power');
    Route::post('/post_virtual_power', [ProfileController::class, 'post_virtual_power'])->name('post_virtual_power');
    
    //change password
    Route::get('/change_password', [ProfileController::class, 'changePassword'])->name('change_password');
    Route::post('/update_change_password', [ProfileController::class, 'updateChangePassword'])->name('update_change_password');
    Route::post('/update_change_transpassword', [ProfileController::class, 'updateChangeTransPassword'])->name('update_change_transpassword');
    //renewal_id
    Route::get('/renewal_id', [ProfileController::class, 'renewalYourId'])->name('renewal_id');
    Route::post('/add_renewal_id', [ProfileController::class, 'addRenewalId'])->name('add_renewal_id');
    Route::post('/approve_renewal_req', [ProfileController::class, 'approveRenewalReq'])->name('approve_renewal_req');
    Route::post('/reject_renewal_req', [ProfileController::class, 'rejectRenewalReq'])->name('reject_renewal_req');
    //distribute_income
    Route::post('/distribute_income', [ProfileController::class, 'distributeIncome'])->name('distribute_income');
    Route::get('/distribute_matching_income', [TopupIdController::class, 'distributeMatchingIncome'])->name('distribute_income');

    //TOPUP ID
    Route::get('/topup_id', [TopupIdController::class, 'topupId'])->name('topup_id');
    Route::post('/add_topup_id', [TopupIdController::class, 'addTopupId'])->name('add_topup_id');
    Route::post('/topup_distribute_income', [TopupIdController::class, 'distributeIncome'])->name('distribute_income');

    Route::post('/renewal_your_id', [TopupIdController::class, 'renewalYourId'])->name('renewal_your_id');

    Route::get('/closing_statement', [TopupIdController::class, 'closingStatement'])->name('closing_statement');
    Route::post('/search_closing_statement', [TopupIdController::class, 'search_closing_statement'])->name('search_closing_statement');

    //test      //test      //test      //test      //test      //test      //test      //test
    Route::get('/test', [TopupIdController::class, 'test'])->name('test');


    //users
    //user registration
    Route::get('/user_registration', [UserController::class, 'userRegistration'])->name('user_registration');
    //list of users
    Route::get('/users', [UserController::class, 'listUser'])->name('users');
    //update_user_profile
    Route::post('/update_user_profile', [ProfileController::class, 'updateUserProfile'])->name('update_user_profile');
    //Inactive users
    Route::get('/inactive_user', [UserController::class, 'inactiveUser'])->name('inactive_user');
    
    Route::post('/wallet_lock', [UserController::class, 'wallet_lock'])->name('wallet_lock');
    Route::get('/wallet_update', [UserController::class, 'wallet_update'])->name('wallet_update');
    Route::post('/post_wallet_update', [UserController::class, 'post_wallet_update'])->name('post_wallet_update');
    

    //genealogy
    //Direct users
    Route::get('/direct_user', [GenealogyController::class, 'directUser'])->name('direct_user');
    //Downline
    Route::get('/downline', [GenealogyController::class, 'downline'])->name('downline');
    Route::get('/left_downline', [GenealogyController::class, 'leftDownline'])->name('left_downline');
    Route::get('/right_downline', [GenealogyController::class, 'rightDownline'])->name('right_downline');
    //tree_view
    Route::get('/tree_view', [GenealogyController::class, 'treeView'])->name('tree_view');
    Route::post('/tree_generate', [GenealogyController::class, 'treeGenerate'])->name('tree_generate');

    //wallet_request
    Route::get('/wallet_request', [WalletController::class, 'walletRequest'])->name('wallet_request');
    Route::post('/add_wallet_req', [WalletController::class, 'addWalletReq'])->name('add_wallet_req');
    Route::post('/approve_wallet_req', [WalletController::class, 'approveWalletReq'])->name('approve_wallet_req');
    Route::post('/reject_wallet_req', [WalletController::class, 'rejectWalletReq'])->name('reject_wallet_req');
    
    //wallet_request
    Route::get('/investment', [WalletController::class, 'investment'])->name('investment');
    Route::post('/add_investment', [WalletController::class, 'add_investment'])->name('add_investment');
    Route::post('/approve_investment', [WalletController::class, 'approve_investment'])->name('approve_investment');
    Route::post('/reject_investment', [WalletController::class, 'reject_investment'])->name('reject_investment');
    

    //withdrwal
    Route::get('/bene_report', [WithdrawalController::class, 'beneficiary'])->name('bene_report');
    Route::post('/add_bene_id', [WithdrawalController::class, 'addBeneId'])->name('add_bene_id');
    Route::post('/add_request_transfer', [RequestTransferController::class, 'addRequestTransfer'])->name('add_request_transfer');
    Route::get('/withdrwal_report', [WithdrawalController::class, 'withdrawalReport'])->name('withdrwal_report');
    Route::get('/manual_payment', [WithdrawalController::class, 'manual_payment'])->name('manual_payment');
    Route::post('/post_manual_payment', [WithdrawalController::class, 'post_manual_payment'])->name('post_manual_payment');

    //Reports
    Route::get('/roi_report', [ReportController::class, 'roiReport'])->name('roi_report');
    Route::get('/booster_report', [ReportController::class, 'boosterReport'])->name('booster_report');
    Route::get('/direct_report', [ReportController::class, 'directReport'])->name('direct_report');
    Route::get('/matching_report', [ReportController::class, 'matchingReport'])->name('matching_report');
    
    Route::post('/search_matching_report', [ReportController::class, 'search_matching_report'])->name('search_matching_report');
    Route::post('/search_direct_matching', [ReportController::class, 'search_direct_matching'])->name('search_direct_matching');

    Route::get('/direct_matching_report', [ReportController::class, 'directMatchingReport'])->name('direct_matching_report');
    Route::get('/topup_report', [ReportController::class, 'topupReport'])->name('topup_report');
    Route::get('/re_topup_report', [ReportController::class, 're_topup_report'])->name('re_topup_report');
    Route::get('/reward_report', [ReportController::class, 'reward_report'])->name('reward_report');

    Route::get('/virtual_power_report', [ReportController::class, 'virtual_power_report'])->name('virtual_power_report');

    
});
