@extends('layouts.new-layout')
@section('content')
    <style>
        div.scrollmenu {
            background-color: #fff;
            overflow: auto;
            white-space: nowrap;
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
    </style>
    <div class="row mt-5">

        <div class="row">
            <div class="col-6">
                <h2>
                    Konn3ct
                </h2>

                <h4>
                    Meet, chat, and collaborate<br/>
                    in just one place.
                </h4>

                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-primary">Start Free Trial</button>
                    </div>

                    <div class="col-6">
                        <button type="button" class="btn btn-primary">Start Free Trial</button>
                    </div>

                </div>

            </div>

            <div class="col-6">
                <img src="/assets/images/front1.png" class="img col-12" alt="pix"/>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <h2>
                    UNIQUE FEATURES
                </h2>

                <h4>
                    Konn3ct is an enterprise solution with features
                    and management modules that makes it suitable
                    for highly structured and sequenced environments.
                </h4>

            </div>

            <div class="col-6">
                <img src="/assets/images/group99.png" class="img col-12" alt="pix"/>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h2>
                    WHY KONN3CT
                </h2>

                <h4>
                    Konn3ct is the first fully featured web-conferencing solution developed in Nigeria and Africa.
                    This gives konn3ct the pioneer status and puts Nigeria in the leadership role we have always
                    provided on the continent.
                    This leadership statement is best proven with konn3ct’s adoption by large corporates and
                    governmental institutions,
                    and its commercial success. This gives the technological edge to every African country as well as
                    her people to thrive on
                </h4>

            </div>

        </div>

        <div class="row">
            <div class="col-6">
                <img src="/assets/images/2345thyj.png" class="img col-12" alt="pix"/>
            </div>
            <div class="col-6">
                <h5>
                    Get to know more about
                </h5>
                <img src="/assets/images/konn3ct_logo123.png" class="img" alt="pix"/>
                <h2>
                    UNIQUE FEATURES
                </h2>

                <h4>
                    Konn3ct is technically a suite of web-conferencing
                    solutions that cover a range of applications used for
                    meetings, conferences, webinars, rooms, live-classroom,
                    syndicate events, remote cinema etc. konn3ct is a fusion
                    of all these applications that is accessible from free plans
                    that allows 100 participants for 60 minutes and paid plans
                    for more features.
                </h4>

                <div class="col-6">
                    <button type="button" class="btn btn-primary">Learn more</button>
                </div>

            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center">
                    Press and Reviews
                </h2>

                <div class="scrollmenu">
                    <a href="#home"><img src="/assets/images/group73.png" class="img" alt="pix"/></a>
                    <a href="#news"><img src="/assets/images/group74.png" class="img" alt="pix"/></a>
                    <a href="#home"><img src="/assets/images/group75.png" class="img" alt="pix"/></a>
                    <a href="#news"><img src="/assets/images/group76.png" class="img" alt="pix"/></a>
                    <a href="#home"><img src="/assets/images/group77.png" class="img" alt="pix"/></a>
                    <a href="#news"><img src="/assets/images/group78.png" class="img" alt="pix"/></a>
                    <a href="#home"><img src="/assets/images/group79.png" class="img" alt="pix"/></a>
                    <a href="#news"><img src="/assets/images/group80.png" class="img" alt="pix"/></a>
                </div>

            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center">
                    Our Clients Feedback
                </h2>

                <div class="row" style="background-color: #012E89">
                    <div class="col-4">
                        <img src="/assets/images/photography-of-a-guy-wearing-green-shirt-1222271.png"
                             class="img col-12" alt="pix"/>
                    </div>
                    <div class="col-8 px-4 py-4">
                        <div>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star checked"></span>
                            <span class="fa fa-star"></span>
                            <span class="fa fa-star"></span>
                        </div>
                        <div style="color: white">
                            "Sed Ut Perspiciatis Unde Omnis Iste Natus Error Sit
                            Voluptatem Accusantium Doloremque Laudantium,
                            Totam Rem Aperiam, Eaque Ipsa Quae Ab Illo
                            Modi Tem."
                        </div>
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

