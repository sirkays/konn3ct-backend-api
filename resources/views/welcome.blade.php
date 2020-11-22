@extends('layouts.layout')

@section('content')
<!-- main-area -->
<main>
    <!-- services-area -->
{{--    <section id="services" class="services-area services-bg services-two pt-120 pb-90">--}}
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="section-title text-center pl-40 pr-40 mb-20 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
                        {{--                        <span>our services</span>--}}
                        <h3 data-animation="fadeInUp" data-delay=".4s">What you can do with<sup><img src="/assets/images/konn3ct_logo.png" height="50px" width="150px" alt="logo"></sup></h3>
                    </div>
                </div>
            </div>
            <div class="row mb-10">
                <div class="col-lg-4 col-md-6">
                    <div class="s-single-services active wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                        <div class="services-icon">--}}
{{--                            <i class="fal fa-dice-d10"></i>--}}
{{--                        </div>--}}
                        <div class="second-services-content">
                            <h5>Meetings & Chats</h5>
                            <p>Hold your 1-on-1 (private) or group meetings</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="s-single-services wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                        <div class="services-icon">--}}
{{--                            <i class="fal fa-dice-d10"></i>--}}
{{--                        </div>--}}
                        <div class="second-services-content">
                            <h5>Strategy Session​</h5>
                            <p>Get on the drawing board to build innovations</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="s-single-services wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                        <div class="services-icon">--}}
{{--                            <i class="fal fa-dice-d10"></i>--}}
{{--                        </div>--}}
                        <div class="second-services-content">
                            <h5>Syndicate Session</h5>
                            <p>Allow Teams go into closed sessions while main session is on</p>
                        </div>
                    </div>
                </div>
{{--                <div class="col-lg-4 col-md-6">--}}
{{--                    <div class="s-single-services wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">--}}
{{--                        <div class="services-icon">--}}
{{--                            <i class="fal fa-dice-d10"></i>--}}
{{--                        </div>--}}
{{--                        <div class="second-services-content">--}}
{{--                            <h5>Live Streaming​</h5>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-lg-4 col-md-6 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                    <div class="s-single-services">
{{--                        <div class="services-icon">--}}
{{--                            <i class="fal fa-dice-d10"></i>--}}
{{--                        </div>--}}
                        <div class="second-services-content">
                            <h5>Join without Internet</h5>
                            <p>No/Poor connection? Join the session with a call from your phone</p>
                        </div>
                    </div>
                </div>

{{--                <div class="col-lg-4 col-md-6">--}}
{{--                    <div class="s-single-services wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">--}}
{{--                        <div class="services-icon">--}}
{{--                            <i class="fal fa-dice-d10"></i>--}}
{{--                        </div>--}}
{{--                        <div class="second-services-content">--}}
{{--                            <h5>Host Classes</h5>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="col-lg-4 col-md-6">
                    <div class="s-single-services wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                        <div class="services-icon">--}}
{{--                            <i class="fal fa-dice-d10"></i>--}}
{{--                        </div>--}}
                        <div class="second-services-content">
                            <h5>Host Webinars & Classes</h5>
                            <p>Build capacities using highly interactive tools & features</p>
                        </div>
                    </div>
                </div>
{{--                <div class="col-lg-4 col-md-6 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">--}}
{{--                    <div class="s-single-services">--}}
{{--                        <div class="services-icon">--}}
{{--                            <i class="fal fa-dice-d10"></i>--}}
{{--                        </div>--}}
{{--                        <div class="second-services-content">--}}
{{--                            <h5>Host Religious Events​</h5>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-lg-4 col-md-6 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                    <div class="s-single-services">
{{--                        <div class="services-icon">--}}
{{--                            <i class="fal fa-dice-d10"></i>--}}
{{--                        </div>--}}
                        <div class="second-services-content">
                            <h5>Customize Room link</h5>
                            <p>No need to copy & paste a link again, just name it as you wish</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
{{--    </section>--}}
    <!-- services-area-end -->

    <!-- slider-area -->
    <section id="home" class="slider-area fix p-relative">

        <div class="slider-active">
            <div class="single-slider slider-bg d-flex align-items-center" style="background-image:url(/assets/img/slider/slider1.jpg)">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-2">
                        </div>
                        <div class="col-xl-8">
                            <div class="slider-content s-slider-content text-center">
                                <h2 data-animation="fadeInUp" data-delay=".4s">Ubiquitous <span>work</span></h2>
                                <p data-animation="fadeInUp" data-delay=".6s">Dial-In | Customize Rooms | Do Audio, Video & Paper work</p>
{{--                                <div class="slider-btn mt-55">--}}
{{--                                    <a href="#" class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s">Get a Quote</a>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                        <div class="col-xl-2">
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-slider slider-bg d-flex align-items-center" style="background-image:url(/assets/img/slider/slider2.jpg)">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-2">
                        </div>
                        <div class="col-xl-8">
                            <div class="slider-content s-slider-content text-center">
                                <h2 data-animation="fadeInUp" data-delay=".4s">Collaboration made <span>easy</span></h2>
                                <p data-animation="fadeInUp" data-delay=".6s">Share chats, notes, screen, camera, whiteboard, emoji | Breakout Rooms | Workflow.</p>
{{--                                <div class="slider-btn mt-55">--}}
{{--                                    <a href="#" class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s">Get a Quote</a>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                        <div class="col-xl-2">
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-slider slider-bg d-flex align-items-center" style="background-image:url(/assets/img/slider/slider4.jpg)">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-2">
                        </div>
                        <div class="col-xl-8">
                            <div class="slider-content s-slider-content text-center">
                                <h2 data-animation="fadeInUp" data-delay=".4s">You are <span>safe</span></h2>
                                <p data-animation="fadeInUp" data-delay=".6s">TLS  & AES-256 Encryption | SSL Encryption  | GDPR Compliant​</p>
{{--                                <div class="slider-btn mt-55">--}}
{{--                                    <a href="#" class="btn ss-btn" data-animation="fadeInRight" data-delay=".8s">Get a Quote</a>--}}
{{--                                </div>--}}
                            </div>
                        </div>
                        <div class="col-xl-2">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- slider-area-end -->

    <!-- choose-area -->
    <section class="choose-area pt-20 pb-20 p-relative" style="background:#f5f8fa;">
        <div class="chosse-img wow fadeInRight animated" data-animation="fadeInRight animated" data-delay=".2s" style="background-image:url(/assets/img/bg/about.jpg)"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="choose-wrap">
                        <div class="section-title w-title left-align mb-20 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h3>Why<sup><img src="/assets/images/konn3ct_logo.png" height="50px" width="150px" alt="logo"></sup>?</h3>
                        </div>
                        <div class="choose-content wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <p>Pellentesque habitant morbi tristique senectus et netus et fames acturpis egestas. Vestibulum tortor quam, feugiat vitae, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. mivitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien.</p>--}}

                            <div class="choose-list mb-30">
                                <ul>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Excellent User Experience​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>5 Secs Meeting Setup​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>HD Audio & Video​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Full-Featured Plans​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Affordable & Simple Plans​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Up To 20,000 Participants​</span>
                                    </li>
                                </ul>
                            </div>
{{--                            <div class="choose-btn">--}}
{{--                                <a href="#" class="btn">Work With us</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- choose-area-end -->

    <!-- counter-area -->
    <div class="counter-area pt-20 pb-30" style="background-image:url(/assets/img/bg/count-bg.jpg)">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="section-title text-center mb-10 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
                        {{--                        <span>Best Work</span>--}}
                        <h3><sup><img src="/assets/images/konn3ct_logo.png" height="50px" width="150px" alt="logo"></sup>Highlights</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <small>+</small>
                        <p>Personalized Rooms​​</p>
                    </div>
                </div>
{{--                <div class="col-lg-3 col-sm-6">--}}
{{--                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">--}}
{{--                        <small>+</small>--}}
{{--                        <p>Custom Design​</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <small>+</small>
                        <p>TLS, SSL & AES-256 Encryption​​</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <small>+</small>
                        <p>User Authentication​​</p>
                    </div>
                </div>

{{--                <div class="col-lg-3 col-sm-6">--}}
{{--                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">--}}
{{--                        <small>+</small>--}}
{{--                        <p>Analytics​​​</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <small>+</small>
                        <p>Dial In</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <small>+</small>
                        <p>GDPR​​​​ & NDPR</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <small>+</small>
                        <p>Pre-load Presentation​</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <small>+</small>
                        <p>Web App​​​​​</p>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <small>+</small>
                        <p>Whiteboard & Annotation Tools​​</p>
                    </div>
                </div>

                <div class="col-lg-12 col-sm-12">
                    <div class="single-counter text-center mb-10 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <small>+</small>
                        <p>Data Region for Business Continuity​</p>
                    </div>
                </div>


                <div class="col-12 text-center">
                    <a href="/features" class="btn btn-primary">See More</a>
                </div>

            </div>
        </div>
    </div>
    <!-- counter-area-end -->
</main>

@endsection
