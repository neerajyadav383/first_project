@extends('admin.layout.app')
@section('content')
@section('meta_title','Offer')
@section('meta_keyword','Offer')
@section('meta_description','Offer')



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
                        <h5 class="card-title">Offer Form</h5>
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
                                <form action="@if(isset($editNotify)) {{ url('update_offer') }} @else {{ url('add_offer') }} @endif" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="title" class="form-control" value="@if(isset($editNotify)){{$editNotify->title}}@endif" >
                                            </div>
                                            <div class="form-group">
                                                <label>Image</label>
                                                <input type="file" name="image" class="form-control" >
                                            </div>
                                            @if(isset($editNotify))
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    @if($editNotify->status=='ON')
                                                    <option selected>ON</option>
                                                    <option>OFF</option>
                                                    @else
                                                    <option>ON</option>
                                                    <option selected>OFF</option>
                                                    @endif
                                                </select>
                                            </div>
                                            @endif
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
                        <h4 class="card-title">Offers</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="datatable table table-stripped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titile</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($notifications as $notification)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $notification->title }}</td>
                                        <td><a href="{{ asset($notification->image) }}" target="_blank"><img height="50" src="{{ asset($notification->image) }}"></a></td>
                                        <td>{{ $notification->status }}</td>
                                        <td>{{ $notification->created_at }}</td>
                                        <td>
                                            <a href="{{ url('offer/'.$notification->id) }}" class="btn btn-sm btn-white text-success mr-2"><i class="fas fa-eye mr-1"></i> Edit</a>
                                            <a href="{{ url('delete_offer/'.$notification->id) }}" class="btn btn-sm btn-white text-danger mr-2"><i class="far fa-trash-alt mr-1"></i>Delete</a>
                                        </td>
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