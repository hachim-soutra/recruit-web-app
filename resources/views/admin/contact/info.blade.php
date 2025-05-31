@extends('admin.layout.app')

@section('title', "Contact Information")

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
                    <h3 class="box-title">Contact List</h3>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th class="text-center">Subject</th>
                            <th class="text-center">Message Body</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                        @forelse ($data as $e)
                            <tr>
                                <td>{{ $e->name }}</td>
                                <td>{{ $e->email }}</td>
                                <td>{{ $e->phone_number }}</td>
                                <td>{{ substr($e->subject_name, 0, 17) }}..</td>
                                <td>{!! ucfirst(substr($e->sms_body, 0, 17)) !!}..</td>
                                <td>{{ $e->status }}</td>
                                <td>
                                    <a href="javascript:void(0);" data-rowid="{{base64_encode($e->id)}}" onclick="deleteme(this)" class="btn btn-sm btn-warning" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Delete Details"><i class="fa fa-trash"></i></a>
                                    @if($e->status == 'Pending to Replied')
                                    <a href="javascript:void(0);" onclick="replymodal('{{base64_encode($e->id)}}')" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Reply"><i class="fa fa-reply"></i></a>
                                    @endif
                                    <a href="{{ route('admin.contact.full.view', ['id' => base64_encode($e->id)]) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Full View"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>No Record Found</td>
                            </tr>
                        @endforelse
                    </table>
                    <div class="mt-5">
                        {{ $data->appends(Request::except('page'))->links("pagination::bootstrap-4") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <form action="{{ route('admin.contact.reply') }}" method="post" id="replymodal">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Response</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <label for="">Response Message</label>
                        <textarea name="response_message" id="response_message" class="form-control" placeholder="Write Response Message Here."></textarea>
                        <span class="response_message_err"></span>
                    </div>
                </div>
            </div>
            <input type="hidden" name="rowid" id="rowid">
            <div class="modal-footer">
                <button type="button" onclick="submitResponse()" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('myscript')
<script>
    function deleteme(element){
        let rowid = $(element).attr('data-rowid');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                location.href="{{ route('admin.contact.delete') }}" + '/' + rowid;    
            }
        });            
    }

    function replymodal(rowid){
        $('#rowid').val(rowid);
        $('#myModal').modal('show');
    }

    function submitResponse(){
        let msg = $('#response_message').val();
        if(msg == ''){
            $('.response_message_err').text('Response Message Required.').css('color', '#f10c37');
            return;
        }
        $('#replymodal').submit();
    }
</script>
@endsection
