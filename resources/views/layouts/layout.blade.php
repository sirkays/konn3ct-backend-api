<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Konn3ct</title>
    <meta name="description" content="Host your virtual events on konn3ct! It's Free!! Register Now!!!">
    <meta name="author" content="Newwaves Ecosystem Limited">

    <meta name="og:url" content="https://konn3ct.com">
    <meta name="og:description" content="Host your virtual events on konn3ct! It's Free!! Register Now!!!">
    <meta name="og:type" content="website">
    <meta name="og:title" content="konn3ct">
    <meta name="og:image" content="{{url('/')}}/assets/images/whiteboard.jpg">
    <meta name="og:locale" content="en_US">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:title" content="konn3ct" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://konn3ct.com" />
    <meta property="og:image" content="{{url('/')}}/assets/images/whiteboard.jpg" />

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/konn3ct.ico">
    <!-- Place favicon.ico in the root directory -->

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


    <link href="/assets/images/konn3ct_logo.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/images/konn3ct_logo.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/images/konn3ct_logo.png" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/assets/images/konn3ct_logo.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/assets/images/konn3ct_logo.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/images/konn3ct_logo.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/assets/images/konn3ct_logo.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/images/konn3ct_logo.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/images/konn3ct_logo.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/images/konn3ct_logo.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />

    <!-- Tile for Win8 -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/manifest/k512.png">


    <script type="text/javascript">
        var deviceDetect = navigator.platform;
        var appleDevicesArr = ['MacIntel', 'MacPPC', 'Mac68K', 'Macintosh', 'iPhone',
            'iPod', 'iPad', 'iPhone Simulator', 'iPod Simulator', 'iPad Simulator', 'Pike v7.6 release 92', 'Pike v7.8 release 517'];

        // If on Apple device
        if(appleDevicesArr.includes(deviceDetect)) {
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


    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
    <!-- CSS here -->
    <link rel="stylesheet" href="/assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/animate.min.css">
    <link rel="stylesheet" href="/assets/css/magnific-popup.min.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/dripicons.min.css">
    <link rel="stylesheet" href="/assets/css/slick.min.css">
    <link rel="stylesheet" href="/assets/css/default.min.css">
    <link rel="stylesheet" href="/assets/css/style.min.css">
    <link rel="stylesheet" href="/assets/css/responsive.min.css">

    <style>
        .more {display: none;}
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
            position:fixed;
            width:120px;
            height:50px;
            bottom:190px;
            right:40px;
            background-color:#35ac39;
            color:#FFF;
            border-radius:30px;
            text-align:center;
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

        a.su{
            color: #042c69;
        }
        a.su:hover{
            color: green;
        }

        button.su{
            background-color: #042c69;
        }
        button.su:hover{
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

        .lih{
            font-weight: bolder;
            font-size: 14px
        }

    </style>

    <style>
        #container2 {
            position:fixed;
            width:60px;
            height:130px;
            bottom:140px;
            left:20px;
        }
        #container {
            position:fixed;
            width:60px;
            height:60px;
            bottom:140px;
            left:20px;
        }
        #item {
            background-color: transparent;
            border-radius: 50%;
            touch-action: none;
            user-select: none;
        }
        #item:active {
            background-color: rgba(168, 218, 220, 1.00);
        }
        #item:hover {
            cursor: move;
            border-width: 20px;
        }
    </style>

    <style>
        /* width */
        ::-webkit-scrollbar {
            width: 20px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px grey;
            border-radius: 10px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #1696e7;
            border-radius: 10px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #35ac39;
        }
        .previous {
            background-color: #f1f1f1;
            color: black;
        }

        .next {
            background-color: #4CAF50;
            color: white;
        }
    </style>

    <!-- Start of Async Drift Code -->
{{--    <script>--}}
{{--        "use strict";--}}

{{--        !function() {--}}
{{--            var t = window.driftt = window.drift = window.driftt || [];--}}
{{--            if (!t.init) {--}}
{{--                if (t.invoked) return void (window.console && console.error && console.error("Drift snippet included twice."));--}}
{{--                t.invoked = !0, t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ],--}}
{{--                    t.factory = function(e) {--}}
{{--                        return function() {--}}
{{--                            var n = Array.prototype.slice.call(arguments);--}}
{{--                            return n.unshift(e), t.push(n), t;--}}
{{--                        };--}}
{{--                    }, t.methods.forEach(function(e) {--}}
{{--                    t[e] = t.factory(e);--}}
{{--                }), t.load = function(t) {--}}
{{--                    var e = 3e5, n = Math.ceil(new Date() / e) * e, o = document.createElement("script");--}}
{{--                    o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";--}}
{{--                    var i = document.getElementsByTagName("script")[0];--}}
{{--                    i.parentNode.insertBefore(o, i);--}}
{{--                };--}}
{{--            }--}}
{{--        }();--}}
{{--        drift.SNIPPET_VERSION = '0.3.1';--}}
{{--        drift.load('9u4f4f3mumcc');--}}
{{--    </script>--}}
<!-- End of Async Drift Code -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-196433825-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-196433825-1');
    </script>

</head>
<body>
<!-- header -->
<header class="header-area">
    <div id="header-sticky" class="menu-area" style="background-color: whitesmoke; margin-top: -5px">
        <div class="container">
            <div class="second-menu">
                <div class="row align-items-center">
                    <div class="col-lg-11 text-center mt-3">
                        <div class="logo">
                            <button class="previous pull-left" style="" aria-label="Go back" onclick="history.back()"><i class="fa fa-arrow-left"></i></button>

                                <a href="{{route('welcome')}}" aria-label="Go to welcome page" class="hidden-xs-down"><img class="text-center" src="/assets/images/konn3ct_logo.png" alt="Konn3ct logo" width="150" height="50px"></a>

                                <a class="hidden-lg-up hidden-sm-up hidden-xl-up" href="{{route('welcome')}}" aria-label="Go to welcome page"><img class="text-center" src="/assets/images/konn3ct_logo.png" alt="Konn3ct logo" width="150" height="30px"></a>

                            {{--                                                <img src="/assets/images/konn3ct_logo.png" height="100px" width="300px" alt="logo">--}}
                            <button class="previous pull-right" aria-label="Go forward" style="margin-right: 20px" onclick="history.go(1)"><i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>

                    <div class="col-xl-10 col-lg-11">
                        <div class="responsive"><i class="icon dripicons-align-right"></i></div>
                        <div class="main-menu text-right text-xl-center">
                            <nav id="mobile-menu">
                                <ul>
                                    <li><a href="/features" aria-label="Navigate to Features"><span class="lih">Features</span></a></li>
                                    <li><a href="/pricing" aria-label="Navigate to Plans & Pricing"><span class="lih">Plans & Pricing</span></a></li>
                                    <li><a href="/contact" aria-label="Navigate to Contact Us"><span class="lih">Contact Us</span></a></li>

                                    @auth
                                        <li><a href="/room" aria-label="Navigate to Dashboard"><span class="lih">Dashboard</span></a></li>
                                    @else
                                        <li><a href="/register" aria-label="Navigate to Create Account" class="su"><strong>Register (It's free - Start Free Trial)</strong></a></li>
                                    @endif

                                    <li><a href="/joinsession" aria-label="Navigate to Join a room"><span class="lih">Join a Meeting Room</span></a></li>

                                    @auth
                                        <li><a href="/logouts" aria-label="Signout your account"><span class="lih">Signout</span></a></li>
                                    @else
                                        <li><a href="{{ route('login') }}" aria-label="Navigate to login page"><span class="lih">Sign In</span></a></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-2 text-right d-none d-xl-block">
                        <div class="header-btn second-header-btn">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" aria-label="Navigate to dashboard page" class="btn">Meeting Room</a>
                                @else
                                    {{--                                    <a href="{{ route('login') }}" class="btn">Sign in</a>--}}
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" aria-label="Navigate to Create Account" class="btn">Register</a>
                                    @endif
                                @endif
                            @endif

                            {{--                            <a href="#" class="btn">Get a Quote</a>--}}
                            <div class="col-12 text-right" id="google_translate_element"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header-end -->


@yield("content")

<!-- footer -->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="section-title text-center pl-40 pr-40 mb-20 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
                <h3 data-animation="fadeInUp" data-delay=".4s">Watch these <strong>"HOW TO VIDEOS"</strong> to learn more<br/></h3>
            </div>
        </div>
    </div>
    <div class="row mb-10">
        <div class="col-lg-3 col-md-6">
            <div class="s-single-services active wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                <div class="second-services-content">
                    <h5>How to Register on konn3ct</h5>
                    <p><a href="https://www.youtube.com/watch?v=jEV7vjngo4g" aria-label="how to register on konn3ct">Watch Now</a></p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="s-single-services active wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                <div class="second-services-content">
                    <h5>How to Create Meeting Room</h5>
                    <p><a href="https://www.youtube.com/watch?v=Dn323U-br5Q" aria-label="how to create meeting room">Watch Now</a></p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="s-single-services active wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                <div class="second-services-content">
                    <h5>How to Join Meeting Room</h5>
                    <p><a href="https://www.youtube.com/watch?v=mLoHB9cltWs" aria-label="how to join meeting room">Watch Now</a></p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="s-single-services active wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                <div class="second-services-content">
                    <h5>How to Manage Meeting Room</h5>
                    <p><a href="https://www.youtube.com/watch?v=eCblbRoL4gs" aria-label="how to manage meeting room">Watch Now</a></p>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="footer-bg footer-p">
    <div class="copyright-wrap">
        <div class="container">

            <div class="row hidden-xs-down">
                <div class="col-6">
                    <div class="copyright-text">
                        <p>&copy; {{date('Y')}} Newwaves Ecosystem Limited</p>
                    </div>
                </div>
                <div class="col-6 font-size-8">
                    <div class="copyright-text">
                        <p>Terms of Service | <a href="/docs/EU_GDPR_Full_Text_EN.pdf" aria-label="GDPR Document">GDPR</a> | <a aria-label="NDPR Document" href="/docs/Nigeria Data Protection Regulation 2019 Implementation Framework.pdf">NDPR</a> | <a aria-label="Privacy Policy" href="/docs/DATAPRIVACY.pdf"> Privacy & Data Protection </a> | <a aria-label="cookers policy" href="/docs/COOKIESPOLICY.pdf">Cookies policy</a></p>
                    </div>
                </div>
            </div>

            <div class="row hidden-lg-up hidden-sm-up hidden-xl-up">
                <div class="col-4">
                    <div class="copyright-text">
                        <p style="font-size: smaller">&copy; {{date('Y')}} Newwaves Ecosystem Limited</p>
                    </div>
                </div>
                <div class="col-8 font-size-5">
                    <div class="copyright-text">
                        <p style="font-size: smaller">Terms of Service | <a href="/docs/EU_GDPR_Full_Text_EN.pdf" aria-label="GDPR Document">GDPR</a> | <a aria-label="NDPR Document" href="/docs/Nigeria Data Protection Regulation 2019 Implementation Framework.pdf">NDPR</a> | <a aria-label="Privacy Policy" href="/docs/DATAPRIVACY.pdf"> Privacy & Data Protection </a> | <a aria-label="cookers policy" href="/docs/COOKIESPOLICY.pdf">Cookies policy</a></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</footer>
<!-- footer-end -->

@if(!\Illuminate\Support\Facades\Auth::user())
    <div id="outerContainer">
        <div id="container" data-toggle="tooltip" data-placement="top" title="Register on konn3ct">
            <a href="/register" aria-label="Register Link">
                <img id="item" src="/assets/img/register.webp" alt="Register Image" width="60px" height="60px"/>
            </a>
        </div>
    </div>
@endif

{{--<div id="outerContainer">--}}
{{--    <div id="container2" data-toggle="tooltip" data-placement="top" title="Join a meeting room">--}}
{{--        <a href="/joinsession" aria-label="Join Meeting">--}}
{{--            <img id="item2" src="/assets/img/jmeeting.png" alt="Register Image" width="60px" height="60px"/>--}}
{{--        </a>--}}
{{--    </div>--}}
{{--</div>--}}


<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />

<!-- Scripts -->
<script rel="noopener" src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>



<script rel="noopener" src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
<script>
    window.cookieconsent.initialise({
        "palette": {
            "popup": {
                "background": "#216942",
                "text": "#b2d192"
            },
            "button": {
                "background": "#afed71"
            }
        },
        "theme": "edgeless",
        "content": {
            "message": "Cookies help us deliver our services. By using our services, you agree to our use of cookies.",
            "dismiss": "I Agree!",
            "href": "{{url('/docs/COOKIESPOLICY.pdf')}}"
        }
    });
</script>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.VERTICAL}, 'google_translate_element');
    }
</script>

<script type="text/javascript" rel="noopener" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<!-- JS here -->
<script src="/assets/js/vendor/modernizr-3.5.0.min.js"></script>
<script src="/assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/bootstrap-5/js/bootstrap.min.js"></script>
<script src="/assets/js/one-page-nav-min.js"></script>
<script src="/assets/js/slick.min.js"></script>
<script src="/assets/js/ajax-form.min.js"></script>
<script src="/assets/js/paroller.min.js"></script>
<script src="/assets/js/wow.min.js"></script>
<script src="/assets/js/js_isotope.pkgd.min.js"></script>
<script src="/assets/js/imagesloaded.min.js"></script>
<script src="/assets/js/parallax.min.js"></script>
<script src="/assets/js/jquery.waypoints.min.js"></script>
<script src="/assets/js/jquery.counterup.min.js"></script>
<script src="/assets/js/jquery.scrollUp.min.js"></script>
<script src="/assets/js/parallax-scroll.min.js"></script>
<script src="/assets/js/jquery.magnific-popup.min.js"></script>
<script src="/assets/js/element-in-view.min.js"></script>
<script src="/assets/js/main.min.js"></script>

<script>
    var dragItem = document.querySelector("#item");
    var container = document.querySelector("#container");

    var active = false;
    var currentX;
    var currentY;
    var initialX;
    var initialY;
    var xOffset = 0;
    var yOffset = 0;

    container.addEventListener("touchstart", dragStart, false);
    container.addEventListener("touchend", dragEnd, false);
    container.addEventListener("touchmove", drag, false);

    container.addEventListener("mousedown", dragStart, false);
    container.addEventListener("mouseup", dragEnd, false);
    container.addEventListener("mousemove", drag, false);

    function dragStart(e) {
        if (e.type === "touchstart") {
            initialX = e.touches[0].clientX - xOffset;
            initialY = e.touches[0].clientY - yOffset;
        } else {
            initialX = e.clientX - xOffset;
            initialY = e.clientY - yOffset;
        }

        if (e.target === dragItem) {
            active = true;
        }
    }

    function dragEnd(e) {
        initialX = currentX;
        initialY = currentY;

        active = false;
    }

    function drag(e) {
        if (active) {

            e.preventDefault();

            if (e.type === "touchmove") {
                currentX = e.touches[0].clientX - initialX;
                currentY = e.touches[0].clientY - initialY;
            } else {
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;
            }

            xOffset = currentX;
            yOffset = currentY;

            setTranslate(currentX, currentY, dragItem);
        }
    }

    function setTranslate(xPos, yPos, el) {
        el.style.transform = "translate3d(" + xPos + "px, " + yPos + "px, 0)";
    }
</script>

</body>

</html>
