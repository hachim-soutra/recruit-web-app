@extends('admin.layout.app')

@section('title', "Looking for staff | Virtual Recruitment")
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
    Virtual Recruitment
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.news.list') }}">Looking Staff</a></li>
        <li class="active">Virtual Recruitment</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Virtual Recruitment</h3>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">{{session('success')}}</div>
				@endif
                @if(session('error'))
                    <div class="alert alert-danger">{{session('error')}}</div>
				@endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('admin.cms.virtual-recruitment-save') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="page_name" value="virtual recruitment">
                    <input type="hidden" name="rowid" value="{{ @$staff->id }}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Banner Heading</label>
                                <input type="text" name="banner_heading" value="{{ old('banner_heading', @$data->banner_heading) }}" id="banner_heading" placeholder="Banner Heading" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Banner Short Description</label>
                                <input type="text" name="short_description" value="{{ old('short_description', @$data->short_description) }}" id="short_description" placeholder="Banner Short Description" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label for="">Banner file</label>
                                <input type="file" name="banner_file" id="banner_file" placeholder="Banner Short Description" class="form-control">
                                <input type="hidden" name="old_banner_file" value="{{ @$data->banner_file }}">
                                @if(@$data->banner_file != '')
                                <p><a target="_blank" href="{{ asset('uploads/staff/'.@$data->banner_file) }}">{{ @$data->banner_file }}</a></p>
                                @endif
                            </div> 
                        </div>
                        <hr>
                        <div class="row">   
                            <div class="col-md-6">
                                <label for="">Header Title</label>
                                <input type="text" name="page_title" value="{{ old('page_title', @$data->page_title) }}" id="page_title" placeholder="Page Title" class="form-control">
                            </div>                       
                            <div class="col-md-6">
                                <label for="">Left file</label>
                                <input type="file" name="left_file" id="left_file" placeholder="Left Description File" class="form-control">
                                <input type="hidden" name="old_left_file" value="{{ @$data->left_file }}">
                                @if(@$data->left_file != '')
                                <p><a target="_blank" href="{{ asset('uploads/staff/'.@$data->left_file) }}">{{ @$data->left_file }}</a></p>
                                @endif
                            </div> 
                            <div class="col-md-12">
                                <label for="">Page Left Content</label>
                                <textarea name="left_content" value="{{ old('left_content', @$data->left_content) }}" id="left_content" placeholder="Page Left Content" class="form-control">{!! nl2br(@$data->left_content) !!}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="">Page Right Content</label>
                                <textarea name="right_content" value="{{ old('right_content', @$data->right_content) }}" id="right_content" placeholder="Page Right Content" class="form-control">{!! str_replace('<br />', '', @$data->right_content) !!}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label for="">Right file</label>
                                <input type="file" name="right_file" id="right_file" placeholder="Right Description File" class="form-control">
                                <input type="hidden" name="old_right_file" value="{{ @$data->right_file }}">
                                @if(@$data->right_file != '')
                                <p><a target="_blank" href="{{ asset('uploads/staff/'.@$data->right_file) }}">{{ @$data->right_file }}</a></p>
                                @endif
                            </div> 
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Middle Banner Heading</label>
                                <input type="text" name="middle_banner_heading" value="{{ old('middle_banner_heading', @$data->middle_banner_heading) }}" id="middle_banner_heading" placeholder="Middle Banner Heading" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Middle Banner Short Description</label>
                                <input type="text" name="middle_short_description" value="{{ old('middle_short_description', @$data->middle_short_description) }}" id="middle_short_description" placeholder="Middle Banner Short Description" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label for="">Middle Banner file</label>
                                <input type="file" name="middle_banner_file" id="middle_banner_file" placeholder="Middle Banner file" class="form-control">
                                <input type="hidden" name="old_middle_banner_file" value="{{ @$data->middle_banner_file }}">
                                @if(@$data->middle_banner_file != '')
                                <p><a target="_blank" href="{{ asset('uploads/staff/'.@$data->middle_banner_file) }}">{{ @$data->middle_banner_file }}</a></p>
                                @endif
                            </div>
                        </div>
                        <div class="row widgetspan">
                            <?php
                                $wh = explode('|', @$data->widget_heading);
                                $widget_descriptiuon = explode('|', @$data->widget_descriptiuon);
                                if(count($wh) > 0):
                            ?>
                            @for($i = 0;$i < count($wh);$i++)
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="">Middle Banner Widget Heading</label>
                                        <input type="text" name="widget_heading[]" value="{{ @$wh[$i] }}" id="widget_heading_0" placeholder="Middle Banner Widget Heading" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Middle Banner Widget Description</label>
                                        <input type="text" name="widget_descriptiuon[]" value="{{ @$widget_descriptiuon[$i] }}" id="widget_descriptiuon_0" placeholder="Middle Banner Widget Description" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" onclick="addWidgets()" style="margin-top:25px;" class="btn btn-primary">Add Row</button>
                                        <button type="button" onclick="deleteProcessName('{{@$wh[$i]}}', '{{ @$widget_descriptiuon[$i] }}')" style="margin-top:25px;" class="btn btn-warning"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                            @endfor
                            <?php endif; ?>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Event Link</label>
                                <input type="text" name="event_link" value="{{ old('event_link', @$data->event_link) }}" id="event_link" class="form-control" placeholder="Event Link.">
                            </div>
                            <div class="col-md-4">
                                <label for="">Facebook Link</label>
                                <input type="text" name="fb_link" value="{{ old('fb_link', @$data->fb_link) }}" id="fb_link" class="form-control" placeholder="Facebook Link">
                            </div>
                            <div class="col-md-4">
                                <label for="">Twitter Link</label>
                                <input type="text" name="twitter_link" value="{{ old('twitter_link', @$data->twitter_link) }}" id="twitter_link" class="form-control" placeholder="Twitter Link">
                            </div>
                            <div class="col-md-4">
                                <label for="">LinkedIn Link</label>
                                <input type="text" name="linkedin_link" value="{{ old('linkedin_link', @$data->linkedin_link) }}" id="linkedin_link" class="form-control" placeholder="LinkedIn Link">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Bottom Banner Heading</label>
                                <input type="text" name="bottom_banner_heading" value="{{ old('bottom_banner_heading', @$data->bottom_banner_heading) }}" id="bottom_banner_heading" placeholder="Bottom Banner Heading" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Bottom Banner file</label>
                                <input type="file" name="bottom_banner_file" id="bottom_banner_file" placeholder="Bottom Banner file" class="form-control">
                                <input type="hidden" name="old_bottom_banner_file" value="{{ @$data->bottom_banner_file }}">
                                @if(@$data->bottom_banner_file != '')
                                <p><a target="_blank" href="{{ asset('uploads/staff/'.@$data->bottom_banner_file) }}">{{ @$data->bottom_banner_file }}</a></p>
                                @endif
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" style="width:100%;" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    setTimeout(function(){
        $('.alert').hide();
    }, 3500);
</script>
@endsection

@section('myscript')
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
<script>
    let spanWidget = 1;
    $(document).ready(function(){
        $('#right_content').wysihtml5();
        $('#left_content').wysihtml5();
    });
    function addWidgets(){
        let html = '<div class="widgetdiv_'+spanWidget+'"><div class="col-md-12"><div class="row"><div class="col-md-4">';
        html += '<label for="">Middle Banner Widget Heading</label>';
        html += '<input type="text" name="widget_heading[]" id="widget_heading_'+spanWidget+'" placeholder="Middle Banner Widget Heading" class="form-control">';
        html += '</div><div class="col-md-6"><label for="">Middle Banner Widget Description</label>';
        html += '<input type="text" name="widget_descriptiuon[]" id="widget_descriptiuon_'+spanWidget+'" placeholder="Middle Banner Widget Description" class="form-control">';
        html += '</div><div class="col-md-2">';
        html += '<button type="button" onclick="removeWidgets('+spanWidget+')" style="margin-top:25px;" class="btn btn-warning">Removed</button>';
        html += '</div></div></div></div>';
        $('.widgetspan').append(html);
        spanWidget++;
    }
    function removeWidgets(rowdivid){
        $('.row').find('.widgetdiv_'+rowdivid).remove();
    }
    function deleteProcessName(processname, processdes){
        let url = "{{ route('admin.cms.staff-update') }}";
        let fldname = ['widget_heading', 'widget_descriptiuon'];
        let data = {_token: '{{ csrf_token() }}', processname: processname, processdes: processdes, field_name: fldname, page_type: 'virtual-recruitment'};
        removeDynamic(url, data);
    }
    function removeDynamic(url, data){
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(res){
                if(res.code == 403){
                    toastr.warning(res.msg);
                }
                if(res.code == 200){
                    toastr.success(res.msg);
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(err){
                console.log(err);
            }
        });
    }
</script>
@endsection
