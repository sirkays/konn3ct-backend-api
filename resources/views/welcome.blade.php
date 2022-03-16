@extends('layouts.website-layout')
@section('content')

    <!-- Start Hero -->
    <section class="background-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-6 align-self-center" style="color: white;">
                    <h1 style="font-weight: bolder;">
                        Konn3ct
                    </h1>

                    <h4 class="mt-3">
                        Meet, Chat, and Collaborate<br/>
                        in just one place.
                    </h4>

                    <div class="row mt-5">
                        <div class="col-12">
                            <a href="{{route('joinmeeting')}}" type="button" class="btn px-3 py-3 mr-3 mt-2"
                               style="border-radius: 30px; background-color: #012E89; color: white; font-weight: bolder">
                                Join a Meeting
                            </a>
                            &nbsp;
                            <a href="{{route('login')}}" type="button" class="btn px-3 py-3 ml-3 mt-2"
                               style="border-radius: 30px; background-color: white; color: black; font-weight: bolder">
                                Host a meeting
                            </a>
                        </div>
                    </div>

                    <div class="mt-5">
                        <i class="fa fa-arrow-down"> </i> <a href="#unique" style="text-decoration: none; color: white">
                            Scroll to explore</a>
                    </div>

                </div>

                <div class="col-md-12 col-lg-6">
                    <img src="/assets/images/front1@2x.png" class="img-fluid" alt="pix"/>
                </div>
                <!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End Hero -->

    <!-- Start -->
    <section id="unique" class="mt-5 pt-4 px-2">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 align-self-center">
                    <h2 style="font-weight: bolder">
                        UNIQUE FEATURES
                    </h2>

                    <h4>
                        Konn3ct is an enterprise solution with features
                        and management modules that makes it suitable
                        for highly structured and sequenced environments.
                    </h4>

                </div>

                <div class="col-md-12 col-lg-6">
                    <img src="/assets/images/group99@2x.png" class="img-fluid" alt="pix"/>
                </div>
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <!-- End -->

    <!-- Start -->
    <section class="section ourrate">
        <div class="container">
            <div class="col-12">
                <h2 style="font-weight: bolder">
                    WHY KONN3CT
                </h2>

                <div id="Konn3ct_is_the_first_fully_fea">
                    <span>Konn3ct is the first fully featured web-conferencing solution developed in Nigeria and Africa.<br/>This gives konn3ct the pioneer status and puts Nigeria in the leadership role we have always provided on the continent.<br/>This leadership statement is best proven with konn3ct’s adoption by large corporates and governmental institutions,<br/>and its commercial success. This gives the technological edge to every African country as well as her people to thrive on </span><br>
                </div>

            </div>
        </div><!--end container-->
    </section>
    <!--end section-->

    <!-- Start -->
    <section class="other-services" style="background-image: url('/assets/images/group2008.png');">
        <div class="container mt-50">
            <div class="row">
                <div class="col-md-12 col-lg-6 px-3 py-3">
                    <img src="/assets/images/2345thyj.png" class="img-fluid" alt="pix"/>
                </div>

                <div class="col-md-12 col-lg-6 mt-4" style="color: white">
                    <h5 class="mb-4">
                        Get to know more about
                    </h5>
                    <img src="/assets/images/konn3ct_logo123.png" class="img" alt="pix"/>

                    <h5 class="mt-5">
                        Konn3ct is technically a suite of web-conferencing
                        solutions that cover a range of applications used for
                        meetings, conferences, webinars, rooms, live-classroom,
                        syndicate events, remote cinema etc. konn3ct is a fusion
                        of all these applications that is accessible from free plans
                        that allows 100 participants for 60 minutes and paid plans
                        for more features.
                    </h5>

                    <div class="col-6 mt-5 mb-4">
                        <button type="button" class="btn"
                                style="border-radius: 30px; background-color: white; color: black; font-weight: bolder">
                            Learn more
                        </button>
                    </div>

                </div>
            </div>

        </div><!--end container-->
    </section>
    <!--end section-->

    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center">
                Featured In
            </h2>

            <div class="scrollmenu">
                {{--                <a href="https://itedgenews.ng/2021/08/16/galaxy-backbone-newwaves-sign-deal-to-deploy-konn3ct-solution-in-public-service/"><img--}}
                {{--                        src="/assets/images/ITEdgeNews3.png" class="img" alt="pix"/></a>--}}
                {{--                <a href="https://ravenewsonline.com/2021/08/16/newwaves-ecosystem-inks-mou-with-galaxy-backbone-to-deploy-konn3ct-solution-in-public-service-space/"><img--}}
                {{--                        src="/assets/images/Ravenews-300x300.jpeg" class="img" alt="pix"/></a>--}}
                {{--                <a href="https://techeconomy.ng/2021/08/galaxy-backbone-adopts-konn3ct-wholly-nigerian-virtual-conferencing-solution/"><img--}}
                {{--                        src="/assets/images/tedownload.png" class="img" alt="pix"/></a>--}}
                <a href="https://guardian.ng/business-services/nigerias-konn3ct-competes-for-78b-virtual-market"><img
                        src="/assets/images/group73.png" class="img" alt="pix"/></a>
                <a href="https://businessday.ng/financial-inclusion/article/nigerias-newwaves-ecosystem-launches-africas-first-zoom-like-app-konn3ct/"><img
                        src="/assets/images/group74.png" class="img" alt="pix"/></a>
                <a href="https://www.premiumtimesng.com/business/business-news/452206-nigerian-firm-newwaves-launches-africas-first-virtual-meeting-solution-konn3ct.html"><img
                        src="/assets/images/group75.png" class="img" alt="pix"/></a>
                <a href="https://www.sunnewsonline.com/nigeria-taps-into-global-online-meetings-business"><img
                        src="/assets/images/group76.png" class="img" alt="pix"/></a>
                <a href="https://pmnewsnigeria.com/2021/04/07/konn3ct-nigerian-tech-firm-unveils-virtual-meeting-app-better-than-zoom/?amp=1"><img
                        src="/assets/images/group77.png" class="img" alt="pix"/></a>
                <a href="https://thisnigeria.com/nigerian-tech-firm-unveils-virtual-meeting-app"><img
                        src="/assets/images/group78.png" class="img" alt="pix"/></a>
                <a href="https://www.nipc.gov.ng/2021/03/31/newwaves-launches-virtual-meeting-solution-konn3ct"><img
                        src="/assets/images/group79.png" class="img" alt="pix"/></a>
                <a href="https://www.prnewswire.com/news-releases/konn3ct-nigerian-tech-start-up-develops-first-online-meeting-solution-in-africa-301261102.html"><img
                        src="/assets/images/group80.png" class="img" alt="pix"/></a>
            </div>

        </div>
    </div>

    <!-- Start -->
    {{--    <section class="mt-5">--}}
    {{--        <div class="container">--}}
    {{--            <div class="row justify-content-center">--}}
    {{--                <div class="col-12">--}}
    {{--                    <div class="section-title text-center">--}}
    {{--                        <small class="">What Our Users Are Saying!</small>--}}
    {{--                        <h4 class="title mb-4">Our Clients Feedback</h4>--}}
    {{--                    </div>--}}
    {{--                </div><!--end col-->--}}
    {{--            </div><!--end row-->--}}
    {{--            <div id="testimonial" class="row">--}}
    {{--                <div class="col-12 col-md-6 col-xl-10">--}}
    {{--                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">--}}
    {{--                        <div class="carousel-inner mx-4 my-3 py-4 px-4">--}}
    {{--                            <div class="carousel-item active text-white">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-3">--}}
    {{--                                        <img src="/assets/images/photography-of-a-guy-wearing-green-shirt-1222271.png"--}}
    {{--                                             class="img-fluid" alt="pix"/>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-8">--}}
    {{--                                        <p>"Sed Ut Perspiciatis Unde Omnis Iste Natus Error Sit--}}
    {{--                                            Voluptatem Accusantium Doloremque Laudantium,--}}
    {{--                                            Totam Rem Aperiam, Eaque Ipsa Quae Ab Illo--}}
    {{--                                            Modi Tem."</p>--}}
    {{--                                        <div class="mt-4" style="font-weight: bolder; color: white">--}}
    {{--                                            Samuel Adekunle--}}
    {{--                                        </div>--}}
    {{--                                        <div style="color: white; font-size: xx-small">--}}
    {{--                                            Manager. @Konn3ct--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-1"></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}

    {{--                            <div class="carousel-item text-white">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-3">--}}
    {{--                                        <img src="/assets/images/photography-of-a-guy-wearing-green-shirt-1222271.png"--}}
    {{--                                             class="img-fluid" alt="pix"/>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-8">--}}
    {{--                                        <p>"Sed Ut Perspiciatis Unde Omnis Iste Natus Error Sit--}}
    {{--                                            Voluptatem Accusantium Doloremque Laudantium,--}}
    {{--                                            Totam Rem Aperiam, Eaque Ipsa Quae Ab Illo--}}
    {{--                                            Modi Tem."</p>--}}
    {{--                                        <div class="mt-4" style="font-weight: bolder; color: white">--}}
    {{--                                            Samuel Adekunle--}}
    {{--                                        </div>--}}
    {{--                                        <div style="color: white; font-size: xx-small">--}}
    {{--                                            Manager. @Konn3ct--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-1"></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="carousel-item text-white">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-3">--}}
    {{--                                        <img src="/assets/images/photography-of-a-guy-wearing-green-shirt-1222271.png"--}}
    {{--                                             class="img-fluid" alt="pix"/>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-8">--}}
    {{--                                        <p>"Sed Ut Perspiciatis Unde Omnis Iste Natus Error Sit--}}
    {{--                                            Voluptatem Accusantium Doloremque Laudantium,--}}
    {{--                                            Totam Rem Aperiam, Eaque Ipsa Quae Ab Illo--}}
    {{--                                            Modi Tem."</p>--}}
    {{--                                        <div class="mt-4" style="font-weight: bolder; color: white">--}}
    {{--                                            Samuel Adekunle--}}
    {{--                                        </div>--}}
    {{--                                        <div style="color: white; font-size: xx-small">--}}
    {{--                                            Manager. @Konn3ct--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-1"></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="carousel-item text-white">--}}
    {{--                                <div class="row">--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-3">--}}
    {{--                                        <img src="/assets/images/photography-of-a-guy-wearing-green-shirt-1222271.png"--}}
    {{--                                             class="img-fluid" alt="pix"/>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-8">--}}
    {{--                                        <p>"Sed Ut Perspiciatis Unde Omnis Iste Natus Error Sit--}}
    {{--                                            Voluptatem Accusantium Doloremque Laudantium,--}}
    {{--                                            Totam Rem Aperiam, Eaque Ipsa Quae Ab Illo--}}
    {{--                                            Modi Tem."</p>--}}
    {{--                                        <div class="mt-4" style="font-weight: bolder; color: white">--}}
    {{--                                            Samuel Adekunle--}}
    {{--                                        </div>--}}
    {{--                                        <div style="color: white; font-size: xx-small">--}}
    {{--                                            Manager. @Konn3ct--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="col-sm-12 col-md-6 col-xl-1"></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"--}}
    {{--                                data-bs-slide="prev">--}}
    {{--                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>--}}
    {{--                            <span class="visually-hidden">Previous</span>--}}
    {{--                        </button>--}}
    {{--                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"--}}
    {{--                                data-bs-slide="next">--}}
    {{--                            <span class="carousel-control-next-icon" aria-hidden="true"></span>--}}
    {{--                            <span class="visually-hidden">Next</span>--}}
    {{--                        </button>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </section>--}}
    <!--end section-->

@endsection

