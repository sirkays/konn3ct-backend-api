<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Konn3ct</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <!-- Place favicon.ico in the root directory -->

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

    <!-- Start of Async Drift Code -->
    <script>
        "use strict";

        !function() {
            var t = window.driftt = window.drift = window.driftt || [];
            if (!t.init) {
                if (t.invoked) return void (window.console && console.error && console.error("Drift snippet included twice."));
                t.invoked = !0, t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ],
                    t.factory = function(e) {
                        return function() {
                            var n = Array.prototype.slice.call(arguments);
                            return n.unshift(e), t.push(n), t;
                        };
                    }, t.methods.forEach(function(e) {
                    t[e] = t.factory(e);
                }), t.load = function(t) {
                    var e = 3e5, n = Math.ceil(new Date() / e) * e, o = document.createElement("script");
                    o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + n + "/" + t + ".js";
                    var i = document.getElementsByTagName("script")[0];
                    i.parentNode.insertBefore(o, i);
                };
            }
        }();
        drift.SNIPPET_VERSION = '0.3.1';
        drift.load('9u4f4f3mumcc');
    </script>
    <!-- End of Async Drift Code -->
</head>
<body>
<!-- header -->
<header class="header-area">
    <div id="header-sticky" class="menu-area">
        <div class="container">
            <div class="second-menu">
                <div class="row align-items-center">
                    {{--                    <div class="col-xl-2 col-lg-2">--}}
                    {{--                        <div class="logo">--}}
                    {{--                            <a href="index-2.html"><img src="/assets/img/logo/logo.png" alt="logo"></a>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    <div class="col-xl-10 col-lg-11">
                        <div class="responsive"><i class="icon dripicons-align-right"></i></div>
                        <div class="main-menu text-right text-xl-center">
                            <nav id="mobile-menu">
                                <ul>
                                    <li><a href="/features">Features</a></li>
                                    <li><a href="/pricing">Plans & Pricing</a></li>
                                    <li><a href="#work">Support</a></li>

                                    <li><a href="/">
{{--                                            <img src="/assets/img/logo/logo.png" alt="logo">--}}
                                            Konn3ct Logo <br /> <span class="text-muted" style="font-size: 10px">Sign Up (It's free - No card is required)</span>
                                        </a></li>

                                    <li><a href="/joinsession">Join Session</a></li>
                                    <li><a href="#contact">Host a Session</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-2 text-right d-none d-xl-block">
                        <div class="header-btn second-header-btn">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn">Sign in</a>
                                    @if (Route::has('register'))
                                        {{--                                        <li><a href="{{ route('register') }}">Register</a></li>--}}
                                    @endif
                                @endif
                            @endif

                            {{--                            <a href="#" class="btn">Get a Quote</a>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header-end -->


@yield("content")

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
