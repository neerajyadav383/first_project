@extends('admin.layout.app')
@section('content')
@section('meta_title','Withdrwal Report')
@section('meta_keyword','Withdrwal Report')
@section('meta_description','Withdrwal Report')

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
                    @if(Session::has('message'))
                    <p class="{{ Session::get('alert-class') }}">
                        {{ Session::get('message') }}
                    </p>
                    @endif
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
                                        <th>Reference Id</th>
                                        <th>Bank Account</th>
                                        <th>IFSC</th>
                                        <th>BeneId</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Utr</th>
                                        <th>Added On</th>
                                        <th>Processed On</th>
                                        <th>Transfer Mode</th>
                                        <th>Acknowledged</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($payoutDetail as $report)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $report->userDetails['userid'] ?? $report->user_id }} ( {{ $report->userDetails['name'] ?? '' }} )</td>
                                        <td>{{ $report->referenceId }}</td>
                                        <td>{{ $report->bankAccount }}</td>
                                        <td>{{ $report->ifsc }}</td>
                                        <td>{{ $report->beneId }}</td>
                                        <td>{{ $report->amount }}</td>
                                        <td>{{ $report->status }}</td>
                                        <td>{{ $report->utr }}</td>
                                        <td>{{ $report->addedOn }}</td>
                                        <td>{{ $report->processedOn }}</td>
                                        <td>{{ $report->transferMode }}</td>
                                        <td>{{ $report->acknowledged }}</td>
                                        <td>{{ $report->created_at }}</td>
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