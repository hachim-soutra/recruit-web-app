<div id="job-activity" class="tabcontent active" style="display:none;">
    @foreach ($data['userAppliedJobs'] as $aj)
        @if (@$aj->jobs->job_title != '')
            <div class="row" style="justify-content: center;padding: 7px;cursor: pointer;">
                <div class="col-lg-12" style="border-radius: 10px;">
                    <div class="item-col">
                        <!--top-->
                        <div class="top" style="padding: 0 0 15px;">
                            <div class="lt toplt"><b>{{ @$aj->jobs->job_title }}</b></div>
                            <span>{{ @$aj->jobs->company_name }}</span>
                        </div>
                        <!--top-->
                        <!-- middle -->
                        <div class="middle">
                            <div class="lt">
                                                    <span><i class="fa fa-map-marker"></i>
                                                        {{ @$aj->jobs->job_location }}</span>
                                <p>{{ @$aj->jobs->preferred_job_type }}</p>
                            </div>
                            <div class="rt">
                                <div class="img-sec">
                                    <img src="{{ @$aj->jobs->company_logo }}">
                                </div>
                            </div>
                        </div>
                        <!-- middle -->
                        <div class="bottom">
                            <p>{{ @$aj->jobs->salary_currency }}{{ @$aj->jobs->salary_from }} -
                                {{ @$aj->jobs->salary_currency }}{{ @$aj->jobs->salary_to }}
                                {{ @$aj->jobs->salary_period }}</p>
                            <p><b>Applied: </b> {{ date('jS F, y', strtotime(@$aj->created_at)) }}</p>
                            <p class="pstatus <?php if (@$aj->status == 'Applied') {
                                                    echo 'pstatus_applied_color';
                                                } else {
                                                    echo 'pstatus_approved_color';
                                                } ?>">{{ ucfirst(@$aj->status) }}</p>
                            <span><b>Posted
                                                        :</b>{{ Carbon\Carbon::createFromTimeStamp(strtotime(@$aj->jobs->created_at))->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
    {{ $data['userAppliedJobs']->appends(Request::except('page'))->onEachSide(1)->links('pagination::bootstrap-4') }}
    @if(is_null($data['userAppliedJobs']) || count($data['userAppliedJobs']) == 0)
        <h3 class="text-center" style="color:red;width: 100%;">No Data Found.</h3>
    @endif
</div>
