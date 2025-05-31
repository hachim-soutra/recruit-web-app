@extends('admin.layout.app')

@section('title', 'Coach Add')

@section('mystyle')

@endsection

@section('content')
    <section class="content-header">
        <h1>
            Recruit.ie Admin
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.coach.list') }}">Coachs</a></li>
            <li class="active">Add</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Coach Add From</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ route('admin.coach.list') }}" class="btn btn-box-tool">
                                <i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('admin.coach.store') }}" method="post" name="coach-add-frm"
                            enctype="multipart/form-data" id="coach-add-frm" class="coach-add-frm">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="email">Email</label>
                                        <input type="text" name="email" class="form-control" id="email"
                                            placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="mobile">Mobile</label>
                                        <input type="number" min="9" onkeypress="keyDown()" name="mobile"
                                            class="form-control" id="tel" placeholder="Mobile">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="date_of_birth">Date Of Birth</label>
                                <input type="text" name="date_of_birth" class="form-control date_picker" id="date_of_birth" placeholder="YYYY-MM-DD" value="{{ $user->coach->date_of_birth }}">
                                </div>
                            </div> --}}

                                {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="country">Country</label>
                                    <select class="form-control" name="country" id="country">
                                        <option selected readonly disabled>Choose Country</option>
                                        @foreach ($countries as $c)
                                            <option value="{{ $c->name }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                                {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="state">State</label>
                                    <select class="form-control" name="state" id="state">
                                        <option selected readonly disabled>Choose State</option>
                                        @foreach ($states as $s)
                                            <option value="{{ $s->name }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            </div>
                            <div class="row">
                                {{-- <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="city">City</label>
                                    <input type="text" class="form-control" name="cityname" id="cityname" placeholder="Enter City">
                                </div>
                            </div> --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="zip"> EIR Code </label>
                                        <input type="text" name="zip" class="form-control" id="zip"
                                            placeholder="Enter EIR Code">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="control-label" for="address"> Address</label>
                                        <input type="text" name="address" class="form-control" id="address"
                                            placeholder="Address" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="avatar">Avatar</label>
                                        <input type="file" name="avatar" class="form-control" id="avatar"
                                            placeholder="Enter Avater">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="coach_banner">Banner</label>
                                        <input type="file" name="coach_banner" class="form-control" id="coach_banner"
                                            placeholder="Enter Banner">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="contact_link"> Website</label>
                                        <input type="text" name="contact_link" class="form-control" id="contact_link"
                                            placeholder="Url" value="{{ @$user->coach->contact_link }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="linkedin_link"> LinkedIn</label>
                                        <input type="text" name="linkedin_link" class="form-control"
                                            id="linkedin_link" placeholder="Url"
                                            value="{{ @$user->coach->linkedin_link }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="facebook_link"> Facebook</label>
                                        <input type="text" name="facebook_link" class="form-control"
                                            id="facebook_link" placeholder="Url"
                                            value="{{ @$user->coach->facebook_link }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="instagram_link"> Instagram</label>
                                        <input type="text" name="instagram_link" class="form-control"
                                            id="instagram_link" placeholder="Url"
                                            value="{{ @$user->coach->instagram_link }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="contact_link"> Website</label>
                                        <input type="text" name="contact_link" class="form-control" id="contact_link"
                                            placeholder="Address">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">About US</label>
                                    <textarea name="about_us" id="about_us" class="form-control" placeholder="Enter About US"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">FAQ</label>
                                    <textarea name="faq" id="faq" class="form-control" placeholder="Enter FAQs"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Coach Skill Detail</label>
                                    <textarea name="skill_detail" id="skill_detail" class="form-control" placeholder="Enter Coach Skill Detail"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">how we help</label>
                                    <textarea name="help_desk" id="help_desk" class="form-control" placeholder="Enter how we help"></textarea>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-12">
                                    <button id="user-edit-btn-submit" type="submit"
                                        class="btn btn-success btn-block">Save</button>
                                </div>
                            </div>

                            <input type="hidden" id="start_latitude" name="start_latitude" value="" />
                            <input type="hidden" id="start_longitude" name="start_longitude" value="" />

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('myscript')
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#about_us').wysihtml5();
            $('#faq').wysihtml5();
            $('#help_desk').wysihtml5();
            $('#skill_detail').wysihtml5();
        });
    </script>
    <script>
        function initialize() {
            var address = (document.getElementById('address'));
            var autocomplete = new google.maps.places.Autocomplete(address);
            autocomplete.setTypes(['geocode']);
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    return;
                }
                document.getElementById("start_latitude").value = place.geometry.location.lat();
                document.getElementById("start_longitude").value = place.geometry.location.lng();
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
    <script>
        function keyDown(e) {
            var e = window.event || e;
            var key = e.keyCode;
            if (key == 32) { //space
                e.preventDefault();
            }
        }
    </script>
    @include('admin.scripts.coach')
    @include('admin.scripts.location')
@endsection
