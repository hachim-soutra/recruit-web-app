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
    .wysihtml5-sandbox{
        height: 130px !important;
    }
</style>
@endsection

@section('content')
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
        <div class="col-md-8">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Testimonial View</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.testimonial.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form name="subjectCreateFrm" id="subjectCreateFrm" action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" autocomplete="off" value="{{ @$detail->name }}" class="form-control" id="name" name="name" placeholder="Your Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Designation</label>
                                    <input type="text" name="designation" id="designation" value="{{ @$detail->designation }}" class="form-control" placeholder="Your Designation.">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Email-ID</label>
                                    <input type="email" autocomplete="off" value="{{ @$detail->email }}" class="form-control" id="emailid" placeholder="Example@gmail.com" name="emailid" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Ratings</label>
                                    <input type="number" name="rating" id="rating" value="{{ @$detail->rating }}" class="form-control" placeholder="Give a rating from 0 to 5" max="5" min="0" step="0.1">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Quote</label>
                                    <textarea class="textarea" readonly name="quote_details" value="{{ @$detail->subject }}" id="quote_details" placeholder="Write Down Your Quotes Here."
                                    style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ @$detail->subject }}</textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Old Picture</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.testimonial.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        @if(count((array)@$detail) > 0)
                        <div class="col-md-12" style="display: flex;align-items: center;justify-content: center;">
                            <img src="{{env('APP_URL').'/uploads/'.(@$detail->image)}}" alt="" style="height: auto;width: 100px;">
                        </div>
                        @endif
                    </div>
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
