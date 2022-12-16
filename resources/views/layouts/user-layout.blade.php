<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Host your virtual events on konn3ct! It's Free!! Register Now!!!">
    <meta name="author" content="Newwaves Ecosystem Limited">
    <meta name="og:url" content="https://konn3ct.com">
    <meta name="og:description" content="Host your virtual events on konn3ct! It's Free!! Register Now!!!">
    <meta name="og:type" content="website">
    <meta name="og:title" content="konn3ct">
    <meta name="og:image" content="https://konn3ct.com/assets/images/konn3ctIcon.png">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/konn3cticon.ico">

    <title>Konn3ct - Home</title>

@include('facebook-pixel::head')

<!-- Meta Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1543717222676161');

        @if(Request::segment(1) === 'dashboard')
        fbq('track', 'Lead');
        @elseif(Request::segment(1) === 'payment' || Request::segment(1) === 'changeplan')
        fbq('track', 'addtocart');
        @elseif(Request::segment(1) === 'paymentreceipt')
        fbq('track', 'purchase');
        @else
        fbq('track', 'PageView');
        @endif
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=1543717222676161&ev=PageView&noscript=1"
        /></noscript>

    <link rel="stylesheet" href="/user_assets/css/vendors_css.css">

{{--    @laravelPWA--}}

    <!-- Style-->
    <link rel="stylesheet" href="/user_assets/css/horizontal-menu.css">
    <link rel="stylesheet" href="/user_assets/css/style.css">
    <link rel="stylesheet" href="/user_assets/css/skin_color.css">
    <link rel="stylesheet" href="/user_assets/assets/icons/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">

    <style>
        /* Style all font awesome icons */
        .myfa {
            padding: 20px;
            font-size: 20px;
            width: 50px;
            text-align: center;
            text-decoration: none;
            margin-right: 10px;
            align-content: center;
        }

        /* Add a hover effect if you want */
        .myfa:hover {
            opacity: 0.7;
        }

        /* Set a specific color for each brand */

        /* Facebook */
        .fa-facebook {
            background: #3B5998;
            color: white;
        }

        /* Twitter */
        .fa-twitter {
            background: #55ACEE;
            color: white;
        }

        .fa-linkedin {
            background: #007bb5;
            color: white;
        }

        .fa-instagram {
            background: #125688;
            color: white;
        }

        .fa-envelope-square {
            background: #dd4b39;
            color: white;
        }
    </style>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @livewireStyles

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-196433825-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-196433825-1');
    </script>

</head>

<body class="layout-top-nav light-skin theme-primary">

<div class="wrapper">

    <header class="main-header">
        <div class="inside-header">
            <div class="d-flex align-items-center logo-box justify-content-between">
                <span class="mx-10 mt-10" style="color: white;">Welcome, {{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}} </span>
                <!-- Logo -->
{{--                <a href="#" class="logo">--}}
{{--                    <!-- logo-->--}}
{{--                    <div class="logo-lg">--}}
{{--                        <span style="color: white">Welcome, {{\Illuminate\Support\Facades\Auth::user()->lastname}} {{\Illuminate\Support\Facades\Auth::user()->firstname}}</span>--}}
{{--                        <span class="light-logo"><img src="/user_assets/images/logo-light-text.png" alt="logo"></span>--}}
{{--                        <span class="dark-logo"><img src="/user_assets/images/logo-light-text.png" alt="logo"></span>--}}
{{--                    </div>--}}
{{--                </a>--}}
            </div>
            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top pl-10">
                <!-- Sidebar toggle button-->
                <div class="app-menu">
{{--                    <ul class="header-megamenu nav">--}}
{{--                        <li class="btn-group nav-item d-none d-xl-inline-block">--}}
{{--                                <a  href="/logouts" class="btn btn-danger waves-effect waves-light"><i class="fa fa-sign-out"> </i> Log Out</a>--}}
{{--                        </li>--}}

{{--                    </ul>--}}
                </div>

                <div class="navbar-custom-menu r-side">
                    <ul class="nav navbar-nav">
                        <!-- Notifications -->
                        <li style="margin-left: 2px">
                            <a href="/logouts" class="waves-effect waves-light dropdown-toggle btn-danger" style="min-width: 45px" title="Logout">
{{--                                <i class="fa fa-sign-out"></i>--}}
                                <span style="font-size: 9px;">Signout</span>
                            </a>
                        </li>

                        <li style="margin-left: 2px">
                            <a href="/" class="waves-effect waves-light dropdown-toggle btn-primary"
                               style="min-width: 45px" title="Home">
                                <span style="font-size: 11px">Home</span>
                            </a>
                        </li>

                        {{--                        <li style="margin-left: 2px">--}}
                        {{--                            <a href="#" data-toggle="modal" data-target="#modal-fill"--}}
                        {{--                               class="waves-effect waves-light dropdown-toggle btn-primary" style="min-width: 45px"--}}
                        {{--                               title="Change Plan">--}}
                        {{--                                <i class="fa fa-link"></i>--}}
                        {{--                                <span style="font-size: 11px">Plan</span>--}}
                        {{--                            </a>--}}
                        {{--                        </li>--}}

                        <li style="margin-left: 2px">
                            <a href="/" data-toggle="modal" data-target="#bs-example-modal-sm"
                               class="waves-effect waves-light dropdown-toggle btn-primary" style="min-width: 45px"
                               title="Share">
                                {{--                                <i class="fa fa-plus-circle"></i>--}}
                                <span style="font-size: 11px">Share</span>
                            </a>
                        </li>

                        {{--                        @if(\Illuminate\Support\Facades\Auth::user()->type=="admin")--}}
                        {{--                        <li style="margin-left: 5px">--}}
{{--                            <a href="/admin/rooms" class="waves-effect waves-light dropdown-toggle btn-success" title="Admin">--}}
{{--                                <i class="fa fa-user-circle"></i>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        @endif--}}

                        <li class="btn-group nav-item d-none d-xl-inline-block" style="margin-right: 20px; margin-top: 5px">
{{--                            <a href="/" class="btn btn-outline-primary">Home</a>--}}
{{--                            @if(\Illuminate\Support\Facades\Auth::user()->type=="admin")--}}
{{--                                <a href="/admin/rooms" class="btn btn-success" style="margin-left: 20px">Admin</a>--}}
{{--                            @endif--}}
{{--                            <Button class="btn btn-primary" data-toggle="modal" data-target="#modal-fill" style="margin-left: 20px">Change Plan</Button>--}}
{{--                            <Button class="btn btn-success" data-toggle="modal" data-target="#bs-example-modal-sm" style="margin-left: 20px">Invite friends</Button>--}}

                            <span style="color: white; margin-left: 20px;"> Current Plan:
                            @if(\Illuminate\Support\Facades\Auth::user()->plan==1)
                                Basic
                            @elseif(\Illuminate\Support\Facades\Auth::user()->plan==2)
                                Lite - Expires in {{\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->subscription), false)}} days
                            @else
                                Pro - @if(\Illuminate\Support\Facades\Auth::user()->status=="free_trial")Free Trial @endif Expires in {{\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->subscription), false)}} days
                            @endif
                                </span>
                        </li>
                        <li class="btn-group nav-item ">
                            <a href="#" data-provide="fullscreen" class="waves-effect waves-light nav-link rounded full-screen" title="Full Screen">
                                <i class="icon-Expand-arrows"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <nav class="main-nav" role="navigation">

        <!-- Mobile menu toggle button (hamburger/x icon) -->
        <input id="main-menu-state" type="checkbox"/>
        <label class="main-menu-btn" for="main-menu-state">
            <span class="main-menu-btn-icon"></span> Toggle main menu visibility
        </label>

        <!-- Sample menu definition -->
        <ul id="main-menu" class="sm sm-blue">
            <li><a href="{{route('rooms')}}"><i class="icon-Layout-4-blocks"><span class="path1"></span><span
                            class="path2"></span></i>Room(s)</a></li>
            <li><a href="{{route('streamList')}}"><i class="icon-Layout-4-blocks"><span class="path1"></span><span
                            class="path2"></span></i>Streamings</a></li>
            <li><a href="/joinsession"><i class="icon-Layout-4-blocks"><span class="path1"></span><span
                            class="path2"></span></i>Join a Meeting Room</a></li>
            {{--            <li><a href="#"><i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>Recording(s)</a></li>--}}
            <li><a href="/recording"><i class="icon-Layout-4-blocks"><span class="path1"></span><span
                            class="path2"></span></i>Recording(s)</a></li>
            <li><a href="/payment"><i class="icon-Incoming-mail"><span class="path1"></span><span class="path2"></span></i>Payment</a>
            </li>
            <li><a href="/profile"><i class="icon-User"><span class="path1"></span><span class="path2"></span></i>Profile</a>
            </li>
            <li><a href="/referee"><i class="icon-User"><span class="path1"></span><span class="path2"></span></i>Referee(s)</a>
            </li>
            <li><a href="/addonsubscription"><i class="icon-User"><span class="path1"></span><span class="path2"></span></i>Addons</a>
            </li>
            <li><a href="/invites"><i class="icon-User"><span class="path1"></span><span class="path2"></span></i>Konn3ct
                    Invite
                    History</a></li>
            <li><a href="{{route('apitokens')}}"><i class="icon-User"><span class="path1"></span><span
                            class="path2"></span></i>API Tokens</a></li>
            {{--            <li><a data-toggle="modal" data-target="#modal-fill"><i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>Change Plan</a></li>--}}
            {{--            <li><a data-toggle="modal" data-target="#bs-example-modal-sm"><i class="icon-Plus"><span class="path1"></span><span class="path2"></span></i>Invite friends</a></li>--}}
            @if(\Illuminate\Support\Facades\Auth::user()->type=="admin")
                <li><a href="{{route('admin.users')}}"><i class="fa fa-user-circle"><span class="path1"></span><span
                                class="path2"></span></i>Admin</a></li>

                {{--                <li style="margin-left: 5px">--}}
                {{--                    <a href="/admin/rooms" class="waves-effect waves-light dropdown-toggle btn-success" title="Admin">--}}
                {{--                        <i class="fa fa-user-circle"></i> Admin--}}
                {{--                    </a>--}}
                {{--                </li>--}}
            @endif
        </ul>
    </nav>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="container-full">
            <!-- Main content -->
            @yield('content')
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right d-none d-sm-inline-block">
            <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="javascript:void(0)">FAQ</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Subscribe Now</a>--}}
{{--                </li>--}}
            </ul>
        </div>
        &copy; {{date('Y')}} <a href="https://newwavesecosystem.com">Newwaves Ecosystem</a>. All Rights Reserved.
    </footer>

    <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->


<div class="modal fade bs-example-modal-sm" id="bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Invite Friends to Konn3ct</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <a href="https://www.facebook.com/sharer/sharer.php?u=https://konn3ct.com/register&title=Konn3ctisagoodandfreeconferencingplatform" target="_blank" class="fa fa-facebook myfa"></a>
                <a href="https://twitter.com/share?url=https://konn3ct.com/register&text=Host your virtual events on konn3ct! It's Free!! Register Now!!!.https://konn3ct.com/register.&hashtags=konn3ct" target="_blank" class="fa fa-twitter myfa"></a>
                <a href="mailto:?Subject=Register with Konn3ct&amp;Body=Host your virtual events on konn3ct! It's Free!! Register Now!!!.https://konn3ct.com/register" target="_blank" class="fa fa-envelope-square myfa"></a>
                <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=https://konn3ct.com/register" target="_blank" class="fa fa-linkedin myfa"></a>
{{--                https://twitter.com/intent/tweet?text=How%20to%20create%20social%20media%20sharing%20buttons%20on%20your%20website&url=https://blog.one.com/create-social-media-sharing-buttons-website/--}}
{{--                https://www.facebook.com/sharer.php?u=https%3A%2F%2Fblog.one.com%2Fcreate-social-media-sharing-buttons-website%2F--}}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade basicplan-modal" id="basicplan-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Warning</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                Changing plan to basic will restrict you to a room, you will also loose your recordings
            </div>
            <div class="modal-footer modal-footer-uniform">
                <button type="button" class="btn bg-success" data-dismiss="modal">Cancel</button>
                <a href="/changeplan/1" class="btn bg-danger float-right">Continue</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->



<!-- Vendor JS -->
<script src="/user_assets/js/vendors.min.js"></script>
<script src="/user_assets/assets/icons/feather-icons/feather.min.js"></script>

<script src="/user_assets/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>

@if(Request::segment(1) === 'payment' || Request::segment(1) === 'participants' || Request::segment(1) === 'referee' || Request::segment(1) === 'preregistration_participants')
<script src="/user_assets/assets/vendor_components/datatable/datatables.min.js"></script>
@endif

<!-- Adminto App -->
<script src="/user_assets/js/jquery.smartmenus.min.js"></script>
<script src="/user_assets/js/menus.min.js"></script>
<script src="/user_assets/js/template.min.js"></script>
<script src="/user_assets/js/pages/dashboard4.js"></script>

<script src="/user_assets/js/pages/data-table.js"></script>


@stack('modals')

@livewireScripts

</body>

</html>

