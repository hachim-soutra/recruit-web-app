@extends('site.layout.app')
@section('title', 'Job-Listing')
@section('mystyle')
    <style>
        .heading {
            position: relative;
            width: 100%;
            min-height: 66px;
            padding-right: 15px;
            padding-left: 15px;
        }

        .appliedbtn {
            border-radius: 25px;
            font-weight: lighter;
            font-size: 13px;
            height: 37px;
            padding-left: 20px;
            padding-right: 20px;
        }

        .appliedbtn i {
            font-size: 12px !important;
        }

        .appliedtext {
            border: 1px solid;
            border-radius: 10px;
            width: 15%;
            text-align: center;
            color: green !important;
            font-size: 12px;
            font-weight: 500;
        }

        .profileimg {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            margin-right: 10px;
            margin-top: -10px;
        }

        .socialicon {
            font-size: 24px;
            border: 1px solid;
            width: 25%;
            display: flex;
            border-radius: 50%;
            justify-content: center;
            height: 22%;
        }

        .banner-block {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            min-height: 370px !important;
            padding: 25px 0 25px 0 !important;
        }

        .applicants {
            border-radius: 11px;
            height: 35px;
            float: right;
        }
    </style>
    <style>
        .img {
            width: 100%;
            height: 400px;
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
        }

        .university h3 {
            font-size: 16px;
            font-weight: 500;
        }

        .tablinks {
            border: none;
            margin-right: 80px;
            border-top: none;
            cursor: pointer;
        }

        .tablinks:focus {
            box-shadow: none;
            outline: none;
            border: 0px;
        }

        .tablinks.last {
            margin-right: 0px !important;
        }

        .tabrow {
            border-bottom: 1px solid #8080801f;
            margin-left: -2px;
            margin-top: 4%;
        }

        .tab .active {
            color: #c12128 !important;
        }

        .tabcontent {
            padding: 1%;
            display: none;
        }

        .findbtn {
            width: 101%;
            color: white;
            background-color: #c12128;
            margin-left: -5px;
            margin-top: 15px;
        }

        .pagination {
            width: 100%;
        }

        .select2-container .select2-container--default .select2-container--open {
            top: 137px !important;
            left: 786.219px !important;
        }

        .select2-container--open .select2-dropdown--below {
            width: 336.016px !important;
            margin-left: -31px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px !important;
        }

        .select2-container--default .select2-selection--single {
            border: none !important;
        }

        .select2-container .select2-selection--single {
            height: 50px !important;
            display: flex !important;
            align-items: center !important;
        }

        .inner-form-custom .banner-block .banner-bd h1 {
            display: block !important;
        }

        .skill-listing {
            margin-left: 5%;
        }

        .skill-listing li {
            font-size: 15px;
            font-weight: 600;
        }

        .h5span {
            color: #ed1c24;
            font-size: 25px;
            font-weight: 900;
        }

        .show-more-link {
            cursor: pointer;
            text-decoration: underline;
            color: #ed1c24;
            padding-left: 4px;
        }

        .show-more-link:hover {
            color: #FF6058;
        }

        .title-more-link:hover {
            text-decoration: underline #0a0a0a;
        }

        .show-more-button {
            font-family: "Muli", Sans-serif;
            text-transform: capitalize;
            letter-spacing: 0.6px;
            color: #FFFFFF;
            background-color: #FF6058;
            border-style: solid;
            border-color: #FF6058;
            border-radius: 5px;
            padding: 6px;
            outline: none;
            cursor: pointer;
        }

        .show-more-button:hover {
            color: #FFFFFF;
        }

        .filter-btn {
            font-family: "Muli", Sans-serif;
            text-transform: capitalize;
            letter-spacing: 0.6px;
            color: #FFFFFF;
            background-color: #c12128;
            border-style: solid;
            border-color: #c12128;
            border-radius: 5px;
            font-size: 1rem;
            padding: 6px;
            outline: none;
            cursor: pointer;
            width: 180px;
            height: 46px;
            align-self: flex-end;
        }

        #filter-row label {
            margin: 0px !important;
            bkit-border-radius: 2px !important;
            border-radius: 2px !important;
        }

        .select2-selection--multiple {
            border: 1px solid #c12128 !important;
            -webkit-border-radius: 2px !important;
            border-radius: 2px !important;
            height: 45px !important;
            overflow: auto !important;
        }

        @media (max-width: 767px) {
            .filter-btn {
                width: 50%;
                margin-top: 1rem;
                align-self: center;
            }
        }
    </style>
@endsection
@section('content')
    <livewire:job-listing :data="$data" />
@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';

        $(document).ready(function() {
            $('.nofilemsg').hide();

            $('#upload_resume').on("change", function() {
                var file = $('#upload_resume')[0].files[0].name;
                $(this).next('label').text(file);
            });

            $('#coverletter').on("change", function() {
                var file = $('#coverletter')[0].files[0].name;
                $(this).next('label').text(file);
            });

            const urlSearchParams = new URLSearchParams(window.location.search);
            const char = urlSearchParams.get('sort');

            if (char == null || char === 'none') {
                $("#sort-by-job-select").val("none");
            } else {
                $("#sort-by-job-select").val(char);
            }
         
        });

        function showJobPost(jobPostId) {
            const searchParams = new URLSearchParams(window.location.search);
            if (searchParams.toString() != '') {
                location.href = "{{ route('common.job-listing') }}" + '/' + jobPostId + '?' + searchParams.toString();
            } else {
                location.href = "{{ route('common.job-listing') }}" + '/' + jobPostId
            }

        }

        function jobApply(jobid) {
            checkingCvCoverLetter(jobid);
        }

        function checkingCvCoverLetter(jobid) {
            $.ajax({
                url: APP_URL + '/search-candidate',
                type: 'GET',
                success: function(res) {
                    if (res.code == '200') {
                        if (res.uploaded_resume == '1') {
                            applySource = "resumeExists";
                            toastr.info("Your old resume & cover-letter will be override.");
                            $('#candidate_detail').modal('show');
                        }
                        if (res.uploaded_resume == '0') {
                            applySource = "resumeNotExists";
                            $('#candidate_detail').modal('show');
                            $('.continueExisting').hide();
                            $('.nofilemsg').show();
                            $('.nofilemsg').find('span').css('color', 'red');
                            $('#candidate_detail').find('#coverletter').attr('required', true);
                            $('#candidate_detail').find('#upload_resume').attr('required', true);
                        }
                    }
                    if (res.code == '403') {
                        toastr.error(res.msg);
                    }
                    if (res.code == '500') {
                        toastr.error(res.msg);
                    }
                    $('#candidate_detail').find('#jobid').val(jobid);
                },
                error: function(err) {
                    if (err.status === 401) {
                        location.href = "{{ route('signin') }}" + '/' + jobid;
                    } else {
                        console.log(err);
                    }
                }
            });
        }

        function updateCandidateResume() {
            let fd = new FormData();
            // fd.append('coverletter', $('#coverletter').val());
            let upload_resume = $("#upload_resume").get(0).files[0] == undefined ? null : $("#upload_resume").get(0).files[
                0];
            let cover_letter = $("#coverletter").get(0).files[0] == undefined ? null : $("#coverletter").get(0).files[0];
            fd.append('resume', upload_resume);
            fd.append('cover_letter', cover_letter);
            fd.append('oldfile', $("#oldfile").val());
            fd.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: APP_URL + '/update-resume-coverletter',
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#btn-update').html(
                        '<i class="fa fa-circle-o-notch fa-spin"></i> Updating! Please wait ...');
                },
                success: function(res) {
                    if (res.code == '200') {
                        toastr.success(res.msg);
                        applySource = "resumeExists";
                        $('#btn-update').html('Update');
                        $('.continueExisting').show();
                        if (res.data.resume !== null) {
                            $("#link_download_resume").attr("href", res.data.resume);
                        }
                        if (res.data.cover_letter !== null) {
                            $("#link_download_coverletter").attr("href", res.data.cover_letter);
                        }

                    }
                    if (res.code == '403') {
                        $('#btn-update').html('Update');
                        let err = res.msg;
                        if (err.hasOwnProperty('cover_letter')) {
                            for (let i = 0; i < err.cover_letter.length; i++) {
                                toastr.error(err.cover_letter[i]);
                            };
                            $("#coverletter").val('');
                        }
                        if (err.hasOwnProperty('resume')) {
                            for (let i = 0; i < err.resume.length; i++) {
                                toastr.error(err.resume[i]);
                            };
                            $("#upload_resume").val('');
                        }
                    }
                    if (res.code == '500') {
                        $('#btn-update').html('Update');
                        toastr.error(res.msg);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        function continueWithExisting(jobid) {
            $.ajax({
                url: APP_URL + '/search-candidate',
                type: 'GET',
                success: function(res) {
                    if (res.code === 200) {
                        if (res.uploaded_resume === 0) {
                            toastr.error("You must upload or have at least your resume");
                        };
                        if (res.uploaded_resume === 1) {
                            jobid = (jobid === undefined || jobid == '') ? $('#jobid').val() : jobid;
                            console.log("jobid => ", jobid);
                            $.ajax({
                                url: APP_URL + '/apply-job',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    job_id: jobid
                                },
                                beforeSend: function() {
                                    if (applySource === "resumeNotExists") {
                                        $('#btn-update').html(
                                            '<i class="fa fa-circle-o-notch fa-spin"></i> Applying! Please wait ...'
                                        );
                                    } else {
                                        $('#btn-update').html('Update');
                                        $('#btn-continue').html(
                                            '<i class="fa fa-circle-o-notch fa-spin"></i> Applying! Please wait ...'
                                        );
                                    }
                                },
                                complete: function() {
                                    $('#btn-update').html('Update');
                                    $('#btn-continue').html('Continue');
                                },
                                success: function(res) {
                                    if (res.code === 200) {
                                        toastr.success(res.msg);
                                        $('#candidate_detail').modal('hide');
                                        setTimeout(() => {
                                            location.reload();
                                        }, 2500);
                                    };
                                    if (res.code === 403) {
                                        toastr.error(res.msg);
                                    };
                                    if (res.code === 500) {
                                        toastr.error(res.msg);
                                    };
                                },
                                error: function(err) {
                                    console.log(err);
                                }
                            });
                        };

                    };
                    if (res.code === 403) {
                        toastr.error(res.msg);
                    };
                    if (res.code === 500) {
                        toastr.error(res.msg);
                    };
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }

        $("#sort-by-job-select").on('change', () => {
            var selectVal = $("#sort-by-job-select option:selected").val();
            this.sortByFilter(selectVal);
        });

        function sortByFilter(item) {
            const urlSearchParams = new URLSearchParams(window.location.search);
            const char = urlSearchParams.get('sort');
            if (char != null) {
                urlSearchParams.set('sort', item);
            } else {
                urlSearchParams.append('sort', item);
            }
            if (urlSearchParams.get('page') != null) {
                urlSearchParams.set('page', "1");
            } else {
                urlSearchParams.append('page', "1");
            }
            window.location.href = "{{ route('common.job-listing') }}?" + urlSearchParams;
        }
    </script>
@endsection
