@extends('admin.layout.app')
@section('content')
@section('meta_title','E-Life | Dashboard')
@section('meta_keyword','E-Life | Dashboard')
@section('meta_description','E-Life | Dashboard')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'>

<style>
    .swal2-popup{
        background-color: #343a40 !important;
    }
    .swal2-container.swal2-shown {
    background-color: rgb(255 165 0 / 40%);
}
</style>

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="row calender-col">
            <div class="col-xl-12 d-flex">
                <div class="card flex-fill text-white details-box" style="background-color: #263940;">
                    <div class="card-header" style="background-color: #0688b9;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-white">
                                @if(Auth::user()->hasRole('admin'))
                                Users
                                @else
                                Downline
                                @endif
                                Details
                            </h5>
                            <!-- <div class="dropdown" data-toggle="dropdown">
                                <a href="javascript:void(0);" class="btn btn-white btn-sm dropdown-toggle" role="button" data-toggle="dropdown">
                                    This Week
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="javascript:void(0);" class="dropdown-item">Today</a>
                                    <a href="javascript:void(0);" class="dropdown-item">This Week</a>
                                    <a href="javascript:void(0);" class="dropdown-item">This Month</a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap flex-md-nowrap">
                            <div class="w-100 d-md-flex align-items-center mb-3 chart-count row">
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6 text-center">
                                    <span>Total @if(Auth::user()->hasRole('admin')) Users @else Downline @endif</span>
                                    <p class="h4 text-info">{{ $downlineDetails['allUser'] }}</p>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6 text-center">
                                    <span>Active @if(Auth::user()->hasRole('admin')) Users @else Downline @endif</span>
                                    <p class="h4 text-success">{{ $downlineDetails['activeUser'] }}</p>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6 text-center">
                                    <span>Inactive @if(Auth::user()->hasRole('admin')) Users @else Downline @endif</span>
                                    <p class="h4 text-danger">{{ $downlineDetails['inactiveUser'] }}</p>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6 text-center">
                                    <span>Direct Active Users</span>
                                    <p class="h4 text-warning">{{ $downlineDetails['directActiveUser'] }}</p>
                                </div>
                            </div>
                        </div>
                        <hr style="background-color: white;">
                        <div class="d-flex align-items-center justify-content-between flex-wrap flex-md-nowrap">
                            <div class="w-100 d-md-flex align-items-center mb-3 chart-count row">
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6 text-center">
                                    <span>Left Team</span>
                                    <p class="h4">{{ $business['leftT'] }}</p>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6 text-center">
                                    <span>Left Bussiness</span>
                                    <p class="h4">{{ $business['leftB'] }}</p>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6 text-center">
                                    <span>Right Team</span>
                                    <p class="h4">{{ $business['rightT'] }}</p>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6 col-6 text-center">
                                    <span>Right Bussiness</span>
                                    <p class="h4">{{ $business['rightB'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div id="dsh_chart_ex_column"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box2" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">ROI Income</h4>
                            <h2 class="text-white">{{ $dashboard->roi+$dashboard2->roi }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box1" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Direct Income</h4>
                            <h2 class="text-white">{{ $dashboard->direct+$dashboard2->direct }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box3" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Binary Income</h4>
                            <h2 class="text-white">{{ $dashboard->matching+$dashboard2->matching }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box4" style="background-color: #262626;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Royalty Income</h4>
                            <h2 class="text-white">{{ $dashboard->direct_team_matching+$dashboard2->direct_team_matching }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box1" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Reward Income</h4>
                            <h2 class="text-white">{{ $dashboard->reward+$dashboard2->reward }}</h2>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box6" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Total Income</h4>
                            <h2 class="text-white">{{ $dashboard->total_income+$dashboard2->total_income }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box2" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Wallet Amount</h4>
                            <h2 class="text-white">{{ $dashboard->wallet }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box4" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Topup Wallet</h4>
                            <h2 class="text-white">{{ $dashboard->topup_wallet }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box3" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Withdrawal Amount</h4>
                            <h2 class="text-white">{{ $withdrwalAmount }}</h2>
                        </div>
                    </div>
                </div>
            </div> --}}
            
            @if(Auth::user()->hasRole('admin'))
            <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box3" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">API Balance</h4>
                            <h2 class="text-white">{{ $payoutApiAmount }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box5" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Closing</h4>
                            <h2 class="text-white" id="closing"><button onclick="closing()" class="btn btn-danger btn-sm">CLOSING</button></h2>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box6" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">ROI Closing</h4>
                            <h2 class="text-white" id="roi"><button onclick="roi()" class="btn btn-danger btn-sm">CLOSING</button></h2>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box1" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Check Booster</h4>
                            <h2 class="text-white" id="checkBooster"><button onclick="checkBooster()" class="btn btn-danger btn-sm">CLOSING</button></h2>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap">
                <div class="card details-box detail-box2" style="background-color: #263940;">
                    <div class="card-body">
                        <div class="dash-contetnt">
                            <div class="mb-3">
                                <img src="assets/img/icons/operating.svg" alt="" width="26">
                            </div>
                            <h4 class="text-white">Booster Closing</h4>
                            <h2 class="text-white" id="booster"><button onclick="booster()" class="btn btn-danger btn-sm">CLOSING</button></h2>
                        </div>
                    </div>
                </div>
            </div> --}}
            @endif
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card flex-fill text-white details-box" style="background-color: #8dc741;">
                    <div class="card-body" onclick="copyReferalLink('https://e-life.co.in/register?id={{Auth::user()->userid}}&placement=Left')" style="cursor: pointer;">
                        <div class="text-center mb-3">
                            <span>Left Referral Link</span>
                            <p class="text-white"><a href="javascript:void(0)" class="text-black" style="font-size: 12px;" id="left_link">https://e-life.co.in/register?id={{Auth::user()->userid}}&placement=Left</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card flex-fill text-white details-box" style="background-color: #8dc741;">
                    <div class="card-body" onclick="copyReferalLink('https://e-life.co.in/register?id={{Auth::user()->userid}}&placement=Right')" style="cursor: pointer;">
                        <div class="text-center mb-3">
                            <span>Right Referral Link</span>
                            <p class="text-white"><a href="javascript:void(0)" class="text-black" style="font-size: 12px;" id="right_link">https://e-life.co.in/register?id={{Auth::user()->userid}}&placement=Right</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12" style="margin-bottom: 50px;">
                <img src="assets/img/trade.jpeg" alt="" style="width: 100%; max-width: 250px; height: auto; border-radius: 15px;">
            </div>
        </div>


    </div>
</div>

<script>
    function closing() {
        var c = confirm("Do you make sure to start the closing");
        if (c == true) {
            document.getElementById("closing").innerHTML = '<i class="fas fa-spinner fa-pulse font-size-24"></i>';
            var url = "<?php echo e(url('closing')); ?>";
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                type: "GET",
                data: {
                    'id': ''
                },
                context: this,
                success: function(result) {
                    alert(result);
                    window.location.reload(true);
                },
                error: function(error) {
                    console.log(error.responseText);
                }
            });
        }
    }

    function roi() {
        var c = confirm("Do you make sure to start the ROI closing");
        if (c == true) {
            document.getElementById("roi").innerHTML = '<i class="fas fa-spinner fa-pulse font-size-24"></i>';
            // var url = "<?php echo e(url('roi')); ?>";
            // $.ajax({
            //     url: url,
            //     headers: {
            //         'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            //     },
            //     type: "POST",
            //     data: {
            //         'id': ''
            //     },
            //     context: this,
            //     success: function(result) {
            //         alert(result);
            //         window.location.reload(true);
            //     },
            //     error: function(error) {
            //         console.log(error.responseText);
            //     }
            // });
        }
    }

    function checkBooster() {
        var c = confirm("Do you make sure to start the Check Booster Users");
        if (c == true) {
            document.getElementById("checkBooster").innerHTML = '<i class="fas fa-spinner fa-pulse font-size-24"></i>';
            // var url = "<?php echo e(url('checkBooster')); ?>";
            // $.ajax({
            //     url: url,
            //     headers: {
            //         'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            //     },
            //     type: "POST",
            //     data: {
            //         'id': ''
            //     },
            //     context: this,
            //     success: function(result) {
            //         alert(result);
            //         window.location.reload(true);
            //     },
            //     error: function(error) {
            //         console.log(error.responseText);
            //     }
            // });
        }
    }

    function booster() {
        var c = confirm("Do you make sure to start the Booster closing");
        if (c == true) {
            document.getElementById("booster").innerHTML = '<i class="fas fa-spinner fa-pulse font-size-24"></i>';
            // var url = "<?php echo e(url('booster')); ?>";
            // $.ajax({
            //     url: url,
            //     headers: {
            //         'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            //     },
            //     type: "POST",
            //     data: {
            //         'id': ''
            //     },
            //     context: this,
            //     success: function(result) {
            //         alert(result);
            //         window.location.reload(true);
            //     },
            //     error: function(error) {
            //         console.log(error.responseText);
            //     }
            // });
        }
    }

    function copyReferalLink(copyText) {
        // variable content to be copied
        // var copyText = document.getElementById(link).innerHTML;
        // alert(copyText);
        // create an input element
        let input = document.createElement('input');
        // setting it's type to be text
        input.setAttribute('type', 'text');
        // setting the input value to equal to the text we are copying
        input.value = copyText;
        // appending it to the document
        document.body.appendChild(input);
        // calling the select, to select the text displayed
        // if it's not in the document we won't be able to
        input.select();
        // calling the copy command
        document.execCommand("copy");
        // removing the input from the document
        document.body.removeChild(input)
        alert("Referral link copied.");
    }

</script>

<script>
    var offerstatus = <?php echo json_encode($offerstatus); ?>;
    // alert(offerstatus);
    if(offerstatus==1){
    var offer = <?php echo json_encode($offer); ?>;
    // alert(offer);
    console.log(offer);
        swal({
            title: "",
            html: '<div class="bg-dark"><div class="text-center mb-2"><img src="./assets/img/logo.png" height="50" alt=""/></div>\n\
                    <div class="text-center bg-warning text-white p-1 mb-2 rounded">'+offer.title+'!</div>\n\
                    <div class="text-center"><img src="'+offer.image+'" style="width: 100%" alt=""/></div>\n\
                    <div class="text-center"></div></div>',
            timer: 90000,
            showCancelButton: true,
            showCloseButton: true,
            showConfirmButton: false
        });
    }
</script>

@endsection