@extends('admin.layout.app')

@section('title', 'Coach Edit')

@section('mystyle')

@endsection

@section('content')
    <section class="content-header">
        <h1>
            Recruit.ie Admin
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.coach.list') }}">Coachs</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Coach Update From</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ route('admin.coach.list') }}" class="btn btn-box-tool">
                                <i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('admin.coach.update', $user->id) }}" enctype="multipart/form-data"
                            method="post" name="user-update-frm" id="user-update-frm" class="user-update-frm">
                            @csrf
                            @method('patch')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter name" value="{{ $user->name }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="email">Email</label>
                                        <input type="text" name="email" class="form-control" id="email"
                                            placeholder="Email" value="{{ $user->email }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="mobile">Mobile</label>
                                        <input type="text" name="mobile" class="form-control" id="tel"
                                            placeholder="Mobile" value="{{ $user->mobile }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="date_of_birth">Date Of Birth</label>
                                <input type="text" name="date_of_birth" class="form-control date_picker" id="date_of_birth" placeholder="YYYY-MM-DD" value="{{ $user->coach->date_of_birth }}">
                                </div>
                            </div> --}}

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="country">Country</label>
                                        <select class="form-control" name="country" id="country">
                                            @forelse ($countries as $row)
                                                <option value="{{ $row->name }}" data-country="{{ $row->id }}"
                                                    {{ @$row->name === @$user->coach->country ? 'selected' : '' }}>
                                                    {{ $row->name }}
                                                </option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="state">State</label>
                                        <select class="form-control" name="state" id="state">
                                            @if (@$user->coach->state)
                                                <option value="{{ $user->coach->state }}" selected>
                                                    {{ @$user->coach->state }}</option>
                                            @else
                                                @foreach ($states as $s)
                                                    <option value="{{ $s->name }}">{{ $s->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="city">City</label>
                                        @if (@$user->coach->city)
                                            <select class="form-control" name="city" id="city"
                                                value="{{ @$user->coach->city }}">
                                                <option value="{{ @$user->coach->city }}">{{ @$user->coach->city }}
                                                </option>
                                            </select>
                                        @else
                                            <input type="text" class="form-control" name="city" id="city"
                                                placeholder="Enter City">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label" for="zip"> EIR Code/Zip Code</label>
                                        <input type="text" name="zip" class="form-control" id="zip"
                                            placeholder="  EIR Code/Zip Code" value="{{ @$user->coach->zip }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="address"> Address</label>
                                        <input type="text" name="address" class="form-control" id="address"
                                            placeholder="Address" value="{{ @$user->coach->address }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="avatar">Avatar</label>
                                        <input type="file" name="avatar" class="form-control" id="avatar"
                                            placeholder="Enter Avater">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="coach_banner">Banner</label>
                                        <input type="file" name="coach_banner" class="form-control" id="coach_banner"
                                            placeholder="Enter Banner">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="contact_link"> Website</label>
                                        <input type="text" name="contact_link" class="form-control" id="contact_link"
                                            placeholder="Url" value="{{ @$user->coach->contact_link }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="linkedin_link"> LinkedIn</label>
                                        <input type="text" name="linkedin_link" class="form-control"
                                            id="linkedin_link" placeholder="Url"
                                            value="{{ @$user->coach->linkedin_link }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="facebook_link"> Facebook</label>
                                        <input type="text" name="facebook_link" class="form-control"
                                            id="facebook_link" placeholder="Url"
                                            value="{{ @$user->coach->facebook_link }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="instagram_link"> Instagram</label>
                                        <input type="text" name="instagram_link" class="form-control"
                                            id="instagram_link" placeholder="Url"
                                            value="{{ @$user->coach->instagram_link }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">About US</label>
                                    <textarea name="about_us" id="about_us" class="form-control" value="{{ @$user->coach->about_us }}"
                                        placeholder="Enter About US">{{ @$user->coach->about_us }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">FAQ</label>
                                    <textarea name="faq" id="faq" class="form-control" value="{{ @$user->coach->faq }}"
                                        placeholder="Enter FAQs">{{ @$user->coach->faq }}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Coach Skill Detail</label>
                                    <textarea name="skill_detail" id="skill_detail" value="{{ @$user->coach->coach_skill }}" class="form-control"
                                        placeholder="Enter Coach Skill Detail">{{ @$user->coach->coach_skill }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="">How We Help</label>
                                    <textarea name="help_desk" id="help_desk" value="{{ @$user->coach->how_we_help }}" class="form-control"
                                        placeholder="Enter How We Help">{{ @$user->coach->how_we_help }}</textarea>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="roles">Roles <span class="required">*</span>
                                    </label>
                                    <div class="row">
                                        @forelse ($roles as $role)
                                            <div class="col-md-3">
                                                <label class="form-check-label">
                                                    <input name="roles[]" type="checkbox" class="flat-red roles" value="{{ $role->id }}" {{ (is_array($user->roles->pluck('id')->toArray()) && in_array($role->id, $user->roles->pluck('id')->toArray())) ? 'checked' : '' }}> {{ ucfirst($role->display_name) }}
                                                </label>
                                            </div>
                                        @empty
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                No Roles Added
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div> --}}
                                <div class="col-md-12">
                                    <button id="user-edit-btn-submit" type="submit"
                                        class="btn btn-success btn-block">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('myscript')
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('backend/plugin/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#about_us').wysihtml5();
            $('#faq').wysihtml5();
            $('#help_desk').wysihtml5();
            $('#skill_detail').wysihtml5();
        });
    </script>
    @include('admin.scripts.coach')
    @include('admin.scripts.location')
@endsection
