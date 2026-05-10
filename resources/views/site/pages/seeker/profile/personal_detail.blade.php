<div id="personal-details" class="tabcontent active" style="display:block;">
    <div class="item-headline">
        <div class="mt-4"></div>
            <div class="row">
                <div class="col-md-4 col-12">
                    <label for="">Gender</label>
                    <select name="gender" class="form-control gender" id="gender">
                        <option selected disabled readonly>Choose gender</option>
                        <option value="Male"
                            <?= @$data['candidateDetail']->gender == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female"
                            <?= @$data['candidateDetail']->gender == 'Female' ? 'selected' : '' ?>>Female
                        </option>
                        <option value="Prefer to not say"
                            <?= @$data['candidateDetail']->gender == 'Prefer to not say' ? 'selected' : '' ?>>
                            Prefer to not say</option>
                    </select>
                </div>
                <div class="col-md-4 col-12">
                    <label for="">First Name</label>
                    <input type="text" name="first_name" id="first_name"
                           value="{{ Auth::user()->first_name }}" class="form-control"
                           placeholder="Enter First Name.">
                </div>
                <div class="col-md-4 col-12">
                    <label for="">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ Auth::user()->last_name }}"
                           class="form-control" placeholder="Enter Last Name.">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="">Your Address</label>
                    <input type="text" name="address" value="{{ @$data['candidateDetail']->address }}"
                           id="address" placeholder="Enter your address" class="form-control" />

                    <input type="hidden" id="start_latitude" name="start_latitude" value="" />
                    <input type="hidden" id="start_longitude" name="start_longitude" value="" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-12">
                    <label for="">Email-ID</label>
                    <input type="text" name="email" id="email" class="form-control"
                           value="{{ Auth::user()->email }}" placeholder="Your Email-ID">
                </div>
                <div class="col-md-4 col-12">
                    <label for="">Mobile (Along With Country Code)</label>
                    <input type="text" name="alternate_mobile_number"
                           value="{{ @$data['candidateDetail']->alternate_mobile_number }}"
                           id="alternate_mobile_number" class="form-control"
                           placeholder="Alternate Mobile Number">
                </div>
                <div class="col-md-4 col-12">
                    <label for="">Date Of Birth</label>
                    <input type="date" name="dob" id="dob" onchange="dobGt18()"
                           value="{{ @$data['candidateDetail']->date_of_birth }}" class="form-control">
                </div>
            </div>
    </div>
</div>
