@extends('admin.layout.app')
@section('content')
@section('meta_title','Closing Statement')
@section('meta_keyword','Closing Statement')
@section('meta_description','Closing Statement')

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
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-4">From Date</div>
                                <div class="col-md-4">To Date</div>
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="date" name="from_date" id="from_date" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="date" name="to_date" id="to_date" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary" onclick="searchClosingStatement()">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <input type="button"class="btn btn-secondary" onclick="export_data('Closing','myTable')" value="Export to Excel">
                        
                        <div class="table-responsive">
                            <table class="datatable table table-stripped" id="myTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User ID</th>
                                        <th>ROI Income</th>
                                        {{-- <th>Booster Income</th> --}}
                                        <th>Direct Income</th>
                                        <th>Binary Income</th>
                                        <th>Royalty Income</th>
                                        {{-- <th>Reward Income</th> --}}
                                        <th>Total Income</th>
                                        <th>TDS</th>
                                        <th>Wallet Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($closing_statement as $closingStatement)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $closingStatement->userDetails['userid'] ?? $closingStatement->user_id }} ( {{ $closingStatement->userDetails['name'] ?? '' }})</td>
                                        <td>{{ $closingStatement->roi }}</td>
                                        {{-- <td>{{ $closingStatement->booster }}</td> --}}
                                        <td>{{ $closingStatement->direct }}</td>
                                        <td>{{ $closingStatement->matching }}</td>
                                        <td>{{ $closingStatement->direct_team_matching }}</td>
                                        {{-- <td>{{ $closingStatement->reward }}</td> --}}
                                        <td>{{ $closingStatement->total_amount }}</td>
                                        <td>{{ $closingStatement->tds }}</td>
                                        <td>{{ $closingStatement->avail_amount }}</td>
                                        <td>{{ $closingStatement->created_at }}</td>
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
    function searchClosingStatement() {
        var from_date = document.getElementById('from_date').value;
        var to_date = document.getElementById('to_date').value;
        var url = "<?php echo e(url('search_closing_statement')); ?>";
        $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                type: "POST",
                data: {
                    'from_date': from_date,
                    'to_date': to_date,
                },
                context: this,
                success: function(result) {
                    // alert(result);
                    console.log(result);
                    var html = '';
                    jQuery.each(result.closing_statement, function(arrKey, arrVal) {
                        // alert();
                        html += '<tr><td>' + (arrKey+1) +
                                '</td><td>' + arrVal.user_details.userid + ' (' + arrVal.user_details.name + ')' +
                                '</td><td>' + arrVal.roi + 
                                '</td><td>' + arrVal.direct + 
                                '</td><td>' + arrVal.matching + 
                                '</td><td>' + arrVal.direct_team_matching + 
                                '</td><td>' + arrVal.reward + 
                                '</td><td>' + arrVal.total_amount + 
                                '</td><td>' + arrVal.tds + 
                                '</td><td>' + arrVal.avail_amount + 
                                '</td><td>' + arrVal.created_at + 
                                '</td></tr>';
                    }); 
                    jQuery('#tbody').html(html);

                },
                error: function(error) {
                    console.log(error.responseText);
                }
            });
    }
</script>

@endsection