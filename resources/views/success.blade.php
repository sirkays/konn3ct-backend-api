<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{session('roomname') ?? "Konn3ct"}} - Pre-registration</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="event, registration, konn3ct, event registration, successful" name="keywords">
    <meta content="Registration successful for {{session('roomname') ?? "Konn3ct"}}" name="description">

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
            <h1><a href="/">E<span>Xi</span>t</a></h1>
{{--            <a href="#intro" class="scrollto"><img src="img/logo.png" alt="" title=""></a>--}}
        </div>
    </div>
</header><!-- #header -->

<!--==========================
  Intro Section
============================-->
<section id="intro">
    <div class="intro-container wow fadeIn">
        <h1 class="mb-4 pb-0">Registration Successful</h1>
{{--        {{session('roomurl')}}--}}
        <p class="mb-4 pb-0">Meeting Room Link: {{url('/join/')}}/{{session('roomurl') ?? ""}} </p>
        <a href="https://www.youtube.com/watch?v=mLoHB9cltWs" class="venobox play-btn mb-4" data-vbtype="video"
           data-autoplay="true"></a>
        <a href="/register" class="about-btn scrollto">Do you want to have access to Pro version for 8 days?</a>
    </div>
</section>

<main id="main">

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

                        @foreach($faqs as $faq)
                            <li>
                                <a data-toggle="collapse" class="collapsed" href="#faq1">{{$faq->title}} <i
                                        class="fa fa-minus-circle"></i></a>
                                <div id="faq1" class="collapse" data-parent="#faq-list">
                                    <p>
                                        {{$faq->content}}
                                    </p>
                                </div>
                            </li>
                        @endforeach

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
                        <a href="https://twitter.com/konn3ctapp" class="twitter"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.facebook.com/konn3ctapp" class="facebook"><i
                                class="fa fa-facebook"></i></a>
                        <a href="https://www.instagram.com/konn3ctng" class="instagram"><i class="fa fa-instagram"></i></a>
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

<script src="/event/contactform/contactform.js"></script>

<script src="/event/js/main.js"></script>
</body>

</html>
