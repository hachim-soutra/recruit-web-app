<div id="career-details" class="tabcontent active" style="display:none;">
    <div class="mt-4"></div>
    <div class="row">
        <div class="col-md-6">
            <label for="">Candidate Type</label>
            <select name="candidate_type" id="candidate_type" class="form-control candidate_type">
                <option selected disabled readonly>Choose a type</option>
                <option value="Graduate"
                    <?= @$data['candidateDetail']->candidate_type == 'Graduate' ? 'selected' : '' ?>>
                    Graduate</option>
                <option value="Entry level"
                    <?= @$data['candidateDetail']->candidate_type == 'Entry level' ? 'selected' : '' ?>>
                    Entry level</option>
                <option value="Manager"
                    <?= @$data['candidateDetail']->candidate_type == 'Manager' ? 'selected' : '' ?>>
                    Manager</option>
                <option value="Director"
                    <?= @$data['candidateDetail']->candidate_type == 'Director' ? 'selected' : '' ?>>
                    Director</option>
                <option value="C-Level"
                    <?= @$data['candidateDetail']->candidate_type == 'C-Level' ? 'selected' : '' ?>>
                    C-Level</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="">Job Role</label>
            <select name="functional_id" id="functional_id" class="form-control functional_id">
                <option disabled readonly selected>Choose a role</option>
                @foreach ($data['industry'] as $i)
                    <option value="{{ $i->id }}"
                            <?= @$data['candidateDetail']->functional_id == $i->id ? 'selected' : '' ?>>
                        {{ $i->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label for="">Current Salary</label>
            <input type="text" name="current_salary"
                   value="{{ @$data['candidateDetail']->current_salary }}" id="current_salary"
                   class="form-control" placeholder="Current Salary">
        </div>
        <div class="col-md-6">
            <label for="">Expected Salary</label>
            <input type="text" name="expected_salary"
                   value="{{ @$data['candidateDetail']->expected_salary }}" id="expected_salary"
                   class="form-control" placeholder="Expected Salary">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label for="">Portfolio Links Or Video CV</label>
            <input type="text" name="portfolio_link"
                   value="{{ @$data['candidateDetail']->portfolio_link }}" id="portfolio_link"
                   class="form-control" placeholder="Portfolio Links Or Video CV">
        </div>
        <div class="col-md-6">
            <label for="">Linkedin Url</label>
            <input type="text" name="linkedin_link"
                   value="{{ @$data['candidateDetail']->linkedin_link }}" id="linkedin_link"
                   class="form-control" placeholder="Linkedin Link">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label for="">Work Permit Status</label>
            <input type="text" name="visa_status"
                   value="{{ @$data['candidateDetail']->visa_status }}" id="visa_status"
                   class="form-control" placeholder="Visa Status">
        </div>
        <div class="col-md-6">
            <label for="">Notice Period (In Days)</label>
            <input type="text" name="notice_period"
                   value="{{ @$data['candidateDetail']->notice_period }}" id="notice_period"
                   class="form-control" placeholder="Notice Period">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label for="">Language(S) You Know</label>
            <select class="js-example-basic-multiple langmultiple" name="languages[]"
                    data-placeholder="select languages" multiple="multiple">
                <option disabled readonly>Choose one</option>
                @foreach ($data['language'] as $l)
                    <option value="{{ $l->name }}" <?php if (@$data['candidateDetail']->languages == $l->name) {
                        echo 'selected';
                    } ?>>{{ $l->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="">Preferred Job Type</label>
            <select name="preferred_job_type" class="form-control preferred_job_type"
                    id="preferred_job_type">
                <option selected disabled readonly>Choose a type</option>
                <option value="Part time"
                    <?= @$data['candidateDetail']->preferred_job_type == 'Part time' ? 'selected' : '' ?>>
                    Part time</option>
                <option value="Full time"
                    <?= @$data['candidateDetail']->preferred_job_type == 'Full time' ? 'selected' : '' ?>>
                    Full time</option>
                <option value="Part time & Full time"
                    <?= @$data['candidateDetail']->preferred_job_type == 'Part time & Full time' ? 'selected' : '' ?>>
                    Part time & Full time</option>
                <option value="Work from home"
                    <?= @$data['candidateDetail']->preferred_job_type == 'Work from home' ? 'selected' : '' ?>>
                    Work from home</option>
                <option value="Remote"
                    <?= @$data['candidateDetail']->preferred_job_type == 'Remote' ? 'selected' : '' ?>>
                    Remote</option>
                <option value="Temporarily remote"
                    <?= @$data['candidateDetail']->preferred_job_type == 'Temporarily remote' ? 'selected' : '' ?>>
                    Temporarily remote</option>
                <option value="Freelance"
                    <?= @$data['candidateDetail']->preferred_job_type == 'Freelance' ? 'selected' : '' ?>>
                    Freelance</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label>Skill</label>
            <select class="js-example-basic-multiple skillmultiple" name="skill[]" data-placeholder="select skills" multiple="multiple">
                <option disabled readonly>Choose a skill</option>
                @foreach ($data['skills'] as $sk)
                    <option value="{{ $sk->id }}" <?php if (in_array($sk->id, @$data['userSkill'])) {
                        echo 'selected';
                    } ?>>{{ $sk->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label>Education</label>
            <select class="js-example-basic-multiple edumultiple" name="education[]" data-placeholder="select education"
                    multiple="multiple">
                <option disabled readonly>Choose education</option>
                @foreach ($data['qualification'] as $q)
                    <option value="{{ $q->id }}" <?php if (in_array($q->id, @$data['userEdu'])) {
                        echo 'selected';
                    } ?>>{{ $q->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
