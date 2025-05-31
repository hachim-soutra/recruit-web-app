@extends('admin.layout.app')

@section('title', "About Us")
@section('mystyle')
<style>
    .add-edu{
        margin-top: 25px;
    }
    .erroralert{
        color: red;
    }
    .btn .btn-primary{
        width: 70% !important;
    }
    .imgstyle {
        height: 180px;
        width: auto;
    }
</style>
@endsection

@section('content')
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    function addAboutUs(){
        $('#aboutmodal').modal('show');
    }
</script>
<section class="content-header">
    <h1>
       About US
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.news.list') }}">About Us</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">About Us</h3>
                    <!-- <div class="box-tools pull-right">
                        <button type="button" onclick="addAboutUs()" class="btn btn-sm btn-primary addbtn">Add</button>
                        <a href="{{ route('admin.cms.about-us') }}" class="btn btn-box-tool"><button type="button" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i>  Back</button></a>
                    </div> -->
                </div>
                @if(session('success'))
					<script>
						toastr.success("{{session('success')}}");
					</script>
				@endif
                @if($errors->has('heading') || $errors->has('detail'))
                    <script>
                        $('.addbtn').trigger('click');
					</script>
                @endif
                <div class="box-body">
                    <form action="{{ route('admin.cms.about-us-save') }}" id="aboutform" method="post" enctype="multipart/form-data">
                    @csrf
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="">Banner Description</label>
                                    <textarea name="description" id="description" value="{{ old('description', @$data->banner_description) }}" class="form-control" placeholder="Banner Description">{{ @$data->banner_description }}</textarea>
                                    @if($errors->has('description'))
                                        <div class="erroralert">{{ $errors->first('description') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="">Banner Heading</label>
                                    <input type="text" name="banner_heading" value="{{ old('banner_heading', @$data->heading) }}" id="banner_heading" class="form-control" placeholder="banner heading">
                                    @if($errors->has('banner_heading'))
                                        <div class="erroralert">{{ $errors->first('banner_heading') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Banner File</label>
                                    <input type="file" name="banner_file" accept="image/*" id="banner_file" class="form-control">
                                    <span><a target="_blank" href="{{ asset('uploads/cms/about/'.@$data->banner_file) }}">{{ @$data->banner_file }}</a></span>
                                    <input type="hidden" name="banner_file_old" id="banner_file_old" value="{{ @$data->banner_file }}">
                                    @if($errors->has('banner_file'))
                                        <div class="erroralert">{{ $errors->first('banner_file') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="">About us Video</label>
                                    <input type="file" name="about_video" id="about_video" accept="video/mp4,video/x-m4v,video/*" class="form-control">
                                    <span><a target="_blank" href="{{ asset('uploads/cms/about/'.@$data->aboutus_video) }}">{{ @$data->aboutus_video }}</a></span>
                                    <input type="hidden" name="about_video_old" id="about_video_old" value="{{ @$data->aboutus_video}}">
                                    @if($errors->has('about_video'))
                                        <div class="erroralert">{{ $errors->first('about_video') }}</div>
                                    @endif
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="">Left Content</label>
                                    <textarea name="left_content" id="left_content" value="{{ old('left_content', @$data->left_detail) }}" class="form-control" placeholder="Left Content" cols="30" rows="5">{{@$data->left_detail}}</textarea>
                                    @if($errors->has('left_content'))
                                        <div class="erroralert">{{ $errors->first('left_content') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Right Content</label>
                                    <textarea name="right_content" id="right_content" value="{{ old('right_content', @$data->right_detail) }}" class="form-control" placeholder="Right Content" cols="30" rows="5">{{@$data->right_detail}}</textarea>
                                    @if($errors->has('right_content'))
                                        <div class="erroralert">{{ $errors->first('right_content') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="">Glorious Years</label>
                                    <input type="number" name="glorious_years" value="{{ old('glorious_years', @$data->glorious_year) }}" id="glorious_years" class="form-control" placeholder="Glorious Years.">
                                    @if($errors->has('glorious_years'))
                                        <div class="erroralert">{{ $errors->first('glorious_years') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Happy Client</label>
                                    <input type="number" name="happy_client" value="{{ old('happy_client', @$data->happy_client) }}" id="happy_client"  class="form-control" placeholder="Happy Client.">
                                    @if($errors->has('happy_client'))
                                        <div class="erroralert">{{ $errors->first('happy_client') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="">Talented Candidates</label>
                                    <input type="number" name="talented_candidate" value="{{ old('talented_candidate', @$data->talented_candidate) }}" id="talented_candidate"  class="form-control" placeholder="Talented Candidates.">
                                    @if($errors->has('talented_candidate'))
                                        <div class="erroralert">{{ $errors->first('talented_candidate') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="">Jobs Expos</label>
                                    <input type="number" name="jobs_expos" value="{{ old('jobs_expos', @$data->jobs_expo) }}" id="jobs_expos"  class="form-control" placeholder="Jobs Expos.">
                                    @if($errors->has('jobs_expos'))
                                        <div class="erroralert">{{ $errors->first('jobs_expos') }}</div>
                                    @endif
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="">Counter Background Image</label>
                                    <input type="file" name="counter_img" accept="image/*" id="counter_img"  class="form-control" placeholder="Jobs Expos.">
                                    <input type="hidden" name="counter_img_old" id="counter_img_old" value="{{ @$data->counter_image}}">
                                    <span><a target="_blank" href="{{ asset('uploads/cms/about/'.@$data->counter_image) }}">{{ @$data->counter_image }}</a></span>
                                    @if($errors->has('counter_img'))
                                        <div class="erroralert">{{ $errors->first('counter_img') }}</div>
                                    @endif
                                </div>
                                <input type="hidden" name="rowid" value="{{ @$data->id }}">
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success" style="width:100%;">Submit</button>
                                </div>
                            </div>
                        </div>
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
        $('#left_content').wysihtml5();
        $('#right_content').wysihtml5();
    });

    function aboutFormSubmit(){
        let heading = $('#heading').val();
        let detail = $('#detail').val();
        let fileds = ['heading', 'detail'];
        showError(fileds, 4000);
    }

    function showError(fileds, timeout){
        for(let i = 0;i < fileds.length;i++){
            if($('#'+fileds[i]).val() == ''){
                let error_span = '<span class="error_'+fileds[i]+'"></span>';
                $('#'+fileds[i]).parent().find('.error_'+fileds[i]).remove();
                $('#'+fileds[i]).parent().append(error_span);
                let emsg = 'Field Value Required.';
                $('#'+fileds[i]).parent().find('.error_'+fileds[i]).text('Error: '+ emsg).css('color', 'red');  
                timeout = (timeout == '' || timeout == undefined) ? 1500 : timeout;
                setTime(timeout);              
            }else{
                if(fileds.length > 0){
                    let totallength = fileds.length - 1;
                    let submitform = $('#'+fileds[totallength]).closest('form');
                    let formid = submitform.attr('id');
                    if(formid !== undefined){
                        $('#'+formid).submit();
                    }else{
                        return false;
                    }
                }else{
                    showError(fileds, timeout);
                }
            }
        };
        function setTime(timeout){
            for(let i = 0;i < fileds.length;i++){
                setTimeout(() => {
                    $('#'+fileds[i]).parent().find('.error_'+fileds[i]).remove();
                }, timeout);
            }
        };
        return false;
    }

    $('#heading').on('keyup', function(){
        $('.error_heading').text('');
    });
    $('#detail').on('keyup', function(){
        $('.error_detail').text('');
    });
</script>
@endsection
