@extends('admin.layout.app')

@section('title', "Add Team")

@section('mystyle')
<style>
    .add-edu{
        margin-top: 25px;
    }
    .erroralert{
        color: red;
    }
</style>
@endsection

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<section class="content-header">
    <h1>
       Manage Team
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.team.list') }}">Teams</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Team Add From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.team.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
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
                <div class="box-body">
                    <form name="subjectCreateFrm" id="subjectCreateFrm" action="{{ route('admin.team.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" autocomplete="off" value="{{ @$detail->name }}" class="form-control" id="name" name="name" placeholder="Your Name">
                                    @if($errors->has('name'))
                                    <div class="erroralert">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Email-ID</label>
                                    <input type="email" autocomplete="off" value="{{ @$detail->email }}" class="form-control" id="email" name="email" placeholder="Your Email-ID">
                                    @if($errors->has('email'))
                                    <div class="erroralert">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="description">Phone</label>
                                    <input type="text" autocomplete="off" value="{{ @$detail->phone }}" class="form-control" id="phone" name="phone" placeholder="Enter Your Phone Number.">
                                    @if($errors->has('phone'))
                                    <div class="erroralert">{{ $errors->first('phone') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Upload Picture</label> 
                                    <input type="file" autocomplete="off" class="form-control" id="imagefile" name="imagefile" >
                                    @if($errors->has('imagefile'))
                                    <div class="erroralert">{{ $errors->first('imagefile') }}</div>
                                    @endif
                                </div>
                            </div>
                            @if(count((array)@$detail) > 0)
                            <div class="col-md-4">
                                <label for="">Old Picture</label>
                                <img src="{{env('APP_URL').'/uploads/'.(@$detail->image)}}" alt="" style="height: auto;width: 100px;">
                                <input type="hidden"  name="oldfile" id="oldfile" value="{{ @$detail->image }}">
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="description">Twitter</label>
                                    <input type="text" value="{{ @$detail->twid }}" class="form-control" id="twid" placeholder="Your Twitter ID" name="twid" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Facebook</label>
                                    <input type="text" name="fbid" id="fbid" value="{{ @$detail->fbid }}" class="form-control" placeholder="Your Facebook ID">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Designation</label>
                                    <textarea name="designation" id="designation" value="{{ @$detail->designation }}" class="form-control" placeholder="Enter Your Designation">{{ @$detail->designation }}</textarea>
                                    @if($errors->has('designation'))
                                    <div class="erroralert">{{ $errors->first('designation') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <input type="hidden" name="rowid" id="rowid" value="{{ @$detail->teamid }}">
                        <input type="hidden" name="userid" id="userid" value="{{ @$detail->userid }}">
                        <button type="submit" class="btn waves-effect waves-light btn-primary">Save</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
<script>
    setTimeout(function(){
        $('.erroralert').hide();
    }, 2500);
</script>
@endsection

@section('myscript')

@endsection
