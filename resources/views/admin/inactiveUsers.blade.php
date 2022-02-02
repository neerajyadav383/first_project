@extends('admin.layout.app')
@section('content')
@section('meta_title', 'Inactive Users')
@section('meta_keyword', 'Inactive Users')
@section('meta_description', 'Inactive Users')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex align-items-center">
                        <h5 class="page-title">Dashboard</h5>
                        <ul class="breadcrumb ml-2">
                            <li class="breadcrumb-item"><a href="index.html"><i
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
                        <h4 class="card-title">Inactive Users</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Sr. no.</th>
                                        <th>Image</th>
                                        <th>Member ID</th>
                                        <th>Member Name</th>
                                        <th>Sponsor Id</th>
                                        <th>Placement Id</th>
                                        <th>Placement</th>
                                        <th>Mobile No.</th>
                                        <th>Email</th>
                                        <th>Activation Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($inactiveUser as $inactiveUsers)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td> 
                                        <h2 class="table-avatar">
                                            <a href="profile.blade.php"><img class="avatar avatar-sm mr-2 avatar-img rounded-circle" src="{{ asset($inactiveUsers->photo)}}" alt="User Image"></a>
                                        </h2>
                                    </td>
                                        <td>{{ $inactiveUsers->userid }}</td>
                                        <td>{{ $inactiveUsers->name }}</td>
                                        <td>{{ $inactiveUsers->sponsorDetails->userid ?? '' }} [{{ $inactiveUsers->sponsorDetails->name ?? '' }}]</td>
                                        <td>{{ $inactiveUsers->placementDetails->userid ?? '' }} [{{ $inactiveUsers->placementDetails->name ?? '' }}]</td>
                                        <td>{{ $inactiveUsers->placement }}</td>
                                        <td>{{ $inactiveUsers->mobile }}</td>
                                        <td>{{ $inactiveUsers->email  }}</td>
                                        <td>{{ $inactiveUsers->created_at }}</td>
                                        <td>{{ $inactiveUsers->status }}</td>
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