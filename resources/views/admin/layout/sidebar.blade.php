<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            @if (Auth::user()->avatar)
                <img class="img-circle" src="{{ Auth::user()->avatar }}" alt="Profile image" width="160" height="160">
            @else
                <img src="{{ asset('backend/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
            @endif
        </div>
        <div class="pull-left info">
            <p>{{ Auth::user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
    </div>

    <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

        {{-- <li class="treeview">
            <a href="#">
                <i class="fa fa-lock"></i> <span>Admin ACL</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="{{ route('admin.user.list') }}"><i class="fa fa-circle-o"></i> Admin</a>
                </li>
                <li>
                    <a href="{{ route('admin.role.list') }}"><i class="fa fa-circle-o"></i> Role</a>
                </li>
                <li>
                    <a href="{{ route('admin.permission.list') }}"><i class="fa fa-circle-o"></i> Permission</a>
                </li>
            </ul>
        </li> --}}

        <li class="treeview {{ Route::is('admin.reports.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <!--<i class="fa fa-child"></i>-->
                <i class="fa fa-bar-chart"></i> <span>Reports</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.reports.recent1') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.recent1') }}"><i class="fa fa-bell"></i> Recent Applications</a>
                </li>
                <li class="{{ Route::is('admin.reports.report1') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.report1') }}"><i class="fa fa-check-square-o"></i> Report 1 -
                        Applications</a>
                </li>
                <li class="{{ Route::is('admin.reports.report2') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-line-chart"></i> Report 2 - Views</a>
                </li>
                <li class="{{ Route::is('admin.reports.search_keywords_report_1') ? 'active' : '' }}">
                    <a href="{{ route('admin.reports.search_keywords_report_1') }}"><i class="fa fa-search"></i> Report
                        3 - Search</a>
                </li>
            </ul>
        </li>

        <li class="treeview {{ Route::is('admin.candidate.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-child"></i> <span>Candidates</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.candidate.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.candidate.list') }}"><i class="fa fa-list"></i> List</a>
                </li>

            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.employer.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-user"></i> <span>Employers</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.employer.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.employer.list') }}"><i class="fa fa-list"></i> List</a>
                </li>

            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.coach.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-users"></i> <span>Coaches</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.coach.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.coach.list') }}"><i class="fa fa-list"></i>All Coaches</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.job.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-bank (alias)"></i> <span>Jobs</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.job.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.job.list') }}"><i class="fa fa-list"></i>All Jobs</a>
                </li>
                <li class="{{ Route::is('admin.job.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.job.create') }}"><i class="fa fa-plus"></i>Add Job</a>
                </li>
                <li class="{{ Route::is('admin.job.import') ? 'active' : '' }}">
                    <a href="{{ route('admin.job.import') }}"><i class="fa fa-circle-o"></i>Import Job</a>
                </li>
                <li class="{{ Route::is('admin.job.expire') ? 'active' : '' }}">
                    <a href="{{ route('admin.job.expire') }}"><i class="fa fa-circle-o"></i>Expire Job</a>
                </li>
                <li class="{{ Route::is('admin.job.welcome-list') ? 'active' : '' }}">
                    <a href="{{ route('admin.job.welcome-list') }}"><i class="fa fa-circle-o"></i>Welcome Jobs</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.advertise.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-bank (alias)"></i> <span>Advertises</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.advertise.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.advertise.list') }}"><i class="fa fa-list"></i>All Advertises</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.event.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-hourglass-2 (alias)"></i> <span>Events</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.event.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.event.list') }}"><i class="fa fa-list"></i>All Events</a>
                </li>
                <li class="{{ Route::is('admin.event.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.event.create') }}"><i class="fa fa-plus"></i>Add Event</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.plan.*') || Route::is('admin.slot.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-hourglass-2 (alias)"></i> <span>Plans / Slots</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.plan.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.plan.list') }}"><i class="fa fa-list"></i>All Plans</a>
                </li>
                <li class="{{ Route::is('admin.slot.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.slot.list') }}"><i class="fa fa-list"></i>All Slots</a>
                </li>
                <li class="{{ Route::is('admin.plan.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.plan.create') }}"><i class="fa fa-plus"></i>Add Plan</a>
                </li>
                <li class="{{ Route::is('admin.slot.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.slot.create') }}"><i class="fa fa-plus"></i>Add Slot</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.subscription.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-hourglass-2 (alias)"></i> <span>Subscriptions</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.subscription.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.subscription.list') }}"><i class="fa fa-list"></i>Inactive Subscriptions</a>
                </li>
                <li class="{{ Route::is('admin.subscription.add-manual') ? 'active' : '' }}">
                    <a href="{{ route('admin.subscription.add-manual') }}"><i class="fa fa-plus"></i>Add Manual Subscription</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.coupon.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-lock"></i> <span>Coupons</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.coupon.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.coupon.list') }}"><i class="fa fa-list"></i>All Coupons</a>
                </li>
                <li class="{{ Route::is('admin.coupon.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.coupon.create') }}"><i class="fa fa-plus"></i>Add Coupon</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.skill.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-paw"></i> <span>Job Skills</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.skill.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.skill.list') }}"><i class="fa fa-list"></i>All Job Skills</a>
                </li>
                <li class="{{ Route::is('admin.skill.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.skill.create') }}"><i class="fa fa-plus"></i>Add Job Skill</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.industry.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-recycle"></i> <span>Industries
                </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.industry.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.industry.list') }}"><i class="fa fa-list"></i>All Industries
                    </a>
                </li>
                <li class="{{ Route::is('admin.industry.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.industry.create') }}"><i class="fa fa-plus"></i>Add Industry</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.qualification.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-plus-circle"></i> <span>Qualifications</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.qualification.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.qualification.list') }}"><i class="fa fa-list"></i>All
                        Qualifications</a>
                </li>
                <li class="{{ Route::is('admin.qualification.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.qualification.create') }}"><i class="fa fa-plus"></i>Add
                        Qualifications</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.language.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-sun-o"></i> <span>Languages</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.language.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.language.list') }}"><i class="fa fa-list"></i>All Languages</a>
                </li>
                <li class="{{ Route::is('admin.language.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.language.create') }}"><i class="fa fa-plus"></i>Add Language</a>
                </li>
            </ul>
        </li>
        <!-- testimonial  -->
        <li class="treeview {{ Route::is('admin.testimonial.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-quote-left"></i> <span>Testimonials</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.testimonial.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.testimonial.list') }}"><i class="fa fa-list"></i>All Testimonials</a>
                </li>
                <li class="{{ Route::is('admin.testimonial.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.testimonial.create') }}"><i class="fa fa-plus"></i>Add Testimonial</a>
                </li>
            </ul>
        </li>
        <!-- news  -->
        <li class="treeview {{ Route::is('admin.news.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-newspaper-o"></i> <span>News</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.news.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.news.list') }}"><i class="fa fa-list"></i>All News</a>
                </li>
                <li class="{{ Route::is('admin.news.category') ? 'active' : '' }}">
                    <a href="{{ route('admin.news.category') }}"><i class="fa fa-plus"></i>Add Category</a>
                </li>
                <li class="{{ Route::is('admin.news.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.news.create') }}"><i class="fa fa-plus"></i>Add News</a>
                </li>
            </ul>
        </li>
        <!-- advice  -->
        <li class="treeview {{ Route::is('admin.advice.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-newspaper-o"></i> <span>Advices</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.advice.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.advice.list') }}"><i class="fa fa-list"></i>All Advices</a>
                </li>
                <li class="{{ Route::is('admin.advice.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.advice.create') }}"><i class="fa fa-plus"></i>Add Advice</a>
                </li>
            </ul>
        </li>
        <!-- client  -->
        <li class="treeview {{ Route::is('admin.client.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-user-o"></i> <span>Clients</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.client.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.client.list') }}"><i class="fa fa-list"></i>All Clients</a>
                </li>
                <li class="{{ Route::is('admin.client.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.client.create') }}"><i class="fa fa-plus"></i>Add Client</a>
                </li>
            </ul>
        </li>
        <!-- team  -->
        <li class="treeview {{ Route::is('admin.team.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-group"></i> <span>Teams</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.team.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.team.list') }}"><i class="fa fa-list"></i>All Teams</a>
                </li>
                <li class="{{ Route::is('admin.team.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.team.create') }}"><i class="fa fa-plus"></i>Add Team</a>
                </li>
            </ul>
        </li>
        <!-- contact us -->
        <li class="treeview {{ Route::is('admin.contact.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-address-book-o"></i> <span>Contact Us</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.contact.info') ? 'active' : '' }}">
                    <a href="{{ route('admin.contact.info') }}"><i class="fa fa-circle-o"></i>Information List</a>
                </li>
            </ul>
        </li>
        <!-- cms  -->
        <li class="treeview {{ Route::is('admin.cms.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-group"></i> <span>CMS Management</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.cms.home-content') ? 'active' : '' }}">
                    <a href="{{ route('admin.cms.home-content') }}"><i class="fa fa-circle-o"></i>Home Page
                        Content</a>
                </li>
                <li class="{{ Route::is('admin.cms.about-us') ? 'active' : '' }}">
                    <a href="{{ route('admin.cms.about-us') }}"><i class="fa fa-circle-o"></i>About Us Content</a>
                </li>
                <li class="{{ Route::is('admin.cms.contact-us') ? 'active' : '' }}">
                    <a href="{{ route('admin.cms.contact-us') }}"><i class="fa fa-circle-o"></i>Contact Us
                        Content</a>
                </li>
                <li class="{{ Route::is('admin.cms.meta-info') ? 'active' : '' }}">
                    <a href="{{ route('admin.cms.meta-info') }}"><i class="fa fa-circle-o"></i>Meta Information</a>
                </li>
                <li class="{{ Route::is('admin.cms.permanent-recruitment') ? 'active' : '' }}">
                    <a href="{{ route('admin.cms.permanent-recruitment') }}"><i class="fa fa-circle-o"></i>Permanent
                        Recruitment</a>
                </li>
                <li class="{{ Route::is('admin.cms.virtual-recruitment') ? 'active' : '' }}">
                    <a href="{{ route('admin.cms.virtual-recruitment') }}"><i class="fa fa-circle-o"></i>Virtual
                        Recruitment Events</a>
                </li>
                <li class="{{ Route::is('admin.cms.tech-careers') ? 'active' : '' }}">
                    <a href="{{ route('admin.cms.tech-careers') }}"><i class="fa fa-circle-o"></i>Tech Careers
                        Expo</a>
                </li>
                <li class="{{ Route::is('admin.cms.jobs-expo') ? 'active' : '' }}">
                    <a href="{{ route('admin.cms.jobs-expo') }}"><i class="fa fa-circle-o"></i>Jobs Expo Ireland</a>
                </li>
            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.campaign.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-lock"></i> <span>Campaigns</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="{{ route('admin.campaign.index') }}"><i class="fa fa-circle-o"></i> Campaigns</a>
                </li>
                <li>
                    <a href="{{ route('admin.campaign.alert') }}"><i class="fa fa-circle-o"></i> Alerts</a>
                </li>

            </ul>
        </li>
        <li class="treeview {{ Route::is('admin.resource.*') ? 'active menu-open' : '' }}">
            <a href="#">
                <i class="fa fa-group"></i> <span>Resources</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="{{ Route::is('admin.resource.list') ? 'active' : '' }}">
                    <a href="{{ route('admin.resource.list') }}"><i class="fa fa-list"></i>All Resources</a>
                </li>
                <li class="{{ Route::is('admin.resource.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.resource.create') }}"><i class="fa fa-plus"></i>Add Resource</a>
                </li>
            </ul>
        </li>
        <li class="{{ Route::is('admin.setting.show') ? 'active' : '' }}">
            <a href="{{ route('admin.setting.show') }}">
                <i class="fa fa-wrench"></i> <span>Settings</span>
                {{-- <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span> --}}
            </a>
        </li>

    </ul>
</section>
