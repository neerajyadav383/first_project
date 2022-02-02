@extends('admin.layout.app')
@section('content')
@section('meta_title', 'Direct Users')
@section('meta_keyword', 'Direct Users')
@section('meta_description', 'Direct Users')

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
                        <h4 class="card-title">Direct Users</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Sr. no.</th>
                                        <th>User ID</th>
                                        <th>User Name</th>
                                        <th>Status</th>
                                        <th>Joining Date</th>
                                        <th>Activation Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($directUser as $directUsers)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $directUsers->userid }}</td>
                                        <td>{{ $directUsers->name }}</td>
                                        <td>{{ $directUsers->status }}</td>
                                        <td>{{ $directUsers->created_at }}</td>
                                        <td>{{ $directUsers->activation_timestamp }}</td>
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