<div id="candidate_detail" class="modal fade" role="dialog">
    <div class="modal-dialog modal-customdesign">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Candidate Details</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="">Resume @if (isset($candidate->resume) && $candidate->resume == '')*@endif
                        </label>
                        @if (isset($candidate->resume) && $candidate->resume != '')
                            <a id="link_download_resume" target="_blank" href="{{ $candidate->resume }}">Your Resume</a>
                        @endif
                        <div class="custom-file">
                            <input type="file" name="upload_resume" id="upload_resume" class="custom-file-input"
                                   id="validatedCustomFile" required>
                            <label class="custom-file-label" for="validatedCustomFile">Upload Your Resume</label>
                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                            <input type="hidden" name="oldfile" id="oldfile"
                                   value="{{ isset($candidate->resume) ? basename($candidate->resume_name) : '' }}">
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="coverletter">Cover Letter @if (isset($candidate->cover_letter) && $candidate->cover_letter == '')*@endif
                        </label>
                        @if (isset($candidate->cover_letter) && $candidate->cover_letter_path != '')
                            <a id="link_download_coverletter" target="_blank" href="{{ $candidate->cover_letter }}">Your
                                Cover Letter</a>
                        @endif
                        <div class="custom-file">
                            <input type="file" name="coverletter" id="coverletter" class="custom-file-input"
                                   id="coverletter" required>
                            <label class="custom-file-label" for="coverletter">Upload Your Cover Letter</label>
                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                            <input type="hidden" name="oldfile" id="oldfile"
                                   value="{{isset($candidate->cover_letter) ? basename($candidate->cover_letter) : '' }}">
                        </div>
                    </div>
                    <input type="hidden" name="jobid" id="jobid">
                    <div class="col-md-12 nofilemsg">
                        <span>You need to upload your resume & cover letter.</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center py-3">
                <button type="button" id="btn-update" class="btn btn-red-cs"
                        onclick="updateCandidateResume()">Update</button>
                <button type="button" id="btn-continue" class="btn btn-red-cs continueExisting"
                        onclick="continueWithExisting()">Continue</button>
                <button type="button" class="btn btn-red-cs" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
