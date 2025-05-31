<div class="ad-block" align="center">
	<script>
	  window.googletag = window.googletag || {cmd: []};
	  googletag.cmd.push(function() {
		googletag.defineSlot('/23122007764/Recruit.ie-Leaderboard', [[970, 250], [728, 90], [320, 50], [468, 60]], 'div-gpt-ad-1715605528342-0').addService(googletag.pubads());
		googletag.pubads().enableSingleRequest();
		googletag.pubads().collapseEmptyDivs();
		googletag.enableServices();
	  });
	</script>

	<!-- /23122007764/Recruit.ie-Leaderboard -->
	<div id='div-gpt-ad-1715605528342-0' style='min-width: 320px; min-height: 50px; margin:0 auto;'>
		<script>googletag.cmd.push(function() { googletag.display('div-gpt-ad-1715605528342-0'); });</script>
	</div>
</div>

<footer class="footer-block">
    <div class="container">
        <div class="footer-bd">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="item">
                        <div class="contact-sec">
                            <img src="{{ asset('frontend/images/logo.svg') }}" class="brand-logo" alt="recruitment"
                                title="recruitment" loading="lazy">
                            <p>{!! $settings ? nl2br($settings->site_short_desc) : '' !!}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 ml-lg-auto col-md-6 col-6">
                    <div class="item">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="{{ route('welcome') }}">Home</a></li>
                            <li><a href="{{ route('blogs', ['type' => 'news']) }}">News</a></li>
                            <li><a href="{{ route('upload-resume') }}">Upload Your CV</a></li>
                            <li><a href="{{ route('career-coach') }}">Career Coach</a></li>
                            <li><a href="{{ route('contact.us') }}">Contact Us</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-6">
                    <div class="item">
                        <h4>About Recruit.ie</h4>
                        <ul>
                            <li><a href="{{ route('about.us') }}">About Us</a></li>
                            <li><a href="{{ route('testimonial') }}">Testimonials</a></li>
                            <li><a href="{{ route('partner.us') }}">Partner with Us</a></li>
                            <li><a href="{{ route('term_of_use') }}">Terms Of Use</a></li>
                            <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                            <li><a href="https://www.recruit.ie/careers/cookies-policy/">Cookies</a></li>
                        </ul>
                    </div>
                </div>

                @if ($settings)

                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="item">
                            <h4>Get In Touch</h4>

                            <div class="contact-sec">


                                @if ($settings->addres_one != '' || $settings->address_two != '')
                                    <div class="sec">
                                        @if ($settings->addres_one != '')
                                            <span><i class="fa fa-map-marker"></i></span>
                                            <p>61 Lower Kilmacud Road, Stillorgan, Co. Dublin, A94 A2F7, Ireland</p>
                                        @endif
                                    </div>
                                    <div class="sec">
                                    </div>
                                @endif

                                @php @endphp
                                @if ($settings->mobile_no != '' || $settings->alt_mobaile_no != '')
                                    <div class="sec">
                                        @if ($settings->mobile_no != '')
                                            <span><i class="fa fa-phone"></i></span>
                                            <a href="tel:">01 215 0518</a>
                                        @endif
                                        @if ($settings->alt_mobaile_no != '')
                                            <span><i class="fa fa-phone"></i></span>
                                            <a href="tel:">087 646 3175</a>
                                        @endif
                                    </div>
                                @endif

                                @if ($settings->contact_email != '')
                                    <div class="sec">
                                        <span><i class="fa fa-envelope"></i></span>
                                        <a href="mailto:">{{ $settings->contact_email }}</a>
                                    </div>
                                @endif


                                <ul class="sochal-footer">
                                    @if ($settings->twitter_link != '')
                                        <li><a target="_balnk" href="https://twitter.com/Recruit_ie"><i
                                                    class="fa fa-twitter"></i></a></li>
                                    @endif
                                    @if ($settings->facebook_link != '')
                                        <li><a target="_balnk" href="https://www.instagram.com/recruit.ie/"><i
                                                    class="fa fa-instagram"></i></a></li>
                                    @endif
                                    @if ($settings->instagram_link != '')
                                        <li><a target="_balnk" href="https://www.facebook.com/recruitiejobs"><i
                                                    class="fa fa-facebook"></i></a></li>
                                    @endif
                                    @if ($settings->linkedin_link != '')
                                        <li><a target="_balnk"
                                                href="https://www.linkedin.com/company/{{ @$settings->linkedin_link }}/"><i
                                                    class="fa fa-linkedin"></i></a></li>
                                    @endif
                                </ul>

                            </div>

                        </div>
                    </div>
                @endif


            </div>
        </div>

        <!--copyright-->
        <div class="copyright-block">


            <!---->
            <div class="item">
                <div class="social-icon">
                    <ul>
                        <li><a href="https://play.google.com/store/apps/details?id=app.recruit.ie&hl=en&gl=US"><img
                                    alt="play google" src="{{ asset('frontend/images/img1.png') }}" loading="lazy"></a>
                        </li>
                        <li><a href="https://apps.apple.com/us/app/recruit-ie/id1638680033"><img alt="apple"
                                    src="{{ asset('frontend/images/img2.png') }}" loading="lazy"></a></li>
                    </ul>
                </div>
            </div>

            <div class="item middle-opyright">
                <p>Copyright © {{ now()->year }} Recruit.ie</p>
            </div>
        </div>
    </div>
</footer>
