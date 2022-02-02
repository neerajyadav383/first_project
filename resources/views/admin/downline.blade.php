@extends('admin.layout.app')
@section('content')
@section('meta_title', 'Downline')
@section('meta_keyword', 'Downline')
@section('meta_description', 'Downline')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex align-items-center">
                        <h5 class="page-title">Dashboard</h5>
                        <ul class="breadcrumb ml-2">
                            <li class="breadcrumb-item"><a href="dashboard.blade.php"><i
                                        class="fas
                                        fa-home"></i></a></li>
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
                        <h4 class="card-title">Downline</h4>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; $downliness = $downlines->downline; @endphp
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


@endsection