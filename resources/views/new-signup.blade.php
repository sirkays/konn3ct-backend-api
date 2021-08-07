@extends('layouts.new-layout')
@section('content')
    <div class="row mt-5">
        <div class="col-md-12 col-lg-6 ml-4">
            <img src="/assets/images/leftkonn3ctdiagram@2x.png" class="img col-12" alt="pix"/>
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

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div id="home" class="container tab-pane active">
                        <form>
                            <div class="mb-3" style="color: grey">

                                <div class="row justify-content-start">
                                    <div class="px-3 py-2 col-6 mr-2">
                                        <label for="exampleInputEmail1" class="form-label text-left">First Name</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="First Name"
                                                   aria-label="First Name" aria-describedby="basic-addon1">
                                        </div>
                                    </div>

                                    <div class="px-3 py-2 col-6 ml-2">
                                        <label for="exampleInputEmail1" class="form-label">Last Name</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Last Name"
                                                   aria-label="Last Name" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-start">
                                    <div class="px-3 py-2 col-6 mr-2">
                                        <label for="exampleInputEmail1" class="form-label text-left">Email
                                            Address</label>
                                        <div class="input-group mb-3">
                                            <input type="email" class="form-control" placeholder="Email Address"
                                                   aria-label="Email Address" aria-describedby="basic-addon1">
                                        </div>
                                    </div>

                                    <div class="px-3 py-2 col-6 ml-2">
                                        <label for="exampleInputEmail1" class="form-label">Phone Number</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Phone Number"
                                                   aria-label="Phone Number" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-start">
                                    <div class="px-3 py-2 col-6 mr-2">
                                        <label for="exampleInputEmail1" class="form-label text-left">Password</label>
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control" placeholder="Password"
                                                   aria-label="Password" aria-describedby="basic-addon1">
                                        </div>
                                    </div>

                                    <div class="px-3 py-2 col-6 ml-2">
                                        <label for="exampleInputEmail1" class="form-label">Confirm Password</label>
                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control" placeholder="Confirm Password"
                                                   aria-label="Confirm Password" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-start">
                                    <div class="px-3 py-2 col-6 mr-2">
                                        <label for="exampleInputEmail1" class="form-label text-left">Referral Code
                                            (Optional)</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Referral Code"
                                                   aria-label="Referral Code" aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-3 form-check col-6">
                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1"> Activate Pro Plan Free
                                            Trial (8 days)</label>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" type="button">Register</button>
                                </div>


                                <div class="col-12 text-center mt-4">
                                    I have an account? <a href="#">Login</a>
                                </div>

                                <div class="col-12 text-center mt-5">
                                    Konn3ct is protected by reCAPTCHA and their Privacy Policy<br/>
                                    and Terms of Service apply.
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <form>
                        <div class="mb-3">

                            <div class="row">
                                <div class="px-3 py-2 col-12 mr-2 justify-content-start">
                                    <label for="exampleInputEmail1" class="form-label text-left">Business Name</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Business Name"
                                               aria-label="First Name" aria-describedby="basic-addon1">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="px-3 py-2 col-6 mr-2 justify-content-start">
                                    <label for="exampleInputEmail1" class="form-label text-left">Email Address</label>
                                    <div class="input-group mb-3">
                                        <input type="email" class="form-control" placeholder="Email Address"
                                               aria-label="Email Address" aria-describedby="basic-addon1">
                                    </div>
                                </div>

                                <div class="px-3 py-2 col-6 ml-2 justify-content-start">
                                    <label for="exampleInputEmail1" class="form-label">Phone Number</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Phone Number"
                                               aria-label="Phone Number" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="px-3 py-2 col-6 mr-2 justify-content-start">
                                    <label for="exampleInputEmail1" class="form-label text-left">Password</label>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" placeholder="Password"
                                               aria-label="Password" aria-describedby="basic-addon1">
                                    </div>
                                </div>

                                <div class="px-3 py-2 col-6 ml-2 justify-content-start">
                                    <label for="exampleInputEmail1" class="form-label">Confirm Password</label>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" placeholder="Confirm Password"
                                               aria-label="Confirm Password" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-start">
                                <div class="px-3 py-2 col-6 mr-2">
                                    <label for="exampleInputEmail1" class="form-label text-left">Referral Code
                                        (Optional)</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Referral Code"
                                               aria-label="Referral Code" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 form-check col-6">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1"> Activate Pro Plan Free Trial (8
                                        days)</label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" type="button">Register</button>
                            </div>


                            <div class="col-12 text-center mt-4">
                                I have an account? <a href="#">Login</a>
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
@endsection

