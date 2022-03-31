@extends('layouts.website-layout')
@section('content')
    <div class="row mt-5">
        <div class="col-md-12 col-lg-6 ml-4 text-center hidden-md-down">
            <img src="/assets/images/leftkonn3ctdiagram@2x.png" height="520px" alt="pix"/>
        </div>
        <div class="col-md-12 col-lg-6 px-5 py-5">
            <h2 class="text-center" style="color: #012E89">Welcome back</h2>
            <h6 class="text-center" style="color: grey">Login back to Konn3ct</h6>

            @if (session('status'))
                <div class="mb-1 font-medium alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-danger alert">
                    <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>

                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="mt-2" method="POST" action="{{ route('login') }}" onsubmit="return checkform(this);">
                @csrf
                <div class="mb-2">

                    <div class="px-3 py-2"
                         style="border-radius: 10px; border-style: solid; border-color: grey; border-width: 1px">
                        <label for="exampleInputEmail1" class="form-label" style="color: grey">Email</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-32"><i class="fas fa-envelope"></i> </span>
                            <input type="email" name="email" class="form-control" placeholder="email" aria-label="Email"
                                   aria-describedby="basic-addon1" value="{{old('email')}}" autofocus required>
                        </div>
                    </div>

                    <div class="px-3 py-2 mt-4"
                         style="border-radius: 10px; border-style: solid; border-color: grey; border-width: 1px">
                        <label for="exampleInputEmail1" class="form-label" style="color: grey">Password</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i> </span>
                            <input type="password" id="inputPassword5" class="form-control"
                                   aria-describedby="passwordHelpBlock" name="password" required
                                   autocomplete="current-password">
                            {{--                            <div id="passwordHelpBlock" class="form-text" style="color: grey">--}}
                            {{--                                Your password must be 8-20 characters long, contain letters and numbers, and must not--}}
                            {{--                                contain spaces, special characters, or emoji.--}}
                            {{--                            </div>--}}
                        </div>

                    </div>

                    <div class="px-3 py-2 mt-4"
                         style="border-radius: 10px; border-style: solid; border-color: grey; border-width: 1px">
                        <label for="exampleInputEmail1" class="form-label" style="color: grey">reCAPTCHA: Type the
                            number</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1" style="font-weight: bolder">
                                <div id="CaptchaDiv"></div>
                                <input type="hidden" id="txtCaptcha">
                            </span>
                            <input type="text" name="CaptchaInput" class="form-control" id="CaptchaInput" size="15"
                                   autocomplete="off"/>
                        </div>

                    </div>

                </div>
                <div class="row">
                    <div class="mb-3 form-check col-6">
                        <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                        <label class="form-check-label" for="exampleCheck1" style="color: grey">Remember me</label>
                    </div>

                    <div class="mb-3 form-check col-6 align-self-end">
                        <a href="{{route('password.request')}}" style="color: grey">Forgot your password?</a>
                    </div>

                </div>

                <div class="d-grid gap-2 mt-1" style="margin-left: 20%; margin-right: 20%">
                    <button type="submit" class="btn px-3 py-3 mr-3 mt-2"
                            style="border-radius: 10px; background-color: #012E89; color: white; font-weight: bolder">
                        LOG IN
                    </button>
                </div>


                <div class="col-12 text-center mt-4" style="color: grey">
                    Don't have an account? <a href="{{route('register')}}"
                                              style="color: #012E89; font-weight: bolder; text-decoration: underline">Sign
                        Up, It’s FREE</a>
                </div>

                {{--                <div class="col-12 text-center mt-5">--}}
                {{--                    <img src="/assets/images/alternativesignin@2x.png" class="img col-4" alt="pix"/>--}}
                {{--                </div>--}}


                <div class="col-12 text-center mt-5" style="color: grey">
                    Konn3ct is protected by reCAPTCHA and their Privacy Policy<br/>
                    and Terms of Service apply.
                </div>

            </form>

        </div>

    </div>


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

        // Validate input against the generated number
        function ValidCaptcha() {
            var str1 = removeSpaces(document.getElementById('txtCaptcha').value);
            var str2 = removeSpaces(document.getElementById('CaptchaInput').value);
            if (str1 == str2) {
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

@endsection
