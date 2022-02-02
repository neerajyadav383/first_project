@extends('admin.layout.app')
@section('content')
@section('meta_title','Topup ID')
@section('meta_keyword','Topup ID')
@section('meta_description','Topup ID')

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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @php $idd = $id ?? 'NOT'; @endphp
                        <h5 class="card-title">@yield('meta_title')</h5>
                        @if(Session::has('message'))
                        <p class="{{ Session::get('alert-class') }}">
                            {{ Session::get('message') }}
                        </p>
                        @endif
                        <p id="requestMessage" class="text-warning"></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <form action="" method="POST">
                                    
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="topup_id">User ID</label>
                                                <input type="text" class="form-control" id="topup_id" name="topup_id" oninput="get_username(this.value);">
                                                <span id="username"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="renewal_amt">Renewal Amount</label>
                                                <select class="form-control" id="renewal_amt" name="renewal_amt">
                                                    <option>800</option>
                                                    <option>12000</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right" id="submitButton">
                                        <button type="button" onclick="activate()" class="btn btn-primary">Submit</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function activate() {
        var topup_id = document.getElementById('topup_id').value;
        var renewal_amt = document.getElementById('renewal_amt').value;
        // alert(topup_id);
        if (topup_id != null && topup_id != '' && renewal_amt != '' && renewal_amt != null) {
            var url = "<?php echo e(url('add_topup_id')); ?>";
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                type: "POST",
                data: {
                    'topup_id': topup_id,
                    'renewal_amt': renewal_amt
                },
                context: this,
                success: function(result) {
                    resultArr = result.split('||||');
                    result = resultArr[0];
                    // alert(topup_id + "  " + result);
                    console.log(result);
                    jQuery('#requestMessage').html(topup_id + "  " + result);
                    jQuery('#submitButton').html('');

                    //distribute_income
                    if (result.trim() == 'ID has been activated successfully.') {
                        var id = resultArr[1];
                        // alert(id + '   call second function');
                        var url2 = "<?php echo e(url('topup_distribute_income')); ?>";
                        $.ajax({
                            url: url2,
                            headers: {
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                            },
                            type: "POST",
                            data: {
                                'id': id,
                                'renewal_amt': renewal_amt
                            },
                            context: this,
                            success: function(result) {
                                // alert(result);
                            },
                            error: function(error) {
                                console.log(error.responseText);
                            }
                        });
                    }
                    //distribute_income
                },
                error: function(error) {
                    console.log(error.responseText);
                }
            });
        }
    }
</script>


<script type="text/javascript">
    function get_username(id) {
        var userid = id;
        var url = "{{ url('get_username') }}";

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: "POST",
            data: {
                'userid': userid
            },
            context: this,
            success: function(result) {
                var html = '';
                if (result.success == 1) {
                    html += '<span class="text-success">' + result.user.name + '</span>';
                }
                jQuery('#username').html(html);
                console.log(result);
            },
            error: function(error) {
                console.log(error.responseText);
            }
        });
    }
</script>


@endsection