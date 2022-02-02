@extends('admin.layout.app')
@section('content')
@section('meta_title','Beneficiary ID')
@section('meta_keyword','Beneficiary ID')
@section('meta_description','Beneficiary ID')

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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">@yield('meta_title')</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <form action=" {{ url('add_bene_id') }} " method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="bankAccount">Bank Account</label>
                                                <input type="text" class="form-control" id="bankAccount" name="bankAccount" value="{{$user->bank_details->account_no ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="ifsc">IFSC</label>
                                                <input type="text" class="form-control" id="ifsc" name="ifsc" value="{{$user->bank_details->ifsc ?? ''}}" required>
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
                                        <th>User ID</th>
                                        <th>Beneficiary Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Bank Account</th>
                                        <th>IFSC</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Pincode</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($beneficiaryDetail as $report)
                                    <tr>
                                        <td>
                                            <button href="javascript:void(0);" data-toggle="modal" data-target=".bs-example-modal-center" class="btn btn-sm btn-white text-success mr-2" onclick="sendBeneId('{{ $report->beneId }}')"><i class="far fa-paper-plane mr-1"></i> SEND</button>
                                        </td>
                                        <td>{{ $i }}</td>
                                        <td>{{ $report->userDetails['userid'] ?? $report->user_id }} ( {{ $report->userDetails['name'] ?? '' }} )</td>
                                        <td>{{ $report->beneId }}</td>
                                        <td>{{ $report->name }}</td>
                                        <td>{{ $report->email }}</td>
                                        <td>{{ $report->phone }}</td>
                                        <td>{{ $report->bankAccount }}</td>
                                        <td>{{ $report->ifsc }}</td>
                                        <td>{{ $report->address1 }}</td>
                                        <td>{{ $report->city }}</td>
                                        <td>{{ $report->state }}</td>
                                        <td>{{ $report->pincode }}</td>
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
                        <form id="idtransid" method="post" action="{{ url('add_request_transfer') }}">
                            @csrf
                            <div class="form-group">
                                <label for="bene_id">beneId</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="hidden" name="m_id" value="" />
                                    <input type="hidden" name="m_name" value="" />
                                    <input type="text" name="bene_id" value="" id="bene_id" readonly class="form-control">
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
                                    <input type="text" name="amount" value="" placeholder="Enter Withdrawal Amount" id="amount" class="form-control">
                                </div>
                                <div id="errorAmount"></div>
                            </div>
                            <div class="form-group">
                                <label for="transferId">transferId</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="text" name="transferId" value="" id="transferId" readonly class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" onclick="style.display = 'none'" class="btn btn-success btn-rounded waves-effect waves-light" name="transfer" id="transfer" value="Transfer" />
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
    function sendBeneId(beneId) {
        document.getElementById('bene_id').value = beneId;
        document.getElementById('transferId').value = makeid(10);
    }

    function makeid(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }
</script>

@endsection