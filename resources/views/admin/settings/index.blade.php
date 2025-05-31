@extends('admin.layout.app')
@section('title', "Setting ")

@section('mystyle')
@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Settings</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Setting Details</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.setting.edit') }}" class="btn btn-xs btn-info">Edit</a>
                    </div>
                </div>

                <div class="box-body">
                    <div class="col-lg-12">
                        <form name="user-update-form" id="user-update-form" a class="form-horizontal form-label-left" data-parsley-validate enctype="multipart/form-data">
                            {!! method_field('patch') !!}
                            {{ csrf_field() }}
                            
                            <div class="form-group {{ $errors->has('app_version') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="app_version"> App Version <span
                                    class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="app_version" id="app_version" class="form-control" placeholder="App Version" value="{{ old('app_version', $settings->app_version) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('app_version'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('app_version') }}</strong>
                                </span>
                                @endif
                            </div>
                            
                            <div class="form-group {{ $errors->has('site_name') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="site_name"> Site Name <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="site_name" id="site_name" class="form-control" placeholder="Enter Contact Email" value="{{ old('site_name', $settings->site_name) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('site_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('site_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('site_description') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="site_description"> Site Description <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="site_description" id="site_description" class="form-control" placeholder="Enter Contact Email" value="{{ old('site_description', $settings->site_short_desc) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('site_description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('site_description') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('contact_email') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact_email"> Contact Email <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="contact_email" id="contact_email" class="form-control" placeholder="Enter Contact Email" value="{{ old('contact_email', $settings->contact_email) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('contact_email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact_email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('new_join_one_month_free') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="new_join_one_month_free"> New Join One Month Free For<span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  
                                     <select class="form-control" name="new_join_one_month_free" id="new_join_one_month_free" disabled>
                                       <option></option>
                                       <option value="Employer" {{ ($settings->new_join_one_month_free == 'Employer')?'selected': ''}}>Employer</option>
                                       <option value="Coach" {{ ($settings->new_join_one_month_free == 'Coach')?'selected': ''}}>Coach</option>
                                       <option value="Both" {{ ($settings->new_join_one_month_free == 'Both')?'selected': ''}}>Both</option>
                                       <option value="None of These" {{ ($settings->new_join_one_month_free == 'None of These')?'selected': ''}}>None of These</option>
                                     </select>
                                </div>
                                @if ($errors->has('new_join_one_month_free'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('new_join_one_month_free') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('mobile_no') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="currency"> Mobile No. <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="Enter Mobile No." value="{{ old('mobile_no', $settings->mobile_no) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('mobile_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mobile_no') }}</strong>
                                    </span>
                                @endif
                            </div> 
                            <div class="form-group {{ $errors->has('alt_mobaile_no') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="currency"> Alternative Mobile No. <span
                                        class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="alt_mobaile_no" id="alt_mobaile_no" class="form-control" placeholder="Enter Alternative Mobile No." value="{{ old('alt_mobaile_no', $settings->alt_mobaile_no) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('alt_mobaile_no'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('alt_mobaile_no') }}</strong>
                                    </span>
                                @endif
                            </div>                            
                            <div class="form-group {{ $errors->has('addres_one') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="addres_one"> Address One <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="addres_one" id="addres_one" class="form-control" placeholder="Enter Address One" value="{{ old('addres_one', $settings->addres_one) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('addres_one'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('addres_one') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('address_two') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address_two"> Address Two <span
                                        class="required"></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="address_two" id="address_two" class="form-control" placeholder="Enter Address Two" value="{{ old('address_two', $settings->address_two) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('address_two'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address_two') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('currency') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="currency"> Currency <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="currency" id="currency" class="form-control" placeholder="Enter Currency" value="{{ old('currency', $settings->currency) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('currency'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('currency') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('payment_gateway') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_gateway"> Payment Gateway <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="payment_gateway" id="payment_gateway" class="form-control" placeholder="Enter Payment Gateway" value="{{ old('payment_gateway', $settings->payment_gateway) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('payment_gateway'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('payment_gateway') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('secret_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="secret_key"> Secret Key <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="secret_key" id="secret_key" class="form-control" placeholder="Enter Secret Key" value="{{ old('secret_key', $settings->secret_key) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('secret_key'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('secret_key') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('published_key') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="published_key"> Published Key <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="published_key" id="published_key" class="form-control" placeholder="Enter Published Key" value="{{ old('published_key', $settings->published_key) }}"required="required" readonly>
                                </div>
                                @if ($errors->has('published_key'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('published_key') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('facebook_link') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="facebook_link"> Facebook Link 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="facebook_link" id="facebook_link" readonly class="form-control" placeholder="Enter Facebook Link" value="{{ old('facebook_link', $settings->facebook_link) }}">
                                </div>
                                @if ($errors->has('facebook_link'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('facebook_link') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('twitter_link') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="twitter_link"> Twitter Link 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="twitter_link" id="twitter_link" readonly class="form-control" placeholder="Enter Twitter Link" value="{{ old('twitter_link', $settings->twitter_link) }}">
                                </div>
                                @if ($errors->has('twitter_link'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('twitter_link') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('instagram_link') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="instagram_link"> Instagram Link 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="instagram_link" id="instagram_link" class="form-control" readonly placeholder="Enter Instagram Link" value="{{ old('instagram_link', $settings->instagram_link) }}">
                                </div>
                                @if ($errors->has('instagram_link'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('instagram_link') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('linkedin_link') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="linkedin_link"> LinkedIn Link 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="linkedin_link" id="linkedin_link" class="form-control" readonly placeholder="Enter Instagram Link" value="{{ old('linkedin_link', $settings->linkedin_link) }}">
                                </div>
                                @if ($errors->has('linkedin_link'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('linkedin_link') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('copyright_content') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="copyright_content"> Footer Copyright Content
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="copyright_content" id="copyright_content" class="form-control" readonly placeholder="Enter Footer Copyright Content" value="{{ old('copyright_content', $settings->copyright_content) }}">
                                </div>
                                @if ($errors->has('copyright_content'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('copyright_content') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('about_us') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="about_us"> About Us <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="about_us" id="about_us" class="form-control textarea" readonly> {{ $settings->about_us }} </textarea>
                                </div>
                                @if ($errors->has('about_us'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('about_us') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('help') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="help">Help <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="help" id="help" class="form-control textarea"  readonly> {{ $settings->help }} </textarea>
                                </div>
                                @if ($errors->has('help'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('help') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('privacy_policy') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="privacy_policy"> Privacy Policy <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="privacy_policy" id="privacy_policy" class="form-control textarea" readonly> {{ $settings->privacy_policy }} </textarea>
                                </div>
                                @if ($errors->has('privacy_policy'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('privacy_policy') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('term_of_use') ? ' has-error' : '' }}">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="term_of_use">Terms of Use <span
                                        class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="term_of_use" id="term_of_use" class="form-control textarea"  readonly> {{ $settings->term_of_use }} </textarea>
                                </div>
                                @if ($errors->has('term_of_use'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('term_of_use') }}</strong>
                                    </span>
                                @endif
                            </div>



                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

@section('myscript')
@include('admin.scripts.settting')
@endsection
