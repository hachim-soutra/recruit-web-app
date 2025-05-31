@extends('admin.layout.app')

@section('title', "Add News Category")

@section('mystyle')
<style>
    .add-edu{
        margin-top: 25px;
    }
    .erroralert{
        color: red;
    }
    .box-header > .box-tools{
        top: -4px !important;
    }
</style>
@endsection

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<section class="content-header">
    <h1>
       Manage News Category
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.news.list') }}">News</a></li>
        <li class="active">Category</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">News Category Add From</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-primary" onclick="addCategory()">Add Category</button>
                        <a href="{{ route('admin.news.list') }}" class="btn btn-box-tool">
                            <button type="button" class="btn btn-default"><i class="fa fa-arrow-left"></i>  Back</button>
                        </a>
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
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th scope="col">#Sl No.</th>
                            <th scope="col">News Category</th>
                            <th scope="col">Category Slug</th>
                            <th scope="col">Creadted Date</th>
                            <th scope="col">Navbar Show</th>
                            <th scope="col" style="text-align: center;width: 11%;">Action</th>
                        </tr>
                        @php $count = 1; @endphp
                        @foreach($categories as $c)
                        <tr>
                            <td>{{ $count++ }}</td>
                            <td>{{ ucfirst(strtolower($c->category_name)) }}</td>
                            <td>{{ $c->category_slug }}</td>
                            <td>{{ date('jS F, Y', strtotime($c->created_at)) }}</td>
                            <td>
                                <select name="navbar_show" class="form-control" onchange="addNavbar(this, '{{ $c->id }}')" id="navbar_show">
                                    <option selected disabled readonly>Choose One</option>
                                    <option value="1" <?php if($c->navbar_show == '1')echo 'selected';?>>Yes</option>
                                    <option value="0" <?php if($c->navbar_show == '0')echo 'selected';?>>No</option>
                                </select>
                            </td>
                            <td>
                                <a href="{{ route('admin.news.category-edit', ['slug' => $c->category_slug]) }}"><button type="button" class="btn btn-sm btn-priamry"><i class="fa fa-edit"></i></button></a>
                                <a href="{{ route('admin.news.category-trash', ['slug' => $c->category_slug]) }}"><button type="button" class="btn btn-sm btn-warning"><i class="fa fa-trash"></i></button></a>
                                @if($c->status == 'A')
                                <a href="{{ route('admin.news.category-change-status', ['slug' => $c->category_slug, 'status' => 'I']) }}"><button type="button" class="btn btn-sm btn-success"><i class="fa fa-check-circle-o"></i></button></a>
                                @else
                                <a href="{{ route('admin.news.category-change-status', ['slug' => $c->category_slug, 'status' => 'A']) }}"><button type="button" class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    <div class="mt-5">

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<div id="categoryAddModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <form action="{{ route('admin.news.add-category') }}" id="categoryAddForm" method="post">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add News Category</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <label for="">Category Name</label>
                <input type="text" class="form-control" name="categoryname" id="categoryname" placeholder="Add News Category Name">
                <span class="error_span"></span>
            </div>
        </div>
      </div>
      <input type="hidden" name="rowid" id="rowid">
      <div class="modal-footer">
        <button type="button" class="btn btn-primary submitbtn" onclick="categoryAddFormSubmit()" style="float: left;">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    </form>

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
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script>
    const APP_URL = '<?= env('APP_URL')?>';
    function addNavbar(element, catid){
        let navbar_show = $(element).val();
        $.ajax({
            url: APP_URL + '/admin/news/category-navbar-add',
            type: 'POST',
            data: {_token: '{{ csrf_token() }}', navbar_show: navbar_show, catid: catid},
            success: function(res){
                console.log(res);
                toastr.success(res);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function(err){
                console.log(err);
            },
        });
    }
</script>
@if( @$type == 'edit' )
<script>
    let type = "{{ @$type }}";
    $('.submitbtn').text("Submit");
    $('#categoryAddModal').modal('hide');
    if(type == 'edit'){
        $('#categoryname').val('{!! @$category->category_name !!}');
        $('#rowid').val('{!! @$category->id !!}');
        $('.submitbtn').text("Update");
        $('#categoryAddModal').modal('show');
    }
</script>
@endif
<script>
    $(document).ready(function(){
        $('#detailnews').wysihtml5();
    });
    function addCategory(){
        $('#categoryAddModal').modal('show');
    }
    function categoryAddFormSubmit(){
        let categoryname = $('#categoryname').val();
        if(categoryname == ''){            
            $('.error_span').text("Field Value Required.").css('color', 'red');  
            $('#categoryAddModal').modal('show');          
        }else{
            $('#categoryAddModal').modal('hide'); 
            $('#categoryAddForm').submit();
        }  
        return false;      
    };  
    $('#categoryname').on('keyup', function(){
        $('.error_span').text('');
    });
</script>
@endsection
