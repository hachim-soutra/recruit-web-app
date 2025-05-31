<form id="signupform">
    @csrf
    <!-- item -->
    <div class="item">
        <input type="hidden" placeholder="ID" id="{{ $usertype . '_id' }}" value="{{$user->id}}">
    </div>
    <!-- item -->
    <!-- item -->
    <div class="item">
        <input type="text" placeholder="Full Name" id="{{ $usertype . '_fullname' }}" value="{{$user->first_name.' '.$user->last_name}}">
    </div>
    <!-- item -->
    <!-- item -->
    <div class="item">
        <input type="email" placeholder="Email Address" id="{{ $usertype . '_email' }}" value="{{$user->email}}" disabled>
    </div>
    <!-- item -->
    <!-- item -->
    <div class="item">
        <input type="password" placeholder="Password" id="{{ $usertype . '_password' }}">
    </div>
    <!-- item -->
    <!-- item -->
    <div class="item">
        <input type="password" placeholder="Confirm Password" id="{{ $usertype . '_confirmpassword' }}">
    </div>
    <!-- item -->
    <!-- item -->
    <div class="item login-sec">
        <input type="button" value="Register" data-usertype="{{ $usertype }}"
            onclick="formSubmit(this, '{{ $usertype }}')">
    </div>
    <!-- item -->
</form>
