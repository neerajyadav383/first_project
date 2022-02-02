@extends('admin.layout.app')
@section('content')
@section('meta_title','Notification')
@section('meta_keyword','Notification')
@section('meta_description','Notification')



<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="d-flex align-items-center">
                        <h5 class="page-title">Dashboard</h5>
                        <ul class="breadcrumb ml-2">
                            <li class="breadcrumb-item"><a href="index.html"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active">@yield('meta_title')</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->hasRole('admin'))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Notification Form</h5>
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
                                <form action="@if(isset($editNotify)) {{ url('update_notification') }} @else {{ url('add_notification') }} @endif" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Notification</label>
                                                <textarea name="notification" rows="5" class="form-control">@if(isset($editNotify)){{$editNotify->notification}}@endif</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if(isset($editNotify))
                                        <input type="hidden" name="id" value="{{$editNotify->id}}" >
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        @else
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Notification</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable table table-stripped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Notification</th>
                                        <th>Date</th>
                                        @if(Auth::user()->hasRole('admin'))
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($notifications as $notification)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $notification->notification }}</td>
                                        <td>{{ $notification->created_at }}</td>
                                        @if(Auth::user()->hasRole('admin'))
                                        <td>
                                            <a href="{{ url('notification/'.$notification->id) }}" class="btn btn-sm btn-white text-success mr-2"><i class="fas fa-eye mr-1"></i> Edit</a>
                                            <a href="{{ url('delete_notification/'.$notification->id) }}" class="btn btn-sm btn-white text-danger mr-2"><i class="far fa-trash-alt mr-1"></i>Delete</a>
                                        </td>
                                        @endif
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
    function editNotify(id) {
        //alert(id);
        jQuery('#notify_id').val(id);
    }
</script>


@endsection