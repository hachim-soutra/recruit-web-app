<form id="signupform">
    @csrf
    <!-- item -->
    <div class="item">
        <input type="text" placeholder="Full Name" id="{{ $usertype . '_fullname' }}">
    </div>
    <!-- item -->
    <!-- item -->
    <div class="item">
        <input type="email" placeholder="Email Address" id="{{ $usertype . '_email' }}">
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
        <input type="button" value="Login" data-usertype="{{ $usertype }}"
            onclick="formSubmit(this, '{{ $usertype }}')">
    </div>
    <!-- item -->
    <div class="item-border">
        <p>or</p>
    </div>
    <div class="with-linkedin">
        <a href="{{ route('auth.social.login', ['usertype' => $usertype, 'provider' => 'linkedin-openid']) }}">
            <input class="btn btn-lg btn-facebook btn-block" type="button" value="Sign In with Linkedin">
        </a>
    </div>
    <div class="with-google" style="margin-top:5%;">
        <a href="{{ route('auth.social.login', ['usertype' => $usertype, 'provider' => 'google']) }}">
            <input class="btn btn-lg btn-facebook btn-block" type="button" value="Sign In with Google">
        </a>
    </div>
    <div class="with-facebook" style="margin-top:5%;">
        <a href="{{ route('auth.social.login', ['usertype' => $usertype, 'provider' => 'facebook']) }}">
            <input class="btn btn-lg btn-facebook btn-block" type="button" value="Sign In with Facebook">
        </a>
    </div>
    <!-- item -->
    <div class="register-sec">
        <!---->
        <p>Already have an account?</p>
        <a href="{{ route('signin') }}">Sign In</a>
        <!---->
    </div>
    <!-- item -->
</form>
