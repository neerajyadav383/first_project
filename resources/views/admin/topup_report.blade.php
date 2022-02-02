@extends('admin.layout.app')
@section('content')
@section('meta_title','Topup Report')
@section('meta_keyword','Topup Report')
@section('meta_description','Topup Report')

<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex align-items-center">
                        <h5 class="page-title">Dashboard</h5>
                        <ul class="breadcrumb ml-2">
                            <li class="breadcrumb-item"><a href="index.html"><i class="fas
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
                        <h4 class="card-title">@yield('meta_title')</h4>
                        <p id="requestMessage" class="text-warning"></p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable table table-stripped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User ID</th>
                                        <th>Topup Type</th>
                                        <th>Amount</th>
                                        <th>Topup By ID</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($topup_reports as $topup_report)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $topup_report->userDetails['userid'] ?? $topup_report->user_id }} ( {{ $topup_report->userDetails['name'] ?? '' }})</td>
                                        <td>{{ $topup_report->topup_type }}</td>
                                        <td>{{ $topup_report->amount }}</td>
                                        <td>{{ $topup_report->userDetails2['userid'] ?? $topup_report->topupby_id }} ( {{ $topup_report->userDetails2['name'] ?? '' }})</td>
                                        <td>{{ $topup_report->created_at }}</td>
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