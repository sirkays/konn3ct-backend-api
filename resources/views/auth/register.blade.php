@extends('layouts.layout')

@section('content')
    <x-guest-layout>

        <div class="row bg-gray-100 mb-95">

            <div class="col-12">
                <div class="box-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills rounded nav-justified">
                        <li class="nav-item"><a href="#navpills-1" class="nav-link active" data-toggle="tab"
                                                aria-expanded="false" style="font-weight: bolder">For Personal</a></li>
                        <li class="nav-item"><a href="#navpills-2" class="nav-link" data-toggle="tab"
                                                aria-expanded="false" style="font-weight: bolder">For Corporate</a></li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="col-12 text-center">
                <!-- Tab panes -->
                <div class="tab-content text-center">
                    <div id="navpills-1" class="tab-pane active">
                        <!-- Categroy 1 -->
                        <div class="col-12 text-left">
                            <x-jet-authentication-card>
                                <x-slot name="logo">
                                </x-slot>

                                <x-jet-validation-errors class="mb-1"/>

                                <form method="POST" action="{{ route('register') }}" onsubmit="return checkform(this);">
                                    @csrf

                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <x-jet-label for="firstname" value="{{ __('First Name') }}"/>
                                            <x-jet-input id="firstname" class="block mt-1 w-full" type="text"
                                                         name="firstname" :value="old('firstname')" required autofocus
                                                         autocomplete="firstname"/>
                                        </div>

                                        <div class="col-lg-6">
                                            <x-jet-label for="lastname" value="{{ __('Last Name') }}"/>
                                            <x-jet-input id="lastname" class="block mt-1 w-full" type="text"
                                                         name="lastname" :value="old('lastname')" required autofocus
                                                         autocomplete="lastname"/>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <x-jet-label for="email" value="{{ __('Email Address') }}"/>
                                            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email"
                                                         :value="old('email')" required/>
                                        </div>

                                        <div class="col-lg-6">
                                            <x-jet-label for="phone" value="{{ __('Phone Number') }}"/>
                                            <x-jet-input id="phone" class="block mt-1 w-full" type="tel" name="phone"
                                                         :value="old('phone')" required/>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <x-jet-label for="password" value="{{ __('Password') }}"/>
                                            <x-jet-input id="password" class="block mt-1 w-full" type="password"
                                                         name="password" required autocomplete="new-password"/>
                                        </div>

                                        <div class="col-lg-6">
                                            <x-jet-label for="password_confirmation"
                                                         value="{{ __('Confirm Password') }}"/>
                                            <x-jet-input id="password_confirmation" class="block mt-1 w-full"
                                                         type="password" name="password_confirmation" required
                                                         autocomplete="new-password"/>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-12">
                                            <x-jet-label for="referral" value="{{ __('Referral Code (Optional)') }}"/>
                                            <x-jet-input id="referral" class="block mt-1 w-full" type="text"
                                                         name="referral" :value="old('referral')" maxlength="6"/>
                                        </div>
                                    </div>

                                    @if($freetrial==1)
                                        <div class="row mx-4">
                                            <div class="form-group">
                                                <input type="checkbox" id="freetrial" name="freetrial" value="true">
                                                <label for="freetrial"> <Strong>Activate Pro Plan Free Trial ({{$freetrial_days}} days)</Strong></label><br>
                                            </div>
                                        </div>
                                    @endif

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
                                        <span class="ml-2 text-sm"><sup><img src="/assets/images/konn3ct_logo.png"
                                                                             height="30px" width="100px"
                                                                             alt="logo"></sup> is protected by reCAPTCHA​</span>
                                    </div>

                                    <div class="flex items-center justify-end mt-4">
                                        {{--                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">--}}
                                        {{--                    {{ __('Already registered?') }}--}}
                                        {{--                </a>--}}

                                        <div class="text-left">
                                            <span class="ml-2 text-sm">Already have a <sup><img
                                                        src="/assets/images/konn3ct_logo.png" height="30px"
                                                        width="100px" alt="logo"></sup> account? <a class="su"
                                                                                                    href="{{ route('login') }}"
                                                                                                    style="font-weight: bolder">Sign In</a>​</span>
                                        </div>

                                        <x-jet-button class="ml-4">
                                            {{ __('Register') }}
                                        </x-jet-button>
                                    </div>

                                    {{--            <div class="block mt-4 text-center">--}}
                                    {{--                <span class="ml-2 text-sm">Already have a <sup><img src="/assets/images/konn3ct_logo.png" height="30px" width="100px" alt="logo"></sup> account? <a href="{{ route('login') }}" style="font-weight: bolder">Sign In</a>​</span>--}}
                                    {{--            </div>--}}

                                </form>


                            </x-jet-authentication-card>
                        </div>
                    </div>

                    <div id="navpills-2" class="tab-pane">
                        <!-- Categroy 2 -->
                        <div class="col-12 text-left">
                            <x-jet-authentication-card>
                                <x-slot name="logo">
                                </x-slot>

                                <x-jet-validation-errors class="mb-1"/>

                                <form method="POST" action="{{ route('register') }}"
                                      onsubmit="return checkform2(this);">
                                    @csrf

                                    <div class="row mb-2">
                                        <div class="col-lg-12">
                                            <x-jet-label for="firstname" value="{{ __('Business Name') }}"/>
                                            <x-jet-input id="firstname" class="block mt-1 w-full" type="text"
                                                         name="firstname" :value="old('firstname')" required autofocus
                                                         autocomplete="firstname"/>
                                            <x-jet-input class="block mt-1 w-full" type="hidden" name="type" value="biz"
                                                         required autofocus autocomplete="firstname"/>
                                        </div>

                                        <div class="col-lg-6 hidden" style="display: none">
                                            <x-jet-label for="lastname" value="{{ __('Last Name') }}"/>
                                            {{--                                <x-jet-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" autofocus autocomplete="lastname" />--}}
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <x-jet-label for="email" value="{{ __('Email Address') }}"/>
                                            <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email"
                                                         :value="old('email')" required/>
                                        </div>

                                        <div class="col-lg-6">
                                            <x-jet-label for="phone" value="{{ __('Phone Number') }}"/>
                                            <x-jet-input id="phone" class="block mt-1 w-full" type="tel" name="phone"
                                                         :value="old('phone')" required/>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-6">
                                            <x-jet-label for="password" value="{{ __('Password') }}"/>
                                            <x-jet-input id="password" class="block mt-1 w-full" type="password"
                                                         name="password" required autocomplete="new-password"/>
                                        </div>

                                        <div class="col-lg-6">
                                            <x-jet-label for="password_confirmation"
                                                         value="{{ __('Confirm Password') }}"/>
                                            <x-jet-input id="password_confirmation" class="block mt-1 w-full"
                                                         type="password" name="password_confirmation" required
                                                         autocomplete="new-password"/>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-lg-12">
                                            <x-jet-label for="referral" value="{{ __('Referral Code (Optional)') }}"/>
                                            <x-jet-input id="referral" class="block mt-1 w-full" type="text"
                                                         name="referral" :value="old('referral')" maxlength="6" />
                                        </div>
                                    </div>

                                    @if($freetrial==1)
                                        <div class="row mx-4">
                                            <div class="form-group">
                                                <input type="checkbox" id="freetrial" name="freetrial" value="true">
                                                <label for="freetrial"> <Strong>Activate Pro Plan Free Trial ({{$freetrial_days}} days)</Strong></label><br>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="block mt-4 text-center">
                                        <!-- START CAPTCHA -->
                                        <div class="capbox">
                                            <div id="CaptchaDiv2"></div>
                                            <div class="capbox-inner">
                                                Type the number:<br>
                                                <input type="hidden" id="txtCaptcha2">
                                                <input type="text" name="CaptchaInput2" id="CaptchaInput2"
                                                       size="15"><br>

                                            </div>
                                        </div>
                                        <!-- END CAPTCHA -->
                                        <br/>
                                        <span class="ml-2 text-sm"><sup><img src="/assets/images/konn3ct_logo.png"
                                                                             height="30px" width="100px"
                                                                             alt="logo"></sup> is protected by reCAPTCHA​</span>
                                    </div>

                                    <div class="flex items-center justify-end mt-4">
                                        {{--                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">--}}
                                        {{--                    {{ __('Already registered?') }}--}}
                                        {{--                </a>--}}

                                        <div class="text-left">
                                            <span class="ml-2 text-sm">Already have a <sup><img
                                                        src="/assets/images/konn3ct_logo.png" height="30px"
                                                        width="100px" alt="logo"></sup> account? <a class="su"
                                                                                                    href="{{ route('login') }}"
                                                                                                    style="font-weight: bolder">Sign In</a>​</span>
                                        </div>

                                        <x-jet-button class="ml-4">
                                            {{ __('Register') }}
                                        </x-jet-button>
                                    </div>

                                    {{--            <div class="block mt-4 text-center">--}}
                                    {{--                <span class="ml-2 text-sm">Already have a <sup><img src="/assets/images/konn3ct_logo.png" height="30px" width="100px" alt="logo"></sup> account? <a href="{{ route('login') }}" style="font-weight: bolder">Sign In</a>​</span>--}}
                                    {{--            </div>--}}

                                </form>


                            </x-jet-authentication-card>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </x-guest-layout>

    <script type="text/javascript">

        // Captcha Script

        function checkform(theform) {
            var why = "";

            if (theform.CaptchaInput.value == "") {
                why += "- Please Enter CAPTCHA Code.\n";
            }
            if (theform.CaptchaInput.value != "") {
                if (ValidCaptcha(theform.CaptchaInput.value) == false) {
                    why += "- The CAPTCHA Code Does Not Match.\n";
                }
            }
            if (why != "") {
                alert(why);
                return false;
            }
        }

        var a = Math.ceil(Math.random() * 9) + '';
        var b = Math.ceil(Math.random() * 9) + '';
        var c = Math.ceil(Math.random() * 9) + '';
        var d = Math.ceil(Math.random() * 9) + '';
        var e = Math.ceil(Math.random() * 9) + '';

        var code = a + b + c + d + e;
        document.getElementById("txtCaptcha").value = code;
        document.getElementById("CaptchaDiv").innerHTML = code;

        document.getElementById("txtCaptcha2").value = code;
        document.getElementById("CaptchaDiv2").innerHTML = code;

        // Validate input against the generated number
        function ValidCaptcha() {
            var str1 = removeSpaces(document.getElementById('txtCaptcha').value);
            var str2 = removeSpaces(document.getElementById('CaptchaInput').value);

            var str1a = removeSpaces(document.getElementById('txtCaptcha2').value);
            var str2b = removeSpaces(document.getElementById('CaptchaInput2').value);
            if (str1 == str2 || str1a == str2b) {
                return true;
            } else {
                return false;
            }
        }

        // Remove the spaces from the entered and generated code
        function removeSpaces(string) {
            return string.split(' ').join('');
        }
    </script>


    <script type="text/javascript">

        // Captcha Script

        function checkform2(theform) {
            var why = "";

            if (theform.CaptchaInput2.value == "") {
                why += "- Please Enter CAPTCHA Code.\n";
            }
            if (theform.CaptchaInput2.value != "") {
                if (ValidCaptcha(theform.CaptchaInput2.value) == false) {
                    why += "- The CAPTCHA Code Does Not Match.\n";
                }
            }
            if (why != "") {
                alert(why);
                return false;
            }
        }

    </script>

@endsection
