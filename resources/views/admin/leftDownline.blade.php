@extends('admin.layout.app')
@section('content')
@section('meta_title', 'Left Downline')
@section('meta_keyword', 'Left Downline')
@section('meta_description', 'Left Downline')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex align-items-center">
                        <h5 class="page-title">Dashboard</h5>
                        <ul class="breadcrumb ml-2">
                            <li class="breadcrumb-item"><a href="dashboard.blade.php"><i class="fas fa-home"></i></a></li>
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
                        <h4 class="card-title">@yield('meta_title')</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th>SN</th>
                                        <th>User ID</th>
                                        <th>User Name</th>
                                        <th>Sponser ID</th>
                                        <th>Placement ID</th>
                                        <th>Placement</th>
                                        <th>Joining Amount</th>
                                        <th>Joining Date</th>
                                        <th>Activation Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($status==1)
                                        @php $i=1;@endphp
                                        @if($left_downline->status==0)
                                        @php $color='text-danger'; @endphp
                                        @elseif($left_downline->status==1)
                                        @php $color='text-success'; @endphp
                                        @elseif($left_downline->status==2)
                                        @php $color='text-warning'; @endphp
                                        @else
                                        @php $color=''; @endphp
                                        @endif
                                        <tr class="{{ $color }}">
                                            <td>{{ $i }}</td>
                                            <td>{{ $left_downline->userid }}</td>
                                            <td>{{ $left_downline->name }}</td>
                                            <td>{{ $left_downline->sponsorDetails->userid }} [{{ $left_downline->sponsorDetails->name }}]</td>
                                            <td>{{ $left_downline->placementDetails->userid }} [{{ $left_downline->placementDetails->name }}]</td>
                                            <td>{{ $left_downline->placement }}</td>
                                            <td>{{ $left_downline->join_amt }}</td>
                                            <td>{{ $left_downline->created_at }}</td>
                                            <td>{{ $left_downline->activation_timestamp }}</td>
                                        </tr>
                                        @php $i++; $downliness = $downlines->downline; @endphp
                                        @foreach($downliness as $getUser)

                                        @if($getUser->userDetails->status==0)
                                        @php $color='text-danger'; @endphp
                                        @elseif($getUser->userDetails->status==1)
                                        @php $color='text-success'; @endphp
                                        @elseif($getUser->userDetails->status==2)
                                        @php $color='text-warning'; @endphp
                                        @else
                                        @php $color=''; @endphp
                                        @endif
                                        <tr class="{{ $color }}">
                                            <td>{{ $i }}</td>
                                            <td>{{ $getUser->userDetails->userid }}</td>
                                            <td>{{ $getUser->userDetails->name }}</td>
                                            <td>{{ $getUser->userDetails->sponsorDetails->userid }} [{{ $getUser->userDetails->sponsorDetails->name }}]</td>
                                            <td>{{ $getUser->userDetails->placementDetails->userid }} [{{ $getUser->userDetails->placementDetails->name }}]</td>
                                            <td>{{ $getUser->userDetails->placement }}</td>
                                            <td>{{ $getUser->userDetails->join_amt }}</td>
                                            <td>{{ $getUser->userDetails->created_at }}</td>
                                            <td>{{ $getUser->userDetails->activation_timestamp }}</td>
                                        </tr>
                                        @php $i++; @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection