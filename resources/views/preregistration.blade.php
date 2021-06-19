<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{$event ?? "Konn3ct"}} - Pre-registration</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicons -->
    <link href=https://konn3ct.com/assets/images/konn3ctIcon.png" rel="icon">
    <link href="https://konn3ct.com/assets/images/konn3ctIcon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800"
        rel="stylesheet">

    <!-- Bootstrap CSS File -->
    <link href="/event/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Libraries CSS Files -->
    <link href="/event/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/event/lib/animate/animate.min.css" rel="stylesheet">
    <link href="/event/lib/venobox/venobox.css" rel="stylesheet">
    <link href="/event/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Main Stylesheet File -->
    <link href="/event/css/style.css" rel="stylesheet">

</head>

<body>

<!--==========================
  Header
============================-->
<header id="header">
    <div class="container">

        <div id="logo" class="pull-left">
            <!-- Uncomment below if you prefer to use a text logo -->
            <!-- <h1><a href="#main">C<span>o</span>nf</a></h1>-->
            <a href="#intro" class="scrollto"><img src="https://konn3ct.com/assets/images/konn3ctIcon.png"
                                                   alt="konn3ct logo" title=""></a>
        </div>

        <nav id="nav-menu-container">
            <ul class="nav-menu">
                <li class="menu-active"><a href="#intro">Home</a></li>
                <li><a href="#about">About</a></li>
                <li class="buy-tickets"><a href="#register">Register Now</a></li>
            </ul>
        </nav><!-- #nav-menu-container -->
    </div>
</header><!-- #header -->

<!--==========================
  Intro Section
============================-->
<section id="intro">
    <div class="intro-container wow fadeIn">
        <h1 class="mb-4 pb-0">{{$preg->title ?? "The Konn3ct Training"}}</h1>
        <p class="mb-4 pb-0">Hosted by {{$preg->host_name}}</p>
        <a href="https://www.youtube.com/watch?v=jDDaplaOz7Q" class="venobox play-btn mb-4" data-vbtype="video"
           data-autoplay="true"></a>
        <a href="#about" class="about-btn scrollto">About The Event</a>
    </div>
</section>

<main id="main">

    <!--==========================
      About Section
    ============================-->
    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2>About The Event</h2>
                    <p>{{$preg->about}}</p>
                </div>
                <div class="col-lg-3">
                    <h3>Where</h3>
                    <p>{{url('/join/')}}/{{$room->url}}</p>
                </div>
                <div class="col-lg-3">
                    <h3>When</h3>
                    <p>{{\Carbon\Carbon::parse($preg->date)->toFormattedDateString()}}
                        <br>{{\Carbon\Carbon::parse($preg->time)->toTimeString()}} {{$preg->timezone}}</p>
                </div>
            </div>
        </div>
    </section>


    <!--==========================
      Contact Section
    ============================-->
    <section id="contact" class="section-bg wow fadeInUp">

        <div id="register" class="container">

            <div class="section-header">
                <h2>Register Now</h2>
                <p>Provide your details below to register for this event</p>
            </div>

            <div class="row contact-info">

                <div class="col-md-4">
                    <div class="contact-address">
                        <i class="ion-ios-location-outline"></i>
                        <h3>Contact Person</h3>
                        <address>{{$u->firstname}} {{$u->lastname}}</address>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="contact-phone">
                        <i class="ion-ios-telephone-outline"></i>
                        <h3>Phone Number</h3>
                        <p><a href="tel:{{$u->phone}}">{{$u->phone}}</a></p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="contact-email">
                        <i class="ion-ios-email-outline"></i>
                        <h3>Email</h3>
                        <p><a href="mailto:{{$u->email}}">{{$u->email}}</a></p>
                    </div>
                </div>

            </div>

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

            <div class="form">
                <form action="{{route('registerprereg')}}" method="post" role="form" class="contactForm">
                    @csrf
                    <input type="hidden" name="ref" class="form-control" value="{{$preg->reference}}"/>

                    <div class="form-group">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Your Name"
                               data-rule="minlen:4" data-msg="Please enter at least 4 chars"/>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Your Email"
                               data-rule="email" data-msg="Please enter a valid email"/>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Your Phone"
                               data-rule="minlen:4" data-msg="Please enter at least 8 chars of your phone digit"/>
                        <div class="validation"></div>
                    </div>
                    <div class="text-center">
                        <button type="submit">Register</button>
                    </div>
                </form>
            </div>

        </div>
    </section><!-- #contact -->

    <!--==========================
      F.A.Q Section
    ============================-->
    <section id="faq" class="wow fadeInUp">

        <div class="container">

            <div class="section-header">
                <h2>F.A.Q </h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <ul id="faq-list">

                        <li>
                            <a data-toggle="collapse" class="collapsed" href="#faq1">Non consectetur a erat nam at
                                lectus urna duis? <i class="fa fa-minus-circle"></i></a>
                            <div id="faq1" class="collapse" data-parent="#faq-list">
                                <p>
                                    Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet
                                    non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor
                                    purus non.
                                </p>
                            </div>
                        </li>

                        <li>
                            <a data-toggle="collapse" href="#faq2" class="collapsed">Feugiat scelerisque varius morbi
                                enim nunc faucibus a pellentesque? <i class="fa fa-minus-circle"></i></a>
                            <div id="faq2" class="collapse" data-parent="#faq-list">
                                <p>
                                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum
                                    velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend
                                    donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in
                                    cursus turpis massa tincidunt dui.
                                </p>
                            </div>
                        </li>

                        <li>
                            <a data-toggle="collapse" href="#faq3" class="collapsed">Dolor sit amet consectetur
                                adipiscing elit pellentesque habitant morbi? <i class="fa fa-minus-circle"></i></a>
                            <div id="faq3" class="collapse" data-parent="#faq-list">
                                <p>
                                    Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus
                                    pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit.
                                    Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis
                                    tellus. Urna molestie at elementum eu facilisis sed odio morbi quis
                                </p>
                            </div>
                        </li>

                        <li>
                            <a data-toggle="collapse" href="#faq4" class="collapsed">Ac odio tempor orci dapibus.
                                Aliquam eleifend mi in nulla? <i class="fa fa-minus-circle"></i></a>
                            <div id="faq4" class="collapse" data-parent="#faq-list">
                                <p>
                                    Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum
                                    velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend
                                    donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in
                                    cursus turpis massa tincidunt dui.
                                </p>
                            </div>
                        </li>

                        <li>
                            <a data-toggle="collapse" href="#faq5" class="collapsed">Tempus quam pellentesque nec nam
                                aliquam sem et tortor consequat? <i class="fa fa-minus-circle"></i></a>
                            <div id="faq5" class="collapse" data-parent="#faq-list">
                                <p>
                                    Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in
                                    est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl
                                    suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in
                                </p>
                            </div>
                        </li>

                        <li>
                            <a data-toggle="collapse" href="#faq6" class="collapsed">Tortor vitae purus faucibus ornare.
                                Varius vel pharetra vel turpis nunc eget lorem dolor? <i class="fa fa-minus-circle"></i></a>
                            <div id="faq6" class="collapse" data-parent="#faq-list">
                                <p>
                                    Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo
                                    integer malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc
                                    eget lorem dolor sed. Ut venenatis tellus in metus vulputate eu scelerisque.
                                    Pellentesque diam volutpat commodo sed egestas egestas fringilla phasellus faucibus.
                                    Nibh tellus molestie nunc non blandit massa enim nec.
                                </p>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

        </div>

    </section>

</main>


<!--==========================
  Footer
============================-->
<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-6 footer-info">
                    <img src="https://konn3ct.com/assets/images/konn3ctIcon.png" alt="Konn3ct">
                    <p>Konn3ct is a web audio and video conferencing platform made in Africa. Earn income by referring
                        friends. </p>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><i class="fa fa-angle-right"></i> <a href="/">Home</a></li>
                        <li><i class="fa fa-angle-right"></i> <a href="/joinsession">Join a Meeting</a></li>
                        <li><i class="fa fa-angle-right"></i> <a href="/contact">Contact us</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>HOW TO VIDEOS</h4>
                    <ul>
                        <li><i class="fa fa-angle-right"></i> <a href="https://www.youtube.com/watch?v=jEV7vjngo4g">How
                                to Register on konn3ct</a></li>
                        <li><i class="fa fa-angle-right"></i> <a href="https://www.youtube.com/watch?v=Dn323U-br5Q">How
                                to Create Meeting Room</a></li>
                        <li><i class="fa fa-angle-right"></i> <a href="https://www.youtube.com/watch?v=mLoHB9cltWs">How
                                to Join Meeting Room</a></li>
                        <li><i class="fa fa-angle-right"></i> <a href="https://www.youtube.com/watch?v=eCblbRoL4gs">How
                                to Manage Meeting Room</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-contact">
                    <h4>Contact Us</h4>
                    <p>
                        220B, Eti-Osa way <br>
                        Ikoyi, Lagos<br>
                        Nigeria <br>
                        <strong>Email:</strong> <a href="mailto:support@newwavesecosystem.com.odoo.com">support@newwavesecosystem.com.odoo.com</a><br>
                    </p>

                    <div class="social-links">
                        <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                        <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
                        <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
                        <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong>Konn3ct</strong>. All Rights Reserved
        </div>
    </div>
</footer><!-- #footer -->

<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

<!-- JavaScript Libraries -->
<script src="/event/lib/jquery/jquery.min.js"></script>
<script src="/event/lib/jquery/jquery-migrate.min.js"></script>
<script src="/event/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/event/lib/easing/easing.min.js"></script>
<script src="/event/lib/superfish/hoverIntent.js"></script>
<script src="/event/lib/superfish/superfish.min.js"></script>
<script src="/event/lib/wow/wow.min.js"></script>
<script src="/event/lib/venobox/venobox.min.js"></script>
<script src="/event/lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Contact Form JavaScript File -->
<script src="/event/contactform/contactform.js"></script>

<!-- Template Main Javascript File -->
<script src="/event/js/main.js"></script>
</body>

</html>
