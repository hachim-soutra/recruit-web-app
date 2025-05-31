@extends('site.layout.app')
@section('title', 'Find Job')
@section('mystyle')
    <style>
        .bd-block {
            display: flex;
            justify-content: center;
        }

        .select2-container .select2-selection--single {
            display: flex !important;
            height: 45px !important;
        }
    </style>
@endsection
@section('content')
    <!-- banner-block -->
    <div class="login-block">
        <div class="container">
            <div class="bd-block">
                <div class="item-headline findjobmain">
                    <h4>Find Job</h4>
                    <p>It's the very first thing clients see, so make it count. Stand out by describing your expertise in
                        your own words.</p>
                    <form action="{{ route('common.job-listing') }}" method="post">
                        @csrf

                        <div class="form-group">
                            <label for="">Job Title</label>
                            <input type="text" name="keyword" id="job_title" class="form-control"
                                placeholder="Job title, keywords, or company">
                        </div>


                        <div class="form-group">
                            <label for="">Location</label>
                            <input type="text" name="job_location" id="job_location" class="form-control"
                                placeholder="Country, State, City" />

                            <input type="hidden" id="start_latitude" name="start_latitude" value="" />
                            <input type="hidden" id="start_longitude" name="start_longitude" value="" />
                        </div>


                        <div class="form-group">
                            <label for="">Which Sector?</label>
                            <select name="sector" id="sector" class="form-control">
                                <option selected disabled>Select Position</option>
                                @foreach ($data['industries'] as $i)
                                    <option value="{{ $i->name }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="from-group">
                            <button type="submit" class="btn btn-submit"
                                style="background-color:#eb1829;color:white;width:100%;">Find Jobs</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';
        $(document).ready(function() {
            select2();
        });

        function select2() {
            $('#sector').select2();
        }
    </script>
    <script>
        function initialize() {
            var address = (document.getElementById('job_location'));
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

            alert('hi');
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@endsection
