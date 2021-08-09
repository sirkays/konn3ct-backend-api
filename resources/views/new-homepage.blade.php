@extends('layouts.new-layout')
@section('content')
    <style>
        div.scrollmenu {
            background-color: #fff;
            overflow: auto;
            white-space: nowrap;
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }

        div.scrollmenu a {
            display: inline-block;
            color: white;
            text-align: center;
            padding: 14px;
            text-decoration: none;
        }

        div.scrollmenu a:hover {
            background-color: #777;
        }

        .checked {
            color: orange;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        div.scrollmenu::-webkit-scrollbar {
            display: none;
        }

        #Konn3ct_is_the_first_fully_fea {
            text-align: left;
            font-family: Poppins;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

    </style>
    <div class="row">

        <div class="row" style="background-image: url('/assets/images/pathgroup.png'); padding-left: 100px">
            <div class="col-md-12 col-lg-6 align-self-center" style="color: white;">
                <h2 style="font-weight: bolder; font-size: 69px">
                    Konn3ct
                </h2>

                <h4 class="mt-3">
                    Meet, chat, and collaborate<br/>
                    in just one place.
                </h4>

                <div class="row mt-5">
                    <div class="col-12">
                        <a href="{{route('new-signup')}}" type="button" class="btn px-3 py-3 mr-3 mt-2"
                           style="border-radius: 30px; background-color: #012E89; color: white; font-weight: bolder">
                            Start Free Trial
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
                <img src="/assets/images/front1@2x.png" class="img col-12" alt="pix"/>
            </div>
        </div>

        <div id="unique" class="row mt-3" style="padding-left: 100px">
            <div class="col-md-12 col-lg-6 align-self-center">
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
                <img src="/assets/images/group99@2x.png" class="img col-12" alt="pix"/>
            </div>
        </div>

        <div class="row mt-3 mb-5" style="padding-left: 100px">
            <div class="col-12">
                <h2 style="font-weight: bolder">
                    WHY KONN3CT
                </h2>

                <div id="Konn3ct_is_the_first_fully_fea">
                    <span>Konn3ct is the first fully featured web-conferencing solution developed in Nigeria and Africa.<br/>This gives konn3ct the pioneer status and puts Nigeria in the leadership role we have always provided on the continent.<br/>This leadership statement is best proven with konn3ct’s adoption by large corporates and governmental institutions,<br/>and its commercial success. This gives the technological edge to every African country as well as her people to thrive on </span><br>
                </div>

            </div>

        </div>

        <div class="row" style="background-image: url('/assets/images/path1539.png'); padding-left: 100px">

            <div class="col-md-12 col-lg-6 px-3 py-3">
                <img src="/assets/images/2345thyj.png" class="img img-fluid" alt="pix"/>
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
                    <button type="button" class="btn px-2 py-2 ml-3 mt-2"
                            style="border-radius: 30px; background-color: white; color: black; font-weight: bolder">
                        Learn more
                    </button>
                </div>

            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center">
                    Press and Reviews
                </h2>

                <div class="scrollmenu">
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

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center">
                    Our Clients Feedback
                </h2>

                <div class="row" style="background-color: #012E89; margin-left: 100px; margin-right: 100px">
                    <div class="col-md-12 col-lg-4">
                        <img src="/assets/images/photography-of-a-guy-wearing-green-shirt-1222271.png"
                             class="img col-12" alt="pix"/>
                    </div>

                    <div class="col-md-12 col-lg-8 align-self-center" style="padding-left: 180px">
                        <div>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                        </div>
                        <h5 class="mb-5 mt-4" style="color: white">
                            "Sed Ut Perspiciatis Unde Omnis Iste Natus Error Sit
                            Voluptatem Accusantium Doloremque Laudantium,
                            Totam Rem Aperiam, Eaque Ipsa Quae Ab Illo
                            Modi Tem."
                        </h5>
                        <div class="mt-4" style="font-weight: bolder; color: white">
                            Samuel Adekunle
                        </div>
                        <div style="color: white; font-size: xx-small">
                            Manager. @Konn3ct
                        </div>

                    </div>

                </div>

            </div>
        </div>


    </div>
@endsection

