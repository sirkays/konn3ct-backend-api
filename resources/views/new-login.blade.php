@extends('layouts.new-layout')
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-6 ml-4">
            <img src="/assets/images/leftkonn3ctdiagram@2x.png" height="550px" width="" class="img col-12" alt="pix"/>
        </div>
        <div class="col-md-12 col-lg-6 mt-1">
            <h2 class="text-center" style="color: #012E89">Welcome back</h2>
            <h6 class="text-center" style="color: grey">Login back to Konn3ct</h6>

            @if (session('status'))
                <div class="mb-1 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-2">

                    <div class="px-3 py-2"
                         style="border-radius: 10px; border-style: solid; border-color: grey; border-width: 1px">
                        <label for="exampleInputEmail1" class="form-label" style="color: grey">Email</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-32"><i class="fas fa-envelope"></i> </span>
                            <input type="email" class="form-control" placeholder="email" aria-label="Email"
                                   aria-describedby="basic-addon1" value="{{old('email')}}" autofocus>
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

                </div>
                <div class="row">
                    <div class="mb-3 form-check col-6">
                        <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                        <label class="form-check-label" for="exampleCheck1" style="color: grey">Remember me</label>
                    </div>

                    <div class="mb-3 form-check col-6 align-self-end">
                        <a href="#" style="color: grey">Forgot your password?</a>
                    </div>

                </div>

                <div class="d-grid gap-2 mt-1" style="margin-left: 20%; margin-right: 20%">
                    <button type="submit" class="btn px-3 py-3 mr-3 mt-2"
                            style="border-radius: 10px; background-color: #012E89; color: white; font-weight: bolder">
                        LOG IN
                    </button>
                </div>


                <div class="col-12 text-center mt-4" style="color: grey">
                    Don't have an account? <a href="#" style="color: grey">Register</a>
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
@endsection

