@extends('layouts.layout')

@section('content')

    <x-jet-authentication-card>
        <x-slot name="logo">
        </x-slot>

        <x-jet-validation-errors class="mb-1" />

        <form method="POST" action="{{ route('register') }}" onsubmit="return checkform(this);">
            @csrf

            <div>
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email Address') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-jet-label for="phone" value="{{ __('Phone Number') }}" />
                <x-jet-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
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
                <span class="ml-2 text-sm">Konn3ct is protected by reCAPTCHA​</span>
            </div>

            <div class="flex items-center justify-end mt-4">
{{--                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">--}}
{{--                    {{ __('Already registered?') }}--}}
{{--                </a>--}}

                <x-jet-button class="ml-4">
                    {{ __('Sign Up') }}
                </x-jet-button>
            </div>

            <div class="block mt-4 text-center">
                <span class="ml-2 text-sm">Already have a Konn3ct account? <a href="{{ route('login') }}">Sign In</a>​</span>
            </div>

        </form>
    </x-jet-authentication-card>

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
