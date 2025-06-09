@extends('site.layout.app')
@section('title', 'Companies-Search')
@section('mystyle')
    <style>
        .search-btn {
            margin: 0;
            padding:14px 30px 14px 30px;
            background: var(--red-color);
            font-size: 15px;
            line-height:24px;
            color: var(--white-color);
            border:0;
            border-radius:1px;
            display: inline-block;
            box-shadow: none;
            outline:0;
            font-weight:500;
            text-align: center;

        }
        .bd-block-no-result {
            margin: 20px auto;
            padding: 24px 30px;
            width: 488px;
            display: block;
            background: #FFFFFF 0% 0% no-repeat padding-box;
            box-shadow: 0px 3px 6px #00000017;
            border-radius: 10px;
            opacity: 1;
        }
        .h5span {
            color: #ed1c24;
            font-size: 25px;
            font-weight: 900;
        }
        .company-list-title {
            margin: 0;
            padding: 0;
            font-size: 16px;
            line-height: 23px;
            color: #1A0305;
            font-weight: bold;
        }
        .company-list-title:hover {
            cursor: pointer;
            text-decoration: underline #ed1c24;
            color: #ed1c24;
        }
        .company-list-logo {
            height: 80px;
            width: 80px;
            border-radius: 50%;
            object-fit: cover;
        }
        .company-detail-container {
            padding-top: 10px;
            padding-bottom: 10px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #e0e0e0;
            min-height: 84px;
        }
    </style>
@endsection
@section('content')
    <div class="post-resume-one">
        <div class="container">
            <div class="title-block">
                <h2>Companies List</h2>
            </div>
            <div class="bd-block">
                There are some great companies to work for; all you need to do is find them. Search all our direct employers by name.
            </div>
            <div class="bg-white d-flex flex-column py-5 mt-2">
                <div class="row">
                    <div class="col-3 d-flex flex-column justify-content-center">
                        <p class="mt-1 mb-0 text-right">Search by first letter : </p>
                    </div>
                    <div class="col-9">
                        <div class="btn-toolbar">
                            <div class="btn-group btn-group-sm flex-wrap">
                                @foreach(range('A', 'Z') as $alphabet)
                                    <button
                                            style="width: 32px; height: auto;"
                                            onclick="searchByChar('<?php echo $alphabet; ?>')"
                                            class="btn btn-default mr-1 mt-2 {{ old('first_letter') == $alphabet ? 'bg-danger text-white' : '' }}">{{ $alphabet }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('common.company-search') }}" id="jobsearchform" method="GET">
                    <div class="row mt-4">
                        <div class="col-3 d-flex flex-column justify-content-center">
                            <p class="mb-0 text-right">Search by company name : </p>
                        </div>
                        <div class="col-9 pr-4">
                            <div class="w-100 d-flex flex-column flex-sm-row align-items-center justify-content-center">
                                <div class="w-100 w-md-75 mr-md-3">
                                    <input type="search" placeholder="company name" value="{{old('company')}}" name="company" id="keyword" class="form-control w-100 mb-0" />
                                </div>
                                <div class="w-100 w-md-25 mt-3 mt-md-0">
                                    <button type="submit" class="btn search-btn w-100 h-100">Search</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @if($data['search'] == true)
                <div class="row flex-column">
                    @if ($data['count'] <= 0)
                        <div class="bd-block-no-result">
                            <h5 class="mb-0 text-center"><span class="h5span">{{ $data['count'] }}</span> Matching Results
                            </h5>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-12 h-auto my-3">
                                <h5 class="mb-0">
                                    <span class="h5span">{{ @$data['count'] }}</span>
                                    Matching Results
                                </h5>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-column">
                                    @foreach($data['companies'] as $company)
                                        <div class="company-detail-container">
                                            <a href="{{  route('common.company-detail', [ 'id' => $company->id ]) }}">
                                                <h4 class="company-list-title">{{ $company->company_name }}</h4>
                                            </a>
                                            <img src="{{ $company->company_logo }}" class="company-list-logo" />
                                        </div>

                                    @endforeach
                                    <div class="text-center mt-3">
                                        {{ $data['companies']->appends(Request::except('page'))->onEachSide(1)->links() }}
                                    </div>

                                </div>

                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
@section('myscript')
    <script>
        const APP_URL = '<?= env('APP_URL') ?>';

        $(document).ready(function() {
            const urlSearchParams = new URLSearchParams(window.location.search);
            const char = urlSearchParams.get('first_letter');
            if (char != null) {
                $('#first_letter').attr('value', char);
            }
        });

        function searchByChar(character) {
            const urlSearchParams = new URLSearchParams(window.location.search);
            const char = urlSearchParams.get('first_letter');
            if (char != null) {
                urlSearchParams.set('first_letter', character);
            } else {
                urlSearchParams.append('first_letter', character);
            }
            window.location.href = "{{ route('common.company-search') }}?"+urlSearchParams;
        }
    </script>
@endsection
