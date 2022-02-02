@extends('admin.layout.app')
@section('content')
@section('meta_title','Change Password')
@section('meta_keyword','Change Password')
@section('meta_description','Change Password')

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
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form action=" {{ url('update_change_password') }}" method="post">
                            @csrf
                            @if($success == 1)
                            <div class="form-group">
                                <label>User ID</label>
                                <input type="text" value="{{ $user->userid }}" name="user_id" id="user_id" readonly class="form-control">
                            </div>
                            @endif
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" id="new_password" class="form-control">
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn
                                    btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
              @if(Auth::user()->hasRole('admin'))
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Change Transaction Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('update_change_transpassword') }}" method="post">
                            @csrf
                            @if($success == 1)
                            <div class="form-group">
                                <label>User ID</label>
                                <input type="text" value="{{ $user->userid }}" name="user_id" id="user_id" readonly class="form-control">
                            </div>
                            @endif
                            <div class="form-group">
                                <label>New Transaction Password</label>
                                <input type="password" name="new_transaction_password" id="new_transaction_password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Confirm Transaction Password</label>
                                <input type="password" name="confirm_transaction_password" id="confirm_transaction_password" class="form-control">
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn
                                    btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
              @endif
            </div>
        </div>
    </div>
</div>

@endsection