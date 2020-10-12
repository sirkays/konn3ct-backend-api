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
    <script src="https://checkout.flutterwave.com/v3.js"></script>



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
            </nav>
        </div>
    </header>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-wrapper-before"></div>
        <div class="container-full">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xl-4">
                        <div class="box">
                            <div class="box-body d-flex p-0">
                                <div class="flex-grow-1 bg-info px-30 pt-50 pb-100 flex-grow-1 bg-img min-h-350" style="background-position: right bottom; background-size: 40% auto; background-image: url(https://www.multipurposethemes.com/admin/adminto-template/images/svg-icon/color-svg/custom-6.svg)">
                                    <h1 class="font-weight-600">Why konn3ct ?</h1>
{{--                                    <p class="py-15 font-size-16">--}}
{{--                                        Offering discounts for better<br>--}}
{{--                                        online a store can loyalty<br>--}}
{{--                                        weapon into driving--}}
{{--                                    </p>--}}
                                    <div>
                                        <ul>
                                            <li>
                                                <i class="icon dripicons-checkmark"></i>
                                                <span>Excellent User Experience​</span>
                                            </li>
                                            <li>
                                                <i class="icon dripicons-checkmark"></i>
                                                <span>5 Secs Meeting Setup​</span>
                                            </li>
                                            <li>
                                                <i class="icon dripicons-checkmark"></i>
                                                <span>HD Audio & Video​</span>
                                            </li>
                                            <li>
                                                <i class="icon dripicons-checkmark"></i>
                                                <span>Full-Featured Plans​​</span>
                                            </li>
                                            <li>
                                                <i class="icon dripicons-checkmark"></i>
                                                <span>Affordable & Simple Plans​​​</span>
                                            </li>
                                            <li>
                                                <i class="icon dripicons-checkmark"></i>
                                                <span>Up To 20,000 Participants <br/>per Unlimited Session​</span>
                                            </li>
                                        </ul>
                                    </div>
{{--                                    <button type="button" onClick="makePayment()" class="btn btn-info-light">Pay Now</button>--}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8" style="align-content: center">
                        <div class="box">
                            <div class="box-body d-flex p-0">
                                <div class="flex-grow-1 bg-warning-light p-30 flex-grow-1 bg-img" style="background-position: left bottom; background-size: auto 100%; background-image: url(https://www.multipurposethemes.com/admin/adminto-template/images/svg-icon/color-svg/custom-2.svg)">
                                    <div class="row">
                                        <div class="col-12 col-xl-5"></div>
                                        <div class="col-12 col-xl-7">
                                            <h4 class="text-warning font-weight-600">Join Us now</h4>

                                            <p class="text-dark my-10 font-size-16">
                                                Kindly click on the button below to make payment and enjoy your plan in loyalty
                                            </p>
                                            <button type="button" onClick="makePayment()" class="btn btn-success">Pay Now</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
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

</body>

</html>


<script>
    function makePayment() {
        FlutterwaveCheckout({
            public_key: "{{env('RAVE_PUB_KEY')}}",
            tx_ref: "konn3ct_{{rand().time()}}",
            amount: @if(\Illuminate\Support\Facades\Auth::user()->plan==2) 11 @elseif(\Illuminate\Support\Facades\Auth::user()->plan==3) 16 @endif,
            currency: "USD",
            country: "NG",
            payment_options: "card, mobilemoneyghana, ussd",
            customer: {
                email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
                phone_number: "{{\Illuminate\Support\Facades\Auth::user()->phone}}",
                name: "{{\Illuminate\Support\Facades\Auth::user()->name}}",
            },
            callback: function (data) {
                console.log(data);
                window.location.href = "/payment/"+data.transaction_id;
            },
            onclose: function() {
                // close modal
                window.location.href = "/payment/3456789";
            },
            customizations: {
                title: "Konn3ct Plan",
                description: "Payment for Konn3ct plan",
                logo: "https://assets.piedpiper.com/logo.png",
            },
        });
    }
</script>
