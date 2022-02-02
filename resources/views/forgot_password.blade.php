@extends('layout.app')
@section('content')
@section('meta_title','E-Life | Forgot Password')
@section('meta_keyword','Forgot Password')
@section('meta_description','Forgot Password')
<style>
    .login-body{
        background: url('assets/img/bg_anim2.gif') #000;
    }
</style>
<article class="wrapper">


<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="container">
            <img class="img-fluid logo-dark mb-2" src="{{asset('assets/img/logo.png')}}" alt="Logo">
            <div class="loginbox">
                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>Forgot Password</h1>
                        @if(Session::has('message'))
                        <p class="{{ Session::get('alert-class') }}">
                            {{ Session::get('message') }}
                        </p>
                        @endif
                        <form action="{{ url('/forgot_password_post') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="form-control-label">User ID</label>
                                <input type="text" name="user_id" class="form-control">
                            </div>
                            
                            <button class="btn btn-lg btn-block btn-primary" type="submit">Send Message</button>


                            <div class="text-center dont-have  text-white"> <a
                                href="{{ url('/login') }}" class=" text-white">Login</a> </div>

                            <div class="text-center dont-have  text-white">Got to  <a href="{{ url('/') }}" class=" text-white">Website</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</article>




@endsection