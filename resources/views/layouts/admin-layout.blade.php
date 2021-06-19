<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/assets/images/konn3cticon.ico">

    <title>Konn3ct - Admin</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="/user_assets/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="/user_assets/css/horizontal-menu.css">
    <link rel="stylesheet" href="/user_assets/css/style.css">
    <link rel="stylesheet" href="/user_assets/css/skin_color.css">

    <style>
        /* Style all font awesome icons */
        .myfa {
            padding: 20px;
            font-size: 30px;
            width: 50px;
            text-align: center;
            text-decoration: none;
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
                <!-- Logo -->
                <a href="/" class="logo">
                    <!-- logo-->
                    <div class="logo-lg">
                        <span style="color: white">Welcome, {{\Illuminate\Support\Facades\Auth::user()->lastname}} {{\Illuminate\Support\Facades\Auth::user()->firstname}}</span>
{{--                        <span class="light-logo"><img src="/user_assets/images/logo-light-text.png" alt="logo"></span>--}}
{{--                        <span class="dark-logo"><img src="/user_assets/images/logo-light-text.png" alt="logo"></span>--}}
                    </div>
                </a>
            </div>
            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top pl-10">
                <!-- Sidebar toggle button-->
                <div class="app-menu">
                    <ul class="header-megamenu nav">
                        <li class="btn-group nav-item d-none d-xl-inline-block">
                                <a  href="/logouts" class="btn btn-danger waves-effect waves-light"><i class="fa fa-sign-out"> </i> Log Out</a>
                        </li>

                    </ul>
                </div>

                <div class="navbar-custom-menu r-side">
                    <ul class="nav navbar-nav">
                        <li class="btn-group nav-item d-none d-xl-inline-block">
                            <a href="/" class="btn btn-outline-primary">Home</a>
                            <a href="/room" class="btn btn-success" style="margin-left: 20px">Switch to Client</a>
{{--                            <Button class="btn btn-primary" data-toggle="modal" data-target="#modal-fill" style="margin-left: 20px">Change Plan</Button>--}}
{{--                            <Button class="btn btn-success" data-toggle="modal" data-target="#bs-example-modal-sm" style="margin-left: 20px">Invite friends</Button>--}}

{{--                            <span style="color: white; margin-left: 20px"> Current Plan:--}}
{{--                            @if(\Illuminate\Support\Facades\Auth::user()->plan==1)--}}
{{--                                Basic--}}
{{--                            @elseif(\Illuminate\Support\Facades\Auth::user()->plan==2)--}}
{{--                                Lite - Expires in {{\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->subscription)->diffInDays(\Carbon\Carbon::now())}} days--}}
{{--                            @else--}}
{{--                                Pro - Expires in {{\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->subscription)->diffInDays(\Carbon\Carbon::now())}} days--}}
{{--                            @endif--}}
{{--                                </span>--}}
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
        <input id="main-menu-state" type="checkbox" />
        <label class="main-menu-btn" for="main-menu-state">
            <span class="main-menu-btn-icon"></span> Toggle main menu visibility
        </label>

        <!-- Sample menu definition -->
        <ul id="main-menu" class="sm sm-blue">
            <li><a href="{{route('admin.users')}}"><i class="icon-User"><span class="path1"></span><span
                            class="path2"></span></i>Users</a></li>
            <li><a href="{{route('admin.rooms')}}"><i class="icon-Layout-4-blocks"><span class="path1"></span><span
                            class="path2"></span></i>Rooms</a></li>
            {{--            <li><a href="#"><i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>Recording(s)</a></li>--}}
            {{--            <li><a href="/recording"><i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>Recording(s)</a></li>--}}
            {{--            <li><a href="/payment"><i class="icon-Incoming-mail"><span class="path1"></span><span class="path2"></span></i>Payment</a></li>--}}
            <li><a href="{{route('admin.recordings')}}"><i class="icon-Layout-4-blocks"><span class="path1"></span><span
                            class="path2"></span></i>Recordings</a></li>
            <li><a href="{{route('admin.meetings')}}"><i class="icon-Layout-4-blocks"><span class="path1"></span><span
                            class="path2"></span></i>Meetings</a></li>
            <li><a href="{{route('admin.payments')}}"><i class="icon-Layout-4-blocks"><span class="path1"></span><span
                            class="path2"></span></i>Payments</a></li>
            <li><a href="{{route('admin.referrals')}}"><i class="icon-Layout-4-blocks"><span class="path1"></span><span
                            class="path2"></span></i>Referrals</a></li>
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
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Subscribe Now</a>
                </li>
            </ul>
        </div>
        &copy; 2020 <a href="https://newwavesecosystem.com">Newwaves Ecosystem</a>. All Rights Reserved.
    </footer>

    <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->

<!-- Modal -->
<div class="modal modal-fill fade" data-backdrop="false" id="modal-fill" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Plan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-lg-4">
                        <div class="box box-inverse bg-gradient-primary">
                            <div class="box-body text-center">
                                <h5 class="text-uppercase text-muted">Free Plan</h5>
                                <br>
                                <p>
                                    <strong>
                                        Free Forever
                                    </strong>
                                </p>
                                <p></p>
                                <br/>

                                <hr>

                                <p><strong>Participant - </strong> 100</p>
                                <p><strong>Session Timeout - </strong> 1 hour</p>
                                <p><strong>Cloud Storage </strong> 1GB</p>
                                <p><strong>Number of Rooms</strong> 1</p>

                                <br><br>
                                @if(\Illuminate\Support\Facades\Auth::user()->plan!=1)
                                    <a class="btn btn-outline btn-white" href="/changeplan/1">Select plan</a>
                                @else
                                    <a class="btn btn-white" href="#">Current Plan</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="box card-shadowed box-inverse bg-gradient-danger">
                            <div class="box-body text-center">
                                <h5 class="text-uppercase text-muted">Lite Plan</h5>
                                <br>
                                <p>
                                    <strong>
                                    $10.99/&#x20A6;4000<sup>Monthly</sup> <br/>
                                    $120/&#x20A6;46,000<sup>Yearly</sup>
                                    </strong>
                                </p>
                                <p></p>
                                <br/>

                                <hr>
                                <p><strong>Participant - </strong> 100</p>
                                <p><strong>Session Timeout - </strong> 10 hours</p>
                                <p><strong>Cloud Storage </strong> 5 GB</p>
                                <p><strong>Number of Rooms</strong> 5</p>


                                <br><br>
                                @if(\Illuminate\Support\Facades\Auth::user()->plan!=2)
                                    <a class="btn btn-outline btn-white" href="/changeplan/2">Select Plan</a>
                                @else
                                    <a class="btn btn-dark btn-white" href="#">Current Plan</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="box box-inverse bg-gradient-success">
                            <div class="box-body text-center">
                                <h5 class="text-uppercase text-muted">Pro Plan</h5>
                                <br>
                                <p>
                                    <strong>
                                        $15.99/&#x20A6;6000<sup>Monthly</sup> <br/>
                                        $175/&#x20A6;67,000<sup>Yearly</sup>
                                    </strong>
                                </p>
                                <p></p>
                                <br/>

                                <hr>
                                <p><strong>Participant - </strong> 250</p>
                                <p><strong>Session Timeout - </strong> 24 hours</p>
                                <p><strong>Cloud Storage </strong> 15 GB</p>
                                <p><strong>Number of Rooms</strong> Unlimited</p>

                                <br><br>
                                @if(\Illuminate\Support\Facades\Auth::user()->plan!=3)
                                    <a class="btn btn-outline btn-white" href="/changeplan/3">Select plan</a>
                                @else
                                    <a class="btn btn-dark btn-white" href="#">Current Plan</a>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

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
<!-- /.modal -->



<!-- Vendor JS -->
<script src="/user_assets/js/vendors.min.js"></script>
<script src="/user_assets/assets/icons/feather-icons/feather.min.js"></script>

<script src="/user_assets/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>

<!-- Adminto App -->
<script src="/user_assets/js/jquery.smartmenus.min.js"></script>
<script src="/user_assets/js/menus.min.js"></script>
<script src="/user_assets/js/template.min.js"></script>
<script src="/user_assets/js/pages/dashboard4.js"></script>

<script src="/user_assets/js/pages/data-table.js"></script>
<script src="/user_assets/assets/vendor_components/datatable/datatables.min.js"></script>


@stack('modals')

@livewireScripts

</body>

</html>

