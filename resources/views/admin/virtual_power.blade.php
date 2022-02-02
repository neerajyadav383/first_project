@extends('admin.layout.app')
@section('content')
@section('meta_title','Virtual Power')
@section('meta_keyword','Virtual Power')
@section('meta_description','Virtual Power')

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
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Virtual Power</h5>
                    </div> 
                    <div class="card-body">
                        <form action=" {{ url('post_virtual_power') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>User ID</label>
                                <input type="text" value="{{ $user->userid }}" name="user_id" id="user_id" readonly class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Left Power</label>
                                <input type="hidden" name="left_old" value="{{ $left }}" >
                                <input type="number" name="left" id="left" value="{{ $left }}" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Right Power</label>
                                <input type="hidden" name="right_old" value="{{ $right }}" >
                                <input type="number" name="right" id="right" value="{{ $right }}" class="form-control" required>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn
                                    btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection