@extends('admin.layout.app')

@section('title', "Profile Details")

@section('mystyle')
@endsection

@section('content')
<section class="content-header">
    <h1>
        Manage Admin Profile
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-name">Update Profile From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ url()->previous() }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>

            <div class="box-body">
                <div class="col-lg-10 col-lg-offset-1">
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">User Settings</h3>
                        </div>
                        <div class="block-content">
                            <form action="{{ route('admin.settings.update') }}" id="settings"  method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="row push">
                                    <div class="col-lg-4">
                                        <p class="font-size-sm text-muted">
                                            Your account’s vital info.
                                        </p>
                                        <br>
                                        @if ($data->avatar)
                                            <img class="profile-student-img img-responsive img-circle" src="{{ $data->avatar }}"
                                            alt="Admin profile picture">
                                        @else
                                            <img class="profile-student-img img-responsive img-circle" src="{{ asset('backend/img/user2-160x160.jpg') }}"
                                            alt="admin profile picture">
                                        @endif
                                    </div>
                                    <div class="col-lg-8 col-xl-5">
                                        <div class="form-group">
                                            <label for="name">Name <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter First Name" value="{{ $data->name}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email<span class="required">*</span></label>
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ $data->email}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Avatar <span class="required"></label>
                                            <input type="file" name="avatar" class="form-control" id="avatar" placeholder="">
                                        </div>


                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-10 col-lg-offset-1">
                    <div class="block">
                        <div class="block-header">
                            <h3 class="block-title">Change Password</h3>
                        </div>

                        <div class="block-content">
                            <form action="{{ route('admin.change.password')}}" id="resetPassFrm" method="POST">
                                @csrf
                                <div class="row push">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <p class="font-size-sm text-muted">
                                                Changing your sign in password is an easy way to keep your account secure.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label for="current_password">Current Password</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password">
                                        </div>
                                        <div class="form-group">
                                            <label for="new_password">New Password</label>
                                            <input type="password" id="new_password" class="form-control"  name="new_password">
                                        </div>
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('myscript')
<script>

    $(document).ready(function() {

        $("#settings").on("submit", function(e) {
            e.preventDefault(e);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: new FormData(this),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".form-group").removeClass("has-error");
                    $(".invalid-feedback").remove();
                    loderStart();
                },
                complete: function() {},
                success: function(response) {
                    loderStop();
                    console.log(response);
                    if (response.data.status === "validation_error") {
                        printError(response.data.message);
                    } else if (response.data.status === "error") {
                        toastr.error(response.data.message);
                    } else {
                        toastr.success(response.data.message);
                        window.location = "{{ route('admin.dashboard') }}";
                    }
                },
                error: function(error) {
                    loderStop();
                    console.log(error);
                }
            });
        });

        $("#resetPassFrm").on("submit", function(e) {
            e.preventDefault(e);
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $(".form-control").removeClass("is-invalid");
                    $(".custom-control-input").removeClass("is-invalid");
                    $(".invalid-feedback").remove();
                    $(".role-error").remove();
                    loderStart();
                },
                complete: function() {},
                success: function(response) {
                    loderStop();
                    if (response.data.status === "validation_error") {
                        printError(response.data.message);
                    } else if (response.data.status === "error") {
                        toastr.error(response.data.message);
                    } else {
                        toastr.success(response.data.message);
                    }
                },
                error: function(error) {
                    loderStop();
                    console.log(error);
                }
            });
        });
    });

</script>
@endsection
