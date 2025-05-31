@extends('site.layout.app')
@section('title', $data['meta_title'])
@section('meta_desc', $data['meta_desc'])
@section('mystyle')
    <style>
        .detail-logo-container {
            box-sizing: content-box;
            width: 84px;
            height: 84px;
            line-height: 78px;
            border-radius: 4px;
            border: 3px solid #fff;
            box-shadow: 0 0 5px #666;
            text-align: center;
        }

        @media (min-width: 992px)
        .detail-logo-container  {
            width: 150px;
            height: 150px;
            line-height: 148px;
            top: -88px;
        }
        @media (min-width: 768px)
        .detail-logo-container  {
            width: 120px;
            height: 120px;
            line-height: 118px;
            top: -63px;
            bottom: 0;
            background-size: auto;
        }
        .detail-logo {
            max-width: 100%;
            max-height: 100%;
            vertical-align: middle;
            border: 0;
        }
        company-detail-title {
            margin: 0;
            padding: 0;
            font-size: 16px;
            line-height: 23px;
            color: #1A0305;
            font-weight: bold;
        }
        .company-detail-address {
            padding-left: 25px;
            background-position: 0;
            line-height: 23px;
            margin: 4px 0;
            font-size: 17px;
        }
        .detail-company-container {
            margin-top: 20px;
            padding-top: 10px;
            padding-bottom: 10px;
            display: flex;
            flex-direction: column;
            border-top: 1px solid #e0e0e0;
            min-height: 84px;
        }
        .link-to-job-from-company {
            margin: 0;
            padding: 3px 0 18px 0;
            font-size: 15px;
            line-height: 23px;
            color: #1A0305;
            font-weight: bold;
        }
        .link-to-job-from-company:hover {
            text-decoration: underline;
            color: var(--red-color);
        }

    </style>
@endsection
@section('content')
    <div class="post-resume-one">
        <div class="container">
            <div class="bg-white d-flex flex-column p-5">
                <div class="d-flex flex-row align-items-end justify-content-start">
                    <div class="detail-logo-container">
                        <img src="{{ $data['company']->company_logo }}" alt="{{ $data['company']->company_name }}" class="detail-logo" />
                    </div>
                    <h4 class="company-detail-title ml-3">{{ $data['company']->company_name }}</h4>
                </div>
                <div class="d-flex flex-row align-items-center mt-4">
                    <i class="fa fa-map-marker fa-2x" aria-hidden="true"></i>
                    <p class="mb-0 company-detail-address">{{ $data['company']->address }}</p>
                </div>

                <div class="detail-company-container">
                    <h4 class="company-detail-title ml-3">About</h4>
                    <p>
                        {!! nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', $data['company']->company_details)) !!}
                    </p>
                    @if(count($data['jobs']) >0)
                        <h4 class="company-detail-title ml-3">Jobs</h4>
                        @foreach ($data['jobs'] as $jp)<!-- item-col 1-->
                            <div class="item-col">
                                <!--top-->
                                <div class="top">
                                    <div class="lt">
                                        <a class="link-to-job-from-company" href="{{ route('common.job-detail', [ 'id' => $jp->slug ]) }}">
                                            <h5>{{ $jp['job_title'] }}</h5>
                                        </a>

                                    </div>
                                </div>
                                <!--top-->
                                <!-- middle -->
                                <div class="middle">
                                    <div class="lt">
                                        <i class="fa fa-map-marker fa-lg text-danger" aria-hidden="true"></i>
                                        <span>{{ $jp['job_location'] }}</span>
                                        <p>{{ $jp['preferred_job_type'] }}</p>
                                    </div>
                                    <div class="rt">
                                        <div class="img-sec">
                                            <img src="{{ $jp['company_logo'] }}" id="company-logo-list">
                                        </div>
                                    </div>
                                </div>
                                <!-- middle -->
                                <!-- bottom -->
                                <div class="bottom">
                                    @if ($jp['hide_salary'] != 'yes')
                                        <p>{{ $jp['salary_currency'] }}{{ $jp['salary_from'] }} -
                                            {{ $jp['salary_currency'] }}{{ $jp['salary_to'] }}
                                            {{ $jp['salary_period'] }}
                                        </p>
                                    @endif
                                    <span><b class="mr-2">Posted :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime($jp['created_at']))->diffForHumans() }}</span>
                                </div>
                                <!-- bottom -->
                            </div>
                        <!-- item-col -->
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
