<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IncomeReport;
use App\Models\Reward;
use App\Models\TopupReport;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function roiReport()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $roiIncomeReport = IncomeReport::where('income_type', 'ROI INCOME')->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
            // echo '<pre>';
            // print_r($roiIncomeReport->toArray());
            // echo '</pre>';
            // die();
        } else {
            $roiIncomeReport = IncomeReport::where('user_id', $user->id)->where('income_type', 'ROI INCOME')->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        }

        return view('admin.roi', ['roiIncomeReport' => $roiIncomeReport]);
    }

    public function boosterReport()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $boosterIncomeReport = IncomeReport::where('income_type', 'BOOSTER INCOME')->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        } else {
            $boosterIncomeReport = IncomeReport::where('user_id', $user->id)->where('income_type', 'BOOSTER INCOME')->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        }
        return view('admin.booster', ['boosterIncomeReport' => $boosterIncomeReport]);
    }

    public function directReport()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $directIncomeReport = IncomeReport::where('income_type', 'DIRECT INCOME')->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }, 'userDetails2' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        } else {
            $directIncomeReport = IncomeReport::where('user_id', $user->id)->where('income_type', 'DIRECT INCOME')->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }, 'userDetails2' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        }
        return view('admin.direct', ['directIncomeReport' => $directIncomeReport]);
    }

    public function matchingReport()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $matchingIncomeReport = IncomeReport::where('income_type', 'BINARY INCOME')->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        } else {
            $matchingIncomeReport = IncomeReport::where('user_id', $user->id)->where('income_type', 'BINARY INCOME')->with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
        }
        return view('admin.matching', ['matchingIncomeReport' => $matchingIncomeReport]);
    }

    public function search_matching_report(Request $request)
    {
        $from_date = $request->from_date . ' 00:00:00';
        $to_date = $request->to_date . ' 23:59:59';

        $user = Auth::user();
        if ($user->hasRole('admin')) {
            // DB::enableQueryLog();
            $matchingIncomeReport = IncomeReport::where('income_type', 'MATCHING INCOME')
                ->where('created_at', '>=', $from_date)
                ->where('created_at', '<=', $to_date)
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
            // $quries = DB::getQueryLog();
            // dd($quries);
        } else {
            $matchingIncomeReport = IncomeReport::where('user_id', $user->id)
                ->where('income_type', 'MATCHING INCOME')
                ->where('created_at', '>=', $from_date)
                ->where('created_at', '<=', $to_date)
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        }

        // echo '<pre>';
        // print_r($matchingIncomeReport->toArray());
        // echo '</pre>';
        // die();
        return response()->json(['matchingIncomeReport' => $matchingIncomeReport]);
    }

    public function directMatchingReport()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $directMachingIncomeReport = IncomeReport::where('income_type', 'ROYALTY INCOME')
                ->orWhere('income_type', 'REFERAL ROYALTY INCOME')
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }, 'userDetails2' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        } else {
            $directMachingIncomeReport = IncomeReport::where('user_id', $user->id)
                ->where(function ($q) {
                    $q->where('income_type', 'ROYALTY INCOME')
                        ->orWhere('income_type', 'REFERAL ROYALTY INCOME');
                })
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }, 'userDetails2' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        }
        return view('admin.direct_matching', ['directMachingIncomeReport' => $directMachingIncomeReport]);
    }

    public function search_direct_matching(Request $request)
    {
        $from_date = $request->from_date . ' 00:00:00';
        $to_date = $request->to_date . ' 23:59:59';
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            // DB::enableQueryLog();
            $directMachingIncomeReport = IncomeReport::where('income_type', 'DIRECT TEAM MATCHING INCOME')
                ->where('created_at', '>=', $from_date)
                ->where('created_at', '<=', $to_date)
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }, 'userDetails2' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
            // $quries = DB::getQueryLog();
            // dd($quries);
        } else {
            $directMachingIncomeReport = IncomeReport::where('user_id', $user->id)
                ->where('income_type', 'DIRECT TEAM MATCHING INCOME')
                ->where('created_at', '>=', $from_date)
                ->where('created_at', '<=', $to_date)
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }, 'userDetails2' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        }
        return response()->json(['directMachingIncomeReport' => $directMachingIncomeReport]);
    }

    public function topupReport()
    {
        $user  = Auth::user();
        if ($user->hasRole('admin')) {
            $topup_report = TopupReport::where('topup_type', 'TOPUP')
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }, 'userDetails2' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        } else {
            $topup_report = TopupReport::where('user_id', $user->id)
                ->orWhere('topupby_id', $user->id)
                ->where('topup_type', 'TOPUP')
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }, 'userDetails2' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        }
        // echo '<pre>';
        // print_r($topup_report[0]->userDetails['userid']);
        // echo '</pre>';
        // die();
        return view('admin.topup_report', ['topup_reports' => $topup_report]);
    }

    public function re_topup_report()
    {
        $user  = Auth::user();
        if ($user->hasRole('admin')) {
            $topup_report = TopupReport::where('topup_type', 'RENEWAL')
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }, 'userDetails2' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        } else {
            $topup_report = TopupReport::where('user_id', $user->id)
                ->orWhere('topupby_id', $user->id)
                ->where('topup_type', 'RENEWAL')
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }, 'userDetails2' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        }
        // echo '<pre>';
        // print_r($topup_report[0]->userDetails['userid']);
        // echo '</pre>';
        // die();
        return view('admin.re_topup_report', ['topup_reports' => $topup_report]);
    }

    public function reward_report()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $roiIncomeReport = Reward::with(array('userDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->get();
            // echo '<pre>';
            // print_r($roiIncomeReport->toArray());
            // echo '</pre>';
            // die();
        } else {
            $roiIncomeReport = Reward::where('user_id', $user->id)
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        }

        return view('admin.reward_report', ['roiIncomeReport' => $roiIncomeReport]);
    }

    public function virtual_power_report()
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            $topup_report = IncomeReport::where('income_type', 'VIRTUAL POWER')
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        } else {
            $topup_report = IncomeReport::where('user_id', $user->id)
                ->where('income_type', 'VIRTUAL POWER')
                ->with(array('userDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->get();
        }
        // echo '<pre>';
        // print_r($topup_report[0]->userDetails['userid']);
        // echo '</pre>';
        // die();
        return view('admin.virtual_power_report', ['reports' => $topup_report]);
    }
}
