<div id="attach-cv" class="tabcontent active" style="display:none;">
    <div class="row">
        <div class="col-12">
            <div class="upload-block uploads">
                <form action="{{ route('profile-file-upload') }}" method="post" id="resume_upload_form" enctype="multipart/form-data">
                    @csrf
                    <h5>Update your CV</h5><br>
                    <p>Upload DOCS / PDF up to 1 MB</p>
                    @if (@$data['candidateDetail']->resume != '')
                        <a target="_blank" href="{{ $data['candidateDetail']->resume }}">
                            <p style="color:blue;">
                                <img style="height: 18px;margin-top: -4px;" src="{{ asset('frontend/images/eye-outline.svg') }}">&nbsp
                                <strong>View CV</strong>
                            </p>
                        </a>
                    @endif

                    <div class="btn-wrapper">
                        <button class="btn">Upload</button>
                        <input type="file" accept="docs/*,pdf/*" name="resume_upload" id="resume_upload" />
                        <input type="hidden" name="upload" value="resume">
                        <input type="hidden" name="fieldname" value="resume_upload">
                        <input type="hidden" name="oldfile" value="{{ @$data['candidateDetail']->cover_letter }}">
                    </div>
                </form>
            </div>
            <div class="upload-block uploads">
                <form action="{{ route('profile-coverletter-update') }}" method="post" id="cover_upload_form" enctype="multipart/form-data">
                    @csrf
                    <h5>Update your Cover Letter</h5><br>
                    <p>Upload DOCS / PDF up to 1 MB</p>
                    @if (@$data['candidateDetail']->cover_letter != '')
                        <a target="_blank" href="{{ $data['candidateDetail']->cover_letter }}">
                            <p style="color:blue;">
                                <img style="height: 18px;margin-top: -4px;" src="{{ asset('frontend/images/eye-outline.svg') }}">&nbsp
                                <strong>View Cover Letter</strong>
                            </p>
                        </a>
                    @endif

                    <div class="btn-wrapper">
                        <button class="btn">Upload</button>
                        <input type="file" accept="docs/*,pdf/*" name="cover_upload" id="cover_upload" />
                        <input type="hidden" name="upload" value="coverletter">
                        <input type="hidden" name="fieldname" value="coverletter_upload">
                        <input type="hidden" name="oldfile" value="{{ @$data['candidateDetail']->cover_letter }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#resume_upload').on('change', function() {
            $('#resume_upload_form').submit();
        });

        $('#cover_upload').on('change', function() {
            $('#cover_upload_form').submit();
        });
    });
</script>
