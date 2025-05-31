@extends('admin.layout.app')
@section('title', "News List")
    @section('mystyle')
    @endsection
    @section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <section class="content-header">
            <h1>
                Advices List
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active">Advices</li>
            </ol>
        </section>
        <section class="content">
            <div class="box">
                <div class="row">
                    <div class="box-body">
                        <div class="col-md-4 col-xs-12">
                            <form method="GET" action="">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control pull-right" placeholder="Search" value="{{ @request()->keyword }}">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 col-xs-12">
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <a href="{{ route('admin.advice.create') }}" class="btn bg-maroon btn-flat btn-sm pull-right">
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
                            <h3 class="box-title">All Advices List</h3>
                        </div>
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                            <script>
                                toastr.warning("{{$error}}");
                            </script>
                            @endforeach
                        @endif
                        @if(session('success'))
                        <script>
                            toastr.success("{{session('success')}}");
                        </script>
                        @endif
                        @if(session('error'))
                            <script>
                                toastr.error("{{session('error')}}");
                            </script>
                        @endif
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th scope="col">#Sl No.</th>
                                    <th scope="col">Advice Title</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Description</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" style="text-align: center;">Action</th>
                                </tr>
                                @php $count = 1; @endphp
                                @foreach($advices as $a)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{!! substr($a->title, 0, 35) !!} ...</td>
                                    <td><img src="{{ $a->image }}" alt="" style="height:auto;width:50px;"></td>
                                    <td>{!! substr(strip_tags($a->details) , 0, 35) !!} ...</td>
                                    <td class="text-center">
                                        @if($a->status === \App\Enum\AdviceStatusEnum::SHOW_IN_LIST)
                                            Show in list
                                        @elseif($a->status === \App\Enum\AdviceStatusEnum::SHOW_IN_HOME)
                                            Show in home
                                        @elseif($a->status === \App\Enum\AdviceStatusEnum::DRAFT)
                                            Draft
                                        @else
                                            Rejected
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                            <a href="{{ route('admin.advice.edit', ['id' => $a->id]) }}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="Edit" data-original-title="Edit">
                                                <i class="fa fa-fw fa-pencil"></i>
                                            </a>
                                            <!--
                                            <a href="{{ route('admin.advice.trash', ['id' => $a->id]) }}" class="btn btn-sm btn-danger js-tooltip-enabled deleteRow" data-toggle="tooltip" data-id="{{ $a->id }}" title="Delete" data-method="DELETE" data-original-title="Delete">
                                                <i class="fa fa-fw fa-trash"></i>
                                            </a>
                                             -->
                                            <a href="{{ route('admin.advice.view', ['id' => $a->id]) }}" class="btn btn-sm btn-warning js-tooltip-enabled" data-toggle="tooltip" data-id="{{ $a->id }}" title="View" data-method="View" data-original-title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            <div class="mt-5">
                            {{ $advices->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
@section('myscript')
@endsection
