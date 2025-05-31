<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Recruit.ie | @yield('title')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/img/fav-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/skins/_all-skins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/custom.css') }}">
    <link href="{{ asset('backend/plugin/jquery-confirm/css/jquery-confirm.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('backend/plugin/loader/jquery.loadingModal.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/plugin/toastr/toastr.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="{{ asset('backend/plugin/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugin/timepicker/bootstrap-timepicker.min.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('backend/plugin/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link href="https://vjs.zencdn.net/7.5.5/video-js.css" rel="stylesheet" />
    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/datatables@1.10.18/media/css/jquery.dataTables.min.css" rel="stylesheet" />
    @livewireStyles
    @yield('mystyle')
    <style>
        body .ck-content .table table,
        .ck-content .table table td,
        body .ck-content .table td {
            border: none !important;
        }
    </style>
</head>

<body class="hold-transition skin-blue fixed sidebar-mini">
    <div class="wrapper">
        @include('admin.layout.topbar')
        <aside class="main-sidebar">
            @include('admin.layout.sidebar')
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>
        @include('admin.layout.footer')
    </div>

    <script src="{{ asset('backend/plugin/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/bootstrap-filestyle/src/bootstrap-filestyle.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ asset('backend/js/custom.min.js') }}"></script>
    <script src="{{ asset('backend/js/demo.js') }}"></script>
    <script src="{{ asset('backend/plugin/jquery-confirm/js/jquery-confirm.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/loader/jquery.loadingModal.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('backend/plugin/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('backend/plugin/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <script src="https://vjs.zencdn.net/7.5.5/video.js"></script>
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
    @include('admin.scripts.common')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('LOCATION_API') }}&libraries=places&callback=Function.prototype">
    </script>
    <script>
        function initialize() {

            var map_options = {
                componentRestrictions: {
                    country: "ie"
                },
            };
            var address = (document.getElementById('job_location'));
            var autocomplete = new google.maps.places.Autocomplete(address, map_options);

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
            $('#myTable').dataTable();
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables@1.10.18/media/js/jquery.dataTables.min.js"></script>
    @yield('myscript')
    @livewireScripts
</body>

</html>
