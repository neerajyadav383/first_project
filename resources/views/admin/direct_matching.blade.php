@extends('admin.layout.app')
@section('content')
@section('meta_title', 'ROYALTY INCOME')
@section('meta_keyword', 'ROYALTY INCOME')
@section('meta_description', 'ROYALTY INCOME')

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
                        <h4 class="card-title">ROYALTY INCOME</h4>
                    </div>
                    <div class="card-body">
                        {{-- <form action="" method="post">
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
                        <input type="button"class="btn btn-secondary" onclick="export_data('DIRECT MATCHING INCOME','myTable')" value="Export to Excel"> --}}
                        
                        
                        <div class="table-responsive" id="tableRes">
                            <table class="table" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Sr. no.</th>
                                        <th>User ID</th>
                                        <th>Income Type</th>
                                        <th>Amount</th>
                                        <th>By Id</th>
                                        <th>Level</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    @php $i=1; @endphp
                                    @foreach($directMachingIncomeReport as $incomeReports)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $incomeReports->userDetails['userid'] ?? $incomeReports->user_id }} ( {{ $incomeReports->userDetails['name'] ?? '' }})</td>
                                        <td>{{ $incomeReports->income_type }}</td>
                                        <td>{{ $incomeReports->amount }}</td>
                                        <td>{{ $incomeReports->userDetails2['userid'] ?? $incomeReports->by_id }} ( {{ $incomeReports->userDetails2['name'] ?? '' }})</td>
                                        <td>{{ $incomeReports->level  }}</td>
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

<script>
    function searchClosingStatement() {
        var from_date = document.getElementById('from_date').value;
        var to_date = document.getElementById('to_date').value;
        // alert(from_date);
        var url = "<?php echo e(url('search_direct_matching')); ?>";
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
                    // jQuery('#myTable').html(result);
                    console.log(result);
                    var html = '<table class="table" id="myTable"><thead><tr><th>Sr. no.</th><th>User ID</th><th>Income Type</th><th>Amount</th><th>By Id</th><th>Level</th><th>Date</th></tr></thead><tbody id="tbody">';
                    jQuery.each(result.directMachingIncomeReport, function(arrKey, arrVal) {
                        // alert();
                        html += '<tr><td>' + (arrKey+1) +
                                '</td><td>' + arrVal.user_details.userid + ' (' + arrVal.user_details.name + ')' +
                                '</td><td>' + arrVal.income_type + 
                                '</td><td>' + arrVal.amount + 
                                '</td><td>' + arrVal.user_details2.userid + ' (' + arrVal.user_details2.name + ')' +
                                '</td><td>' + arrVal.level + 
                                '</td><td>' + arrVal.created_at + 
                                '</td></tr>';
                    }); 
                    html+= '</tbody></table>';
                    jQuery('#tableRes').html(html);

                },
                error: function(error) {
                    console.log(error.responseText);
                }
            });
    }
</script>

@endsection