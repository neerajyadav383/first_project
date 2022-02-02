@extends('layout.app')
@section('content')
@section('meta_title','E-Life | Login')
@section('meta_keyword','Login')
@section('meta_description','Login')
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
                        <h1>Login</h1>
                        @if(Session::has('message'))
                        <p class="{{ Session::get('alert-class') }}">
                            {{ Session::get('message') }}
                        </p>
                        @endif
                        <form action="{{ url('/login_post') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="form-control-label">User ID</label>
                                <input type="text" name="user_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Password</label>
                                <div class="pass-group">
                                    <input type="password" name="password" class="form-control pass-input">
                                    <span class="fas fa-eye toggle-password"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="cb1">
                                            <label class="custom-control-label" for="cb1">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-right">
                                        <!-- <a class="forgot-link" href="javascript:void(0)">Forgot Password ?</a> -->
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-lg btn-block btn-primary" type="submit">Login</button>

                            <div class="text-center text-white dont-have"><a href="{{ url('/forgot_password') }}" class=" text-white">Forgot Password</a></div>

                            <div class="text-center text-white dont-have">Don't have an account yet? <a href="{{ url('/register') }}" class=" text-white">Register</a></div>
                            <div class="text-center text-white dont-have">Got to  <a href="{{ url('/') }}" class=" text-white">Website</a></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</article>




@endsection