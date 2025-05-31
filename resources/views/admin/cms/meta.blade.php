@extends('admin.layout.app')

@section('title', "Meta Seo")
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
    .wysihtml5-sandbox{
        height: 90px !important;
    }
</style>
@endsection

@section('content')
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>
<script>
    function addAboutUs(){
        $('#aboutmodal').modal('show');
    }
</script>
<section class="content-header">
    <h1>
       Meta Seo
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.news.list') }}">Meta Seo</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Meta Seo</h3>
                    <div class="box-tools pull-right">
                        <button type="button" onclick="openMetaForm()" class="btn btn-sm btn-primary addbtn">Add</button>
                        <!-- <a href="{{ route('admin.cms.about-us') }}" class="btn btn-box-tool"><button type="button" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i>  Back</button></a> -->
                    </div>
                </div>
                
                
                <div class="box-body">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">Page Name</th>
                            <th scope="col">Meta Title</th>
                            <th scope="col">Meta Tags</th>
                            <th scope="col">Meta Description</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                        @forelse (@$data as $key => $e)
                            <tr>
                                <td style="width:15%">{{ ucwords(str_replace('_', ' ', $e->page_name)) }}</td>
                                <td style="width:20%">{{ ucfirst($e->meta_title) }}</td>
                                <?php 
                                    $str_tag = '';
                                    $tags = $e->meta_tags;
                                    $explode_tags = explode(',', $tags);
                                    for($i = 0;$i < count($explode_tags);$i++):
                                        $str_tag .= ucwords($explode_tags[$i]);
                                        if($i <= (count($explode_tags) - 2)){
                                            $str_tag .= ', ';
                                        }
                                    endfor;
                                ?>
                                <td style="width:20%">{{ $str_tag }}</td>
                                <td style="width:35%">{!! nl2br(ucfirst($e->meta_description)) !!}</td>
                                <td style="width:10%">
                                    <a href="javascript:void(0);" onclick="editMetaInfo('{{ base64_encode($e->id) }}')"><i class="fa fa-pencil"></i></a>
                                    <a href="javascript:void(0);" onclick="trashMetaInfo('{{ base64_encode($e->id) }}')"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center">No Data found</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="metaform" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Meta Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.cms.meta-info-save') }}" method="post" id="metainfosaveform">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <label for="">Page Dropdrown</label>
                    <select name="page_name" id="page_name" value="{{ old('page_name') }}" class="form-control">
                        <option disabled readonly selected>Choose One</option>
                        <option value="home">Home Page</option>
                        <option value="news_detail">News Details</option>
                        <option value="coach_info">Career Coach Info</option>
                        <option value="login_dashboard">Dashboard</option>
                        <option value="applied_job_detail">Applied Job Details</option>
                        <option value="subscription">Subscription</option>
                    </select>
                    @if($errors->has('page_name'))
                        <div class="erroralert">{{ $errors->first('page_name') }}</div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="">Meta Tag</label>
                    <input type="text" name="meta_tags" value="{{ old('meta_tags') }}" id="meta_tags" class="form-control" placeholder="Enter Meta Tags">
                    <span><small>Comma Seperated Text. E.g., contact, contact-us</small></span>
                    @if($errors->has('meta_tags'))
                        <div class="erroralert">{{ $errors->first('meta_tags') }}</div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label for="">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" id="meta_title" class="form-control" placeholder="Enter Meta Title">
                    @if($errors->has('meta_title'))
                        <div class="erroralert">{{ $errors->first('meta_title') }}</div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="">Meta Description</label>
                    <textarea name="meta_description" value="{{ old('meta_description') }}" id="meta_description" class="form-control" placeholder="Enter Meta Description"></textarea>
                    @if($errors->has('meta_description'))
                        <div class="erroralert">{{ $errors->first('meta_description') }}</div>
                    @endif
                </div>
            </div>
            <input type="hidden" name="rowid" id="rowid">
            <br>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success" style="width:100%">Submit</button>
                </div>
            </div>
        </form>
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
    $(document).ready(function(){
        CKEDITOR.replace('meta_description', { height: 100 });
    });
    function openMetaForm(){
        $('#metaform').modal('show');
        resetform();
    }
    function trashMetaInfo(metarowid){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                location.href="{{ route('admin.cms.meta-info-delete') }}" + '/' + metarowid;
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }
        })
        
    }
    function editMetaInfo(metarowid){
        $.ajax({
            url: "{{ route('admin.cms.meta-info-get') }}",
            type: 'POST',
            data: {'_token': '{{ csrf_token() }}', 'rowid': metarowid},
            success: function(res){
                $('#metaform').modal('show');
                let form = $('#metainfosaveform');
                form.find('#page_name option[value='+res.page_name+']').prop('selected', true);
                form.find('#meta_tags').val(res.meta_tags);
                form.find('#meta_title').val(res.meta_title);
                CKEDITOR.instances['meta_description'].setData(res.meta_description);
                form.find('#rowid').val(res.id);                
            },
            error: function(err){
                console.log(err);
            }
        });
    }

    function resetform(){
        $('#metainfosaveform')[0].reset();
        let form = $('#metainfosaveform');
        form.find('#page_name').val($("#page_name option:first").val());
        CKEDITOR.instances['meta_description'].setData('');        
    }
</script>
@if($errors->any())
    <script>
        $('#metaform').modal('show');
    </script>
@endif
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
@endsection
