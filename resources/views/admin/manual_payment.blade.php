@extends('admin.layout.app')
@section('content')
@section('meta_title','Manual Payment')
@section('meta_keyword','Manual Payment')
@section('meta_description','Manual Payment')

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
                                        <th>Action</th>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>wallet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>
                                            <button href="javascript:void(0);" data-toggle="modal" data-target=".bs-example-modal-center" class="btn btn-sm btn-white text-success mr-2" onclick="sendBeneId('{{ $report->id }}', '{{ $report->wallet }}')"><i class="far fa-paper-plane mr-1"></i> SEND</button>
                                        </td>
                                        <td>{{ $i }}</td>
                                        <td>{{ $report->user_id }} ( {{ $report->name }} )</td>
                                        <td>{{ $report->email }}</td>
                                        <td>{{ $report->mobile }}</td>
                                        <td>{{ $report->wallet }}</td>
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

        <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0 text-dark">Transfer Money</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="idtransid" method="post" action="{{ url('post_manual_payment') }}">
                            @csrf
                            <div class="form-group">
                                <label for="main_wallet">Main Wallet</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="hidden" name="m_id" id="m_id" value="" />
                                    <input type="hidden" name="m_name" value="" />
                                    <input type="text" name="main_wallet" value="" id="main_wallet" readonly class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="amount">Withdrawal Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="number" name="amount" value="" placeholder="Enter Withdrawal Amount" onkeyup="checkWallet(this.value)" id="amount" class="form-control">
                                </div>
                                <div id="errorAmount"></div>
                            </div>
                            <div class="form-group">
                                <label for="remaining_wallet">Remaining Wallet</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="remaining_wallet" value="" id="remaining_wallet" readonly class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success btn-rounded waves-effect waves-light" name="transfer" id="transfer" value="Transfer" onclick="style.display = 'none'" />
                                <span id="info_msg"></span>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>
</div>

<script>
    function sendBeneId(user_id, wallet) {
        document.getElementById('m_id').value = user_id;
        document.getElementById('main_wallet').value = wallet;
    }

    function checkWallet(amount){
        if(amount==""){
            amount = 0;
        }
        amount = parseFloat(amount);
        var wallet = document.getElementById('main_wallet').value;
        wallet = parseFloat(wallet);
        if(amount <= wallet){
            var remamt = wallet-amount;
            document.getElementById('remaining_wallet').value = remamt;
        } else {
            document.getElementById('amount').value=wallet;
            document.getElementById('remaining_wallet').value=0;
            alert('The amount should not exceed the wallet amount');
        }
    }
</script>

@endsection