@extends('layout.app')
@section('content')
@section('meta_title', 'E-Life | Registration')
@section('meta_keyword', 'Registration')
@section('meta_description', 'Registration')
<style>
    .login-body{
        background: url('assets/img/bg_anim2.gif') #000;
    }
</style>
<article class="wrapper">

<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="container">
            <img class="img-fluid logo-dark mb-2" src="assets/img/logo.png" alt="Logo">
            <div class="loginbox">
                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>Register</h1>
                        <p class="account-subtitle text-white">Access to our dashboard</p>
                        @if (Session::has('message'))
                            <p class="{{ Session::get('alert-class') }}">
                                {{ Session::get('message') }}
                            </p>
                        @endif
                        <form action="{{ url('/register_post') }}" method="POST">
                            @csrf
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
                                <input class="form-control" type="text" value="{{$_GET['id'] ?? ''}}" name="sponsor_id" id="sponsor_id" onblur="get_username(this.value);">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Sponsor Name</label>
                                <input class="form-control" type="text"  name="sponsor_name" id="username">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Placement</label>
                                <select name="placement" class="form-control">
                                    @php $placement = isset($_GET['placement']) ? $_GET['placement'] : ''; @endphp
                                    @if($placement=='Left')
                                    <option value="Left" selected>Left</option>
                                    <option value="Right">Right</option>
                                    @else
                                    <option value="Right" selected>Right</option>
                                    <option value="Left">Left</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                <button class="btn btn-lg btn-block btn-primary" name="submit"
                                    type="submit" onclick="style.display = 'none'">Register</button>
                            </div>
                        </form>


                        <div class="text-center dont-have  text-white">Already have an account? <a
                                href="{{ url('/login') }}" class=" text-white">Login</a></div>
                        <div class="text-center dont-have  text-white">Got to <a href="{{ url('/') }}" class=" text-white">Website</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</article>



<script type="text/javascript">
    window.onload = function() {
        var userid = document.getElementById('sponsor_id').value;
        if(userid!=''){
            get_username(userid);
        }
    };

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
