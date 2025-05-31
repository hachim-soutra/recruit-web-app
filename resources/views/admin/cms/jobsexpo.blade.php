@extends('admin.layout.app')

@section('title', "Looking for staff | Job Expo")
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
    Job Expo
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.news.list') }}">Looking Staff</a></li>
        <li class="active">Job Expo</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Expo</h3>
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
                <form action="{{ route('admin.cms.jobs-expo-save') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <input type="hidden" name="rowid" value="{{ @$staff->id }}">
                    <input type="hidden" name="page_name" value="Jobs Expo">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Banner Heading</label>
                                <input type="text" name="banner_heading" value="{{ old('banner_heading', @$data->banner_heading) }}" id="banner_heading" placeholder="Banner Heading" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for="">Banner file</label>
                                <input type="file" name="banner_file" id="banner_file" placeholder="Banner Short Description" class="form-control">
                                <input type="hidden" name="old_banner_file" value="{{ @$data->banner_file }}">
                                @if(@$data->banner_file != '')
                                    <a target="_blank" href="{{ asset('uploads/staff/'.@$data->banner_file) }}">{{ @$data->banner_file }}</a>
                                @endif
                            </div> 
                        </div>
                        <hr>
                        <div class="row">                        
                            <div class="col-md-6">
                                <label for="">Left file</label>
                                <input type="file" name="left_file" id="left_file" placeholder="Left Description File" class="form-control">
                                <input type="hidden" name="old_left_file" value="{{ @$data->left_file }}">
                                @if(@$data->left_file != '')
                                    <a target="_blank" href="{{ asset('uploads/staff/'.@$data->left_file) }}">{{ @$data->left_file }}</a>
                                @endif
                            </div> 
                            <div class="col-md-6">
                                <label for="">Right file</label>
                                <input type="file" name="right_file" id="right_file" placeholder="Right Description File" class="form-control">
                                <input type="hidden" name="old_right_file" value="{{ @$data->right_file }}">
                                @if(@$data->right_file != '')
                                    <a target="_blank" href="{{ asset('uploads/staff/'.@$data->right_file) }}">{{ @$data->right_file }}</a>
                                @endif
                            </div> 
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Page Right Content</label>
                                <textarea name="right_content" value="{{ old('banner_heading', @$data->right_content) }}" id="right_content" placeholder="Page Right Content" class="form-control">{{@$data->right_content}}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="">Page Left Content</label>
                                <textarea name="left_content" value="{{ old('banner_heading', @$data->left_content) }}" id="left_content" placeholder="Page Left Content" class="form-control">{{@$data->left_content}}</textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Candidates Count</label>
                                <input type="number" name="candidate_count" value="{{ old('candidate_count', @$data->candidate_count) }}" id="candidate_count" placeholder="Candidates Count" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="">Employers Count</label>
                                <input type="number" name="employer_count" value="{{ old('employer_count', @$data->employer_count) }}" id="employer_count" placeholder="Employers Count" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="">Roles Count</label>
                                <input type="number" name="roles_count" value="{{ old('roles_count', @$data->roles_count) }}" id="roles_count" placeholder="Roles Count" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Tech Careers Process</label>
                            </div>
                        </div>
                        <div class="row widgetspan">
                            <?php
                                $process_icon = explode('|', @$data->process_icon);
                                $process_name = explode('|', @$data->process_name);
                                $process_detail = explode('|', @$data->process_detail);
                                if(count($process_icon) > 0):
                            ?>
                            @for($i = 0;$i < count($process_icon);$i++)
                            <div class="col-md-12">
                                <div class="row">                                
                                    <div class="col-md-2">
                                        <label for="">Process Icon</label>
                                        <input type="text" name="process_icon[]" value="{{ $process_icon[$i] }}" id="process_icon_0" placeholder="Process Icon" class="form-control">
                                    </div>    
                                    <div class="col-md-3">
                                        <label for="">Process Name</label>
                                        <input type="text" name="process_name[]" value="{{ $process_name[$i] }}" id="process_name_0" placeholder="Process Name" class="form-control">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="">Process Details</label>
                                        <input type="text" name="process_detail[]" value="{{ $process_detail[$i] }}" id="process_detail_0" placeholder="Process Detail" class="form-control">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" onclick="addProcess()" style="margin-top:25px;" class="btn btn-primary">Add Row</button>
                                        <button type="button" onclick="deleteProcessName('{{ $process_icon[$i] }}', '{{@$process_name[$i]}}', '{{ $process_detail[$i] }}')" style="margin-top:25px;" class="btn btn-warning"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                            @endfor
                            <?php endif; ?>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Gallery Short Description</label>
                                <input type="text" name="gallery_short_desc" value="{{ @$data->gallery_short_desc }}" placeholder="Gallery Short Description" id="gallery_short_desc" class="form-control">
                            </div>
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
                                <button type="Submit" style="width:100%;" class="btn btn-success">Submit</button>
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
        $('#right_content').wysihtml5();
        $('#left_content').wysihtml5();
    });
    function addProcess(){
        let html = '<div class="widgetdiv_'+spanWidget+'"><div class="col-md-12"><div class="row">';
        html += '<div class="col-md-2"><label for="">Process Icon</label>';
        html += '<input type="text" name="process_icon[]" id="process_icon_'+spanWidget+'" placeholder="Process Icon" class="form-control">';
        html += '</div><div class="col-md-3"><label for="">Process Name</label>';
        html += '<input type="text" name="process_name[]" id="process_name_'+spanWidget+'" placeholder="Process Name" class="form-control">';
        html += '</div><div class="col-md-5"><label for="">Process Details</label>';
        html += '<input type="text" name="process_detail[]" id="process_detail_'+spanWidget+'" placeholder="Process Detail" class="form-control">';
        html += '</div><div class="col-md-2">';
        html += '<button type="button" onclick="removeWidgets('+spanWidget+')" style="margin-top:25px;" class="btn btn-warning">Removed</button>';
        html += '</div></div></div></div>';
        $('.widgetspan').append(html);
        spanWidget++;
    }
    function removeWidgets(rowdivid){
        $('.row').find('.widgetdiv_'+rowdivid).remove();
    }
    function deleteProcessName(process_icon, processname, process_detail){
        let url = "{{ route('admin.cms.staff-update') }}";
        let fldname = ['process_icon', 'process_name', 'process_detail'];
        let data = {_token: '{{ csrf_token() }}', process_icon: process_icon, processname: processname, 
            process_detail: process_detail, field_name: fldname, page_type: 'jobs-expo'};
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
