<!-- Country Modal -->
<div class="modal fade" id="consentModal" tabindex="-1" aria-labelledby="consentModal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    I want the newest jobs in Recruit.ie
                </h5>
                <a href="{{ route('reject-consent', ['type' => 'job']) }}">
                    <button type="button" class="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </a>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <form action="{{ route('accept-consent', ['type' => 'job']) }}" method="GET">
{{--                        <div class="form-group w-100">--}}
{{--                            <label for="exampleInputEmail1">Email address</label>--}}
{{--                            <input type="email" id="consent_email" name="consent_email" class="form-control form-control-sm" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" required>--}}
{{--                        </div>--}}
                        <input type="submit" id="seopress-user-consent-accept" class="w-50 text-center cursor-pointer button px-4 py-2" value="Activate" />
                    </form>

                    <p class="mt-3 mb-md-0 text-secondary text-muted">
                        By creating a job alert, you agree to our <a target="_blank" href="{{ route('term_of_use') }}">Terms</a>.
                        You can change your consent settings at any time by unsubscribing or as detailed in our terms.
                    <p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Country Modal -->
