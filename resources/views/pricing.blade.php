@extends('layouts.website-layout')
@section('pricing','navLinkactive')
@section('content')

    <style>
        @import url("https://fonts.googleapis.com/css?family=Lato");

        #pricing-tables {
            background-color: #fff;
            padding: 30px 0;
            position: relative;
            font-family: "Lato", sans-serif;
        }

        #pricing-tables .col-md-3,
        #pricing-tables .col-sm-6,
        #pricing-tables .col-xs-12 {
            padding-right: 10px;
            padding-left: 10px;
        }

        #pricing-tables .col-md-3:hover,
        #pricing-tables .col-sm-6:hover,
        #pricing-tables .col-xs-12:hover {
            box-shadow: 0px 11px 30px 0px rgba(0, 0, 0, 0.75);
            z-index: 2;
            transform: scale(1.06);
            border: 0;
            transition: 0.5s all;
            border: none;
        }

        .single-table {
            background: #fff;
            transition: all 0.2s linear;
            z-index: 1;
            /* Bubble Float Right */
        }

        .single-table .plan-header {
            background: #012E89;
            color: #fff;
            text-transform: capitalize;
            padding: 2px 0;
        }

        .single-table .plan-headerRecomended {
            background: #FFFFFF;
            color: #000000;
            text-transform: capitalize;
            padding: 2px 0;
            border-color: #628F41;
            border-width: 2px;
            border-style: solid
        }

        .single-table .plan-header h3 {
            margin: 0;
            padding: 20px 0 5px 0;
            text-transform: uppercase;
        }

        .single-table .plan-price {
            display: inline-block;
            color: #FFFFFF;
            margin: 0 0 10px 0;
            font-size: 17px;
            font-weight: bold;
            color: #FFFFFF;
            padding: 33px 15px;
        }

        .single-table .plan-priceRecomended {
            display: inline-block;
            color: #000000;
            margin: 0 0 10px 0;
            font-size: 17px;
            font-weight: bold;
            background: #fff;
            border-radius: 50%;
            color: #000000;
            padding: 33px 15px;
        }

        .single-table .plan-price span {
            font-size: 14px;
            font-weight: normal;
        }

        .single-table ul {
            margin: 0;
            padding: 20px 0;
            list-style: none;
        }

        .single-table ul li {
            padding: 8px 0;
            margin: 0 20px;
            border-bottom: 1px solid white;
            font-size: 15px;
        }

        .single-table .plan-submit {
            display: inline-block;
            text-decoration: none;
            margin: 20px 0 30px 0;
            padding: 10px 40px;
            border: 1px solid #e67e22;
            color: #e67e22;
            font-size: 15px;
            text-transform: uppercase;
            border-radius: 3px;
            transition: all 0.25s linear;
        }

        .single-table .plan-submit:hover {
            background: #e67e22;
            color: #fff;
            transition: all 0.25s linear;
        }

        .single-table .hvr-bubble-float-right {
            display: inline-block;
            vertical-align: middle;
            transform: translateZ(0);
            box-shadow: 0 0 1px rgba(0, 0, 0, 0);
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -moz-osx-font-smoothing: grayscale;
            position: relative;
            transition-duration: 0.3s;
            transition-property: transform;
        }

        .single-table .hvr-bubble-float-right:before {
            position: absolute;
            z-index: -1;
            top: calc(50% - 10px);
            right: 0;
            content: "";
            border-style: solid;
            border-width: 10px 0 10px 10px;
            border-color: transparent transparent transparent transparent;
            transition-duration: 0.3s;
            transition-property: transform;
        }

        .single-table .hvr-bubble-float-right:hover,
        .single-table .hvr-bubble-float-right:focus,
        .single-table .hvr-bubble-float-right:active {
            transform: translateX(-10px);
        }

        .single-table .hvr-bubble-float-right:hover:before,
        .single-table .hvr-bubble-float-right:focus:before,
        .single-table .hvr-bubble-float-right:active:before {
            transform: translateX(10px);
            border-color: transparent transparent transparent #012E89;
        }

        .color-2 .single-table .plan-header {
            background: #3498db;
            color: #fff;
        }

        .color-2 .single-table .plan-header .plan-price {
            color: #3498db;
            background: #fff;
        }

        .color-2 .single-table .plan-submit {
            border: 1px solid #3498db;
            color: #628F41;
            border-radius: 15px;
            background-color: #FFFFFF;
            height: 60px;
            align-items: center;
            align-self: center;
        }

        .color-2 .single-table .plan-submit:hover {
            background: #3498db;
            color: #fff;
        }

        .color-2 .hvr-bubble-float-right:hover:before,
        .color-2 .hvr-bubble-float-right:focus:before,
        .color-2 .hvr-bubble-float-right:active:before {
            transform: translateX(10px);
            border-color: transparent transparent transparent #FFFFFF;
        }

        .color-3 .single-table .plan-header {
            background: #2ecc71;
            color: #fff;
        }

        .color-3 .single-table .plan-header .plan-price {
            color: #2ecc71;
            background: #fff;
        }

        .color-3 .single-table .plan-submit {
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }

        .color-3 .single-table .plan-submit:hover {
            background: #2ecc71;
            color: #fff;
        }

        .color-3 .hvr-bubble-float-right:hover:before,
        .color-3 .hvr-bubble-float-right:focus:before,
        .color-3 .hvr-bubble-float-right:active:before {
            transform: translateX(10px);
            border-color: transparent transparent transparent #2ecc71;
        }

        .color-4 .single-table .plan-header {
            background: #9b59b6;
            color: #fff;
        }

        .color-4 .single-table .plan-header .plan-price {
            color: #9b59b6;
            background: #fff;
        }

        .color-4 .single-table .plan-submit {
            border: 1px solid #9b59b6;
            color: #9b59b6;
        }

        .color-4 .single-table .plan-submit:hover {
            background: #9b59b6;
            color: #fff;
        }

        .color-4 .hvr-bubble-float-right:hover:before,
        .color-4 .hvr-bubble-float-right:focus:before,
        .color-4 .hvr-bubble-float-right:active:before {
            transform: translateX(10px);
            border-color: transparent transparent transparent #9b59b6;
        }

        #ifeaturesRecomended {
            color: #FFFFFF;
        }

        #ifeatures {
            color: #628F41;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #012E89;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #012E89;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>

    <div class="row mt-5">
        <div class="col-md-12 col-lg-12">
            <h2 class="text-center" style="font-weight: bolder">Ready to start with <br/>Konn3ct?</h2>
            <div class="text-center" style="color: grey">Choose the package that suits you.</div>
        </div>

        <div class="col-12 align-self-center text-center mt-5">
            <div class="form-check form-switch">
                <label class="form-check-label" for="flexSwitchCheckChecked" style="color: grey">Monthly</label>
                <label class="switch">
                    <input id="slider" type="checkbox" value="on" checked onchange="changeSlider(this.value)">
                    <span class="slider round"></span>
                </label>
                <label class="form-check-label" for="flexSwitchCheckChecked" style="color: grey">Yearly</label>
            </div>
        </div>

    </div>

    <!-- pricing table  -->
    <section id="pricing-tables">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12 color-1">
                    <div class="single-table" style="border-color: #012E89; border-width: 2px; border-style: solid">
                        <div class="plan-header mb-3 text-center">
                            <h3 class="mt-4" style="font-weight: bolder">BASIC PLAN</h3>
                            <p>Free forever</p>
                        </div>


                        <div style="background-color: #FFFFFF; color: #000000">
                            <ul class="text-justify">
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Participant - 100</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Session Timeout - 1 hour
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Number of Rooms - 1</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Audio & Video Preview Window
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Screen Sharing</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Whiteboard & Annotation Tools
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> User Status</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Breakout Rooms</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Full-Featured Admin Controls
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Share Webcam</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Shared Notes</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Share YouTube Videos</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Attendance</li>
                            </ul>


                            <div class="text-center mb-4">
                                <a href="/register" class="btn px-3 py-3 mr-3 mt-2 hvr-bubble-float-right"
                                   style="border-radius: 10px; width: 200px; background-color: #012E89; color: white; font-weight: bolder">
                                    Choose Plan
                                </a>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 color-2">
                    <div class="single-table">
                        <div class="plan-headerRecomended text-center">
                            <h3 class="mt-4" style="font-weight: bolder">LITE PLAN</h3>
                            <p></p>
                            <h4 id="yearly" class="plan-priceRecomended">$120 <sup>Yearly</sup> / ₦66,000
                                <sup>Yearly</sup></h4>
                            <h4 id="monthly" class="plan-priceRecomended" style="display: none">$10.99 <sup>Month</sup>
                                / ₦6,000 <sup>Month</sup></h4>
                        </div>

                        <div class="pb-4" style="background-color: #628F41; color: #FFFFFF">
                            <ul class="text-justify">
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Participant - 100
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Session Timeout -
                                    10 hour
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Cloud Storage - 5GB
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Number of Rooms - 2
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Audio & Video
                                    Preview Window
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Screen Sharing</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Whiteboard &
                                    Annotation Tools
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> User Status</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Breakout Rooms</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Recording</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Full-Featured Admin
                                    Controls
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Share Webcam</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Shared Notes</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeaturesRecomended"></i> Share YouTube
                                    Videos
                                </li>
                            </ul>

                            <div class="text-center mb-4">
                                <a id="liteLink" href="/register/2"
                                   class="btn px-3 py-3 mr-3 mt-2 hvr-bubble-float-right"
                                   style="border-radius: 10px; width: 200px; background-color: #ffff; color: #628F41; font-weight: bolder">
                                    Choose Plan
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 color-1">
                    <div class="single-table" style="border-color: #012E89; border-width: 2px; border-style: solid">
                        <div class="plan-header mb-3 text-center">
                            <h3 class="mt-4" style="font-weight: bolder">PRO PLAN</h3>
                            <p></p>
                            <h4 id="yearly1" class="plan-price">$175 <sup>Yearly</sup> / ₦88,000 <sup>Yearly</sup></h4>
                            <h4 id="monthly1" class="plan-price" style="display: none">$15.99 <sup>Month</sup> / ₦8,000
                                <sup>Month</sup></h4>
                        </div>


                        <div style="background-color: #FFFFFF; color: #000000">
                            <ul class="text-justify">
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Participant - 250</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Session Timeout - 24 hour
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Cloud Storage - 15GB</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Number of Rooms - 3</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Audio & Video Preview Window
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Screen Sharing</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Whiteboard & Annotation Tools
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> User Status</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Breakout Rooms</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Recording</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Full-Featured Admin Controls
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Share Webcam</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Shared Notes</li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Share YouTube Videos</li>
                            </ul>

                            <div class="text-center mb-4">
                                <a id="proLink" href="/register/3"
                                   class="btn px-3 py-3 mr-3 mt-2 hvr-bubble-float-right"
                                   style="border-radius: 10px; width: 200px; background-color: #012E89; color: white; font-weight: bolder">
                                    Choose Plan
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 color-1">
                    <div class="single-table" style="border-color: #012E89; border-width: 2px; border-style: solid">
                        <div class="plan-header mb-3 text-center">
                            <h3 class="mt-4" style="font-weight: bolder">ENTERPRISE PLAN</h3>
                            <p>Contact us</p>
                        </div>


                        <div class="mx-4 my-3" style="background-color: #FFFFFF; color: #727272">
                            <p>
                                Do you need more than what Pro offers?
                            </p>

                            <p>
                                Talk to a Dedicated Success<br/>
                                Manager and we will provide it.
                            </p>

                            <div class="text-center mb-4">
                                <a href="{{route('contact')}}" class="btn px-3 py-3 mr-3 mt-2 hvr-bubble-float-right"
                                   style="border-radius: 10px; width: 200px; background-color: #012E89; color: white; font-weight: bolder">
                                    Contact us
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-3 col-sm-6 col-xs-12 color-1">
                    <div class="single-table" style="border-color: #012E89; border-width: 2px; border-style: solid">
                        <div class="plan-header mb-3 text-center">
                            <h3 class="mt-4" style="font-weight: bolder">ADDONS</h3>
                            <p></p>
                        </div>


                        <div style="background-color: #FFFFFF; color: #000000">
                            <ul class="text-justify">
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Streaming Service - ₦20,000
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Room Bundles (10) - ₦15,000
                                </li>
                                <li><i class="fa fa-check-circle mr-2" id="ifeatures"></i> Whatsapp Invite - ₦3,000
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="text-center">
                    <img src="/assets/images/visa_mastercard.png" class="col-2" alt="pix"/>
                    <br/>
                    <span style="font-weight: bolder">Mastercard and Visa cards are accepted here</span>
                </div>
            </div>

        </div>
    </section>

    <script>
        function changeSlider(status) {
            console.log(status);
            var register = document.getElementById("liteLink");
            var register2 = document.getElementById("proLink");

            if (status == "on") {
                document.getElementById('slider').value = "off";
                document.getElementById('yearly').style.display = 'none';
                document.getElementById('monthly').style.display = 'block';
                document.getElementById('yearly1').style.display = 'none';
                document.getElementById('monthly1').style.display = 'block';

                var att = document.createAttribute("href");        // Create a "href" attribute
                att.value = "/register/21";            // Set the value of the href attribute
                register.setAttributeNode(att);

                var att2 = document.createAttribute("href");        // Create a "href" attribute
                att2.value = "/register/31";            // Set the value of the href attribute
                register2.setAttributeNode(att2);
            } else {
                document.getElementById('slider').value = "on";
                document.getElementById('monthly').style.display = 'none';
                document.getElementById('yearly').style.display = 'block';
                document.getElementById('monthly1').style.display = 'none';
                document.getElementById('yearly1').style.display = 'block';

                var att = document.createAttribute("href");        // Create a "href" attribute
                att.value = "/register/2";            // Set the value of the href attribute
                register.setAttributeNode(att);

                var att2 = document.createAttribute("href");        // Create a "href" attribute
                att2.value = "/register/3";            // Set the value of the href attribute
                register2.setAttributeNode(att2);
            }
        }
    </script>

    <!-- end priceing table -->
@endsection
