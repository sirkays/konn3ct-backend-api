@extends('layouts.new-layout')
@section('content')
    <div class="row mt-5">
        <div class="col-md-12 col-lg-6 ml-4 text-center">
            <img src="/assets/images/leftkonn3ctdiagram@2x.png" height="520px" width="auto" alt="pix"/>
        </div>

        <div class="col-md-12 col-lg-6 justify-content-right">

            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                            aria-selected="true">Personal
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                            aria-selected="false">Corporate
                    </button>
                </li>
            </ul>


            @if (session('status'))
                <div class="mb-1 font-medium text-sm text-green-600">
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


            {{--            <div class="nav nav-tabs justify-content-center mb-5" id="nav-tab" role="tablist">--}}
            {{--                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Home</button>--}}
            {{--                <button class="nav-link" id="pills-profile-tab"  data-bs-toggle="tab" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Corporate</button>--}}
            {{--            </div>--}}

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div id="home" class="container tab-pane active">
                        <form method="POST" action="{{ route('register') }}" onsubmit="return checkform(this);">
                            @csrf
                            <div class="mb-3">

                                <div class="row justify-content-start" style="color: grey; font-weight: bold">
                                    <div class="px-3 py-2 col-6 mr-2">
                                        <label for="exampleInputEmail1" class="form-label text-left">First Name</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="First Name"
                                                   name="firstname" value="{{old('firstname')}}" required autofocus
                                                   autocomplete="firstname" aria-label="First Name"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>

                                    <div class="px-3 py-2 col-6 ml-2">
                                        <label for="exampleInputEmail1" class="form-label">Last Name</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Last Name"
                                                   name="lastname" value="{{old('lastname')}}" required autofocus
                                                   autocomplete="lastname" aria-label="Last Name"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-start" style="color: grey; font-weight: bold">
                                    <div class="px-3 py-2 col-6 mr-2">
                                        <label for="exampleInputEmail1" class="form-label text-left">Email
                                            Address</label>
                                        <div class="input-group mb-3">
                                            <input type="email" class="form-control" placeholder="Email Address"
                                                   name="email"
                                                   value="{{old('email')}}" required aria-label="Email Address"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>

                                    <div class="px-3 py-2 col-6 ml-2">
                                        <label for="exampleInputEmail1" class="form-label">Phone Number</label>
                                        <div class="input-group mb-3">
                                            <input type="tel" class="form-control" placeholder="Phone Number"
                                                   name="phone"
                                                   value="{{old('phone')}}" required aria-label="Phone Number"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-start" style="color: grey; font-weight: bold">
                                    <div class="px-3 py-2 col-6 mr-2">
                                        <label for="exampleInputEmail1" class="form-label text-left">Password</label>
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control" placeholder="Password"
                                                   name="password" required autocomplete="new-password"
                                                   aria-label="Password" aria-describedby="basic-addon1">
                                        </div>
                                    </div>

                                    <div class="px-3 py-2 col-6 ml-2">
                                        <label for="exampleInputEmail1" class="form-label">Confirm Password</label>
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control" placeholder="Confirm Password"
                                                   name="password_confirmation" required
                                                   autocomplete="new-password" aria-label="Confirm Password"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-start" style="color: grey; font-weight: bold">
                                    <div class="px-3 py-2 col-6 mr-2">
                                        <label for="exampleInputEmail1" class="form-label text-left">reCAPTCHA: Type the
                                            number</label>
                                        <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1" style="font-weight: bolder">
                                            <div id="CaptchaDiv"></div>
                                            <input type="hidden" id="txtCaptcha">
                                        </span>
                                            <input type="text" name="CaptchaInput" class="form-control"
                                                   id="CaptchaInput" size="15" autocomplete="off"/>
                                        </div>
                                    </div>

                                    <div class="px-3 py-2 col-6 mr-2">
                                        <label for="exampleInputEmail1" class="form-label text-left">Referral Code
                                            (Optional)</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Referral Code"
                                                   name="referral" value="{{old('referral')}}" maxlength="6"
                                                   aria-label="Referral Code" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>

                                @if($freetrial==1)
                                    <div class="row" style="color: grey">
                                        <div class="mb-3 form-check col-6">
                                            <input type="checkbox" class="form-check-input" id="freetrial"
                                                   name="freetrial" value="true">
                                            <label class="form-check-label" for="exampleCheck1"> Activate Pro Plan Free
                                                Trial ({{$freetrial_days}} days)</label>
                                        </div>
                                    </div>
                                @endif

                                <div class="d-grid gap-2 mt-5" style="margin-left: 20%; margin-right: 20%">
                                    <button type="submit" class="btn px-3 py-3 mr-3 mt-2"
                                            style="border-radius: 10px; background-color: #012E89; color: white; font-weight: bolder">
                                        Register
                                    </button>
                                </div>


                                <div class="col-12 text-center mt-4" style="color: grey">
                                    I have an account? <a href="{{route('new-login')}}" style="color: grey">Login</a>
                                </div>

                                <div class="col-12 text-center mt-5" style="color: grey">
                                    Konn3ct is protected by reCAPTCHA and their Privacy Policy<br/>
                                    and Terms of Service apply.
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <form method="POST" action="{{ route('register') }}" onsubmit="return checkform2(this);">
                        @csrf
                        <div class="mb-3">

                            <div class="row justify-content-start" style="color: grey; font-weight: bold">
                                <div class="px-3 py-2 col-12 mr-2 justify-content-start">
                                    <label for="exampleInputEmail1" class="form-label text-left">Business Name</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Business Name"
                                               name="firstname" value="{{old('firstname')}}" required autofocus
                                               autocomplete="firstname" aria-label="First Name"
                                               aria-describedby="basic-addon1">
                                        <input type="hidden" class="form-control" name="type" value="biz" required
                                               autofocus autocomplete="firstname">
                                    </div>
                                </div>

                            </div>

                            <div class="row justify-content-start" style="color: grey; font-weight: bold">
                                <div class="px-3 py-2 col-6 mr-2 justify-content-start">
                                    <label for="exampleInputEmail1" class="form-label text-left">Email Address</label>
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control" placeholder="Email Address"
                                               name="email" value="{{old('email')}}" required aria-label="Email Address"
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>

                                <div class="px-3 py-2 col-6 ml-2 justify-content-start">
                                    <label for="exampleInputEmail1" class="form-label">Phone Number</label>
                                    <div class="input-group mb-3">
                                        <input type="tel" class="form-control" placeholder="Phone Number" name="phone"
                                               value="{{old('phone')}}" required aria-label="Phone Number"
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-start" style="color: grey; font-weight: bold">
                                <div class="px-3 py-2 col-6 mr-2 justify-content-start">
                                    <label for="exampleInputEmail1" class="form-label text-left">Password</label>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" placeholder="Password"
                                               name="password" required autocomplete="new-password"
                                               aria-label="Password" aria-describedby="basic-addon1">
                                    </div>
                                </div>

                                <div class="px-3 py-2 col-6 ml-2 justify-content-start">
                                    <label for="exampleInputEmail1" class="form-label">Confirm Password</label>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" placeholder="Confirm Password"
                                               name="password_confirmation" required
                                               autocomplete="new-password" aria-label="Confirm Password"
                                               aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-start" style="color: grey; font-weight: bold">
                                <div class="px-3 py-2 col-6 mr-2">
                                    <label for="exampleInputEmail1" class="form-label text-left">reCAPTCHA: Type the
                                        number</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1" style="font-weight: bolder">
                                            <div id="CaptchaDiv2"></div>
                                            <input type="hidden" id="txtCaptcha2">
                                        </span>
                                        <input type="text" name="CaptchaInput2" class="form-control" id="CaptchaInput"
                                               size="15" autocomplete="off"/>
                                    </div>
                                </div>

                                <div class="px-3 py-2 col-6 mr-2">
                                    <label for="exampleInputEmail1" class="form-label text-left">Referral Code
                                        (Optional)</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Referral Code"
                                               name="referral" value="{{old('referral')}}" maxlength="6"
                                               aria-label="Referral Code" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>

                            @if($freetrial==1)
                                <div class="row" style="color: grey">
                                    <div class="mb-3 form-check col-6">
                                        <input type="checkbox" class="form-check-input" id="freetrial" name="freetrial"
                                               value="true">
                                        <label class="form-check-label" for="exampleCheck1"> Activate Pro Plan Free
                                            Trial ({{$freetrial_days}} days)</label>
                                    </div>
                                </div>
                            @endif

                            <div class="d-grid gap-2 mt-5" style="margin-left: 20%; margin-right: 20%">
                                <button type="submit" class="btn px-3 py-3 mr-3 mt-2"
                                        style="border-radius: 10px; background-color: #012E89; color: white; font-weight: bolder">
                                    Register
                                </button>
                            </div>


                            <div class="col-12 text-center mt-4">
                                I have an account? <a href="{{route('login')}}">Login</a>
                            </div>

                            <div class="col-12 text-center mt-5">
                                Konn3ct is protected by reCAPTCHA and their Privacy Policy<br/>
                                and Terms of Service apply.
                            </div>
                        </div>

                    </form>
                </div>
            </div>
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

