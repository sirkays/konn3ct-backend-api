@extends('layouts.website-layout')
@section('contact','navLinkactive')
@section('content')
    <div class="row pt-5" style="background-color: #012E89">

        <div class="mt-5 mb-5">
            <h2 class="text-center" style="color: white; font-weight: bold">Get In Touch</h2>
            <h5 class="text-center" style="color: white">Contact us for a quote today</h5>
        </div>

        <form>
            <div class="mx-3 my-3 px-3 py-3  row" style="background-color: white; border-radius: 15px">

                <div class="col-md-12 col-lg-6 ml-4">
                    <div class="mb-3">

                        <div class="row justify-content-start">
                            <div class="px-3 py-2 col-12 mr-2">
                                <div class="input-group mb-3">
                                    <span class="mr-4">Name<span class="text-danger">*</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control ml-3" name="name"
                                           style="border-radius: 10px" required>
                                </div>
                            </div>

                            <div class="px-3 py-2 col-12 mr-2">
                                <div class="input-group mb-3">
                                    <span class="mr-4">Mail<span class="text-danger">*</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control ml-3" name="email"
                                           style="border-radius: 10px" required>
                                </div>
                            </div>

                            <div class="px-3 py-2 col-12 mr-2">
                                <div class="input-group mb-3">
                                    <span class="mr-4">Phone number<span class="text-danger">*</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control ml-3" name="phone"
                                           style="border-radius: 10px" required>
                                </div>
                            </div>

                            <div class="px-3 py-2 col-12 mr-2">
                                <div class="input-group mb-3">
                                    <span class="mr-4">Subject<span class="text-danger">*</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="text" class="form-control ml-3" name="phone"
                                           style="border-radius: 10px" required>
                                </div>
                            </div>

                        </div>

                        <div class="mt-5">
                            <i class="fa fa-phone-alt"></i> Support Phone number
                        </div>

                        <div class="mt-3" style="color: #012E89">
                            +234 803 304 6408<br/>
                            +234 807 335 1737
                        </div>

                    </div>

                </div>

                <div class="col-md-12 col-lg-6 justify-content-right">

                    <div class="px-3 py-2 col-12 mr-2">
                        <div class="input-group mb-3">
                            <span class="mr-4">Message<span class="text-danger">*</span></span>&nbsp;&nbsp;&nbsp;&nbsp;
                            <textarea class="form-control ml-3" name="message" style="border-radius: 10px"
                                      rows="10" required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>

                    <div class="m-lg-4">
                        <div class="mt-5">
                            <i class="fa fa-envelope"></i> Message Us
                        </div>

                        <div class="mt-3">
                            We are always with you to solve<br/>
                            your problem mail us :
                        </div>

                        <a href="mailto:support@newwavesecosystem.com" class="mt-3" style="color: #012E89">
                            support@newwavesecosystem.com
                        </a>
                    </div>
                </div>

            </div>
        </form>

        <div class="row text-center mb-4">
            <div>
                <img src="/assets/images/Facebook_white.png" alt="facebook"/>
                <img src="/assets/images/Twitter_white.png" alt="twitter"/>
                <img src="/assets/images/Instagram_white.png" alt="instagram"/>
                <img src="/assets/images/LinkedIN_white.png" alt="linkedin"/>
                <img src="/assets/images/Youtube_white.png" alt="Youtube_white"/>
            </div>
        </div>

    </div>
@endsection

