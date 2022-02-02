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
                                <form action="{{ url('/register_post') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_registration" value="user_registration" >
                                    <div class="form-group">
                                        <label class="form-control-label">Name</label>
                                        <input class="form-control" type="text" name="name">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Mobile</label>
                                        <input class="form-control" type="number" name="mobile">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Email Address</label>
                                        <input class="form-control" type="email" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Password</label>
                                        <input class="form-control" type="password" name="password">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Confirm Password</label>
                                        <input class="form-control" type="password" name="confirm_password">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Sponsor ID</label>
                                        <input class="form-control" type="text" name="sponsor_id" id="sponsor_id" onblur="get_username(this.value);">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Sponsor Name</label>
                                        <input class="form-control" type="text" name="sponsor_name" id="username">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Placement</label>
                                        <select name="placement" class="form-control">
                                            <option value="Left">Left</option>
                                            <option value="Right">Right</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-0">
                                        <button class="btn btn-lg btn-block btn-primary" name="submit" type="submit" onclick="style.display = 'none'">Register</button>
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

<script type="text/javascript">
    var userid = document.getElementById('sponsor_id').value;
    get_username(userid);

    function get_username(id) {
        var url = "{{ url('get_username') }}";

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: "POST",
            data: {
                'userid': id
            },
            context: this,
            success: function(result) {
                // alert(result);
                if (result.success == 1) {
                    $("#username").val(result.user.name);
                }
                
                console.log(result);
            },
            error: function(error) {
                console.log(error.responseText);
            }
        });
    }
</script>

@endsection