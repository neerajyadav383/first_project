@extends('admin.layout.app')
@section('content')
@section('meta_title','Wallet Request')
@section('meta_keyword','Wallet Request')
@section('meta_description','Wallet Request')

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

        @if(Auth::user()->hasRole('user'))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@yield('meta_title')</h5>
                        @if(Session::has('message'))
                        <p class="{{ Session::get('alert-class') }}">
                            {{ Session::get('message') }}
                        </p>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <form action=" {{ url('add_wallet_req') }} " method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="amount">Amount</label>
                                                <input type="number" class="form-control" id="amount" name="amount" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="transaction_id">Transaction ID</label>
                                                <input type="text" class="form-control" id="transaction_id" name="transaction_id" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="screenshot" class="form-label">Upload Payment Screenshot</label>
                                                <input class="form-control form-control-sm" id="screenshot" name="screenshot" type="file">
                                              </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

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
                                        <th>Amount</th>
                                        <th>Transaction ID</th>
                                        <th>Payment Screenshot</th>
                                        <th>Status</th>
                                        <th>Reason</th>
                                        <th>Date</th>
                                        @if(Auth::user()->hasRole('admin'))
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($walletRequestReport as $walletRequest)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $walletRequest->wrUsers['userid'] ?? $walletRequest->user_id }} ( {{ $walletRequest->wrUsers['name'] ?? '' }} )</td>
                                        <td>{{ $walletRequest->amount }}</td>
                                        <td>{{ $walletRequest->trans_id }}</td>
                                        <td><a href="{{ asset($walletRequest->screenshot) }}" target="_blank"><img height="50" src="{{ asset($walletRequest->screenshot) }}"></a></td>
                                        <td id="renewReportStatus{{ $walletRequest->id }}">{{ $walletRequest->status }}</td>
                                        <td id="renewReportReason{{ $walletRequest->id }}">{{ $walletRequest->reason }}</td>
                                        <td>{{ $walletRequest->created_at }}</td>
                                        @if(Auth::user()->hasRole('admin'))
                                        <td id="renewReportAction{{ $walletRequest->id }}">
                                            @if($walletRequest->status=='Pending')
                                            <button href="javascript:void(0);" class="btn btn-sm btn-white text-success mr-2" onclick="approve('{{ $walletRequest->id }}')"><i class="far fa-edit mr-1"></i> Approve</button>
                                            <button href="javascript:void(0);" class="btn btn-sm btn-white text-danger mr-2" onclick="reject('{{ $walletRequest->id }}')"><i class="far fa-trash-alt mr-1"></i> Reject</button>
                                            @endif
                                        </td>
                                        @endif
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
    function reject(id) {
        // alert(id);

        var url = "<?php echo e(url('reject_wallet_req')); ?>";
        var reason = prompt("Enter Reason");

        if (reason != null && reason != '') {
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                type: "POST",
                data: {
                    'id': id,
                    'reason': reason
                },
                context: this,
                success: function(result) {
                    // alert(result);
                    jQuery('#requestMessage').html(result);
                    jQuery('#renewReportAction' + id).html('');
                    jQuery('#renewReportStatus' + id).html('Rejected');
                    jQuery('#renewReportReason' + id).html(reason);
                    console.log(result);
                },
                error: function(error) {
                    console.log(error.responseText);
                }
            });
        }
    }

    function approve(id) {
        // alert(id);

        var url = "<?php echo e(url('approve_wallet_req')); ?>";
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            type: "POST",
            data: {
                'id': id
            },
            context: this,
            success: function(result) {
                // alert(result);
                jQuery('#requestMessage').html(result);
                jQuery('#renewReportAction' + id).html('');
                jQuery('#renewReportStatus' + id).html('Approved');
                console.log(result);
            },
            error: function(error) {
                console.log(error.responseText);
            }
        });
    }
</script>

@endsection