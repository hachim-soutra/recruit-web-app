@extends('admin.layout.app')

@section('title', 'Footer Content')
@section('mystyle')
    <style>
        .add-edu {
            margin-top: 25px;
        }

        .erroralert {
            color: red;
        }

        .pac-container {
            z-index: 99999 !important;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Footer Content
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.news.list') }}">Footer Content</a></li>
            <li class="active">Content</li>
        </ol>
    </section>

    <!-- =============Main Page====================== -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Footer Content</h3>
                    </div>
                    <div class="box-body">
                        <form id="banner_form">
                            <div class="container">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5>Social Links</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <label for="">Twitter <i class="fa fa-twitter"></i></label>
                                            <input type="text" name="twitter" value="{{ @$home->banner_heading }}"
                                                id="banner_heading" class="form-control"
                                                placeholder="ENter Banner Heading.">
                                        </div>
                                        <div class="col-md-10">
                                            <label for="">Twitter <i class="fa fa-twitter"></i></label>
                                            <input type="file" name="banner_file" id="banner_file" class="form-control">
                                        </div>
                                        <input type="hidden" name="banner_file_old" id="banner_file_old"
                                            value="{{ @$home->banner_file }}">
                                        <div class="col-md-5">
                                            <button type="button" onclick="bannerSave('banner_form')"
                                                style="margin-top: 25px;" class="btn btn-primary"><i
                                                    class="fa fa-send-o"></i>Submit</button>
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
                                            <img src="{{ $home->banner_file_get }}" height="100px;" width="auto"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="start_latitude" name="start_latitude" value="" />
                            <input type="hidden" id="start_longitude" name="start_longitude" value="" />

                        </form>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        setTimeout(function() {
            $('.erroralert').hide();
        }, 3500);
    </script>
@endsection

@section('myscript')
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <script>
        $(document).ready(function() {});
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
                document.getElementById("start_latitude").innerHTML = place.geometry.location.lat();
                document.getElementById("start_longitude").innerHTML = place.geometry.location.lng();
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
