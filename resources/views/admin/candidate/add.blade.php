@extends('admin.layout.app')

@section('title', "Candidate Edit")

@section('mystyle')

@endsection

@section('content')
<section class="content-header">
    <h1>
        Recruit.ie Admin
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.candidate.list') }}">Candidates</a></li>
        <li class="active">Edit</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Candidate Update From</h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('admin.candidate.list') }}" class="btn btn-box-tool">
                            <i class="fa fa-arrow-left"></i>  Back</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.candidate.update', $user->id) }}" method="post" name="user-update-frm" id="user-update-frm" class="user-update-frm">
                        @csrf
                        @method('patch')
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="name">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter name" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="text" name="email" class="form-control" id="email" placeholder="Email" value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="mobile">Mobile</label>
                                    <input type="text" name="mobile" class="form-control" id="mobile" placeholder="Mobile" value="{{ $user->mobile }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="dob">Date Of Birth</label>
                                <input type="text" name="date_of_birth" class="form-control date_picker" id="date_of_birth" placeholder="YYYY-MM-DD" value="{{ $user->candidate->date_of_birth }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="gender">Gender</label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="Male" {{ ($user->candidate->gender === 'Male') ? "selected" : ""  }}>Male</option>
                                        <option value="Female" {{ ($user->candidate->gender === 'Female') ? "selected" : ""  }}>Female</option>
                                        <option value="Prefer to not say" {{ ($user->candidate->gender === 'Prefer to not say') ? "selected" : ""  }}>Prefer to not say</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="marital_status">Marital Status</label>
                                    <select class="form-control" name="marital_status" id="marital_status">
                                        <option value="Married" {{ ($user->candidate->marital_status === 'Married') ? "selected" : ""  }}>Married</option>
                                        <option value="Single" {{ ($user->candidate->marital_status === 'Single') ? "selected" : ""  }}>Single</option>
                                        <option value="Divorced" {{ ($user->candidate->marital_status === 'Divorced') ? "selected" : ""  }}>Divorced</option>></option>
                                        <option value="Widowed" {{ ($user->candidate->marital_status === 'Widowed') ? "selected" : ""  }}>Widowed</option>></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="country">Country</label>
                                    <select class="form-control" name="country" id="country">
                                        @forelse ($countries as $row)
                                            <option value="{{ $row->name }}" data-country="{{ $row->id }}" {{ ($row->name === $user->candidate->country) ? 'selected' : "" }}>
                                                {{ $row->name }}
                                            </option>
                                        @empty

                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="state">State</label>
                                    <select class="form-control" name="state" id="state">
                                        @if ($user->candidate->state)
                                            <option value="{{ $user->candidate->state }}" selected>{{ $user->candidate->state }}</option>
                                        @else
                                            <option></option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="city">City</label>
                                    <select class="form-control" name="city" id="city">
                                        @if ($user->candidate->city)
                                            <option value="{{ $user->candidate->city }}" selected>{{ $user->candidate->city }}</option>
                                        @else
                                            <option></option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="zip">  EIR Code/Zip Code</label>
                                    <input type="text" name="zip" class="form-control" id="zip" placeholder="  EIR Code/Zip Code" value="{{ $user->candidate->zip }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="address"> Address</label>
                                    <input type="text" name="address" class="form-control" id="address" placeholder="Address" value="{{ $user->candidate->address }}">
                                </div>
                            </div>
                        </div>
                        {{-- <h3>Career Information</h3>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="candidate_type">Job Experience</label>
                                    <select class="form-control" name="candidate_type" id="candidate_type">
                                        <option value="Experience" {{ ($user->candidate_type === 'Experience') ? "selected" : ""  }}>Experience</option>
                                        <option value="Fresher" {{ ($user->candidate_type === 'Fresher') ? "selected" : ""  }}>Fresher</option>


                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="marital_status">Marital Status</label>
                                    <select class="form-control" name="marital_status" id="marital_status">
                                        <option value="Married" {{ ($user->marital_status === 'Married') ? "selected" : ""  }}>Married</option>
                                        <option value="Single" {{ ($user->marital_status === 'Single') ? "selected" : ""  }}>Single</option>
                                        <option value="Divorced" {{ ($user->marital_status === 'Divorced') ? "selected" : ""  }}>Divorced</option>></option>
                                        <option value="Widowed" {{ ($user->marital_status === 'Widowed') ? "selected" : ""  }}>Widowed</option>></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="zip">  EIR Code/Zip Code</label>
                                    <input type="text" name="zip" class="form-control" id="zip" placeholder="  EIR Code/Zip Code" value="{{ $user->zip }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label" for="address"> Address</label>
                                    <input type="text" name="address" class="form-control" id="address" placeholder="Address" value="{{ $user->address }}">
                                </div>
                            </div>
                        </div> --}}
                        <hr/>
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
                                <button id="user-edit-btn-submit" type="submit" class="btn btn-success btn-block">Save</button>
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
@include('admin.scripts.candidate')
@include('admin.scripts.location')
@endsection
