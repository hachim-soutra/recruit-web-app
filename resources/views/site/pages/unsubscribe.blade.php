@extends('site.layout.app')
@section('title', 'Unsubscribe')
@section('mystyle')
    <style>
        .recruiter-hover {
            cursor: pointer;
            padding: 0.5rem 3rem;
            margin-top: 1rem;
        }
    </style>
@endsection

@section('content')
    <section class="cms-pagemain our-news-block bg-light-red">
        <div class="container">
            <div class="title-block mb-4">
                <h1>Unsubscribe</h1>
            </div>
            <form class="subw-CancelSurveyForm" action="{{ route('unsubscribe.store', ['email' => $email]) }}" method="POST">
                @csrf
                <div class="subw-CancelSurveyForm-radioGroupContainer">
                    <fieldset class="subw-FoundJobRadioGroup">
                        <legend>Can you tell us the reason for your unsubscription? (Optional)</legend>
                        <div class="icl-Radio-item"><label class="RadioGroup-radio" id="label-foundJob" for="foundJob"><input
                                    class="subw-foundJobRadioGroup-control" aria-labelledby="label-foundJob" type="radio"
                                    id="foundJob" name="motif" value="foundJob"><span class="RadioGroup-radioLabels">I
                                    have found a job </span></label></div>

                        <div class="icl-Radio-item"><label class="RadioGroup-radio" id="label-foundJobElsewhere"
                                for="foundJobElsewhere"><input class="subw-foundJobRadioGroup-control"
                                    aria-labelledby="label-foundJobElsewhere" type="radio" id="foundJobElsewhere"
                                    name="motif" value="foundJobElsewhere"><span class="RadioGroup-radioLabels">I have
                                    found a job through another site</span></label></div>

                        <div class="icl-Radio-item"><label class="RadioGroup-radio" id="label-other" for="other"><input
                                    class="subw-foundJobRadioGroup-control" aria-labelledby="label-other" type="radio"
                                    id="other" name="motif" value="other"><span
                                    class="RadioGroup-radioLabels">Other</span></label></div>
                    </fieldset>
                </div><button type="submit" class="recruiter-hover"><span aria-hidden="false">Send</span></button>
            </form>
    </section>
@endsection
@section('myscript')
@endsection
