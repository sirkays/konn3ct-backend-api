<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>Naira2Dollar: Home Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="email" content="">
    <meta name="website" content="">
    <meta name="Version" content="v3.2.0">
    <!-- favicon -->
    <link rel="shortcut icon" href="">
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

    <style></style>
    <style type="text/css" id="#jarallax-clip-0">#jarallax-container-0 {
            clip: rect(0 1349px 673px 0);
            clip: rect(0, 1349px, 673px, 0);
        }</style>
</head>

<body>
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

<!-- TAGLINE START-->
<div class="tagline">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="text-slider">
                    <ul class="list-inline mb-0 move-text">
                        <li class="list-inline-item px-2 mb-0">
                            <script type="text/javascript">
                                baseUrl = "https://widgets.cryptocompare.com/";
                                var scripts = document.getElementsByTagName("script");
                                var embedder = scripts[scripts.length - 1];
                                var cccTheme = {"General": {"enableMarquee": true}};
                                (function () {
                                    var appName = encodeURIComponent(window.location.hostname);
                                    if (appName == "") {
                                        appName = "local";
                                    }
                                    var s = document.createElement("script");
                                    s.type = "text/javascript";
                                    s.async = true;
                                    var theUrl = baseUrl + 'serve/v3/coin/header?fsyms=BTC,ETH,LTC,XRP,ADA&tsyms=USD,EUR,GBP';
                                    s.src = theUrl + (theUrl.indexOf("?") >= 0 ? "&" : "?") + "app=" + appName;
                                    embedder.parentNode.appendChild(s);
                                })();
                            </script>
                        </li>
                    </ul>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</div><!--end tagline-->
<!-- TAGLINE END-->

<!-- Navbar STart -->
<header id="topnav" class="defaultscroll sticky tagline-height">
    <div class="container">
        <!-- Logo container-->
        <a class="logo" href="{{route('new-homepage')}}">
                    <span class="logo-light-mode">
                        <img src="/assets/images/konn3ct_logo@2x.png" class="l-dark" height="45" alt="">
                        <img src="/assets/images/konn3ct_logo@2x.png" class="l-light" height="45" alt="">
                    </span>
            <img src="/assets/images/konn3ct_logo@2x.png" height="45" class="logo-dark-mode" alt="">
        </a>
        <div class="buy-button">
            <a href="@auth {{route('room')}} @else {{route('register')}} @endif" target="_blank">
                <div class="btn btn-success login-btn-primary">@auth Meeting Room @else Register @endif</div>
                <div class="btn btn-success login-btn-light">@auth Meeting Room @else Register @endif</div>
            </a>
        </div><!--end login button-->
        <!-- End Logo container-->
        <div class="menu-extras">
            <div class="menu-item">
                <!-- Mobile menu toggle-->
                <a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </div>
        </div>

        <div id="navigation">
            <!-- Navigation Menu-->
            <ul class="navigation-menu nav-light">
                <li class="slant">
                    <a href="" class="sub-menu-item">Solutions</a>
                </li>
                <li class="slant">
                    <a href="#">Contact sales</a>
                </li>
                <li class="slant">
                    <a href="#">Plans & Pricing</a>
                </li>
                <li class="slant">
                    <a href="#">Join a Meeting Room</a>
                </li>
                <li class="slant">
                    <a href="#">advertise</a>
                </li>
                <li class="">
                    <a href="#">contact us</a>
                </li>

            </ul><!--end navigation menu-->
            <div class="buy-menu-btn d-none">
                <a href="#" target="_blank" class="btn btn-success">LOGIN</a>
            </div><!--end login button-->
        </div><!--end navigation-->
    </div><!--end container-->
</header><!--end header-->
<!-- Navbar End -->

<!-- Start Hero -->
<section class="background-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-6 align-self-center" style="color: white;">
                <h2 style="font-weight: bolder; font-size: 69px">
                    Konn3ct
                </h2>

                <h4 class="mt-3">
                    Meet, chat, and collaborate<br/>
                    in just one place.
                </h4>

                <div class="row mt-5">
                    <div class="col-12">
                        <a href="{{route('new-signup')}}" type="button" class="btn px-3 py-3 mr-3 mt-2"
                           style="border-radius: 30px; background-color: #012E89; color: white; font-weight: bolder">
                            Start Free Trial
                        </a>
                        &nbsp;
                        <a href="{{route('login')}}" type="button" class="btn px-3 py-3 ml-3 mt-2"
                           style="border-radius: 30px; background-color: white; color: black; font-weight: bolder">
                            Host a meeting
                        </a>
                    </div>
                </div>

                <div class="mt-5">
                    <i class="fa fa-arrow-down"> </i> <a href="#unique" style="text-decoration: none; color: white">
                        Scroll to explore</a>
                </div>

            </div>

            <div class="col-md-12 col-lg-6">
                <img src="/assets/images/front1@2x.png" class="img col-12" alt="pix"/>
            </div>
            <!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section><!--end section-->
<!-- End Hero -->

<!-- Start -->
<section class="mt-5 pt-4 px-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-4 col-12"></div><!--end col-->
            <div class="col-lg-6 col-md-4 col-6 rotating-banner">
                <h5 class=""><u>PLACE YOUR 728X90 BANNER HERE!</u></h5>
                <P>in front of thousands of visitors<br>
                    Price: $50/weekly</p>
            </div><!--end col-->
            <div class="col-lg-2 col-md-4 col-12"></div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section><!--end section-->
<!-- End -->

<!-- Start -->
<section class="section ourrate">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-4 pb-2">
                    <h4 class="title mb-4">Exchange Rates</h4>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 mt-4 pt-2">
                <div class="table-responsive bg-white shadow-md rounded-md">
                    <table class="table mb-0 table-center">
                        <thead>
                        <tr>
                            <th scope="col" class="border-bottom" style="width: 10%;">#</th>
                            <th scope="col" class="border-bottom" style="min-width: 70%;">Name</th>
                            <th scope="col" class="border-bottom" style="width: 10%;">Buy</th>
                            <th scope="col" class="border-bottom" style="width: 10%;">Sell</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr>
                            <td>1</td>
                            <th>
                                <div class="align-items-center">
                                    <img src="images/bitcoin.png" class="me-3" height="50" alt="">
                                    <p class="mb-0 d-inline fw-normal h5">Bitcoin</p>
                                </div>
                            </th>
                            <td>&#8358;430</td>
                            <td>&#8358;400</td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <th>
                                <div class="align-items-center">
                                    <img src="images/perfectmoney.png" class="me-3" height="50" alt="">
                                    <p class="mb-0 d-inline fw-normal h5">Perfect Money</p>
                                </div>
                            </th>
                            <td>&#8358;435</td>
                            <td>&#8358;400</td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <th>
                                <div class="align-items-center">
                                    <img src="images/ethereum.png" class="me-3" height="50" alt="">
                                    <p class="mt-2 d-inline fw-normal h5">Ethereum</p>
                                </div>
                            </th>
                            <td>&#8358;420</td>
                            <td>&#8358;400</td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <th>
                                <div class="align-items-center">
                                    <img src="images/Tether.png" class="me-3" height="50" alt="">
                                    <p class="mb-0 d-inline fw-normal h5">Tether USDT</p>
                                </div>
                            </th>
                            <td>&#8358;430</td>
                            <td>&#8358;400</td>
                        </tr>

                        </tbody>
                    </table><!--end table-->
                </div>

                <div class="mt-4 pt-2 text-center" hidden>
                    <a href="javascript:void(0)" class="text-primary h6">See More <i
                            class="uil uil-angle-right-b align-middle"></i></a>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section><!--end section-->

<section class="other-services mb-5" hidden>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-4 pb-2">
                    <h4 class="title mb-4">Other Services</h4>

                </div>
            </div><!--end col-->
        </div><!--end row-->
        <div class="row justify-content-center">
            <div class="col-12">
                <table class="table table-bordered border-success table-responsive">

                    <tbody>
                    <tr>
                        <td style="width: 40%;">Verifed Perfect Money Account <br>Duration: 3 Business days</td>
                        <td style="width: 40%;">Buy a Personal Verified Perfect Money Account</td>
                        <td style="width: 20%;">
                            <p>$50</p>
                            <button class="btn btn-success">BUY</button>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%;">UK Company E-Incoporation <br>Duration: 5 Business days</td>
                        <td style="width: 40%;">Register UK Company with a license number, you will a digital company
                            documents. The company can be verified via </a target="_blank" href="
                            http://beta.companieshouse.gov.uk">http://beta.companieshouse.gov.uk</a></td>
                        <td style="width: 20%;">
                            <p>$120</p>
                            <button class="btn btn-success">BUY</button>
                        </td>
                    </tr>
                    <tr>

                    </tr>
                    </tbody>
                </table>
            </div>
        </div><!--end row-->
    </div><!--end container-->
</section>


<section class="other-services" hidden>
    <div class="container mt-100">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-4 pb-2">
                    <h4 class="title mb-4">Other Services</h4>

                </div>
            </div><!--end col-->
        </div><!--end row-->
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div>
                    <p>Verifed Perfect Money Account <br>Duration: 3 Business days</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div>
                    <p>Buy a Personal Verified Perfect Money Account</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div>
                    <p>$50</p>
                    <button class="btn btn-success">BUY</button>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div>
                    <p>UK Company E-Incoporation <br>Duration: 5 Business days</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div>
                    <p>Register UK Company with a license number, you will a digital company documents. The company can
                        be verified via </a target="_blank" href="http://beta.companieshouse.gov.uk">
                        http://beta.companieshouse.gov.uk</a></p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <div>
                    <p>$120</p>
                    <button class="btn btn-success">BUY</button>
                </div>
            </div>
        </div>

    </div><!--end container-->
</section>

<section class="bg-light">
    <div class="container mt-80 pt-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-lg-4 pb-lg-2">
                    <h4 class="title mb-4">How To Buy Or Sell</h4>

                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row" id="counter">
            <div class="col-12 col-md-6 col-xl-4 mt-4 pt-2">
                <div class="flexbox">
                    <div class="text-center">
                        <h4 class="fs-40 fw-600 text-secondary"><em>1</em></h4>
                    </div>
                    <div class="">
                        <h4 class="text-center">Register And Verify Your ID</h4>
                        <p>Sign up and go through a very simple and interactive identity verification process and start
                            trading immidiately!</p>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                <div class="flexbox gap-items-4">
                    <div class="text-center">
                        <h4 class="fs-40 fw-600 text-secondary"><em>2</em></h4>
                    </div>
                    <div class="">
                        <h4 class="text-center">Create Order</h4>
                        <p>Login to your NairaEx account, click on create new order. Select Buy or Sell order and fill
                            the amount.</p>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                <div class="flexbox gap-items-4">
                    <div class="text-center">
                        <h4 class="fs-40 fw-600 text-secondary"><em>3</em></h4>
                    </div>
                    <div class="">
                        <h4 class="text-center">Get Funded!</h4>
                        <p>Processing time is instant and automated 24/7. Once our system confirms your transaction, it
                            will be marked as “completed” and money sent immediately. </p>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section>

<section id="testimonial">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-5 text-white">
                    <small class="">AWESOME FEEDBACKS!</small>
                    <h4 class="title mb-4">What Our Users Are Saying</h4>
                </div>
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-12 col-md-6 col-xl-3"></div>
            <div class="col-12 col-md-6 col-xl-6">
                <div class="text-center text-white"><i>&rdquo;</i></div>
                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active text-white text-center">
                            <p>Swift service buying and selling. I love Naira2Dollar.</p>
                            <span>Gillis</span>
                        </div>
                        <div class="carousel-item text-white text-center">
                            <p>This is an amazing company...Naira2Dollar is exceptional in all there services. I
                                therefore, recommend Naira2Dollar to all around the globe.</p>
                            <span>Mbea Confidence</span>
                        </div>
                        <div class="carousel-item text-white text-center">
                            <p>Swift service. I received my money immediately the bitcoin was confirmed. I've been with
                                Naira2Dollar for 3 years, I've never doubted their service for a d...</p>
                            <span>James Nnaemeka</span>
                        </div>
                        <div class="carousel-item text-white text-center">
                            <p>Nice one..got the alert right in time...</p>
                            <span>opeyemi olatosi</span>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                            data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                            data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3"></div>
        </div>
    </div>
</section>


<section class="bg-light">
    <div class="container mt-80 pt-5 pb-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center mb-4 pb-2">
                    <h4 class="title mb-4">Latest News</h4>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div class="row" id="counter">
            <div class="col-12 col-md-6 col-xl-4 mt-4 pt-2">
                <div class="card" style="width: 18rem;">
                    <img src="images/1.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Bitcoin falls sharply after China signals crypto crackdown</h6>
                        <p class="mt-4">MAY 19, 2021. <span class="text-success">|</span> BY ADMIN</p>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                <div class="card" style="width: 18rem;">
                    <img src="images/2.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Bitcoin falls sharply after China signals crypto crackdown</h6>
                        <p class="mt-4">MAY 19, 2021. <span class="text-success">|</span> BY ADMIN</p>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-4 col-md-6 col-12 mt-4 pt-2">
                <div class="card" style="width: 18rem;">
                    <img src="images/4.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h6 class="card-title">Bitcoin falls sharply after China signals crypto crackdown</h6>
                        <p class="mt-4">MAY 19, 2021. <span class="text-success">|</span> BY ADMIN</p>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</section>


<!-- Footer Start -->
<footer class="footer footer-border">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                <a href="index.html" class="logo-footer">
                    <img src="images/logo-light.png" height="45" alt="">
                </a>
                <p class="mt-4">Naira2Dollar a company duly registered with CAC. You one way ticket to trading
                    cryptocurrency with juicy rates</p>
            </div><!--end col-->

            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h5 class="text-light footer-head">Legal</h5>
                <ul class="list-unstyled footer-list mt-4">
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> AML Police</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Privacy Policy</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Refund Policy</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Terms and Conditions</a>
                    </li>
                </ul>
            </div><!--end col-->

            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0 quicklink">
                <h5 class="text-light footer-head">Quick Links</h5>
                <ul class="list-unstyled footer-list mt-4">
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Home</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Contact Us</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> About Us</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Advertise</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> F.A.Q</a></li>
                    <li hidden><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Services</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Referral</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-chevron-right"></i> Blog</a></li>
                </ul>
            </div><!--end col-->

            <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <h5 class="text-light footer-head">Contact Us</h5>
                <ul class="list-unstyled footer-list mt-4">
                    <li><a href="#" class="text-foot"><i class="mdi mdi-phone"></i> 08080249194</a></li>
                    <li><a href="mailto:support@naira2dollar.ng" class="text-foot"><i class="mdi mdi-email"></i>
                            support@naira2dollar.ng</a></li>
                    <li><a href="#" class="text-foot"><i class="mdi mdi-map-marker"></i> 44 Presidential Road,
                            Enugu.</a></li>
                </ul>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</footer><!--end footer-->
<footer class="footer footer-bar">
    <div class="container text-center">
        <div class="row align-items-center">
            <div class="col-6">
                <div class="text-sm-start">
                    <p class="mb-0">©
                        <script>document.write(new Date().getFullYear())</script>
                        Naira2Dollar.
                    </p>
                </div>
            </div><!--end col-->
            <div class="col-6">
                <div class="text-sm-end">
                    <ul class="list-unstyled social-icon foot-social-icon mb-0">
                        <li class="list-inline-item"><a href="javascript:void(0)" class="rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-facebook fea icon-sm fea-social">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg>
                            </a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-instagram fea icon-sm fea-social">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                            </a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-twitter fea icon-sm fea-social">
                                    <path
                                        d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                                </svg>
                            </a></li>
                        <li class="list-inline-item"><a href="javascript:void(0)" class="rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-linkedin fea icon-sm fea-social">
                                    <path
                                        d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                    <rect x="2" y="9" width="4" height="12"></rect>
                                    <circle cx="4" cy="4" r="2"></circle>
                                </svg>
                            </a></li>
                    </ul><!--end icon-->
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</footer><!--end footer-->
<!-- Footer End -->

<!-- Back to top -->
<a href="#" onclick="topFunction()" id="back-to-top" class="btn btn-icon btn-success back-to-top"
   style="display: none;">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up icons">
        <line x1="12" y1="19" x2="12" y2="5"></line>
        <polyline points="5 12 12 5 19 12"></polyline>
    </svg>
</a>
<!-- Back to top -->

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

</body>
</html>
