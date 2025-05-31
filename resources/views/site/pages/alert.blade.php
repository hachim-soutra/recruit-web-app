@extends('site.layout.app')
@section('title', 'Jobs by email')
@section('style')
    <style>
        .section-heading {
            margin-top: .5rem;
        }
    </style>
@endsection
@section('content')
    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif
    <!-- banner-block -->
    <div class="our-news-block bg-light-red">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="contactform-main">
                        <h2 class="text-left">Create Jobs by email</h2>
                        <p>Well send you emails with the latest jobs you're interested in.</p>
                        <hr>
                        <form name="contact-form" action="{{ route('alert.store') }}" class="row mt-3 mx-0" method="POST"
                            id="contact-form" class="bd_contform">
                            @csrf
                            <h4 class="col-12 section-heading mb-2">What kind of job are you looking for?</h4>
                            <div class="form-group col-12">
                                <label for="industry">Industry :</label>
                                <select name="industry" id="industry">
                                    <option value="any" disabled selected>Any</option>
                                    @foreach ($industries as $i)
                                        <option {{ old('industry') == $i->name ? 'selected' : '' }}
                                            value="{{ $i->name }}">{{ $i->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="salary_period">Salary Period :</label>
                                <select name="salary_period" id="salary_period" class="form-control salary_period">
                                    <option disabled readonly>Choose a salary type</option>
                                    <option {{ old('salary_period') == 'Hourly' ? 'selected' : '' }} value="Hourly">
                                        Per Hour</option>
                                    <option {{ old('salary_period') == 'Weekly' ? 'selected' : '' }} value="Weekly">
                                        Per Week</option>
                                    <option {{ old('salary_period') == 'Monthly' ? 'selected' : '' }} value="Monthly">
                                        Per Month</option>
                                    <option {{ old('salary_period') == 'Yearly' ? 'selected' : '' }} value="Yearly">
                                        Per Year</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label>Salary Range :</label>
                                <select name="salary_rate" class="form-control ">
                                    <option {{ old('salary_rate') == '1|0' ? 'selected' : '' }} value="1|0"
                                        selected="selected">Any</option>
                                    <option {{ old('salary_rate') == '1|10000' ? 'selected' : '' }} value="1|10000">at
                                        least €10,000.00</option>
                                    <option {{ old('salary_rate') == '1|12000' ? 'selected' : '' }} value="1|12000">at
                                        least €12,000.00</option>
                                    <option {{ old('salary_rate') == '1|14000' ? 'selected' : '' }} value="1|14000">at
                                        least €14,000.00</option>
                                    <option {{ old('salary_rate') == '1|16000' ? 'selected' : '' }} value="1|16000">at
                                        least €16,000.00</option>
                                    <option {{ old('salary_rate') == '1|18000' ? 'selected' : '' }} value="1|18000">at
                                        least €18,000.00</option>
                                    <option {{ old('salary_rate') == '1|20000' ? 'selected' : '' }} value="1|20000">at
                                        least €20,000.00</option>
                                    <option {{ old('salary_rate') == '1|22000' ? 'selected' : '' }} value="1|22000">at
                                        least €22,000.00</option>
                                    <option {{ old('salary_rate') == '1|24000' ? 'selected' : '' }} value="1|24000">at
                                        least €24,000.00</option>
                                    <option {{ old('salary_rate') == '1|26000' ? 'selected' : '' }} value="1|26000">at
                                        least €26,000.00</option>
                                    <option {{ old('salary_rate') == '1|28000' ? 'selected' : '' }} value="1|28000">at
                                        least €28,000.00</option>
                                    <option {{ old('salary_rate') == '1|30000' ? 'selected' : '' }} value="1|30000">at
                                        least €30,000.00</option>
                                    <option {{ old('salary_rate') == '1|35000' ? 'selected' : '' }} value="1|35000">at
                                        least €35,000.00</option>
                                    <option {{ old('salary_rate') == '1|40000' ? 'selected' : '' }} value="1|40000">at
                                        least €40,000.00</option>
                                    <option {{ old('salary_rate') == '1|45000' ? 'selected' : '' }} value="1|45000">at
                                        least €45,000.00</option>
                                    <option {{ old('salary_rate') == '1|50000' ? 'selected' : '' }} value="1|50000">at
                                        least €50,000.00</option>
                                    <option {{ old('salary_rate') == '1|55000' ? 'selected' : '' }} value="1|55000">at
                                        least €55,000.00</option>
                                    <option {{ old('salary_rate') == '1|60000' ? 'selected' : '' }} value="1|60000">at
                                        least €60,000.00</option>
                                    <option {{ old('salary_rate') == '1|65000' ? 'selected' : '' }} value="1|65000">at
                                        least €65,000.00</option>
                                    <option {{ old('salary_rate') == '1|70000' ? 'selected' : '' }} value="1|70000">at
                                        least €70,000.00</option>
                                    <option {{ old('salary_rate') == '1|75000' ? 'selected' : '' }} value="1|75000">at
                                        least €75,000.00</option>
                                    <option {{ old('salary_rate') == '1|80000' ? 'selected' : '' }} value="1|80000">at
                                        least €80,000.00</option>
                                    <option {{ old('salary_rate') == '1|85000' ? 'selected' : '' }} value="1|85000">at
                                        least €85,000.00</option>
                                    <option {{ old('salary_rate') == '1|90000' ? 'selected' : '' }} value="1|90000">at
                                        least €90,000.00</option>
                                    <option {{ old('salary_rate') == '1|95000' ? 'selected' : '' }} value="1|95000">at
                                        least €95,000.00</option>
                                    <option {{ old('salary_rate') == '1|100000' ? 'selected' : '' }} value="1|100000">at
                                        least €100,000.00</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="job_type">Employment Type :</label>
                                <select name="preferred_job_type" class="form-control job_type" id="job_type">
                                    <option disabled selected readonly>Choose a type</option>
                                    <option {{ old('preferred_job_type') == 'Permanent' ? 'selected' : '' }}
                                        value="Permanent">Permanent</option>
                                    <option {{ old('preferred_job_type') == 'Temporary' ? 'selected' : '' }}
                                        value="Temporary">Temporary</option>
                                    <option {{ old('preferred_job_type') == 'Fixed-Term' ? 'selected' : '' }}
                                        value="Fixed-Term">Fixed-Term</option>
                                    <option {{ old('preferred_job_type') == 'Internship' ? 'selected' : '' }}
                                        value="Internship">Internship</option>
                                    <option {{ old('preferred_job_type') == 'Commission-Only' ? 'selected' : '' }}
                                        value="Commission-Only">Commission-Only</option>
                                    <option {{ old('preferred_job_type') == 'Freelance' ? 'selected' : '' }}
                                        value="Freelance">Freelance</option>
                                    <option {{ old('preferred_job_type') == 'Part time' ? 'selected' : '' }}
                                        value="Part time">Part time</option>
                                    <option {{ old('preferred_job_type') == 'Full time' ? 'selected' : '' }}
                                        value="Full time">Full time</option>
                                    <option {{ old('preferred_job_type') == 'Work from' ? 'selected' : '' }}
                                        value="Work from home">Work from home</option>
                                    <option {{ old('preferred_job_type') == 'Remote' ? 'selected' : '' }} value="Remote">
                                        Remote</option>
                                    <option {{ old('preferred_job_type') == 'Temporarily remote' ? 'selected' : '' }}
                                        value="Temporarily remote">Temporarily remote</option>
                                </select>
                            </div>
                            <div class="form-group col-6">
                                <label for="job_location">Job Location :</label>
                                <input type="text" name="job_location" id="job_location" class="form-control"
                                    placeholder="Enter Job Location." value="{{ old('job_location') }}" />
                                <input type="hidden" id="start_latitude" name="start_latitude" value="" />
                                <input type="hidden" id="start_longitude" name="start_longitude" value="" />
                            </div>
                            <hr class="col-12 p-0 my-3" />
                            <h4 class="col-12 section-heading mb-2">Personal information</h4>

                            <div class="form-group col-3">
                                <select name="gender" id="gender"
                                    class="form-control mb-0 @error('gender') is-invalid @enderror">
                                    <option {{ old('gender') == 'Ms' ? 'selected' : '' }} value="Ms">Ms</option>
                                    <option {{ old('gender') == 'Miss' ? 'selected' : '' }} value="Miss">Miss</option>
                                    <option {{ old('gender') == 'Mrs' ? 'selected' : '' }} value="Mrs">Mrs</option>
                                    <option {{ old('gender') == 'Mr' ? 'selected' : '' }} value="Mr">Mr</option>
                                    <option {{ old('gender') == 'Dr' ? 'selected' : '' }} value="Dr">Dr</option>
                                    <option {{ old('gender') == 'Prof' ? 'selected' : '' }} value="Prof">Prof</option>
                                </select>
                                @error('gender')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-9">
                                <input type="text" class="form-control mb-0 @error('last_name') is-invalid @enderror"
                                    id="first_name" name="first_name" placeholder="First Name *"
                                    value="{{ old('first_name') }}">
                                @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-12">
                                <input type="text" class="form-control mb-0 @error('last_name') is-invalid @enderror"
                                    id="last_name" value="{{ old('last_name') }}" name="last_name"
                                    placeholder="Last Name *">
                                @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-12">
                                <input type="text" class="form-control mb-0 @error('email') is-invalid @enderror"
                                    id="email" value="{{ old('email') }}" name="email" placeholder="Email *">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-12">
                                <div class="jbe-setup-info">
                                    <p>Setting up jobs by email will register you with our site. <a data-toggle="collapse"
                                            href="#jbe-setup-more-info" role="button" aria-expanded="false"
                                            aria-controls="jbe-setup-more-info" class="jbe-setup-more-info-link">Find out
                                            more <span class="caret caret-spacer"></span></a></p>

                                    <div class="collapse" id="jbe-setup-more-info">
                                        <p>By choosing to continue you will become a registered user and be able to sign
                                            into <a target="_blank" href="https://www.recruit.ie/careers/">recruit.ie</a>.
                                            We will use your email address to send you jobs that match your
                                            settings, as well as contact you about other relevant goods and services from
                                            recruit.ie.</p>
                                        <p>Once you set up Jobs by email you will be sent a password you can use to log in
                                            and manage your alerts.</p>
                                        <p>By continuing you are giving consent for us and our partner organisations to
                                            store <a href="#" target="_blank">cookies</a> on your device to
                                            personalise your experience. Your application activity will be visible when you
                                            enable employers to find you. recruit.ie does not verify the names of
                                            organisations
                                            applied to. </p>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group mb-0 col-12">
                                <input class="btn w-100" type="submit" value="Email me jobs like these" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
