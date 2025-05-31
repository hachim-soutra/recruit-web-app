@extends('admin.layout.app')

@section('title', "Looking for staff | Permanent Recruitment")
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script>
    function addAboutUs(){
        $('#aboutmodal').modal('show');
    }
</script>
<section class="content-header">
    <h1>
    Permanent Recruitment
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.news.list') }}">Looking Staff</a></li>
        <li class="active">Permanent Recruitment</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Permanent Recruitment</h3>
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
                <form method="post" enctype="multipart/form-data" action="{{ route('admin.cms.permanent-recruitment-save') }}">
                    @csrf
                    <input type="hidden" name="rowid" value="{{ @$staff->id }}">
                    <input type="hidden" name="page_name" value="Parmanent Recruitment">
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
                                <p><a target="_blank" href="{{ asset('uploads/staff/'. @$data->banner_file) }}">{{ @$data->banner_file }}</a></p>
                                @endif
                            </div> 
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Content Title</label>
                                <input type="text" name="content_title" value="{{ old('content_title', @$data->content_title) }}" id="content_title" placeholder="content Title" class="form-control">
                            </div>    
                            <div class="col-md-6">
                                <label for="">Content file</label>
                                <input type="file" name="content_file" id="content_file" placeholder="Content Description File" class="form-control">
                                <input type="hidden" name="old_content_file" value="{{ @$data->content_file }}">
                                @if(@$data->content_file != '')
                                <p><a target="_blank" href="{{ asset('uploads/staff/'. @$data->content_file) }}">{{ @$data->content_file }}</a></p>
                                @endif
                            </div> 
                            <div class="col-md-12">
                                <label for="">Content Details</label>
                                <textarea name="page_content" value="{{ old('page_content', @$data->page_content) }}" id="page_content" placeholder="Page Content" class="form-control">{{@$data->page_content}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">The Entire Cycle For The Recruitment Process</label>
                            </div>
                        </div>
                        <div class="row widgetspan">
                            <?php
                                $process_name = explode('|', @$data->process_name);
                                if(count($process_name) > 0):
                            ?>
                            @for($i = 0;$i < count($process_name);$i++)
                            <div class="col-md-12">
                                <div class="row">                                
                                    <div class="col-md-10">
                                        <label for="">Process Name</label>
                                        <input type="text" name="process_name[]" value="{{@$process_name[$i]}}" id="process_name_0" placeholder="Process Name" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" onclick="addProcess()" style="margin-top:25px;" class="btn btn-primary">Add Row</button>
                                        <button type="button" onclick="deleteProcessName('{{@$process_name[$i]}}')" style="margin-top:25px;" class="btn btn-warning"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                            @endfor
                            <?php else: ?>
                                <div class="col-md-12">
                                    <div class="row">                                
                                        <div class="col-md-10">
                                            <label for="">Process Name</label>
                                            <input type="text" name="process_name[]" id="process_name_0" placeholder="Process Name" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" onclick="addProcess()" style="margin-top:25px;" class="btn btn-primary">Add Row</button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">The End Result Process</label>
                            </div>
                        </div>
                        <div class="row processdiv">
                            <?php
                                $result_image = explode('|', @$data->result_image);
                                $proccess_des = explode('|', @$data->proccess_des);
                            ?>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="">Result Process Image</label>
                                        <input type="file" name="result_image_one" id="result_image_0" class="form-control">
                                        <span><a target="_blank" href="{{ asset('uploads/staff/'.@$result_image[0]) }}">{{@$result_image[0]}}</a></span>
                                        <input type="hidden" name="old_result_image_one" value="{{@$result_image[0]}}" id="old_result_image_0" class="form-control">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Process Short Description</label>
                                        <input type="text" placeholder="Result Process Short Description" value="{{ @$proccess_des[0] }}" name="proccess_des_one" id="proccess_des_0" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="">Result Process Image</label>
                                        <input type="file" name="result_image_two" id="result_image_1" class="form-control">
                                        <span><a target="_blank" href="{{ asset('uploads/staff/'.@$result_image[1]) }}">{{@$result_image[1]}}</a></span>
                                        <input type="hidden" name="old_result_image_two" value="{{@$result_image[1]}}" id="old_result_image_1" class="form-control">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Process Short Description</label>
                                        <input type="text" placeholder="Result Process Short Description" value="{{ @$proccess_des[1] }}" name="proccess_des_two" id="proccess_des_0" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="">Result Process Image</label>
                                        <input type="file" name="result_image_three" id="result_image_2" class="form-control">
                                        <span><a target="_blank" href="{{ asset('uploads/staff/'.@$result_image[2]) }}">{{@$result_image[2]}}</a></span>
                                        <input type="hidden" name="old_result_image_three" value="{{@$result_image[2]}}" id="old_result_image_2" class="form-control">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Process Short Description</label>
                                        <input type="text" placeholder="Result Process Short Description" value="{{ @$proccess_des[2] }}" name="proccess_des_three" id="proccess_des_0" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Gallery <sub>(Upload Multiple Gallery Images.)</sub></label>
                                <input type="file" name="gallery[]" id="gallery" class="form-control" multiple>
                                <input type="hidden" name="old_gallery" value="{{ @$data->gallery }}">
                                @if(@$data->gallery != '')
                                <p>
                                <?php
                                    $file = explode('|', @$data->gallery);
                                    for($i = 0;$i < count($file);$i++):
                                ?>
                                {{ $i + 1 }})<a target="_blank" href="{{ asset('uploads/staff/'.$file[$i]) }}">{{ $file[$i] }}</a>&nbsp;&nbsp;
                                <?php endfor; ?>
                                </p>
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
    let spanWidget = process = 1;
    $(document).ready(function(){
        $('#page_content').wysihtml5();
    });
    function addProcess(){
        let html = '<div class="widgetdiv_'+spanWidget+'"><div class="col-md-12"><div class="row"><div class="col-md-10">';
        html += '<label for="">Process Name</label>';
        html += '<input type="text" name="process_name[]" id="process_name_'+spanWidget+'" placeholder="Process Name" class="form-control">';
        html += '</div><div class="col-md-2">';
        html += '<button type="button" onclick="removeWidgets('+spanWidget+')" style="margin-top:25px;" class="btn btn-warning">Removed</button>';
        html += '</div></div></div></div>';
        $('.widgetspan').append(html);
        spanWidget++;
    }
    function removeWidgets(rowdivid){
        $('.row').find('.widgetdiv_'+rowdivid).remove();
    }
    function addEndProcess(){
        let html = '<div class="processdiv_'+process+'"><div class="col-md-12"><div class="row"><div class="col-md-5">';
        html += '<label for="">Result Process Image</label><input type="file" name="result_image[]" id="result_image_'+process+'" class="form-control">';
        html += '</div><div class="col-md-5"><label for="">Process Short Description</label>';
        html += '<input type="text" placeholder="Result Process Short Description" name="proccess_des[]" id="proccess_des_'+process+'" class="form-control">';
        html += '</div><div class="col-md-2">';
        html += '<button type="button" onclick="removeEndProcess('+process+')" style="margin-top:25px;" class="btn btn-warning">Removed</button>';
        html += '</div></div></div></div>';
        $('.processdiv').append(html);
        process++;
    }
    function removeEndProcess(rowdivid){
        $('.row').find('.processdiv_'+rowdivid).remove();
    }
    function deleteProcessName(processname){
        let url = "{{ route('admin.cms.staff-update') }}";
        let data = {_token: '{{ csrf_token() }}', processname: processname, field_name: 'process_name', page_type: 'parmanent-recruitment'};
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
