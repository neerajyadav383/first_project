@extends('admin.layout.app')
@section('content')
@section('meta_title', 'Users')
@section('meta_keyword', 'Users')
@section('meta_description', 'Users')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex align-items-center">
                        <h5 class="page-title">Dashboard</h5>
                        <ul class="breadcrumb ml-2">
                            <li class="breadcrumb-item"><a href="index.html"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">@yield('meta_title')</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">List of Users</h4>
                        <p><text class="text-info">User Status:</text> &nbsp; &nbsp; <text class="text-danger">0 -> Inactive</text> &nbsp; &nbsp; <text class="text-success">1 -> Active</text> &nbsp; &nbsp; <text class="text-warning">2 -> Required Renewal</text></p>
                        @if(Session::has('message'))
                        <p class="{{ Session::get('alert-class') }}">
                            {{ Session::get('message') }}
                        </p>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Sr. no.</th>
                                        <th>Image</th>
                                        <th>User ID</th>
                                        <th>User Name</th>
                                        <th>Sponsor Id</th>
                                        <th>Placement Id</th>
                                        <th>Placement</th>
                                        <th>Mobile No.</th>
                                        <th>Email</th>
                                        <th>Activation Date</th>
                                        <th>Total Wallet</th>
                                        <th>Status</th>
                                        <th>Join Amount</th>
                                        <th>Wallet Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($listUser as $listUsers)

                                    @if($listUsers->status==0)
                                    @php
                                    $color='text-danger';
                                    @endphp
                                    @elseif($listUsers->status==1)
                                    @php $color='text-success'; @endphp
                                    @elseif($listUsers->status==2)
                                    @php $color='text-warning'; @endphp
                                    @else
                                    @php $color=''; @endphp
                                    @endif
                                    <tr class="{{ $color }}">
                                        <td>{{ $i }}</td>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="profile.blade.php"><img class="avatar avatar-sm mr-2 avatar-img rounded-circle" src="{{ asset($listUsers->photo)}}" alt="User Image"> </a>
                                            </h2>
                                        </td>
                                        <td>{{ $listUsers->userid }}</td>
                                        <td>{{ $listUsers->name }}</td>
                                        <td>{{ $listUsers->sponsorDetails->userid ?? '' }} [{{ $listUsers->sponsorDetails->name ?? '' }}]</td>
                                        <td>{{ $listUsers->placementDetails->userid ?? '' }} [{{ $listUsers->placementDetails->name ?? '' }}]</td>
                                        <td>{{ $listUsers->placement }}</td>
                                        <td>{{ $listUsers->mobile }}</td>
                                        <td>{{ $listUsers->email  }}</td>
                                        <td>{{ $listUsers->created_at }}</td>
                                        <td>{{ $listUsers->wallet }} <a class="btn btn-sm btn-primary" href="{{url('wallet_update')}}?id={{$listUsers->id }}">Wallet Update</a></td>
                                        <td>{{ $listUsers->status }}</td>
                                        <td>{{ $listUsers->join_amt }}</td>
                                        <?php 
                                            if($listUsers->wallet_lock=='Unlock'){
                                                $wallet_status = 'Lock';
                                            } else {
                                                $wallet_status = 'Unlock';
                                            }
                                        ?>
                                        <td id="wallet_status{{ $listUsers->id }}">{{ $listUsers->wallet_lock }}  <button class="btn btn-sm btn-primary" onclick="walletLock('{{ $listUsers->id }}', '{{ $listUsers->userid }}', '{{ $wallet_status }}')">Wallet {{ $wallet_status }}</button></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="{{url('virtual_power')}}?id={{$listUsers->id }}">Virtual Power</a>
                                                    <a class="dropdown-item" href="{{url('edit_profile')}}?id={{$listUsers->id }}">Edit Profile</a>
                                                    <a class="dropdown-item" href="{{url('change_password')}}?id={{$listUsers->id }}">Edit Password</a>
                                                    <form class="dropdown-item" method="post" action="{{ url('/login_member') }}">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $listUsers->id }}">
                                                        <button class="btn" type="submit" style="width: inherit;padding: 0;text-align: left;">Login</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function walletLock(id, userId, walletStatus) {
        var c = confirm("Are you sure want to "+walletStatus+" wallet of "+userId);
        if (c == true) {
            var url = "<?php echo e(url('wallet_lock')); ?>";
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                type: "POST",
                data: {
                    'id': id, 'user_id': userId, 'wallet_status': walletStatus
                },
                context: this,
                success: function(result) {
                    alert(result);
                    jQuery('#wallet_status' + id).html(result);
                },
                error: function(error) {
                    console.log(error.responseText);
                }
            });
        }
    }
</script>

@endsection