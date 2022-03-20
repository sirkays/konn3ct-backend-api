<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Konn3ct</title>
    <meta name="description" content="Host your virtual events on konn3ct! It's Free!! Register Now!!!">
    <meta name="author" content="Newwaves Ecosystem Limited">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/konn3ct.ico">
    <!-- Place favicon.ico in the root directory -->


    <meta name="og:url" content="https://konn3ct.com">
    <meta name="og:description" content="Host your virtual events on konn3ct! It's Free!! Register Now!!!">
    <meta name="og:type" content="website">
    <meta name="og:title" content="konn3ct">
    <meta name="og:image" content="{{url('/')}}/assets/images/whiteboard.jpg">
    <meta name="og:locale" content="en_US">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:title" content="konn3ct"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="https://konn3ct.com"/>
    <meta property="og:image" content="{{url('/')}}/assets/images/whiteboard.jpg"/>

@include('facebook-pixel::head')

<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1543717222676161');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1543717222676161&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->

<!-- Bootstrap -->
    <link href="/website/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- Icons -->
    <link href="/website/css/materialdesignicons.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/website/css/line.css">
    <!-- Slider -->
    <link rel="stylesheet" href="/website/css/tiny-slider.css">
    <!-- Main Css -->
    <link href="/website/css/style.min.css" rel="stylesheet" type="text/css" id="theme-opt">
    <link href="/website/css/default.css" rel="stylesheet" id="color-opt">

    <meta name="facebook-domain-verification" content="vc0h3gs5jtphygh7xftaydys9d3jjo"/>

    <style>

        #navLink {
            white-space: nowrap;
            text-align: left;
            font-family: Poppins;
            font-style: normal;
            font-weight: normal;
            color: rgba(1, 46, 137, 1);
        }

        #navLinkactive {
            white-space: nowrap;
            text-align: left;
            font-family: Poppins;
            font-style: normal;
            font-weight: normal;
            color: #6C993C;
        }

        #Konn3ct_is_the_first_fully_fea {
            text-align: left;
            font-family: Poppins;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #footer_link_header {
            white-space: nowrap;
            text-align: left;
            font-family: Poppins;
            font-style: normal;
            font-weight: bolder;
            font-size: 20px;
            color: rgba(1, 46, 136, 1);
        }

        #footer_link {
            white-space: nowrap;
            text-align: left;
            font-family: Poppins;
            font-style: normal;
            font-weight: normal;
            font-size: 15px;
            color: rgba(1, 46, 136, 1);
            text-decoration: none;
        }
    </style>

    <style>
        .more {
            display: none;
        }

        /* COMPACT CAPTCHA */

        .capbox {
            background-color: #BBBBBB;
            background-image: linear-gradient(#BBBBBB, #9E9E9E);
            border: #2A7D05 0px solid;
            border-width: 2px 2px 2px 20px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            display: inline-block;
            padding: 5px 8px 5px 8px;
            border-radius: 4px 4px 4px 4px;
        }

        .capbox-inner {
            font: bold 12px arial, sans-serif;
            color: #000000;
            background-color: #E3E3E3;
            margin: 0px auto 0px auto;
            padding: 3px 10px 5px 10px;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
        }

        #CaptchaDiv {
            color: #000000;
            font: normal 25px Impact, Charcoal, arial, sans-serif;
            font-style: italic;
            text-align: center;
            vertical-align: middle;
            background-color: #FFFFFF;
            user-select: none;
            display: inline-block;
            padding: 3px 14px 3px 8px;
            margin-right: 4px;
            border-radius: 4px;
        }

        #CaptchaInput {
            border: #38B000 2px solid;
            margin: 3px 0px 1px 0px;
            width: 105px;
        }

        #CaptchaDiv2 {
            color: #000000;
            font: normal 25px Impact, Charcoal, arial, sans-serif;
            font-style: italic;
            text-align: center;
            vertical-align: middle;
            background-color: #FFFFFF;
            user-select: none;
            display: inline-block;
            padding: 3px 14px 3px 8px;
            margin-right: 4px;
            border-radius: 4px;
        }

        #CaptchaInput2 {
            border: #38B000 2px solid;
            margin: 3px 0px 1px 0px;
            width: 105px;
        }

        #freg {
            position: fixed;
            width: 120px;
            height: 50px;
            bottom: 190px;
            right: 40px;
            background-color: #35ac39;
            color: #FFF;
            border-radius: 30px;
            text-align: center;
            box-shadow: 2px 2px 3px #999;
        }

        #freg:hover {
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            transition: all 0.5s;
            transform: translateZ(10px);
        }

        #t:hover {
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
            transition: all 0.5s;
            transform: translateZ(10px);
        }

        #container-floating:hover {
            height: 400px;
            width: 180px;
            padding: 30px;
        }

        a.su {
            color: #042c69;
        }

        a.su:hover {
            color: green;
        }

        button.su {
            background-color: #042c69;
        }

        button.su:hover {
            background-color: green;
        }

        /*body > .skiptranslate {*/
        /*    display: none;*/
        /*}*/

        .goog-te-banner-frame.skiptranslate {
            display: none !important;
        }

        body {
            top: 0px !important;
        }

        .lih {
            font-weight: bolder;
            font-size: 14px
        }

    </style>

    <style type="text/css" id="#jarallax-clip-0">#jarallax-container-0 {
            clip: rect(0 1349px 673px 0);
            clip: rect(0, 1349px, 673px, 0);
        }</style>

    <!-- Web Application Manifest -->
    <link rel="manifest" href="/manifest.json">
    <!-- Chrome for Android theme color -->
    <meta name="theme-color" content="#042c69">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="Konn3ct">
    <link rel="icon" sizes="512x512" href="/assets/manifest/k512.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Konn3ct">
    <link rel="apple-touch-icon" href="/assets/manifest/k512.png">


    <link href="/assets/images/konn3ct_logo.png"
          media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link href="/assets/images/konn3ct_logo.png"
          media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link href="/assets/images/konn3ct_logo.png"
          media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)"
          rel="apple-touch-startup-image"/>
    <link href="/assets/images/konn3ct_logo.png"
          media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)"
          rel="apple-touch-startup-image"/>
    <link href="/assets/images/konn3ct_logo.png"
          media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link href="/assets/images/konn3ct_logo.png"
          media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)"
          rel="apple-touch-startup-image"/>
    <link href="/assets/images/konn3ct_logo.png"
          media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link href="/assets/images/konn3ct_logo.png"
          media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link href="/assets/images/konn3ct_logo.png"
          media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>
    <link href="/assets/images/konn3ct_logo.png"
          media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)"
          rel="apple-touch-startup-image"/>

    <!-- Tile for Win8 -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/manifest/k512.png">


    <script type="text/javascript">
        var deviceDetect = navigator.platform;
        var appleDevicesArr = ['MacIntel', 'MacPPC', 'Mac68K', 'Macintosh', 'iPhone',
            'iPod', 'iPad', 'iPhone Simulator', 'iPod Simulator', 'iPad Simulator', 'Pike v7.6 release 92', 'Pike v7.8 release 517'];

        // If on Apple device
        if (appleDevicesArr.includes(deviceDetect)) {
            // Execute code
        }
        // If NOT on Apple device
        else {

            // Initialize the service worker
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/serviceworker.min.js', {
                    scope: '.'
                }).then(function (registration) {
                    // Registration was successful
                    console.log('Laravel PWA: ServiceWorker registration successful with scope: ', registration.scope);
                }, function (err) {
                    // registration failed :(
                    console.log('Laravel PWA: ServiceWorker registration failed: ', err);
                });
            }
        }
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-196433825-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-196433825-2');
    </script>


</head>

<body>
@include('facebook-pixel::body')
<!-- Loader -->
<!-- <div id="preloader">
    <div id="status">
        <div class="spinner">
            <div class="double-bounce1"></div>
            <div class="double-bounce2"></div>
        </div>
    </div>
</div> -->
<!-- Loader -->
<nav class="navbar navbar-expand-lg navbar-light bg-body">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{route('welcome')}}"><img class="img img-responsive"
                                                                 src="/assets/images/konn3ct_logo@2x.png"
                                                                 height="50" alt="Konn3ct logo"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
                aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a id="navLink" class="nav-link  dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Solutions </a>
                    <div class="dropdown-menu">
                        <div class="row" style="width: 800px">
                            <div class="col-6">
                                <a class="mb-4 dropdown-item" href="#"> <img
                                        src="/assets/images/videoconference.png" height="35px" class="mr-3"
                                        alt="navicons"> Meeting, Chat & Calling</a>
                                <a class="mb-4 dropdown-item" href="#"> <img src="/assets/images/online-class.png"
                                                                             height="35px" class="mr-3"
                                                                             alt="navicons"> Webinar & Conferencing
                                </a>
                                <a class="mb-4 dropdown-item" href="#"> <img src="/assets/images/online-course.png"
                                                                             height="35px" class="mr-3"
                                                                             alt="navicons"> E-Learning</a>
                            </div>

                            <div class="col-6">
                                <a class="mb-4 dropdown-item" href="#"> <img src="/assets/images/secure-shield.png"
                                                                             height="35px" class="mr-3"
                                                                             alt="navicons"> Security &
                                    Compliance</a>
                                <a class="mb-4 dropdown-item" href="#"> <img src="/assets/images/remote.png"
                                                                             height="35px" class="mr-3"
                                                                             alt="navicons"> Work Remotely </a>
                                <a class="mb-4 dropdown-item" href="#"> <img src="/assets/images/ellipsis.png"
                                                                             height="15px" class="mr-3"
                                                                             alt="navicons"> Others</a>
                            </div>
                        </div>

                    </div>
                </li>
                <li class="nav-item">
                    <a id="@yield('contact','navLink')" class="nav-link" href="{{route('contact')}}">Contact
                        sales</a>
                </li>
                <li class="nav-item">
                    <a id="@yield('pricing','navLink')" class="nav-link" href="{{route('pricing')}}">Plans &
                        Pricing</a>
                </li>
            </ul>
            <div class="d-flex">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a id="@yield('join','navLink')" class="nav-link" href="{{route('joinmeeting')}}">Join a
                            Meeting Room</a>
                    </li>
                    <li class="nav-item">
                        @auth
                            <a id="navLink" class="nav-link" href="{{route('logouts')}}">Signout</a>
                        @else
                            <a id="navLink" class="nav-link" href="{{route('login')}}">Log in</a>
                        @endif
                    </li>
                </ul>

                <a href="@auth {{route('rooms')}} @else {{route('register')}} @endif"
                   class="btn text-center"
                   style="border-radius: 30px; background-color: #012E89; color: white; border-color: #012E89; max-height: 50px">
                    <span>@auth Meeting Room @else Sign Up, It’s FREE @endif</span>
                </a>
            </div>
        </div>
    </div>
</nav>

@yield('content')

<div class="row mt-5 mb-5">
    <div class="col-12 text-center mb-4">
        <a href="{{route('joinmeeting')}}" class="btn px-3 py-3 mr-3 mt-2"
           style="border-radius: 30px; background-color: #012E89; color: white; font-weight: bolder; width: 200px">
            <img src="/assets/images/joinMeetingIcon.png" width="25px" height="30px" alt="joinmeetingIcon"/> &nbsp;
            Join a meeting
        </a>
        &nbsp;
        <a href="{{route('register')}}" type="button" class="btn px-3 py-3 ml-3 mt-2"
           style="border-radius: 30px; background-color: #012E89; color: white; font-weight: bolder; width: 220px">
            <img src="/assets/images/registerIcon.png" width="25px" height="30px" alt="regIcon"/> &nbsp;
            Sign Up For FREE
        </a>
    </div>

    <div class="col-12 text-center">
        <div class="col-12 text-right" id="google_translate_element"></div>
    </div>
</div>

<!-- Footer -->
<footer class="page-footer font-small indigo mt-5">

    <!-- Footer Links -->
    <div class="container text-center text-md-left">

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column -->
            <div class="col-lg-3 col-md-6 mt-md-0 mt-3">

                <!-- Content -->
                <a href="{{route('new-homepage')}}">
                    <img class="img img-responsive" src="/assets/images/konn3ct_logo@2x.png" height="80"
                         alt="Konn3ct logo"/>
                </a>
                <div class="text-center">
                    <a href="https://www.facebook.com/konn3ctapp"> <img src="/assets/images/group2005.png"
                                                                        height="35px"></a>

                    <a href="https://twitter.com/konn3ctapp"><img src="/assets/images/group2004.png" height="35px"></a>

                    <a href="https://www.instagram.com/konn3ctng"><img src="/assets/images/group2003.png"
                                                                       height="35px"></a>

                    <img src="/assets/images/group2002.png" height="35px"/>

                    <a href="https://www.youtube.com/channel/UCt8nu6M8VBWonkFOuUTuHUg"><img
                            src="/assets/images/group2001.png" height="35px"></a>

                </div>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none pb-3">

            <!-- Grid column -->
            <div class="col-lg-2 col-md-6 col-sm-6 mx-auto">

                <!-- Links -->
                <span id="footer_link_header" class="font-weight-bold mt-3 mb-4">How To</span>

                <ul class="list-unstyled">
                    <li>
                        <a href="https://www.youtube.com/watch?v=jEV7vjngo4g" id="footer_link">Register on konn3ct</a>
                    </li>
                    <li>
                        <a href="https://www.youtube.com/watch?v=Dn323U-br5Q" id="footer_link">Create Meeting Room</a>
                    </li>
                    <li>
                        <a href="https://www.youtube.com/watch?v=mLoHB9cltWs" id="footer_link">Join Meeting Room</a>
                    </li>

                    <li>
                        <a href="https://www.youtube.com/watch?v=eCblbRoL4gs" id="footer_link">Manage Meeting Room</a>
                    </li>

                    {{--                    <li>--}}
                    {{--                        <a href="#!" id="footer_link">About us</a>--}}
                    {{--                    </li>--}}
                    {{--                    <li>--}}
                    {{--                        <a href="#!" id="footer_link">Blog</a>--}}
                    {{--                    </li>--}}
                    {{--                    <li>--}}
                    {{--                        <a href="#!" id="footer_link">FAQ</a>--}}
                    {{--                    </li>--}}
                </ul>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none d-sm-none">

            <!-- Grid column -->
            <div class="col-lg-2 col-md-6 col-sm-6 mx-auto">

                <!-- Links -->
                <span id="footer_link_header" class="font-weight-bold mt-3 mb-4">Solutions</span>

                <ul class="list-unstyled">
                    <li>
                        <a href="#!" id="footer_link">Branding</a>
                    </li>
                    <li>
                        <a href="#!" id="footer_link">Optimized Bandwidth</a>
                    </li>
                    <li>
                        <a href="#!" id="footer_link">Multi-Channel Support</a>
                    </li>
                    <li>
                        <a href="#!" id="footer_link">Konn3ct Doc</a>
                    </li>
                </ul>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none d-sm-none col-">

            <!-- Grid column -->
            <div class="col-lg-2 col-md-6 col-sm-6 mx-auto">

                <!-- Links -->
                <span id="footer_link_header" class="font-weight-bold mt-3 mb-4">Legal</span>

                <ul class="list-unstyled">
                    <li>
                        <a href="#!" id="footer_link">Terms of Use</a>
                    </li>
                    <li>
                        <a href="/docs/DATAPRIVACY.pdf" id="footer_link">Privacy Policy</a>
                    </li>
                    <li>
                        <a href="/docs/Nigeria Data Protection Regulation 2019 Implementation Framework.pdf"
                           id="footer_link">NDPR</a>
                    </li>
                    <li>
                        <a href="/docs/EU_GDPR_Full_Text_EN.pdf" id="footer_link">GDPR</a>
                    </li>
                </ul>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none d-sm-none">

            <!-- Grid column -->
            <div class="col-lg-2 col-md-6 col-sm-6 mx-auto">

                <!-- Links -->
                <span id="footer_link_header" class="font-weight-bold mt-3 mb-4">Contact</span>

                <ul class="list-unstyled">
                    <li>
                        <a href="mailto:info@konn3ct.com" id="footer_link">info@konn3ct.com</a>
                    </li>
                </ul>

            </div>
            <!-- Grid column -->

        </div>
        <!-- Grid row -->

    </div>
    <!-- Footer Links -->

    <!-- Copyright -->
    <div class="footer-copyright text-center py-3 mt-4">
        <div class="row">
            <div class="col-lg-4 col-sm-6 col-md-6">
                <span style="color: #012E88; font-style: normal; font-weight: normal;">© 2021 konn3ct • All Rights Reserved</span>
            </div>

            <div class="col-lg-4 d-md-none d-sm-none d-lg-block"></div>

            <div class="col-lg-4 col-sm-6 col-md-6 justify-content-right">
                <a href="/docs/DATAPRIVACY.pdf"
                   style="color: #012E88; font-style: normal; font-weight: normal; margin-right: 20px; text-decoration: none">Terms
                    of use</a> | <a
                    style="margin-left: 20px; color: #012E88; font-style: normal; font-weight: normal;">Privacy
                    Policy</a>
            </div>


        </div>
    </div>
    <!-- Copyright -->

</footer>
<!-- Footer -->

<!-- Back to top -->
<a href="#" onclick="topFunction()" id="back-to-top" class="btn btn-icon btn-primary back-to-top"
   style="display: none;">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up icons">
        <line x1="12" y1="19" x2="12" y2="5"></line>
        <polyline points="5 12 12 5 19 12"></polyline>
    </svg>
</a>
<!-- Back to top -->

<!-- Google Translator -->
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            layout: google.translate.TranslateElement.InlineLayout.VERTICAL
        }, 'google_translate_element');
    }
</script>

<script type="text/javascript" rel="noopener"
        src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<!-- javascript -->
<script src="/website/js/bootstrap.bundle.min.js.download"></script>
<!-- SLIDER -->
<script src="/website/js/tiny-slider.js.download"></script>
<!-- Parallax -->
<script src="/website/js/parallax.js.download"></script>
<!-- Icons -->
<script src="/website/js/feather.min.js.download"></script>
<!-- Switcher -->
<script src="/website/js/switcher.js.download"></script>
<!-- Main Js -->
<script src="/website/js/plugins.init.js.download"></script>
<style type="text/css">.typewrite > .wrap {
        border-right: 0.08em solid transparent
    }</style>
<!--Note: All init js like tiny slider, counter, countdown, maintenance, lightbox, gallery, swiper slider, aos animation etc.-->
<script src="/website/js/app.js.download"></script>
<!--Note: All important javascript like page loader, menu, sticky menu, menu-toggler, one page menu etc. -->

<!-- Start of Qontak Webchat Script -->
<script>
    const qchatInit = document.createElement('script');
    qchatInit.src = "https://webchat.qontak.com/qchatInitialize.js";
    const qchatWidget = document.createElement('script');
    qchatWidget.src = "https://webchat.qontak.com/js/app.js";
    document.head.prepend(qchatInit);
    document.head.prepend(qchatWidget);
    qchatInit.onload = function () {
        qchatInitialize({id: '9e6325f3-5554-4a44-a64e-31d177c9ef6e', code: '2nFxcEWaXxoZDDNcXoh_wQ'})
    };
</script>
<!-- End of Qontak Webchat Script -->

</body>
</html>
