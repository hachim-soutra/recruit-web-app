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
<section class="content-header">
    <h1>
       Manage News
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.news.list') }}">News</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">News Add From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.news.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form name="subjectCreateFrm" id="subjectCreateFrm" action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Category</label>
                                <select name="newscategory" id="newscategory" class="form-control">
                                    <option disabled selected readonly>Choose a category</option>
                                    @foreach($newstype as $nc)  
                                        <option value="{{ $nc->id }}" <?php if($nc->id == @$detail->news_category_id)echo 'selected';?>>{{ $nc->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">News Title</label>
                                    <input type="text" autocomplete="off" value="{{ @$detail->title }}" class="form-control" id="title" name="title" placeholder="Your News Title/ Headings.">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description">Expire Date</label>
                                    <input type="date" min="<?php echo date('Y-m-d')?>" value="{{ @$detail->expire_date }}" class="form-control" id="expiredate" name="expiredate" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">News Details</label>
                                    <textarea name="detailnews" id="detailnews" class="form-control" placeholder="Write Down Your News Description.">{{ @$detail->newsdetail }}</textarea>
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
                        <a href="{{ route('admin.news.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        @if(count((array)@$detail) > 0)
                        <div class="col-md-12" style="display: flex;align-items: center;justify-content: center;">
                            <img src="{{env('APP_URL').'/uploads/'.(@$detail->image)}}" alt="" style="height: auto;width: 100%;">
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
    }, 3500);
</script>
@endsection

@section('myscript')
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#detailnews').wysihtml5()
    });
</script>
@endsection
