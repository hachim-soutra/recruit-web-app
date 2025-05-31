@extends('admin.layout.app')

@section('title', "Contact Us")
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
    .imgstyle {
        height: 180px;
        width: auto;
    }
</style>
@endsection

@section('content')
<!-- <script src="https://maps.googleapis.com/maps/api/js?key={{ env('LOCATION_API') }}&libraries=places&callback=Function.prototype"></script> -->
<section class="content-header">
    <h1>
       Contact US
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.news.list') }}">Contact Us</a></li>
        <li class="active">Create</li>
    </ol>
</section>

<!-- =============Main Page====================== -->
<section class="content">   
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Contact Us</h3>
                    <div class="box-tools pull-right">
                        <button type="button" onclick="addContactUs()" class="btn btn-sm btn-primary">Add</button>
                        <a href="{{ route('admin.cms.contact-us') }}" class="btn btn-box-tool"><button type="button" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i>  Back</button></a>
                    </div>
                </div>
                @if(session()->has('errMsg'))
                <div class="erroralert">{{ session()->get('errMsg') }}</div>
                @endif
                <div class="box-body">
                    @if(!empty($contacts))
                    @foreach($contacts as $c)
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Contact Image</label>
                                <img class="imgstyle" src="{{ asset('uploads/cms/contact/'.$c->contactus_image) }}" width="" height="" alt="">
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>{{ucfirst($c->heading)}}</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <label for="">Contact Address</label>
                                        <p><i class="fa fa-map-marker" aria-hidden="true"></i> {{ucfirst($c->contactlocation)}}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">  
                                        <label for="">Contact Details</label>
                                        <p>{!! ucfirst(str_replace(array("\n", "\r", "<br/>"), '', $c->detail)); !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.cms.contact-us', $c->id) }}"><i class="fa fa-pencil"></i></a>
                            </div>
                        </div>
                    @endforeach
                    <hr>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>
<div id="aboutmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <form action="{{ route('admin.cms.contact-us-save') }}" id="contactform" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Add Contact Us Content</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-8">
                    <label for="">Heading</label>
                    <input type="text" name="heading" id="heading" class="form-control" placeholder="Enter About Us Heading.">
                </div>
                <div class="col-md-4">
                    <label for="">Upload File</label>
                    <input type="file" name="contactfile" id="contactfile" class="form-control">
                    <input type="hidden" name="oldfile" id="oldfile">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="">Details</label>
                    <textarea name="detail" id="detail" class="form-control" placeholder="Enter About Us Details."></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="">Location</label>
                    <input type="text" name="contactlocation" id="location" class="form-control" placeholder="Enter Your Location." />
					
					<input type="hidden" id="start_latitude" name="start_latitude" value="" />
					<input type="hidden" id="start_longitude" name="start_longitude" value="" />
                </div>
            </div>
        </div>
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lang" id="lang">
        <input type="hidden" name="rowid" id="rowid">
        <div class="modal-footer">
            <button type="button" onclick="contactFormSubmit()" class="btn btn-primary" style="float:left;">Submit</button>
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
<script>
    $(document).ready(function(){
        $('#detail').wysihtml5();
        let type = "{{$type}}";
        if(type == 'edit'){
            $('#aboutmodal').modal('show');
            $('#heading').val('{{ @$singlecontact->heading }}');
            let detail = `{!! nl2br(@$singlecontact->detail) !!}`;
            $('#detail').val(detail);
            $('#location').val('{{ @$singlecontact->contactlocation }}');
            $('#oldfile').val('{{ @$singlecontact->contactus_image }}');
            $('#rowid').val('{{ @$singlecontact->id }}');
        }
    });
    function addContactUs(){
        $('#aboutmodal').modal('show');
    }
    function contactFormSubmit(){
        $('#contactform').submit();
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
