<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/assets/images/konn3ct_logo.ico">

    <title>Konn3ct - Home</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="/user_assets/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="/user_assets/css/horizontal-menu.css">
    <link rel="stylesheet" href="/user_assets/css/style.css">
    <link rel="stylesheet" href="/user_assets/css/skin_color.css">
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>



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
                <a href="/" class="logo">
                    <!-- logo-->
                    <div class="logo-lg">
                        <span style="color: white">Welcome, {{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}</span>
                        {{--                        <span class="light-logo"><img src="/user_assets/images/logo-light-text.png" alt="logo"></span>--}}
                        {{--                        <span class="dark-logo"><img src="/user_assets/images/logo-light-text.png" alt="logo"></span>--}}
                    </div>
                </a>
            </div>
            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top pl-10 pull-right">
                <!-- Sidebar toggle button-->
                <div class="app-menu">
                    <ul class="header-megamenu nav">

                        <li class="btn-group nav-item d-none d-xl-inline-block ml-5">
                            <a href="{{route('rooms')}}" class="btn btn-dark waves-effect waves-light"><i
                                    class="fa fa-home-lg"> </i>
                                Goto Dashboard</a>
                        </li>

                        <li class="btn-group nav-item d-none d-xl-inline-block">
                            <a href="/logouts" class="btn btn-danger waves-effect waves-light"><i
                                    class="fa fa-sign-out"> </i> Log Out</a>
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

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8" style="align-content: center">
                        <div class="box">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="box-body d-flex p-0">
                                <div class="flex-grow-1 bg-warning-light p-30 flex-grow-1 bg-img"
                                     style="background-position: left bottom; background-size: auto 100%; background-image: url(https://www.multipurposethemes.com/admin/adminto-template/images/svg-icon/color-svg/custom-2.svg)">
                                    <div class="row">
                                        <div class="col-12 col-xl-6">
                                            <h4 class="text-warning font-weight-600">Monthly</h4>

                                            <p class="text-dark my-10 font-size-16">
                                                Kindly click on the button below to make payment
                                            </p>
                                            <hr style="color: white">


                                            <div class="col-12 mb-10 mt-10">
                                                @foreach($pricing_monthly as $monthly)
                                                    <div class="card text-center">
                                                        <div class="card-body">
                                                            <div class="card-title h5">Pay
                                                                with {{strtoupper($monthly->payment_gateway)}}</div>

                                                            <p class="card-text"><font
                                                                    size="20"><b>{{$monthly->currency}} {{$monthly->price}}</b></font>
                                                            </p>
                                                            @if($monthly->currency == "NGN")
                                                                <a href="{{route('payment_paystack', $monthly->id)}}"
                                                                   class="btn btn-primary">PAY NOW</a>
                                                            @endif

                                                            @if($monthly->currency == "USD")
                                                                <a href="{{route('payment_mastercard', $monthly->id)}}"
                                                                   class="btn btn-primary">PAY NOW</a>
                                                            @endif

                                                            @if($monthly->currency == "INR")
                                                                <a href="{{route('payment_stripe', $monthly->id)}}"
                                                                   class="btn btn-primary">PAY NOW</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>


                                        </div>
                                        <div class="col-12 col-xl-6">
                                            <h4 class="text-warning font-weight-600">Yearly</h4>

                                            <p class="text-dark my-10 font-size-16">
                                                Kindly click on the button below to make payment and enjoy your plan in
                                                loyalty
                                            </p>
                                            <hr style="color: white">

                                            <div class="col-12 mb-10 mt-10">
                                                @foreach($pricing_yearly as $yearly)
                                                    <div class="card text-center">
                                                        <div class="card-body">
                                                            <div class="card-title h5">Pay
                                                                with {{strtoupper($yearly->payment_gateway)}}</div>

                                                            <p class="card-text"><font
                                                                    size="20"><b>{{$yearly->currency}} {{$yearly->price}}</b></font>
                                                            </p>
                                                            @if($yearly->currency == "NGN")
                                                                <a href="{{route('payment_paystack', $yearly->id)}}"
                                                                   class="btn btn-primary">PAY NOW</a>
                                                            @endif

                                                            @if($yearly->currency == "USD")
                                                                <a href="{{route('payment_mastercard', $yearly->id)}}"
                                                                   class="btn btn-primary">PAY NOW</a>
                                                            @endif

                                                            @if($yearly->currency == "INR")
                                                                <a href="{{route('payment_stripe', $yearly->id)}}"
                                                                   class="btn btn-primary">PAY NOW</a>
                                                            @endif


                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        {{--                                        @if(\Illuminate\Support\Facades\Auth::user()->plan!=1)--}}
                                        {{--                                            <div class="col-12 mt-25">--}}
                                        {{--                                                <button data-toggle="modal" data-target="#basicplan-modal"--}}
                                        {{--                                                        class="btn btn-danger btn-block">Can't Pay Now? Migrate--}}
                                        {{--                                                    to Free forever--}}
                                        {{--                                                </button>--}}
                                        {{--                                            </div>--}}
                                        {{--                                        @endif--}}
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mt-4">
                                                <h3>Do you have any coupon code?</h3>
                                                <form method="POST" action="{{route('apply.coupon')}}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-8">
                                                            <input class="form-control" name="code"
                                                                   placeholder="Enter coupon code (optional)"
                                                                   autocomplete="off" required>
                                                        </div>
                                                        <div class="col-4">
                                                            <button type="submit" class="btn btn-primary btn-sm">Apply
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="mt-4">
                                                <h3>Who refer you?</h3>
                                                @if(\Illuminate\Support\Facades\Auth::user()->referral != null)
                                                    <span
                                                        style="font-weight: bolder; color: black">You are referred by {{\App\Models\User::where('referral_code', \Illuminate\Support\Facades\Auth::user()->referral)->first()->lastname}}</span>
                                                @else
                                                    <form method="POST" action="{{route('addReferral')}}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-8">
                                                                <input class="form-control" name="code"
                                                                       placeholder="Enter referral code (optional)"
                                                                       required autocomplete="off"/>
                                                            </div>
                                                            <div class="col-4">
                                                                <button type="submit" class="btn btn-primary btn-sm">
                                                                    Apply
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endif
                                            </div>
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

<!-- Adminto App -->
<script src="/user_assets/js/jquery.smartmenus.min.js"></script>
<script src="/user_assets/js/menus.min.js"></script>
<script src="/user_assets/js/template.min.js"></script>
<script src="/user_assets/js/pages/dashboard4.js"></script>

</body>

</html>


<script>
    import Button from "../js/Jetstream/Button";

    function makePayment(cur) {
        @if($plan ?? false)
        if(cur=="USD") {
            FlutterwaveCheckout({
                public_key: "{{env('RAVE_PUB_KEY')}}",
                tx_ref: "konn3ct_{{rand().time()}}",
                amount: @if($plan==2) 10.99 @elseif($plan==3) 15.99 @endif,
                currency: "USD",
                country: "NG",
                payment_options: "card, mobilemoneyghana, ussd",
                customer: {
                    email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
                    phone_number: "{{\Illuminate\Support\Facades\Auth::user()->phone}}",
                    name: "{{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}",
                },
                callback: function (data) {
                    console.log(data);
                    window.location.href = "/payment/1/transid/" + data.transaction_id;
                },
                onclose: function () {
                    // close modal
                    // window.location.href = "/payment/1/transid/876212";
                },
                customizations: {
                    title: "Konn3ct @if($plan==2) Lite @elseif($plan==3) Pro @endif Plan",
                    description: "Payment for Konn3ct plan",
                    logo: "https://konn3ct.com/assets/images/konn3ctIcon.png",
                },
            });
        }else if(cur=="USD2") {
            FlutterwaveCheckout({
                public_key: "{{env('RAVE_PUB_KEY')}}",
                tx_ref: "konn3ct_{{rand().time()}}",
                amount: @if($plan==2) 120 @elseif($plan==3) 175 @endif,
                currency: "USD",
                country: "NG",
                payment_options: "card, mobilemoneyghana, ussd",
                customer: {
                    email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
                    phone_number: "{{\Illuminate\Support\Facades\Auth::user()->phone}}",
                    name: "{{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}",
                },
                callback: function (data) {
                    console.log(data);
                    window.location.href = "/payment/2/transid/" + data.transaction_id;
                },
                onclose: function () {
                    // close modal
                    // window.location.href = "/payment/2/transid/4447895";
                },
                customizations: {
                    title: "Konn3ct @if($plan==2) Lite @elseif($plan==3) Pro @endif Plan" ,
                    description: "Payment for Konn3ct plan",
                    logo: "https://konn3ct.com/assets/images/konn3ctIcon.png",
                },
            });
        }else if(cur=="NGN"){
            FlutterwaveCheckout({
                public_key: "{{env('RAVE_PUB_KEY')}}",
                tx_ref: "konn3ct_{{rand().time()}}",
                amount: @if($plan==2) 4000 @elseif($plan==3) 6000 @endif,
                currency: "NGN",
                country: "NG",
                payment_options: "card, mobilemoneyghana, ussd",
                customer: {
                    email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
                    phone_number: "{{\Illuminate\Support\Facades\Auth::user()->phone}}",
                    name: "{{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}",
                },
                callback: function (data) {
                    console.log(data);
                    window.location.href = "/payment/1/transid/" + data.transaction_id;
                },
                onclose: function () {
                    // close modal
                    @if(env("APP_ENV")=="local")
                        window.location.href = "/payment/1/transid/5585221";
                    @endif
                },
                customizations: {
                    title: "Konn3ct @if($plan==2) Lite @elseif($plan==3) Pro @endif Plan",
                    description: "Payment for Konn3ct plan",
                    logo: "https://konn3ct.com/assets/images/konn3ctIcon.png",
                },
            });
        }else{
            FlutterwaveCheckout({
                public_key: "{{env('RAVE_PUB_KEY')}}",
                tx_ref: "konn3ct_{{rand().time()}}",
                amount: @if($plan==2) 46000 @elseif($plan==3) 67000 @endif,
                currency: "NGN",
                country: "NG",
                payment_options: "card, mobilemoneyghana, ussd",
                customer: {
                    email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
                    phone_number: "{{\Illuminate\Support\Facades\Auth::user()->phone}}",
                    name: "{{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}",
                },
                callback: function (data) {
                    console.log(data);
                    window.location.href = "/payment/2/transid/" + data.transaction_id;
                },
                onclose: function () {
                    // close modal
                    // window.location.href = "/payment/2/transid/3456789";
                },
                customizations: {
                    title: "Konn3ct @if($plan==2) Lite @elseif($plan==3) Pro @endif Plan",
                    description: "Payment for Konn3ct plan",
                    logo: "https://konn3ct.com/assets/images/konn3ctIcon.png",
                },
            });
        }
        @else
        if(cur=="USD") {
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
                    name: "{{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}",
                },
                callback: function (data) {
                    console.log(data);
                    window.location.href = "/payment/1/transid/" + data.transaction_id;
                },
                onclose: function () {
                    // close modal
                    // window.location.href = "/payment/1/transid/876212";
                },
                customizations: {
                    title: "Konn3ct @if(\Illuminate\Support\Facades\Auth::user()->plan==2) Lite @else Pro @endif Plan",
                    description: "Payment for Konn3ct plan",
                    logo: "https://konn3ct.com/assets/images/konn3ctIcon.png",
                },
            });
        }else if(cur=="USD2") {
            FlutterwaveCheckout({
                public_key: "{{env('RAVE_PUB_KEY')}}",
                tx_ref: "konn3ct_{{rand().time()}}",
                amount: @if(\Illuminate\Support\Facades\Auth::user()->plan==2) 120 @elseif(\Illuminate\Support\Facades\Auth::user()->plan==3) 175 @endif,
                currency: "USD",
                country: "NG",
                payment_options: "card, mobilemoneyghana, ussd",
                customer: {
                    email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
                    phone_number: "{{\Illuminate\Support\Facades\Auth::user()->phone}}",
                    name: "{{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}",
                },
                callback: function (data) {
                    console.log(data);
                    window.location.href = "/payment/2/transid/" + data.transaction_id;
                },
                onclose: function () {
                    // close modal
                    // window.location.href = "/payment/2/transid/4447895";
                },
                customizations: {
                    title: "Konn3ct @if(\Illuminate\Support\Facades\Auth::user()->plan==2) Lite @else Pro @endif Plan",
                    description: "Payment for Konn3ct plan",
                    logo: "https://konn3ct.com/assets/images/konn3ctIcon.png",
                },
            });
        }else if(cur=="NGN"){
            FlutterwaveCheckout({
                public_key: "{{env('RAVE_PUB_KEY')}}",
                tx_ref: "konn3ct_{{rand().time()}}",
                amount: @if(\Illuminate\Support\Facades\Auth::user()->plan==2) 4000 @elseif(\Illuminate\Support\Facades\Auth::user()->plan==3) 6000 @endif,
                currency: "NGN",
                country: "NG",
                payment_options: "card, mobilemoneyghana, ussd",
                customer: {
                    email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
                    phone_number: "{{\Illuminate\Support\Facades\Auth::user()->phone}}",
                    name: "{{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}",
                },
                callback: function (data) {
                    console.log(data);
                    window.location.href = "/payment/1/transid/" + data.transaction_id;
                },
                onclose: function () {
                    // close modal
                    @if(env("APP_ENV")=="local")
                    window.location.href = "/payment/1/transid/5585221";
                    @endif
                },
                customizations: {
                    title: "Konn3ct @if(\Illuminate\Support\Facades\Auth::user()->plan==2) Lite @else Pro @endif Plan",
                    description: "Payment for Konn3ct plan",
                    logo: "https://konn3ct.com/assets/images/konn3ctIcon.png",
                },
            });
        }else{
            FlutterwaveCheckout({
                public_key: "{{env('RAVE_PUB_KEY')}}",
                tx_ref: "konn3ct_{{rand().time()}}",
                amount: @if(\Illuminate\Support\Facades\Auth::user()->plan==2) 46000 @elseif(\Illuminate\Support\Facades\Auth::user()->plan==3) 67000 @endif,
                currency: "NGN",
                country: "NG",
                payment_options: "card, mobilemoneyghana, ussd",
                customer: {
                    email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
                    phone_number: "{{\Illuminate\Support\Facades\Auth::user()->phone}}",
                    name: "{{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}",
                },
                callback: function (data) {
                    console.log(data);
                    window.location.href = "/payment/2/transid/" + data.transaction_id;
                },
                onclose: function () {
                    // close modal
                    // window.location.href = "/payment/2/transid/3456789";
                },
                customizations: {
                    title: "Konn3ct @if(\Illuminate\Support\Facades\Auth::user()->plan==2) Lite @else Pro @endif Plan",
                    description: "Payment for Konn3ct plan",
                    logo: "https://konn3ct.com/assets/images/konn3ctIcon.png",
                },
            });
        }
        @endif
    }

    export default {
        components: {Button}
    }
</script>

<script>
    function payWithPaystack(plan, planid) {
        var handler = PaystackPop.setup({
            key: "{{env('PAYSTACK_PUB_KEY')}}", // Replace with your public key
            email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
            plan: plan, // the amount value is multiplied by 100 to convert to the lowest currency unit
            currency: 'NGN', // Use GHS for Ghana Cedis or USD for US Dollars
            callback: function (response) {
                //this happens after the payment is completed successfully
                var reference = response.reference;
                // alert('Payment complete! Reference: ' + reference);
                // Make an AJAX call to your server with the reference to verify the transaction
                console.log(response);
                window.location.href = "/paystackpayment/" + planid + "/transid/" + reference;
            },
            onClose: function () {
                alert('Transaction was not completed, window closed.');
                // close modal
                // window.location.href = "/payment/2/transid/3456789";
            },
        });
        handler.openIframe();
    }
</script>

<script>
    function payWithPaystackAmount(amount, planid) {
        var handler = PaystackPop.setup({
            key: "{{env('PAYSTACK_PUB_KEY')}}", // Replace with your public key
            email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
            amount: amount * 100, // the amount value is multiplied by 100 to convert to the lowest currency unit
            currency: 'NGN', // Use GHS for Ghana Cedis or USD for US Dollars
            callback: function (response) {
                //this happens after the payment is completed successfully
                var reference = response.reference;
                // alert('Payment complete! Reference: ' + reference);
                // Make an AJAX call to your server with the reference to verify the transaction
                console.log(response);
                window.location.href = "/paystackpayment/" + planid + "/transid/" + reference;
            },
            onClose: function () {
                alert('Transaction was not completed, window closed.');
                // close modal
                // window.location.href = "/payment/2/transid/3456789";
            },
        });
        handler.openIframe();
    }
</script>
