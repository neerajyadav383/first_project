@extends('admin.layout.app')
@section('content')
@section('meta_title', 'ROI INCOME')
@section('meta_keyword', 'ROI INCOME')
@section('meta_description', 'ROI INCOME')

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
                        <h4 class="card-title">ROI INCOME</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Sr. no.</th>
                                        <th>User ID</th>
                                        <th>Income Type</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($roiIncomeReport as $incomeReports)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $incomeReports->userDetails['userid'] ?? $incomeReports->user_id }} ( {{ $incomeReports->userDetails['name'] ?? '' }})</td>
                                        <td>{{ $incomeReports->income_type }}</td>
                                        <td>{{ $incomeReports->amount }}</td>
                                        <td>{{ $incomeReports->created_at }}</td>
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