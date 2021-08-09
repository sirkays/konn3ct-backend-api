@extends('layouts.new-layout')
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
        }

        .single-table .plan-header h3 {
            margin: 0;
            padding: 20px 0 5px 0;
            text-transform: uppercase;
        }

        .single-table .plan-price {
            display: inline-block;
            color: #e67e22;
            margin: 0 0 10px 0;
            font-size: 17px;
            font-weight: bold;
            background: #fff;
            border-radius: 50%;
            color: #e67e22;
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
            border-color: transparent transparent transparent #e67e22;
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
            color: #3498db;
        }

        .color-2 .single-table .plan-submit:hover {
            background: #3498db;
            color: #fff;
        }

        .color-2 .hvr-bubble-float-right:hover:before,
        .color-2 .hvr-bubble-float-right:focus:before,
        .color-2 .hvr-bubble-float-right:active:before {
            transform: translateX(10px);
            border-color: transparent transparent transparent #3498db;
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
    </style>

    <div class="row mt-5">
        <div class="col-md-12 col-lg-12">
            <h2 class="text-center" style="font-weight: bolder">Ready to start with <br/>Konn3ct?</h2>
            <div class="text-center" style="color: grey">Choose the package that suits you.</div>
        </div>

        <div class="col-12 text-center mt-5">
            <div class="form-check form-switch">
                <label class="form-check-label" for="flexSwitchCheckChecked">Monthly</label>
                <input class="form-check-input text-center" type="checkbox" id="flexSwitchCheckChecked" checked>
                <label class="form-check-label" for="flexSwitchCheckChecked">Yearly</label>
            </div>
        </div>

    </div>

    <!-- pricing table  -->
    <section id="pricing-tables">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12 color-1">
                    <div class="single-table text-center">
                        <div class="plan-header mb-3">
                            <h3 style="font-weight: bolder">BASIC PLAN</h3>
                            <p>Free forever</p>
                        </div>


                        <ul class="text-center">
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                        </ul>
                        <a href="#" class="plan-submit hvr-bubble-float-right">buy now</a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 color-2">
                    <div class="single-table text-center"
                         style="border-radius: 20px; border-color: #628F41; border-width: 2px; border-style: solid">
                        <div class="plan-headerRecomended">
                            <h3 style="font-weight: bolder">LITE PLAN</h3>
                            <p></p>
                            <h4 class="plan-priceRecomended">$10.99 <sup>Month</sup> / ₦4,000 <sup>Month</sup></h4>
                        </div>


                        <ul class="text-center">
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                        </ul>
                        <a href="#" class="plan-submit hvr-bubble-float-right">buy now</a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 color-3">
                    <div class="single-table text-center">
                        <div class="plan-header">
                            <h3>basic</h3>
                            <p>plan for basic user</p>
                            <h4 class="plan-price">$30<span>/mo</span></h4>
                        </div>


                        <ul class="text-center">
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                        </ul>
                        <a href="#" class="plan-submit hvr-bubble-float-right">buy now</a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12 color-4">
                    <div class="single-table text-center">
                        <div class="plan-header">
                            <h3>basic</h3>
                            <p>plan for basic user</p>
                            <h4 class="plan-price">$30<span>/mo</span></h4>
                        </div>


                        <ul class="text-center">
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                            <li>10 Free PSD files</li>
                        </ul>
                        <a href="#" class="plan-submit hvr-bubble-float-right">buy now</a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- end priceing table -->
@endsection

