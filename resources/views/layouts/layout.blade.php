<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Konn3ct</title>
    <meta name="description" content="Start a meeting in 5 secs, Customize link, Enjoy HD Audio & Video in meetings for up-to 1000 students, Full-Featured Admin Controls, Multi-User Whiteboard.">
    <meta name="og:url" content="http://konn3ct.com">
    <meta name="og:description" content="Start a meeting in 5 secs, Customize link, Enjoy HD Audio & Video in meetings for up-to 1000 students, Full-Featured Admin Controls, Multi-User Whiteboard.">
    <meta name="og:type" content="website">
    <meta name="og:title" content="konn3ct">
    <meta name="og:image" content="https://konn3ct.com/assets/images/konn3ct_logo.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/konn3ct_logo.ico">
    <!-- Place favicon.ico in the root directory -->

    <link rel='manifest' href='/assets/manifest.json'>

    <!-- CSS here -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/animate.min.css">
    <link rel="stylesheet" href="/assets/css/magnific-popup.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/dripicons.css">
    <link rel="stylesheet" href="/assets/css/slick.css">
    <link rel="stylesheet" href="/assets/css/default.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">

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

        .su{
            color: blue;
        }
        .su:hover{
            color: green;
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

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />

    <script
        type="module"
        src="https://cdn.jsdelivr.net/npm/@pwabuilder/pwainstall"
    ></script>

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
</head>
<body>
<!-- header -->
<header class="header-area">
    <div id="header-sticky" class="menu-area" style="background-color: whitesmoke; margin-top: -5px">
        <div class="container">
            <div class="second-menu">
                <div class="row align-items-center">
                                        <div class="col-lg-11 text-center">
                                            <div class="logo">
                                                <a href="/"><img class="text-center" src="/assets/images/konn3ct_logo.png" alt="logo" height="50px"></a>
{{--                                                <img src="/assets/images/konn3ct_logo.png" height="100px" width="300px" alt="logo">--}}
                                            </div>
                                        </div>

                    <div class="col-xl-10 col-lg-11">
                        <div class="responsive"><i class="icon dripicons-align-right"></i></div>
                        <div class="main-menu text-right text-xl-center">
                            <nav id="mobile-menu">
                                <ul>
                                    <li><a href="/features"><span class="lih">Features</span></a></li>
                                    <li><a href="/pricing"><span class="lih">Plans & Pricing</span></a></li>
                                    <li><a href="/contact"><span class="lih">Contact Us</span></a></li>
                                    <li>&nbsp;</li>

                                    <li><a href="/register" class="su"><strong>Register (It's free - No card is required)</strong></a></li>
                                    <li>&nbsp;</li>

                                    <li><a href="/joinsession"><span class="lih">Join a Meeting Room</span></a></li>

                                    @auth
{{--                                        <li><a href="/room">Host a Meeting Room</a></li>--}}
                                    @else
                                        <li><a href="{{ route('login') }}"><span class="lih">Sign In</span></a></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-2 text-right d-none d-xl-block">
                        <div class="header-btn second-header-btn">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn">Meeting Room</a>
                                @else
{{--                                    <a href="{{ route('login') }}" class="btn">Sign in</a>--}}
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn">Register</a>
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
<footer class="footer-bg footer-p">
    <div class="copyright-wrap">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <div class="copyright-text">
                        <p>&copy; 2020 Newwaves Ecosystem Limited</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="copyright-text">
                        <p>Terms of Service | GDPR | NDPR | <a href="/docs/DATAPRIVACY.pdf"> Privacy & Data Protection </a> | <a href="/docs/COOKIESPOLICY.pdf">Cookies policy</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer-end -->

<!-- Scripts -->
<script type="module">

    import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';

    const el = document.createElement('pwa-update');
    document.body.appendChild(el);
</script>

<script type="module" src="/assets/pwabuilder-sw.js"> </script>

<script type="module">

    import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';

    const el = document.createElement('pwa-update');
    document.body.appendChild(el);
</script>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.VERTICAL}, 'google_translate_element');
    }
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
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

<!-- JS here -->
<script src="/assets/js/vendor/modernizr-3.5.0.min.js"></script>
<script src="/assets/js/vendor/jquery-1.12.4.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/one-page-nav-min.js"></script>
<script src="/assets/js/slick.min.js"></script>
<script src="/assets/js/ajax-form.js"></script>
<script src="/assets/js/paroller.js"></script>
<script src="/assets/js/wow.min.js"></script>
<script src="/assets/js/js_isotope.pkgd.min.js"></script>
<script src="/assets/js/imagesloaded.min.js"></script>
<script src="/assets/js/parallax.min.js"></script>
<script src="/assets/js/jquery.waypoints.min.js"></script>
<script src="/assets/js/jquery.counterup.min.js"></script>
<script src="/assets/js/jquery.scrollUp.min.js"></script>
<script src="/assets/js/parallax-scroll.js"></script>
<script src="/assets/js/jquery.magnific-popup.min.js"></script>
<script src="/assets/js/element-in-view.js"></script>
<script src="/assets/js/main.js"></script>
</body>

</html>
