@extends('admin.layout.app')

@section('title', "Add News")

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
       Manage Advices
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.advice.list') }}">Advices</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Advice Add From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.news.list') }}" class="btn btn-box-tool">
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
                    <form name="subjectCreateFrm" id="subjectCreateFrm" action="{{ route('admin.advice.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">News Title</label>
                                    <input type="text" autocomplete="off" value="{{@$detail->title}}" class="form-control" id="title" name="title" placeholder="Your Advice Title/ Headings.">
                                    @if($errors->has('title'))
                                        <div class="erroralert">{{ $errors->first('title') }}</div>
                                    @endif
                                </div>
                            </div>
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
                                <img src="{{ @$detail->image }}" alt="" style="height: auto;width: 100px;">
                                <input type="hidden"  name="oldfile" id="oldfile" value="{{ @$detail->image }}">
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Status</label>
                                <select name="status" id="status"
                                        class="form-control">
                                    <option disabled selected readonly>Choose Status</option>
                                    @foreach (\App\Enum\AdviceStatusEnum::cases() as $status)
                                        <option value="{{ $status->value }}" {{ @$detail->status->value == $status->value ? 'selected': '' }}>
                                            {{ $status->value }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('status'))
                                    <div class="erroralert">{{ $errors->first('status') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Advice Details</label>
                                    <div style="margin: 10px 0">
                                        <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            Add a button event
                                        </button>
                                        <div class="collapse" id="collapseExample">
                                            <div class="card card-body">
                                                copy the below short code to the line wher you need the button and then change the title and link parameters with your choice<br />
                                                <span style="font-weight: bold; font-size: 14px;">[button_shortcode title="Button" link="recruit.ie"]</span>

                                            </div>
                                        </div>
                                    </div>
                                    <textarea name="details" id="details" class="form-control" placeholder="Write Down Your News Description.">{{ @$detail->details }}</textarea>
                                    @if($errors->has('details'))
                                    <div class="erroralert">{{ $errors->first('details') }}</div>
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
    }, 3500);
</script>
@endsection

@section('myscript')
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#details').wysihtml5()
    });
</script>
@endsection
