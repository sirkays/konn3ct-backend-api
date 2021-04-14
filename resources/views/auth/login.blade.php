@extends('layouts.layout')

@section('content')

<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
        </x-slot>

        <x-jet-validation-errors class="mb-1" />

        @if (session('status'))
            <div class="mb-1 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form class="mb-90" method="POST" action="{{ route('login') }}" onsubmit="return checkform(this);">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email Address') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-2">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-2">
                <label for="remember_me" class="flex items-center">
                    <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="block mt-1 text-center">
                <!-- START CAPTCHA -->
                <div class="capbox">
                    <div id="CaptchaDiv"></div>
                    <div class="capbox-inner">
                        Type the number:<br>
                        <input type="hidden" id="txtCaptcha">
                        <input type="text" name="CaptchaInput" id="CaptchaInput" size="15" autocomplete="off"><br>

                    </div>
                </div>
                <!-- END CAPTCHA -->
                <br/>
                    <span class="ml-2 text-sm"><sup><img src="/assets/images/konn3ct_logo.png" height="30px" width="100px" alt="logo"></sup> is protected by reCAPTCHA​</span>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button class="ml-4">
                    {{ __('Sign In') }}
                </x-jet-button>
            </div>

            <div class="block mt-4 text-center">
                <span>New to <sup><img src="/assets/images/konn3ct_logo.png" height="30px" width="100px" alt="logo"></sup>? <a class="su" href="{{ route('register') }}"  style="font-weight: bolder">Sign Up for Free</a></span>
            </div>

        </form>
    </x-jet-authentication-card>
</x-guest-layout>

<p style="margin-top: 200px">

</p>


<script type="text/javascript">

    // Captcha Script

    function checkform(theform){
        var why = "";

        if(theform.CaptchaInput.value == ""){
            why += "- Please Enter CAPTCHA Code.\n";
        }
        if(theform.CaptchaInput.value != ""){
            if(ValidCaptcha(theform.CaptchaInput.value) == false){
                why += "- The CAPTCHA Code Does Not Match.\n";
            }
        }
        if(why != ""){
            alert(why);
            return false;
        }
    }

    var a = Math.ceil(Math.random() * 9)+ '';
    var b = Math.ceil(Math.random() * 9)+ '';
    var c = Math.ceil(Math.random() * 9)+ '';
    var d = Math.ceil(Math.random() * 9)+ '';
    var e = Math.ceil(Math.random() * 9)+ '';

    var code = a + b + c + d + e;
    document.getElementById("txtCaptcha").value = code;
    document.getElementById("CaptchaDiv").innerHTML = code;

    // Validate input against the generated number
    function ValidCaptcha(){
        var str1 = removeSpaces(document.getElementById('txtCaptcha').value);
        var str2 = removeSpaces(document.getElementById('CaptchaInput').value);
        if (str1 == str2){
            return true;
        }else{
            return false;
        }
    }

    // Remove the spaces from the entered and generated code
    function removeSpaces(string){
        return string.split(' ').join('');
    }
</script>

@endsection

@section('before-styles')
    @laravelPWA
@stop
