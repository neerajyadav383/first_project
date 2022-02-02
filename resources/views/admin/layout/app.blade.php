<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>@yield('meta_title')</title>
    <meta name="description" content="@yield('meta_description')">
    <meta name="keywords" content="@yield('meta_keyword')">

    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/img/favicon.png')}}">

    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome/css/all.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/plugins/simple-calendar/simple-calendar.css')}}">

    <link rel="stylesheet" href="{{asset('assets/css/feather.css')}}">

    <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/datatables.min.css')}}">

    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>

    <!-- <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/css/jquery.dataTables_themeroller.css">

    <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.0/jquery.dataTables.min.js"></script> -->

    <style>
        body {
        background:
            /* url('assets/img/bg_anim.gif') 
            top center / 100% 100% 
            no-repeat 
            fixed 
            padding-box 
            content-box  */
            #5b6871;
        }
    </style>
</head>

<body>

    <div class="main-wrapper">

        <div class="header">

            <div class="header-left">
                <a href="{{ url('dashboard') }}" class="logo">
                    <img src="{{asset('assets/img/logo.png')}}" alt="Logo">
                </a>
                <a href="{{ url('dashboard') }}" class="logo logo-small">
                    <img src="{{asset('assets/img/logo-small.png')}}" alt="Logo" width="30" height="30">
                </a>
            </div>

            <a href="javascript:void(0);" id="toggle_btn"> <i class="fas fa-bars"></i>
            </a>
            <!-- <div class="top-nav-search">
                <form>
                    <input type="text" class="form-control" placeholder="Search here">
                    <button class="btn" type="submit"><i class="fa fa-search"></i>
                    </button>
                </form>
            </div> -->

            <a class="mobile_btn" id="mobile_btn"> <i class="fas fa-bars"></i>
            </a>


            <ul class="nav user-menu">

                <!-- <li class="nav-item dropdown">
                    <a href="#" class="nav-link notifications-item">
                        <i class="feather-bell"></i> <span class="badge badge-pill">3</span>
                    </a>
                </li> -->

                <li class="nav-item dropdown has-arrow main-drop ml-md-3">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <span class="user-img">
                            <span class="status online">{{ Auth::user()->userid; }}
                                <img src="{{asset(Auth::user()->photo ?? 'assets/img/user.png')}}" alt="">
                                <span class="status online"><i class="fas fa-caret-down"></i></span>
                            </span>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0)"> {{ Auth::user()->name; }} [&#8377; {{ Auth::user()->join_amt; }}]</a>
                        <a class="dropdown-item" href="{{ url('/profile') }}"><i class="feather-user"></i> My Profile</a>
                        <a class="dropdown-item" href="{{ url('/logout') }}"><i class="feather-power"></i> Logout</a>
                    </div>
                </li>
            </ul>

        </div>


        <div class="sidebar" id="sidebar">
            @php $pagename = \Request::route()->getName(); @endphp
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="menu-title"> <span>{{ Auth::user()->userid; }} [&#8377; {{ Auth::user()->join_amt; }}]
                                <br>{{ Auth::user()->name; }}</span>
                        </li>
                        <li class="@if($pagename=='dashboard') {{'active'}} @endif"> <a href="{{ url('/dashboard') }}"><i class="feather-home"></i><span class="shape1"></span><span class="shape2"></span><span>Dashboard</span></a>
                        </li>
                        <li class="@if($pagename=='notification') {{'active'}} @endif"><a href="{{ url('/notification') }}"><i class="fa fa-bell"></i> <span>Notification</span></a>
                        </li>
                        @if(Auth::user()->hasRole('admin'))
                        <li class="@if($pagename=='offer') {{'active'}} @endif"><a href="{{ url('/offer') }}"><i class="fa fa-gift"></i> <span>Offer</span></a>
                        </li>
                        @endif
                        
                        <li class="@if($pagename=='topup_id') {{'active'}} @endif"><a href="{{ url('/topup_id') }}"><i class="fa fa-toggle-on"></i> <span>Topup Your ID</span></a></li>

                        {{-- @if(Auth::user()->status == '2') --}}
                        {{-- <li class="@if($pagename=='renewal_id') {{'active'}} @endif"><a href="{{ url('/renewal_id') }}"><i class="fa fa-toggle-on"></i> <span>Renewal Your ID</span></a></li> --}}
                        {{-- @endif --}}
                        
                        <li class="submenu @if($pagename=='profile' || $pagename=='change_password') {{'active'}} @endif"><a href="#"><i class="feather-user"></i> <span>Profile</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a class="@if($pagename=='profile') {{'active'}} @endif" href="{{ url('/profile') }}">Profile</a></li>
                                <li><a class="@if($pagename=='change_password') {{'active'}} @endif" href="{{ url('/change_password') }}">Change Password</a></li>
                            </ul>
                        </li>
                        <li class="@if($pagename=='user_registration') {{'active'}} @endif"><a href="{{ url('/user_registration') }}"><i class="feather-user"></i> <span>User Registration</span></a>
                        </li>
                        @if(Auth::user()->hasRole('admin'))
                        <li class="submenu @if($pagename=='users' || $pagename=='inactive_user') {{'active'}} @endif"><a href="#"><i class="fa fa-users"></i> <span>Users</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a class="@if($pagename=='users') {{'active'}} @endif" href="{{ url('/users') }}">Users</a></li>
                                <li><a class="@if($pagename=='inactive_user') {{'active'}} @endif" href="{{ url('/inactive_user') }}">Inactive Users</a></li>
                            </ul>
                        </li>
                        @endif
                        <li class="submenu @if($pagename=='direct_user' || $pagename=='left_downline' || $pagename=='right_downline' || $pagename=='downline' || $pagename=='tree_view') {{'active'}} @endif"><a href="#"><i class="fa fa-layer-group"></i> <span>Genealogy</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a class="@if($pagename=='direct_user') {{'active'}} @endif" href="{{ url('/direct_user') }}">Direct Users</a></li>
                                <li><a class="@if($pagename=='left_downline') {{'active'}} @endif" href="{{ url('/left_downline') }}">Left Downline</a></li>
                                <li><a class="@if($pagename=='right_downline') {{'active'}} @endif" href="{{ url('/right_downline') }}">Right Downline</a></li>
                                <li><a class="@if($pagename=='downline') {{'active'}} @endif" href="{{ url('/downline') }}">Downline</a></li>
                                <li><a class="@if($pagename=='tree_view') {{'active'}} @endif" href="{{ url('/tree_view') }}">Tree View</a></li>
                            </ul>
                        </li>
                        <li class="@if($pagename=='wallet_request') {{'active'}} @endif"><a href="{{ url('/wallet_request') }}"><i class="fa fa-wallet"></i> <span>Wallet Request</span></a>
                        </li>
                        <li class="@if($pagename=='investment') {{'active'}} @endif"><a href="{{ url('/investment') }}"><i class="fa fa-wallet"></i> <span>Investment</span></a>
                        </li>
                        <li class="@if($pagename=='closing_statement') {{'active'}} @endif"><a href="{{ url('/closing_statement') }}"><i class="fa fa-times"></i> <span>Closing Statement</span></a>
                        </li>
                        @if(Auth::user()->wallet_lock == 'Unlock')
                        <li class="submenu @if($pagename=='bene_report' || $pagename=='withdrwal_report') {{'active'}} @endif"><a href="#"><i class="fas fa-money-check-alt"></i> <span>Withdrwal</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a class="@if($pagename=='bene_report') {{'active'}} @endif" href="{{ url('/bene_report') }}">Beneficiary ID</a></li>
                                <li><a class="@if($pagename=='withdrwal_report') {{'active'}} @endif" href="{{ url('/withdrwal_report') }}">Withdrawal Report</a></li>
                                {{-- @if(Auth::user()->hasRole('admin')) --}}
                                {{-- <li><a class="@if($pagename=='manual_payment') {{'active'}} @endif" href="{{ url('/manual_payment') }}">Manual Payment</a></li> --}}
                                {{-- @endif --}}
                            </ul>
                        </li>
                        @endif
                        <li class="submenu @if($pagename=='re_topup_report' || $pagename=='topup_report' || $pagename=='roi_report' || $pagename=='booster_report' || $pagename=='direct_report' || $pagename=='matching_report' || $pagename=='direct_matching_report') {{'active'}} @endif"><a href="#"><i class="feather-file"></i> <span>Report</span> <span class="menu-arrow"></span></a>
                            <ul style="display: none;">
                                <li><a class="@if($pagename=='topup_report') {{'active'}} @endif" href="{{url('/topup_report')}}">TOPUP Report</a></li>
                                {{-- <li><a class="@if($pagename=='re_topup_report') {{'active'}} @endif" href="{{url('/re_topup_report')}}">RE-TOPUP Report</a></li> --}}
                                <li><a class="@if($pagename=='roi_report') {{'active'}} @endif" href="{{url('/roi_report')}}">ROI Report</a></li>
                                {{-- <li><a class="@if($pagename=='booster_report') {{'active'}} @endif" href="{{url('/booster_report')}}">Booster Report</a></li> --}}
                                <li><a class="@if($pagename=='direct_report') {{'active'}} @endif" href="{{url('/direct_report')}}">Direct Report</a></li>
                                <li><a class="@if($pagename=='matching_report') {{'active'}} @endif" href="{{url('/matching_report')}}">Binary Income Report</a></li>
                                <li><a class="@if($pagename=='direct_matching_report') {{'active'}} @endif" href="{{url('/direct_matching_report')}}">Royalty Report</a></li>
                                {{-- <li><a class="@if($pagename=='reward_report') {{'active'}} @endif" href="{{url('/reward_report')}}">Reward Report</a></li> --}}
                              {{-- @if(Auth::user()->hasRole('admin')) --}}
                                {{-- <li><a class="@if($pagename=='virtual_power_report') {{'active'}} @endif" href="{{url('/virtual_power_report')}}">Virtual Power Report</a></li> --}}
                              {{-- @endif --}}
                            </ul>
                        </li>

                        <li>.</li>
                        <li>.</li>
                        <li>.</li>
                        <li>.</li>
                        <li>.</li>

                    </ul>
                </div>
            </div>
        </div>











        @yield('content')













        <div class="notifications">
            <div class="topnav-dropdown-header">
                <span class="notification-title">Notifications</span>
                <a href="javascript:void(0)" class="clear-noti"> <i class="feather-x-circle"></i> </a>
            </div>
            <div class="noti-content">
                <ul class="notification-list">

                    <li class="notification-message">
                        <a href="#">
                            <div class="media">
                                <!-- <span class="avatar">
                                    <img alt="" src="{{asset('assets/img/profiles/avatar-02.jpg')}}" class="rounded-circle">
                                </span> -->
                                <div class="media-body">
                                    <p class="noti-details"><span class="noti-title">New Registration</span> M7700 is a new registered</p>
                                    <p class="noti-time"><span class="notification-time">4 mins ago</span></p>
                                </div>
                            </div>
                        </a>
                    </li>

                </ul>
            </div>
            <div class="topnav-dropdown-footer">
                <a href="javascript:void(0);">View all Notifications</a>
            </div>
        </div>

    </div>


    <script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>

    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset('assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>

    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/datatables.min.js')}}"></script>

    <script src="{{asset('assets/js/script.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

    
{{-- export to excel --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
    function export_data(page, id) {
        let data = document.getElementById(id);
        var fp = XLSX.utils.table_to_book(data, {
            sheet: '' + page + 'MYSO_WORLD'
        });
        XLSX.write(fp, {
            bookType: 'xlsx',
            type: 'base64'
        });
        XLSX.writeFile(fp, '' + page + ' MYSO_WORLD.xlsx');
    }
</script>
{{-- export to excel --}}

</body>

</html>