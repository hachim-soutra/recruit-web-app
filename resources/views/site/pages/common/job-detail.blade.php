@extends('site.layout.app')
@section('title', $data['meta_title'])
@section('meta_desc', $data['meta_desc'])
@section('mystyle')
    <style>
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
        .posted-span {
            margin: 0;
            padding: 0 2px 0 0;
            font-size: 12px;
            line-height: 28px;
            color: #9E9E9E;
            font-weight: 500;
            display: inline-block;
        }

        .detail-part-title {
            color: #17191c;
            font-size: 1.25rem;
            font-weight: 600;
            line-height: 1.75rem;
            margin-bottom: 1em;
            margin-top: 1em;
        }
    </style>
@endsection
@section('content')
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif
    @if (session('errors'))
        <script>
            toastr.error("{{ session('errors')->first() }}");
        </script>
    @endif
    <div class="your-sector">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12 col-md-2 text-center">
                            <div class="figure w-100 h-100 d-none d-md-block">
                                <img class="w-75 h-auto" id="company-logo-web" src="{{ $data['jobPost']->company_logo }}">
                            </div>
                            <div class="figure w-100 h-100 d-block d-md-none">
                                <img class="w-25 h-auto" id="company-logo-mobile" src="{{ $data['jobPost']->company_logo }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-10 text-center text-md-left">
                            <h4>{{ $data['jobPost']->job_title }}</h4>
                            <p>{{ $data['jobPost']->company_name }}</p>
                        </div>
                    </div>
                    <div class="row mt-md-3">
                        <div class="col-12 d-flex flex-column flex-md-row align-items-center justify-content-md-between">
                            <div class="d-flex flex-row align-items-center justify-content-center" style="gap: 10px;">
                                @if(Auth::user() == null || Auth::user()->user_type === 'candidate')
                                    @if ($data['jobPost']->applicatons() != null && $data['jobPost']->applicatons()->where('candidate_id', Auth::id())->exists())
                                        <button type="button" class="btn btn-success appliedbtn"><i
                                                class="fa fa-check" style="font-size: xx-small;"></i>
                                            {{$data['jobPost']->post_job_type === 'career_website' ? 'Applied via Career Website' : 'Applied'}}
                                        </button>
                                    @elseif ($data['jobPost']->post_job_type === 'career_website')
                                        <input type="button"
                                            id="jobapplybtn"
                                            class="jobapplybtn"
                                            value="Apply via Career Website"
                                            onclick="applyViaCareerWebsite('{{ $data["jobPost"]->application_url }}', '{{ $data['jobPost']->id }}')"
                                    />
                                    @else
                                        <input type="button" id="jobapplybtn" class="jobapplybtn"
                                            value="Apply" name=""
                                            onclick="jobApply('{{ $data['jobPost']->id }}')"
                                        />
                            @endif
                                @endif

                                <a href="{{  route('common.company-detail', [ 'id' => $data['jobPost']->company_id ]) }}"
                                   class="btn employer-info" type="button">Employer info</a>
                            </div>
                            <div class="d-flex flex-row align-items-center justify-content-center mt-4 mt-md-0">
                                @if (Auth::user() && $data['jobPost']->bookmark != null && $data['jobPost']->bookmark->candidate_id === Auth::user()->id)
                                    <div class="save-icon mr-2">
                                        <a href="{{ route('bookmarked-job', ['jobid' => $data['jobPost']->id]) }}" style="color: #EB1829; cursor: pointer;">
                                            <i class="fa fa-bookmark fa-lg"></i>
                                            Unsave
                                        </a>
                                    </div>
                                @else
                                    <div class="save-icon mr-2">
                                        <a href="{{ route('bookmarked-job', ['jobid' => $data['jobPost']->id]) }}" style="color: #EB1829; cursor: pointer;">
                                            <i class="fa fa-bookmark-o fa-lg"></i>
                                            Save
                                        </a>
                                    </div>
                                @endif
                                <div class="save-icon">
                                    <a data-toggle="modal" data-target="#job-detail-share" style="color: #EB1829; cursor: pointer;">
                                        <i class="fa fa-share-alt  fa-lg"></i>
                                        Share
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 d-flex flex-column align-items-start">
                        <div class="rt">
                            <span class="posted-span"><b>Posted: </b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($data['jobPost']->created_at))->diffForHumans() }}</span>
                        </div>
                        <div class="rt">
                            <span class="posted-span">
                                <i class="fa fa-map-marker fa-2x" aria-hidden="true"></i>
                                LOCATION
                            </span>
                            <p>{{ $data['jobPost']->job_location }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <h5 class="detail-part-title">
                            <i class="fa fa-info mr-2" aria-hidden="true"></i>
                            Job Title
                        </h5>
                        <p>{{ $data['jobPost']->job_title }}</p>
                    </div>
                    @if($data['jobPost']->salary_from != null)
                        <div class="col-12">
                            <h5 class="detail-part-title">
                                <i class="fa fa-info mr-2" aria-hidden="true"></i>
                                Job Salary
                            </h5>
                            <p>
                                {{ $data['jobPost']->salary_from }}
                                {{ $data['jobPost']->salary_to ? ' to '. $data['jobPost']->salary_to : '' }}
                                {{ $data['jobPost']->salary_currency  }} | {{ $data['jobPost']->salary_period  }}
                            </p>
                        </div>
                    @endif
                    <div class="col-12">
                        <h5 class="detail-part-title">
                            <i class="fa fa-pencil-square-o mr-2" aria-hidden="true"></i>
                            Job Descriptions
                        </h5>
                        <p>{!! nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', $data['jobPost']->job_details)) !!}</p>
                    </div>
                    <div class="col-12">
                        <h5 class="detail-part-title">
                            <i class="fa fa-tags mr-2" aria-hidden="true"></i>
                            Qualifications
                        </h5>
                        <?php
                        $replace = str_replace('[', ' ', str_replace(']', ' ', $data['jobPost']->qualifications));
                        $qualification_explode = explode('|', $replace);
                        ?>
                        <?php for ($i = 0;
                                   $i < count($qualification_explode);
                                   $i++): ?>
                        <p>{{ stripslashes(str_replace('"', ' ', $qualification_explode[$i])) }}
                        </p>
                        <?php endfor; ?>
                    </div>
                    <div class="col-12">
                        <h5 class="detail-part-title">
                            <i class="fa fa-star mr-2" aria-hidden="true"></i>
                            Skills
                        </h5>
                        <?php
                        $skill_explode = [];
                        $replace = str_replace('[', ' ', str_replace(']', ' ', $data['jobPost']->job_skills));
                        if (str_contains($replace, '|')) {
                            $skill_explode = explode('|', $replace);
                        }
                        if (str_contains($replace, ',')) {
                            $skill_explode = explode(',', $replace);
                        }
                        ?>
                        <ul>
                            <?php for ($i = 0;
                                       $i < count($skill_explode);
                                       $i++): ?>
                            <li>{{ stripslashes(str_replace('"', ' ', $skill_explode[$i])) }}</li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                    <div class="col-12">
                        <h5 class="detail-part-title">
                            <i class="fa fa-calendar mr-2" aria-hidden="true"></i>
                            Experience
                        </h5>
                        <?php
                        $replace = str_replace('[', ' ', str_replace(']', ' ', $data['jobPost']->experience));
                        $experience_explode = explode('|', $replace);
                        ?>
                        <ul>
                            <?php for ($i = 0;
                                       $i < count($experience_explode);
                                       $i++): ?>
                            <li>{{ stripslashes(str_replace('"', ' ', $experience_explode[$i])) }}
                            </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="your-sector">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <div class="d-flex flex-row align-items-center justify-content-center mt-4 mt-md-0 h-100">
                        @if (Auth::user() && $data['jobPost']->bookmark != null && $data['jobPost']->bookmark->candidate_id === Auth::user()->id)
                            <div class="save-icon mr-2">
                                <a href="{{ route('bookmarked-job', ['jobid' => $data['jobPost']->id]) }}" class="d-flex flex-row align-items-center" style="color: #EB1829; cursor: pointer;">
                                    <i class="fa fa-bookmark fa-2x mr-2"></i>
                                    Unsave
                                </a>
                            </div>
                        @else
                            <div class="save-icon mr-2">
                                <a href="{{ route('bookmarked-job', ['jobid' => $data['jobPost']->id]) }}" class="d-flex flex-row align-items-center" style="color: #EB1829; cursor: pointer;">
                                    <i class="fa fa-bookmark-o fa-2x mr-2"></i>
                                    Save
                                </a>
                            </div>
                        @endif
                        <div class="save-icon">
                            <a data-toggle="modal" data-target="#job-detail-share" class="d-flex flex-row align-items-center" style="color: #EB1829; cursor: pointer;">
                                <i class="fa fa-share-alt  fa-2x mr-2"></i>
                                Share
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('site.pages.partial.common.job-detail.share-job-modal', [
        'job' => $data['jobPost'],
    ])
    @if(Auth::user() && Auth::user()->user_type === 'candidate')
        @include('site.pages.partial.common.apply-job-modal', [
            'candidate' => $data['candidateDetail'],
        ])
    @endif
@endsection
@section('myscript')
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "JobPosting",
          "hiringOrganization": "<?= $data['jobPost']->company_name ?>",
          "baseSalary": "<?= $data['jobPost']->salary_from ?>",
          "jobBenefits": "<?= $data['jobPost']->functional_area ?>",
          "datePosted": "<?= $data['jobPost']->created_at ?>",
          "description": "<?= $data['jobPost']->job_details ?>",
          "educationRequirements": "<?= str_replace('"', '', str_replace('[', ' ', str_replace(']', ' ', $data['jobPost']->qualifications))) ?>",
          "employmentType": "<?= strip_tags($data['jobPost']->preferred_job_type) ?>",
          "experienceRequirements": "<?= strip_tags($data['jobPost']->experience) ?>",
          "incentiveCompensation": "Performance-based annual bonus plan, project-completion bonuses",
          "industry":  "<?= $data['jobPost']->functional_area ?>",
          "jobLocation": {
            "@type": "Place",
            "address": {
              "@type": "PostalAddress",
              "addressLocality": "<?= $data['jobPost']->job_location ?>"
            }
          },
          "qualifications": "<?= str_replace('"', '', str_replace('[', ' ', str_replace(']', ' ', $data['jobPost']->job_skills))) ?>",
          "responsibilities": "Design and write specifications for tools for in-house customers Build tools according to specifications",
          "salaryCurrency": "<?= $data['jobPost']->salary_currency ?>",
          "skills": "<?= str_replace('"', '', str_replace('[', ' ', str_replace(']', ' ', $data['jobPost']->job_skills))) ?>",
          "specialCommitments": "Commit",
          "title": "<?= $data['jobPost']->job_title ?>",
          "workHours": "40 hours per week"
        }
    </script>
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';

        $(document).ready(function() {
            const imageLink = $('#company-logo-web').attr('src');
            checkImage(imageLink);

            $('.nofilemsg').hide();

            $('#upload_resume').on("change",function() {
                var file = $('#upload_resume')[0].files[0].name;
                $(this).next('label').text(file);
            });

            $('#coverletter').on("change",function() {
                var file = $('#coverletter')[0].files[0].name;
                $(this).next('label').text(file);
            });
        });
        function checkImage(url) {
            const request = new XMLHttpRequest();
            request.open("GET", url, true);
            request.send();
            request.onload = function() {
                status = request.status;
                if (request.status !== 200)
                {
                    $('#company-logo-web').attr('src', `${APP_URL}/uploads/no_company.png`);
                    $('#company-logo-mobile').attr('src', `${APP_URL}/uploads/no_company.png`);
                }
            }
            $('#company-logo-web').attr('src', url);
            $('#company-logo-mobile').attr('src', url);
        }

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
                    $('#btn-update').prop('disabled', true);
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

        function shareJob(social) {
            const title = '<?= $data['jobPost']->job_title ?>';
            const link = encodeURI(window.location.href);
            const msg = encodeURIComponent('Hey, I found this job');

            if (social === 'facebook') {
                window.open(`https://www.facebook.com/share.php?u=${link}`, "_blank");
            }
            else if (social === 'whatsapp') {
                window.open(`https://api.whatsapp.com/send?text=${msg}: ${link}`, "_blank");
            }
            else if (social === 'twitter') {
                window.open(`http://twitter.com/share?&url=${link}&text=${msg}&hashtags=javascript,programming`, "_blank");
            }
            else if (social === 'linkedin') {
                window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${link}`, "_blank");
            }
            else if (social === 'clipboard') {
                navigator.clipboard.writeText(link).then(() => {
                    $('#job-detail-share').modal('hide');
                    toastr.success("Job link copied to clipboard");
                },() => {
                    toastr.error('Failed to copy job link');
                });
            }
        }

    function applyViaCareerWebsite(url, jobId) {
        var isAuthenticated = @json(auth()->check());
        if (!isAuthenticated) {
            window.location.href = "{{ route('signin') }}" + '/' +jobId;
            return;
        }
        window.open(url, '_blank');
         $.ajax({
            url: APP_URL +'/save-job-carrier',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                job_id: jobId
            }}
        );
        setTimeout(() => {
            window.addEventListener('focus', function onFocus() {
                window.removeEventListener('focus', onFocus);
                showConfirmationPopup(jobId);
            });
        }, 500);
    }

    function showConfirmationPopup(jobId) {
        $('#applyConfirmModal').modal({ backdrop: 'static',
            keyboard: false,
            show: true
        });

        $('#confirmYes').off('click').on('click', function () {
            const APP_URL = '<?= env('APP_URL') ?>';
            jobApply(jobId);
            // $.ajax({
            //     url: APP_URL +'/apply-job',
            //     type: 'POST',
            //     data: {
            //         _token: '{{ csrf_token() }}',
            //         job_id: jobId
            //     },
            //     // beforeSend: function () {
            //     //     $('#confirmYes').prop('disabled', true);
            //     // },
            //     // complete: function () {
            //     //     $('#confirmYes').prop('disabled', false);
            //     // },
            //     success: function (res) {
            //         if (res.code === 200) {
            //             toastr.success(res.msg);
            //             $('#applyConfirmModal').modal('hide');
            //             setTimeout(() => location.reload(), 2500);
            //         } else {
            //             toastr.error(res.msg);
            //         }
            //     },
            //     error: function (err) {
            //         console.error(err);
            //         toastr.error('An error occurred. Please try again.');
            //     }
            // });
        });

        $('#confirmNo').off('click').on('click', function() {
            $('#applyConfirmModal').modal('hide');
        });
    }
    </script>
@endsection
