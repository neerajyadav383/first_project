@extends('admin.layout.app')
@section('content')
@section('meta_title', 'Edit Profile')
@section('meta_keyword', 'Edit Profile')
@section('meta_description', 'Edit Profile')

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
            <div class="col-xl-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="far fa-user"></i> Profile</h5>
                        @if(Session::has('message'))
                        <p class="{{ Session::get('alert-class') }}">
                            {{ Session::get('message') }}
                        </p>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- update_user_profile -->
                        @if(Auth::user()->hasRole('admin'))
                            @php $required = ''; @endphp
                        @else
                            @php $required = 'required'; @endphp
                        @endif
                        
                        @php
                        if(isset($_GET['id'])){
                        $old_id = $_GET['id'];
                        $readonly = '';
                        @endphp
                        <form action="{{ url('update_user_profile') }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="old_id" value="{{$old_id}}">
                            @php
                            } else {
                                $readonly = 'readonly';
                            @endphp
                            <form action="{{ url('update_profile') }}" method="post" enctype="multipart/form-data">
                                @php
                                }
                                @endphp
                                @csrf
                                <h5 class="card-title">Personal Information</h5>
                                <div class="row form-group">
                                    <div class="col-sm-12 text-center">
                                        <div class="d-inline-flex align-items-center">
                                            <label class="avatar avatar-xxl profile-cover-avatar m-0" for="edit_img">
                                                <img id="avatarImg" class="avatar-img" src="{{ asset($user->photo) }}" alt="Profile Image">
                                                <input type="file" id="edit_img" name="photo">
                                                <span class="avatar-edit">
                                                    <i class="feather-edit-2 avatar-uploader-icon shadow-soft"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>User ID</label>
                                            <input type="text" name="userid" id="userid" value="{{ $user->userid }}" class="form-control" readonly="" {{$required}}>
                                        </div>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="name" id="name" value="{{ $user->name }}" {{$required}} class="form-control" {{$readonly}}>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" id="email" {{$required}} value="{{ $user->email  }}" class="form-control" {{$readonly}}>
                                        </div>
                                        <div class="form-group">
                                            <label>Mobile Number</label>
                                            <input type="text" name="mobile" id="mobile" {{$required}} value="{{ $user->mobile }}" class="form-control" {{$readonly}}>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title">Postal Address</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>State</label>
                                            <select id="state_id" onchange="getCities(this.value)" name="state_id" {{$required}} class="select">
                                                <option value="" selected disabled>--select--</option>
                                                @foreach($states as $state)
                                                @php $selected = ''; @endphp
                                                @if($user->state_id == $state->id)
                                                @php $selected = 'selected'; @endphp
                                                @endif
                                                <option value="{{ $state->id }}" {{ $selected }}>{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>City</label>
                                            <select id="city_id" class="select" {{$required}} value="{{ $user->city_id  }}" name="city_id">
                                                @if(count($cities)>0)
                                                <option value="{{ $cities['id'] }}">{{ $cities['name'] }}</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pincode</label>
                                            <input name="pincode" type="text" value="{{ $user->pincode }}" class="form-control" id="pincode" {{$required}}>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea id="address" {{$required}} class="form-control" name="address" rows="3">{{ $user->address }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <h5 class="card-title">Bank Details</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="hidden" name="bank_details_id" id="bank_details_id" value="{{ $user->bank_id ?? ''}}">
                                            <label>Holder Name</label>
                                            <input type="text" class="form-control" value="{{ $user->bank_details->holder_name ?? ''}}" name="holder_name" id="holder_name" {{$required}}>
                                        </div>
                                        <div class="form-group">
                                            <label>Bank</label>
                                            <select id="bank_id" name="bank_id" {{$required}} class="select">
                                                <option value="" selected disabled>--select--</option>
                                                @foreach($banks as $bank)
                                                @php
                                                $selected = '';
                                                $bankid = $user->bank_details->bank_id ?? '';
                                                @endphp
                                                @if($bankid == $bank->id)
                                                @php $selected = 'selected'; @endphp
                                                @endif
                                                <option value="{{ $bank->id }}" {{ $selected }}>{{ $bank->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Branch</label>
                                            <input type="text" class="form-control" value="{{ $user->bank_details->branch ?? ''}}" id="branch" {{$required}} name="branch">
                                        </div>
                                        <div class="form-group">
                                            <label>IFSC</label>
                                            <input type="text" class="form-control" value="{{ $user->bank_details->ifsc ?? ''}}" id="ifsc" {{$required}} name="ifsc">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Account No.</label>
                                            <input type="text" class="form-control" value="{{ $user->bank_details->account_no ?? ''}}" id="account_no" {{$required}} name="account_no">
                                        </div>
                                        <div class="form-group">
                                            <label>Account Type</label>
                                            <input type="text" class="form-control" id="account_type" value="{{ $user->bank_details->account_type ?? ''}}" {{$required}} name="account_type">
                                        </div>
                                    </div>
                                </div>
                              
                              <h5 class="card-title">ID Details</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pan Number</label>
                                            <input type="text" class="form-control" value="{{ $user->pan ?? ''}}" onkeyup="panValid(this.value)" id="pan" {{$required}} name="pan">
                                        </div>
                                        <span id="pan_error" class="text-danger"></span>
                                        <div class="form-group">
                                            <label>Pan Card</label>
                                            <input type="file" class="form-control" value="" id="pan_file" name="pan_file">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Aadhar Number</label>
                                            <input type="text" class="form-control" value="{{ $user->aadhar ?? ''}}" onkeyup="aadharValid(this.value)" id="aadhar" {{$required}} name="aadhar">
                                        </div>
                                        <span id="aadhar_error" class="text-danger"></span>
                                        <div class="form-group">
                                            <label>Aadhar Card</label>
                                            <input type="file" class="form-control" value="" id="aadhar_file" name="aadhar_file">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" name="sumbit" id="sumbit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function getCities(state_id) {

        var url = "{{ url('get_cities') }}";

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            type: "POST",
            data: {
                'state_id': state_id
            },
            context: this,
            success: function(result) {
                var html = '';
                jQuery.each(result, function(arrKey, arrVal) {
                    html += '<option value="' + arrVal.id + '">' + arrVal.name + '</option>';
                })
                jQuery('#city_id').html(html);
                console.log(result);
            },
            error: function(error) {
                console.log(error.responseText);
            }
        });
    }
  
  function panValid(panVal) {
        // var panVal = $('#pan').val();
        var regpan = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/;

        if(regpan.test(panVal)){
            // valid pan card number
            $('#pan_error').html('');
        } else {
            // invalid pan card number
            $('#pan_error').html('invalid pan card number');
        }
    }

    function aadharValid(aadharVal) {
        // var aadharVal = $('#pan').val();
        var regpan = /^([2-9]){1}([0-9]){11}?$/;

        if(regpan.test(aadharVal)){
            $('#aadhar_error').html('');
        } else {
            $('#aadhar_error').html('invalid aadhar card number');
        }
    }
</script>


@endsection