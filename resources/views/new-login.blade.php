@extends('layouts.new-layout')
@section('content')
    <div class="row mt-5">
        <div class="col-md-12 col-lg-6 ml-4">
            <img src="/assets/images/leftkonn3ctdiagram@2x.png" class="img col-12" alt="pix"/>
        </div>
        <div class="col-md-12 col-lg-6">
            <h2 class="text-center">Welcome back</h2>
            <h4 class="text-center">Login back to Konn3ct</h4>

            <form>
                <div class="mb-3">

                    <div class="px-3 py-2" style="outline: 1px solid grey; border-radius: 10px">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-envelope"></i> </span>
                            <input type="email" class="form-control" placeholder="email" aria-label="Email"
                                   aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="px-3 py-2 mt-4" style="outline: 1px solid grey; border-radius: 10px">
                        <label for="exampleInputEmail1" class="form-label">Password</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i> </span>
                            <input type="password" id="inputPassword5" class="form-control"
                                   aria-describedby="passwordHelpBlock">
                            <div id="passwordHelpBlock" class="form-text">
                                Your password must be 8-20 characters long, contain letters and numbers, and must not
                                contain spaces, special characters, or emoji.
                            </div>
                        </div>

                    </div>

                </div>
                <div class="row">
                    <div class="mb-3 form-check col-6">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Remember me</label>
                    </div>

                    <div class="mb-3 form-check col-6 text-right">
                        <a href="#">Forgot your password?</a>
                    </div>

                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="button">Submit</button>
                </div>


                <div class="col-12 text-center mt-4">
                    Don't have an account? <a href="#">Register</a>
                </div>

                <div class="col-12 text-center mt-5">
                    <hr style="max-width: 50px">
                    Or sign in with
                    <hr style="max-width: 50px">
                </div>

                <div class="col-12 text-center mt-5">
                    Konn3ct is protected by reCAPTCHA and their Privacy Policy<br/>
                    and Terms of Service apply.
                </div>

            </form>

        </div>

    </div>
@endsection

