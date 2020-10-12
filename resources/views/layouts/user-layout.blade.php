<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://www.multipurposethemes.com/admin/adminto-template/images/favicon.ico">

    <title>Konn3ct - Home</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="/user_assets/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="/user_assets/css/horizontal-menu.css">
    <link rel="stylesheet" href="/user_assets/css/style.css">
    <link rel="stylesheet" href="/user_assets/css/skin_color.css">



    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @livewireStyles

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>

</head>

<body class="layout-top-nav light-skin theme-primary">

<div class="wrapper">

    <header class="main-header">
        <div class="inside-header">
            <div class="d-flex align-items-center logo-box justify-content-between">
                <!-- Logo -->
                <a href="index.html" class="logo">
                    <!-- logo-->
                    <div class="logo-lg">
                        <span style="color: white">Welcome, {{\Illuminate\Support\Facades\Auth::user()->name}}</span>
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
                            <a  href="#" class="btn btn-dark">PLAN -
                                @if(\Illuminate\Support\Facades\Auth::user()->plan==1)
                                Basic
                                @elseif(\Illuminate\Support\Facades\Auth::user()->plan==2)
                                Lite
                                @else
                                Pro
                                @endif
                            </a>
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
            <li><a href="/room"><i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>Room(s)</a></li>
            <li><a href="/recording"><i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>Recording(s)</a></li>
            <li><a href="/payments"><i class="icon-Incoming-mail"><span class="path1"></span><span class="path2"></span></i>Payment(s)</a></li>
            <li><a href="/profile"><i class="icon-User"><span class="path1"></span><span class="path2"></span></i>Profile</a></li>
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



<!-- Vendor JS -->
<script src="/user_assets/js/vendors.min.js"></script>
<script src="/user_assets/assets/icons/feather-icons/feather.min.js"></script>

<script src="/user_assets/assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>

<!-- Adminto App -->
<script src="/user_assets/js/jquery.smartmenus.min.js"></script>
<script src="/user_assets/js/menus.min.js"></script>
<script src="/user_assets/js/template.min.js"></script>
<script src="/user_assets/js/pages/dashboard4.js"></script>


@stack('modals')

@livewireScripts

</body>

</html>
<script>
    import Button from "../../js/Jetstream/Button";
    export default {
        components: {Button}
    }
</script>
