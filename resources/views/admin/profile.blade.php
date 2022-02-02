@extends('admin.layout.app')
@section('content')
@section('meta_title','Profile')
@section('meta_keyword','Profile')
@section('meta_description','Profile')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center">
                                <h5 class="page-title">Dashboard</h5>
                                <ul class="breadcrumb ml-2">
                                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="fas fa-home"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Profile</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="profile-cover">
                    <div class="profile-cover-wrap">
                        <img class="profile-cover-img" src="{{ asset($user->photo) }}" alt="Profile Cover">
                    </div>
                </div>
                <div class="text-center my-4">
                    <label class="avatar avatar-xxl profile-cover-avatar">
                        <img class="avatar-img" src="{{ asset($user->photo) }}" alt="Profile Image">
                    </label>
                    <h2>
                        {{ $user->name }}
                        <i class="fas fa-certificate text-primary small" data-toggle="tooltip" data-placement="top" title="" data-original-title="Verified"></i>
                    </h2>
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <i class="fas fa-id-card"></i>
                            <span>{{ $user->userid }}</span>
                        </li>
                        <li class="list-inline-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $user->address }}</span>
                        </li>
                        <li class="list-inline-item">
                            <i class="far fa-calendar-alt"></i>
                            <span>{{ $user->created_at }}</span>
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card card-body">
                            <h5>Complete your profile</h5>
                            @if(Session::has('message'))
                            <p class="{{ Session::get('alert-class') }}">
                                {{ Session::get('message') }}
                            </p>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="progress progress-md flex-grow-1">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $profileComplete }}%" aria-valuenow="{{ $profileComplete }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="ml-4">{{ $profileComplete }}%</span>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title d-flex
                                    justify-content-between">
                                    <span>Profile</span>
                                    <a class="btn btn-sm btn-white" href="{{ url('edit_profile') }}">Edit</a>
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li class="py-0">
                                        <strong class="text-dark">Personal Details</strong>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-6">
                                                Email: <b>{{ $user->email }}</b>
                                            </div>
                                            <div class="col-md-6">
                                                Mobile: <b>{{ $user->mobile }}</b>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-6">
                                                State: <b>{{ $user->state->name ?? '' }}</b>
                                            </div>
                                            <div class="col-md-6">
                                                City: <b>{{ $user->city->name ?? '' }}</b>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-6">
                                                Pincode: <b>{{ $user->pincode }}</b>
                                            </div>
                                            <div class="col-md-6">
                                                Address: <b>{{ $user->address }}</b>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="pt-2 pb-0">
                                        <strong class="text-dark">Bank Details</strong>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-6">
                                                Holder Name: <b>{{ $user->bank_details->holder_name ?? '' }}</b>
                                            </div>
                                            <div class="col-md-6">
                                                Bank: <b>{{ $user->bank_details->banks->name ?? '' }}</b>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-6">
                                                Branch: <b>{{ $user->bank_details->branch ?? '' }}</b>
                                            </div>
                                            <div class="col-md-6">
                                                Acc Type: <b>{{ $user->bank_details->account_type ?? '' }}</b>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-6">
                                                IFSC: <b>{{ $user->bank_details->ifsc ?? '' }}</b>
                                            </div>
                                            <div class="col-md-6">
                                                Acc Number: <b>{{ $user->bank_details->account_no ?? '' }}</b>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Activity</h5>
                            </div>
                            <div class="card-body card-body-height">
                                <ul class="activity-feed">
                                    <li class="feed-item">
                                        <div class="feed-date">Nov 16</div>
                                        <span class="feed-text">Login on IP Address: 32342.345.46.56</span>
                                    </li>
                                    <li class="feed-item">
                                        <div class="feed-date">Nov 16</div>
                                        <span class="feed-text">Login on IP Address: 32342.345.46.56</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection