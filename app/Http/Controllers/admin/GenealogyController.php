<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Downline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class GenealogyController extends Controller
{
    public function directUser()
    {
        $user = Auth::user();
        $directUser = User::where('sponsor_id', $user->id)->get();
        return view('admin.directUser', ['directUser' => $directUser]);
    }

    public function leftDownline()
    {
        $user = Auth::user();
        $left_downline = User::where('placement_id', $user->id)->where('placement', 'Left')
            ->with(array('sponsorDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))
            ->with(array('placementDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->first();
        if ($left_downline == null) {
            $status = 0;
            $downlines = '';
            $left_downline = '';
        } else {
            $status = 1;
            $downlines = User::where('id', $left_downline->id)->with('downline', 'downline.userDetails')
                ->with(array('downline.userDetails.sponsorDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))
                ->with(array('downline.userDetails.placementDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->first();
        }

        // echo'<pre>';print_r($downlines->toArray());echo'</pre>';die();
        return view('admin.leftDownline', ['downlines' => $downlines, 'left_downline' => $left_downline, 'status' => $status]);
    }

    public function rightDownline()
    {
        $user = Auth::user();
        $left_downline = User::where('placement_id', $user->id)->where('placement', 'Right')
            ->with(array('sponsorDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))
            ->with(array('placementDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->first();
        if ($left_downline == null) {
            $status = 0;
            $downlines = '';
            $left_downline = '';
        } else {
            $status = 1;
            $downlines = User::where('id', $left_downline->id)->with('downline', 'downline.userDetails')
                ->with(array('downline.userDetails.sponsorDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))
                ->with(array('downline.userDetails.placementDetails' => function ($q) {
                    $q->select('id', 'userid', 'name');
                }))->first();
        }

        // echo'<pre>';print_r($downlines->toArray());echo'</pre>';die();
        return view('admin.rightDownline', ['downlines' => $downlines, 'left_downline' => $left_downline, 'status' => $status]);
    }

    public function downline()
    {
        $user = Auth::user();
        $downlines = User::where('id', $user->id)->with('downline', 'downline.userDetails')
            ->with(array('downline.userDetails.sponsorDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))
            ->with(array('downline.userDetails.placementDetails' => function ($q) {
                $q->select('id', 'userid', 'name');
            }))->first();

        // echo'<pre>';print_r($downlines->toArray());echo'</pre>';die();
        return view('admin.downline', ['downlines' => $downlines]);
    }


    public function treeView()
    {
        $user = Auth::user();
        // echo'<pre>';print_r($user);echo'</pre>';die();
        return view('admin/treeView', ['user' => $user]);
    }

    public function treeGenerate(Request $request)
    {
        if (Session::get('tree') != null) {
            $treeID = Session::get('tree');
        } else {
            $treeID = array();
        }

        if (isset($request->user_id)) {
            $user1 = User::where('userid', $request->user_id)->first();
            $request->id = $user1->id;
        }
        
        if (isset($request->id)) {
            $id = $request->id;
            array_push($treeID, $id);
            Session::put('tree', $treeID);
        } else {
            if (count($treeID) > 1) {
                array_pop($treeID);
            }
            Session::put('tree', $treeID);
            $id = end($treeID);
        }


        $result = '';
        $user1 = User::where('id', $id)->first();
        if ($user1->status == 0) {
            $photo1 = 'assets/img/userr.png';
        } elseif ($user1->status == 1) {
            $photo1 = 'assets/img/userg.png';
        } elseif ($user1->status == 2) {
            $photo1 = 'assets/img/usery.png';
        } else {
            $photo1 = $user1->photo;
        }
        // <!-------------------------1 st Level Start-------------------------------------------->
        $result .= '<ul class="list-inline">
                <li>
                    <a onclick="generateTree(' . $user1->id . ');" class="tooltipme" style="cursor:pointer; height: 80px; width: 130px; color: #000; padding:0px; font-size: 13px;"><div data-toggle="tooltip"  data-placement="top"><img src="' . $photo1 . '" width="40" height="40" class="br-50" ></div>
                        ' . $user1->userid . '<br>' . $user1->name . '<br>
                        <span class="tooltipmetext" tabindex="1">
                            <div class="border">Joining Amount : ' . $user1->join_amt . '</div>
                            <div class="border">Left Team : ' . $this->leftTeam($user1->id) . '</div>
                            <div class="border">Right Team : ' . $this->rightTeam($user1->id) . '</div>
                            <div class="border">Joining Date : ' . $user1->created_at . '</div>
                            <div class="border">Activation Date : ' . $user1->activation_timestamp . '</div>
                        </span>
                    </a>';
        //<!-------------------------2 st Level Start-------------------------------------------->
        $user2 = User::where('placement_id', $user1->id)->where('placement', 'Left')->first();
        if ($user2 != null) {
            if ($user2->status == 0) {
                $photo2 = 'assets/img/userr.png';
            } elseif ($user2->status == 1) {
                $photo2 = 'assets/img/userg.png';
            } elseif ($user2->status == 2) {
                $photo2 = 'assets/img/usery.png';
            } else {
                $photo2 = $user2->photo;
            }
            $a2 = 'onclick="generateTree(' . $user2->id . ');"';
            $span2 = '<div class="border">Joining Amount : ' . $user2->join_amt . '</div>
            <div class="border">Left Team : ' . $this->leftTeam($user2->id) . '</div>
            <div class="border">Right Team : ' . $this->rightTeam($user2->id) . '</div>
            <div class="border">Joining Date : ' . $user2->created_at . '</div>
            <div class="border">Activation Date : ' . $user2->activation_timestamp . '</div>';
            $userDetails2 = $user2->userid . '<br>' . $user2->name . '<br>';
            $user2id = $user2->id;
        } else {
            $photo2 = 'assets/img/userb.png';
            $a2 = 'href="javascript:void(0);"';
            $span2 = '';
            $userDetails2 = '';
            $user2id = 'zzzzzzzzzzdbjdbhdsbhcdsbchjsdbks';
        }
        $user3 = User::where('placement_id', $user1->id)->where('placement', 'Right')->first();
        if ($user3 != null) {
            if ($user3->status == 0) {
                $photo3 = 'assets/img/userr.png';
            } elseif ($user3->status == 1) {
                $photo3 = 'assets/img/userg.png';
            } elseif ($user3->status == 2) {
                $photo3 = 'assets/img/usery.png';
            } else {
                $photo3 = $user3->photo;
            }
            $a3 = 'onclick="generateTree(' . $user3->id . ');"';
            $span3 = '<div class="border">Joining Amount : ' . $user3->join_amt . '</div>
            <div class="border">Left Team : ' . $this->leftTeam($user3->id) . '</div>
            <div class="border">Right Team : ' . $this->rightTeam($user3->id) . '</div>
            <div class="border">Joining Date : ' . $user3->created_at . '</div>
            <div class="border">Activation Date : ' . $user3->activation_timestamp . '</div>';
            $userDetails3 = $user3->userid . '<br>' . $user3->name . '<br>';
            $user3id = $user3->id;
        } else {
            $photo3 = 'assets/img/userb.png';
            $a3 = 'href="javascript:void(0);"';
            $span3 = '';
            $userDetails3 = '';
            $user3id = 'zzzzzzzzzzdbjdbhdsbhcdsbchjsdbks';
        }
        $user4 = User::where('placement_id', $user2id)->where('placement', 'Left')->first();
        if ($user4 != null) {
            if ($user4->status == 0) {
                $photo4 = 'assets/img/userr.png';
            } elseif ($user4->status == 1) {
                $photo4 = 'assets/img/userg.png';
            } elseif ($user4->status == 2) {
                $photo4 = 'assets/img/usery.png';
            } else {
                $photo4 = $user4->photo;
            }
            $a4 = 'onclick="generateTree(' . $user4->id . ');"';
            $span4 = '<div class="border">Joining Amount : ' . $user4->join_amt . '</div>
            <div class="border">Left Team : ' . $this->leftTeam($user4->id) . '</div>
            <div class="border">Right Team : ' . $this->rightTeam($user4->id) . '</div>
            <div class="border">Joining Date : ' . $user4->created_at . '</div>
            <div class="border">Activation Date : ' . $user4->activation_timestamp . '</div>';
            $userDetails4 = $user4->userid . '<br>' . $user4->name . '<br>';
        } else {
            $photo4 = 'assets/img/userb.png';
            $a4 = 'href="javascript:void(0);"';
            $span4 = '';
            $userDetails4 = '';
        }
        $user5 = User::where('placement_id', $user2id)->where('placement', 'Right')->first();
        if ($user5 != null) {
            if ($user5->status == 0) {
                $photo5 = 'assets/img/userr.png';
            } elseif ($user5->status == 1) {
                $photo5 = 'assets/img/userg.png';
            } elseif ($user5->status == 2) {
                $photo5 = 'assets/img/usery.png';
            } else {
                $photo5 = $user5->photo;
            }
            $a5 = 'onclick="generateTree(' . $user5->id . ');"';
            $span5 = '<div class="border">Joining Amount : ' . $user5->join_amt . '</div>
            <div class="border">Left Team : ' . $this->leftTeam($user5->id) . '</div>
            <div class="border">Right Team : ' . $this->rightTeam($user5->id) . '</div>
            <div class="border">Joining Date : ' . $user5->created_at . '</div>
            <div class="border">Activation Date : ' . $user5->activation_timestamp . '</div>';
            $userDetails5 = $user5->userid . '<br>' . $user5->name . '<br>';
        } else {
            $photo5 = 'assets/img/userb.png';
            $a5 = 'href="javascript:void(0);"';
            $span5 = '';
            $userDetails5 = '';
        }
        $user6 = User::where('placement_id', $user3id)->where('placement', 'Left')->first();
        if ($user6 != null) {
            if ($user6->status == 0) {
                $photo6 = 'assets/img/userr.png';
            } elseif ($user6->status == 1) {
                $photo6 = 'assets/img/userg.png';
            } elseif ($user6->status == 2) {
                $photo6 = 'assets/img/usery.png';
            } else {
                $photo6 = $user6->photo;
            }
            $a6 = 'onclick="generateTree(' . $user6->id . ');"';
            $span6 = '<div class="border">Joining Amount : ' . $user6->join_amt . '</div>
            <div class="border">Left Team : ' . $this->leftTeam($user6->id) . '</div>
            <div class="border">Right Team : ' . $this->rightTeam($user6->id) . '</div>
            <div class="border">Joining Date : ' . $user6->created_at . '</div>
            <div class="border">Activation Date : ' . $user6->activation_timestamp . '</div>';
            $userDetails6 = $user6->userid . '<br>' . $user6->name . '<br>';
        } else {
            $photo6 = 'assets/img/userb.png';
            $a6 = 'href="javascript:void(0);"';
            $span6 = '';
            $userDetails6 = '';
        }
        $user7 = User::where('placement_id', $user3id)->where('placement', 'Right')->first();
        if ($user7 != null) {
            if ($user7->status == 0) {
                $photo7 = 'assets/img/userr.png';
            } elseif ($user7->status == 1) {
                $photo7 = 'assets/img/userg.png';
            } elseif ($user7->status == 2) {
                $photo7 = 'assets/img/usery.png';
            } else {
                $photo7 = $user7->photo;
            }
            $a7 = 'onclick="generateTree(' . $user7->id . ');"';
            $span7 = '<div class="border">Joining Amount : ' . $user7->join_amt . '</div>
            <div class="border">Left Team : ' . $this->leftTeam($user7->id) . '</div>
            <div class="border">Right Team : ' . $this->rightTeam($user7->id) . '</div>
            <div class="border">Joining Date : ' . $user7->created_at . '</div>
            <div class="border">Activation Date : ' . $user7->activation_timestamp . '</div>';
            $userDetails7 = $user7->userid . '<br>' . $user7->name . '<br>';
        } else {
            $photo7 = 'assets/img/userb.png';
            $a7 = 'href="javascript:void(0);"';
            $span7 = '';
            $userDetails7 = '';
        }

        $result .= '<ul class="list-inline">
                        <li>
                            <a ' . $a2 . ' class="tooltipme" style="cursor:pointer; height: 80px; width: 130px; color: #000; padding:0px; font-size: 13px;">
                                <div data-toggle="tooltip" data-placement="top"><img src="' . $photo2 . '" width="40" height="40" class="br-50"></div>
                                ' . $userDetails2 . '
                                <span class="tooltipmetext" tabindex="1">
                                    ' . $span2 . '
                                </span>
                            </a>
                            <ul class="list-inline">
                                <li>
                                    <a ' . $a4 . ' class="tooltipme" style="cursor:pointer; height: 80px; width: 130px; color: #000; padding:0px; font-size: 13px;">
                                        <div data-toggle="tooltip" data-placement="top"><img src="' . $photo4 . '" width="40" height="40" class="br-50"></div>
                                        ' . $userDetails4 . '
                                        <span class="tooltipmetext" tabindex="1">
                                            ' . $span4 . '
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a ' . $a5 . ' class="tooltipme" style="cursor:pointer; height: 80px; width: 130px; color: #000; padding:0px; font-size: 13px;">
                                        <div data-toggle="tooltip" data-placement="top"><img src="' . $photo5 . '" width="40" height="40" class="br-50"></div>
                                        ' . $userDetails5 . '
                                        <span class="tooltipmetext" tabindex="1">
                                            ' . $span5 . '
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a ' . $a3 . ' class="tooltipme" style="cursor:pointer; height: 80px; width: 130px; color: #000; padding:0px; font-size: 13px;">
                                <div data-toggle="tooltip" data-placement="top"><img src="' . $photo3 . '" width="40" height="40" class="br-50"></div>
                                ' . $userDetails3 . '
                                <span class="tooltipmetext" tabindex="1">
                                    ' . $span3 . '
                                </span>
                            </a>
                            <ul class="list-inline">
                                <li>
                                    <a ' . $a6 . ' class="tooltipme" style="cursor:pointer; height: 80px; width: 130px; color: #000; padding:0px; font-size: 13px;">
                                        <div data-toggle="tooltip" data-placement="top"><img src="' . $photo6 . '" width="40" height="40" class="br-50"></div>
                                        ' . $userDetails6 . '
                                        <span class="tooltipmetext" tabindex="1">
                                            ' . $span6 . '
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a ' . $a7 . ' class="tooltipme" style="cursor:pointer; height: 80px; width: 130px; color: #000; padding:0px; font-size: 13px;">
                                        <div data-toggle="tooltip" data-placement="top"><img src="' . $photo7 . '" width="40" height="40" class="br-50"></div>
                                        ' . $userDetails7 . '
                                        <span class="tooltipmetext" tabindex="1">
                                            ' . $span7 . '
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>';

        return $result;
    }

    public function leftTeam($id)
    {
        $user = User::where('placement_id', $id)->where('placement', 'Left')->first();
        if ($user != null) {
            $downline = Downline::where('user_id', $user->id)->get();
            return count($downline) + 1;
        } else {
            return 0;
        }
    }

    public function rightTeam($id)
    {
        $user = User::where('placement_id', $id)->where('placement', 'Right')->first();
        if ($user != null) {
            $downline = Downline::where('user_id', $user->id)->get();
            return count($downline) + 1;
        } else {
            return 0;
        }
    }
}
