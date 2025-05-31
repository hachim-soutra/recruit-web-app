@extends('admin.layout.app')
@section('title', "Testimonial List")
    @section('mystyle')
    @endsection
    @section('content')
        <section class="content-header">
            <h1>
                Testimonial List
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active">Testimonial</li>
            </ol>
        </section>
        <section class="content">
            <div class="box">
                <div class="row">
                    <div class="box-body">
                        <div class="col-md-4 col-xs-12">
                            <form method="GET" action="">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control pull-right" placeholder="Search" value="{{ (isset($request->keyword)) ? $request->keyword : null }}">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 col-xs-12">
                        </div>
                        <div class="col-md-4 col-xs-12"> 
                            <a href="{{ route('admin.testimonial.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
                                Create
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">All Testimonial List</h3>
                        </div>
                        @if(session()->has('errMsg'))
                        <div class="erroralert">{{ session()->get('errMsg') }}</div>
                        @endif
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th scope="col">#Sl No.</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Quotes</th>
                                    <th scope="col">Designation</th>
                                    <th scope="col">Email-ID</th>
                                    <th scope="col">Ratings</th>
                                    <th scope="col" style="text-align: center;">Action</th>
                                </tr>
                                @php $count = 1; @endphp
                                @foreach($testimonials as $t)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $t->name }}</td>
                                    <td><img src="{{ env('APP_URL').'/uploads/'.($t->image) }}" alt="" style="height:auto;width:50px;"></td>
                                    <td>{!! substr($t->subject, 0, 35) !!} ..</td>
                                    <td>{{ $t->designation }}</td>
                                    <td>{{ $t->email }}</td>
                                    <td>{{ $t->rating }}</td>
                                    <td style="text-align: center;">
                                        <div class="btn-group"> 
                                            @if($t->status === 'A')
                                                <a href="{{ route('admin.testimonial.change-status', ['status' => 'I', 'id' => $t->id]) }}" class="btn btn-sm btn-success js-tooltip-enabled" data-toggle="tooltip" title="Status" data-original-title="Status=I">
                                                    <i class="fa fa-check-circle-o"></i>
                                                </a> 
                                            @else
                                                <a href="{{ route('admin.testimonial.change-status', ['status' => 'A', 'id' => $t->id]) }}" class="btn btn-sm btn-danger js-tooltip-enabled" data-toggle="tooltip" title="Status" data-original-title="Status=A">
                                                    <i class="fa fa-times"></i>
                                                </a> 
                                            @endif
                                            <a href="{{ route('admin.testimonial.edit', ['id' => $t->id]) }}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="Edit" data-original-title="Edit">
                                                <i class="fa fa-fw fa-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.testimonial.trash', ['id' => $t->id]) }}" class="btn btn-sm btn-danger js-tooltip-enabled deleteRow" data-toggle="tooltip" data-id="{{ $t->id }}" title="Delete" data-method="DELETE" data-original-title="Delete">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </a>
                                            <a href="{{ route('admin.testimonial.view', ['id' => $t->id]) }}" class="btn btn-sm btn-warning js-tooltip-enabled deleteRow" data-toggle="tooltip" data-id="{{ $t->id }}" title="View" data-method="View" data-original-title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>  
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            <div class="mt-5">
                                {{ $testimonials->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
@section('myscript')
@endsection
