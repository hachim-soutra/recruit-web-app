@extends('admin.layout.app')

@section('title', "Home Content")
@section('mystyle')
<style>
    .add-edu{
        margin-top: 25px;
    }
    .erroralert{
        color: red;
    }
    .pac-container{
        z-index: 99999 !important;
    }
</style>
@endsection

@section('content')
<section class="content-header">
    <h1>
    Home Content
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.news.list') }}">Home Content</a></li>
        <li class="active">Content</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Home Content</h3>
                </div>
                <div class="box-body">
                    <form id="banner_form">
                        <div class="container">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Banner Change</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <label for="">Banner Heading</label>
                                        <input type="text" name="banner_heading" value="{{ @$home->banner_heading }}" id="banner_heading" class="form-control" placeholder="ENter Banner Heading.">
                                    </div>
                                    <div class="col-md-10">
                                        <label for="">Banner Picture</label>
                                        <input type="file" name="banner_file" id="banner_file" class="form-control">
                                    </div>
                                    <input type="hidden" name="banner_file_old" id="banner_file_old" value="{{ @$home->banner_file }}">
                                    <div class="col-md-5">
                                        <button type="button" onclick="bannerSave('banner_form')" style="margin-top: 25px;" class="btn btn-primary"><i class="fa fa-send-o"></i>Submit</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Banner Picture</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <img src="{{ $home->banner_file_get }}" height="100px;" width="auto" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <form id="recruitment_form">
                        <div class="container">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Recruitment Content</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="">Heading</label>
                                        <input type="text" name="recruitment_heading" value="{{ @$home->recruitment_content_heading }}" id="recruitment_heading" class="form-control" placeholder="Enter Recruitment Heading.">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="">Description</label>
                                        <textarea name="recruitment_description" id="recruitment_description" class="form-control" placeholder="Enter Recruitment Description.">{{ @$home->recruitment_content_description }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="button" onclick="recruitmentSave('recruitment_form')" style="margin-top: 25px;" class="btn btn-primary"><i class="fa fa-send-o"></i>Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <form id="recruitment_type_form">
                        <div class="container">
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Recruitment Type</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Recruitment Type</label>
                                        <select name="recruitment_type" id="recruitment_type" class="form-control">
                                            <option disabled selected readonly>Choose One</option>
                                            <option value="permanent-recruitment">Permanent Recruitment</option>
                                            <option value="virtual-recruitment-event">Virtual Recruitment</option>
                                            <option value="jobs-expo">Jobs Expo</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="edit_rowid" id="edit_rowid">
                                    <div class="col-md-9">
                                        <label for="">Type Picture</label>
                                        <input type="file" name="recruitment_type_picture" id="recruitment_type_picture" class="form-control" placeholder="Enter Recruitment Description.">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" onclick="recruitmentTypeSave('recruitment_type_form')" style="margin-top: 25px;" class="btn btn-primary"><i class="fa fa-send-o"></i>Submit</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <label for="">Type Picture</label>
                                <div class="col-md-12">
                                    <div class="row">
                                        @foreach($type as $t)
                                        <div class="col-md-4 type_{{@$t->id}}">
                                            <div class="row"><p><small>{{ @$t->recruitment_type_file }}</small></p></div>
                                            <div class="row">
                                                <img src="{{ @$t->type_file }}" height="100px" width="auto" alt="">
                                                <i id="" data-row_id="{{ @$t->id }}" data-recruitment_type="{{@$t->recruitment_type}}" onclick="trashSingleType('{{@$t->id}}')" class="fa fa-trash"></i>
                                                <i id="edit_{{@$t->id}}" data-old_file="{{@$t->type_file}}" data-row_id="{{ @$t->id }}" data-recruitment_type="{{@$t->recruitment_type}}" onclick="editSingleType('{{@$t->id}}')" class="fa fa-edit"></i>
                                            </div>
                                            <div class="row">{{ @$t->recruitment_type }}</div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <form id="career_coach_text_form">
                        <div class="container">
                            <div class="col-md-12">
                                <h5>Career Coach</h5>
                            </div>
                            <div class="col-md-6">
                                <label for="">Career Coach Text</label>
                                <input type="text" name="career_coach_text" class="form-control" value="{{ @$home->career_coach_text  }}" id="career_coach_text" placeholder="Change Career Coach Text.">
                            </div>
                            <div class="col-md-3">
                                <button type="button" onclick="careertext('career_coach_text_form')" style="margin-top: 25px;" class="btn btn-primary"><i class="fa fa-send-o"></i>Submit</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <form id="counting_form">
                        <div class="container">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Homepage Counting</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Years in Recruitment</label>
                                        <input type="text" name="glorious_years" value="{{ @$home->glorious_years }}" id="glorious_years" class="form-control" placeholder="Years in Recruitment">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Jobs Filled</label>
                                        <input type="text" name="jobs_filled" value="{{ @$home->job_filled }}" id="jobs_filled" class="form-control" placeholder="Enter Jobs Filled.">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Job Vacancies</label>
                                        <input type="text" name="job_vacancies" value="{{ @$home->job_vacancy }}" id="job_vacancies" class="form-control" placeholder="Enter Job Vacancies.">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <button type="button" onclick="countingSave('counting_form')" style="margin-top: 25px;" class="btn btn-primary"><i class="fa fa-send-o"></i>Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="edittypemodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Update Recruitment Type</h4>
      </div>
      <div class="modal-body">
        <form id="recruitment_type_edit_form">
            <div class="row">
                <div class="col-md-12">
                    <label for="">Recruitment Type</label>
                    <select name="recruitment_type" id="recruitment_type_edit" class="form-control">
                        <option disabled selected readonly>Choose One</option>
                        <option value="permanent-recruitment">Permanent Recruitment</option>
                        <option value="virtual-recruitment-event">Virtual Recruitment</option>
                        <option value="jobs-expo">Jobs Expo</option>
                    </select>
                </div>
                <input type="hidden" name="edit_rowid" id="edit_rowid">
                <input type="hidden" name="recruitment_type_picture_old" id="recruitment_type_picture_old">
                <div class="col-md-9">
                    <label for="">Type Picture</label>
                    <input type="file" name="recruitment_type_picture" id="recruitment_type_picture" class="form-control" placeholder="Enter Recruitment Description.">
                </div>
                <div class="col-md-3">
                    <button type="button" onclick="recruitmentTypeSave('recruitment_type_edit_form')" style="margin-top: 25px;" class="btn btn-primary"><i class="fa fa-send-o"></i>Submit</button>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
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
    const APP_URL = '<?= env('APP_URL')?>';
    $(document).ready(function(){
        $('#recruitment_description').wysihtml5();
    });
    function bannerSave(formid){
        let fd = new FormData($('#'+formid)[0]);
        let imgfile = $('#'+formid).find('#banner_file')[0].files[0];
        imgfile = (imgfile == undefined) ? '' : imgfile;
        fd.append('banner_file', imgfile);
        fd.append('_token', '{{ csrf_token() }}');
        commonajax("{{ route('admin.cms.home-banner-save') }}", fd);
    }

    function recruitmentSave(formid){
        let fd = new FormData($('#'+formid)[0]);
        fd.append('_token', '{{ csrf_token() }}');
        commonajax("{{ route('admin.cms.home-recruitment-content') }}", fd);
    }

    function countingSave(formid){
        let fd = new FormData($('#'+formid)[0]);
        fd.append('_token', '{{ csrf_token() }}');
        commonajax("{{ route('admin.cms.home-counting') }}", fd);
    }

    function recruitmentTypeSave(formid){
        let fd = new FormData($('#'+formid)[0]);
        fd.append('_token', '{{ csrf_token() }}');
        commonajax("{{ route('admin.cms.home-recruitment-type') }}", fd);
    }

    function careertext(formid){
        let fd = new FormData($('#'+formid)[0]);
        fd.append('_token', '{{ csrf_token() }}');
        commonajax("{{ route('admin.cms.home-career-text') }}", fd);
    }

    function commonajax(url, fd){
        $.ajax({
            url: url,
            type: 'POST',
            enctype: 'multipart/form-data',
            data: fd,
            contentType: false,
            processData: false,
            cache: false,
            success: function(res){
                if(res.code == '200'){
                    toastr.success(res.msg);
                    $('#edittypemodal').modal('hide');
                    location.reload();
                };
                if(res.code == '403'){
                    if(res.for == 'home_banner'){
                        bannerError(res);
                    };
                    if(res.for == "home_recruitment_content"){
                        recruitment_contentError(res);
                    };
                    if(res.for == 'home_counting'){
                        recruitmentCounting(res);
                    };
                    if(res.for == 'home_recruitment_type'){
                        recruitmentType(res);
                    };
                };
            },
            error: function(err){
                console.log(err);
            }
        });
    }

    function bannerError(res){
        let err = res.error;
        if(err.hasOwnProperty('banner_file')){
            for(let i = 0;i < err.banner_file.length;i++){
                toastr.error(err.banner_file[i]);
            }
        };
        if(err.hasOwnProperty('banner_heading')){
            for(let i = 0;i < err.banner_heading.length;i++){
                toastr.error(err.banner_heading[i]);
            }
        };
    }
    function recruitment_contentError(res){
        let err = res.error;
        if(err.hasOwnProperty('recruitment_description')){
            for(let i = 0;i < err.recruitment_description.length;i++){
                toastr.error(err.recruitment_description[i]);
            }
        };
        if(err.hasOwnProperty('recruitment_heading')){
            for(let i = 0;i < err.recruitment_heading.length;i++){
                toastr.error(err.recruitment_heading[i]);
            }
        };
    }
    function recruitmentCounting(res){
        let err = res.error;
        if(err.hasOwnProperty('glorious_years')){
            for(let i = 0;i < err.glorious_years.length;i++){
                toastr.error(err.glorious_years[i]);
            }
        };
        if(err.hasOwnProperty('jobs_filled')){
            for(let i = 0;i < err.jobs_filled.length;i++){
                toastr.error(err.jobs_filled[i]);
            }
        };
        if(err.hasOwnProperty('job_vacancies')){
            for(let i = 0;i < err.job_vacancies.length;i++){
                toastr.error(err.job_vacancies[i]);
            }
        };
    }
    function recruitmentType(res){
        let err = res.error;
        if(err.hasOwnProperty('recruitment_type')){
            for(let i = 0;i < err.recruitment_type.length;i++){
                toastr.error(err.recruitment_type[i]);
            }
        };
        if(err.hasOwnProperty('recruitment_type_picture')){
            for(let i = 0;i < err.recruitment_type_picture.length;i++){
                toastr.error(err.recruitment_type_picture[i]);
            }
        };
    }
    function trashSingleType(typeid){
        if(!confirm("DO You Want To Delete?"))return false;
        location.href="{{ route('admin.cms.home-recruitment-type-delete') }}" + '/' + typeid;
    }
    function editSingleType(typeid){
        let rowid = $('#edit_'+typeid).attr('data-row_id');
        let recruitment_type = $('#edit_'+typeid).attr('data-recruitment_type');
        let old_file = $('#edit_'+typeid).attr('data-old_file');
        $('#recruitment_type_edit option[value='+recruitment_type+']').attr('selected','selected');
        $('#edit_rowid').val(rowid);
        $('#recruitment_type_picture_old').val(old_file);
        $('#edittypemodal').modal('show');
    }
</script>
<script>
    function initialize() {
        var address = (document.getElementById('location'));
        var autocomplete = new google.maps.places.Autocomplete(address);
        autocomplete.setTypes(['geocode']);
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }
            document.getElementById("start_latitude").innerHTML  = place.geometry.location.lat();
            document.getElementById("start_longitude").innerHTML  = place.geometry.location.lng();
            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
            }
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endsection
