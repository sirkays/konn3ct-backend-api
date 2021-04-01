@extends('layouts.layout')

@section('content')

<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}" onsubmit="return checkform(this);">
            @csrf

            <div class="block">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="block mt-6 text-center">
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
                <span class="ml-2 text-sm"><sup><img src="/assets/images/konn3ct_logo.png" height="30px" width="100px" alt="logo"></sup>is protected by reCAPTCHA​</span>
            </div>


            <div class="flex items-center justify-end mt-4">
                <x-jet-button>
                    {{ __('Email Password Reset Link') }}
                </x-jet-button>
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
