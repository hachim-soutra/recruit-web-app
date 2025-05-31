@extends('admin.layout.app')

@section('title', "Contact Information Single")

@section('mystyle')
<style>
    .subscribed{
        height: 28px;
        width: 99px;
        background-color: #4caf50;
        border-radius: 10px;
        /* border: 1px solid; */
        display: flex;
        justify-content: center;
        color: white;
        font-size: large;
        font-weight: 600;
    }
    .unsubscribed{
        height: 28px;
        width: 99px;
        background-color: #dd4b39;
        border-radius: 10px;
        /* border: 1px solid; */
        display: flex;
        justify-content: center;
        color: white;
        font-size: large;
        font-weight: 600;
    }
    .box{
        height: 500px !important;
    }
    .p2r{
        color: #f10c37;
    }
    .r{
        color: #009688;
    }
</style>
@endsection

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<section class="content-header">
    <h1>
        Contact Information
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Contacts</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Sender Information</h3>
                    <a href="{{ URL::previous() }}"><button type="button" style="float:right;" class="btn btn-sm btn-defalut"><i class="fa fa-arrow-left"></i>&nbsp;Back</button></a>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-8">
                            <label for="">Sender Name</label>
                            <input type="text" readonly id="sendername" value="{{ $data->name }}" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="">Email-ID</label>
                            <input type="text" readonly id="sendername" value="{{ $data->email }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label for="">Phone</label>
                            @if( $data->phone_number == '' )
                            <input type="text" readonly id="sendername" value="N/A" class="form-control">
                            @else
                            <input type="text" readonly id="sendername" value="{{ $data->phone_number }}" class="form-control">
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label for="">Dated</label>
                            <input type="text" readonly id="senderdate" class="form-control" value="{{ date('jS F, Y H:i a', strtotime($data->created_at)) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">Status</label>
                            <?php $colorstatus = ($data->status == 'Replied') ? 'r' : 'p2r'?>
                            <input type="text" readonly id="senderdate" class="form-control {{$colorstatus}}" value="{{ $data->status }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="">Subject</label>
                            <textarea class="form-control" readonly>{!! nl2br($data->subject_name) !!}</textarea>
                        </div>
                        <div class="col-md-8">
                            <label for="">Message Body</label>
                            <textarea class="form-control" readonly>{!! strip_tags($data->sms_body) !!}</textarea>
                        </div>
                        @if($data->replymsg != '')
                        <div class="col-md-8">
                            <label for="">Replied Message</label>
                            <textarea class="form-control" readonly>{!! strip_tags($data->replymsg) !!}</textarea>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('myscript')
<script>

</script>
@endsection
