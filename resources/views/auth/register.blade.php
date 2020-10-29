@extends('layouts.layout')

@section('content')
    <x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
        </x-slot>

        <x-jet-validation-errors class="mb-1" />

        <form method="POST" action="{{ route('register') }}" onsubmit="return checkform(this);">
            @csrf

            <div class="row mb-2">
                <div class="col-lg-6">
                    <x-jet-label for="firstname" value="{{ __('First Name') }}" />
                    <x-jet-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" required autofocus autocomplete="firstname" />
                </div>

                <div class="col-lg-6">
                    <x-jet-label for="lastname" value="{{ __('Last Name') }}" />
                    <x-jet-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required autofocus autocomplete="lastname" />
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-lg-6">
                    <x-jet-label for="email" value="{{ __('Email Address') }}" />
                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>

                <div class="col-lg-6">
                    <x-jet-label for="phone" value="{{ __('Phone Number') }}" />
                    <x-jet-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required />
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-lg-6">
                    <x-jet-label for="password" value="{{ __('Password') }}" />
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="col-lg-6">
                    <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>
            </div>

            <div class="block mt-4 text-center">
                <!-- START CAPTCHA -->
                <div class="capbox">
                    <div id="CaptchaDiv"></div>
                    <div class="capbox-inner">
                        Type the number:<br>
                        <input type="hidden" id="txtCaptcha">
                        <input type="text" name="CaptchaInput" id="CaptchaInput" size="15"><br>

                    </div>
                </div>
                <!-- END CAPTCHA -->
                <br/>
                <span class="ml-2 text-sm"><sup><img src="/assets/images/konn3ct_logo.png" height="30px" width="100px" alt="logo"></sup> is protected by reCAPTCHA​</span>
            </div>

            <div class="flex items-center justify-end mt-4">
{{--                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">--}}
{{--                    {{ __('Already registered?') }}--}}
{{--                </a>--}}

                <x-jet-button class="ml-4">
                    {{ __('Register') }}
                </x-jet-button>
            </div>

            <div class="block mt-4 text-center">
                <span class="ml-2 text-sm">Already have a <sup><img src="/assets/images/konn3ct_logo.png" height="30px" width="100px" alt="logo"></sup> account? <a href="{{ route('login') }}" style="font-weight: bolder">Sign In</a>​</span>
            </div>

        </form>
    </x-jet-authentication-card>
    </x-guest-layout>

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
