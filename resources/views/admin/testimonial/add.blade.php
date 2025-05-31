@extends('admin.layout.app')

@section('title', "Add Testimonial")

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
       Manage Testimonial
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.testimonial.list') }}">Testimonials</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Testimonial Add From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.testimonial.list') }}" class="btn btn-box-tool">
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
                    <form name="subjectCreateFrm" id="subjectCreateFrm" action="{{ route('admin.testimonial.store') }}" method="POST" enctype="multipart/form-data">
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
                                    <label for="name">Designation</label>
                                    <input type="text" name="designation" id="designation" value="{{ @$detail->designation }}" class="form-control" placeholder="Your Designation.">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="description">Email-ID</label>
                                    <input type="email" autocomplete="off" value="{{ @$detail->email }}" class="form-control" id="emailid" placeholder="Example@gmail.com" name="emailid" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Ratings</label>
                                    <input type="number" name="rating" id="rating" value="{{ @$detail->rating }}" class="form-control" placeholder="Give a rating from 0 to 5" max="5" min="0" step="0.1">
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
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="description">Quote</label>
                                    <textarea class="textarea" name="quote" value="{{ @$detail->subject }}" id="quote_details" placeholder="Write Down Your Quotes Here."
                                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ @$detail->subject }}</textarea>
                                    @if($errors->has('quote'))
                                    <div class="erroralert">{{ $errors->first('quote') }}</div>
                                    @endif
                                    
                                </div>
                            </div>
                        </div>
                        <hr>
                        <input type="hidden" name="rowid" id="rowid" value="{{ @$detail->id }}">
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
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#quote_details').wysihtml5()
    });
</script>
@endsection
