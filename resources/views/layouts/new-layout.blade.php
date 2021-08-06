<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konn3ct</title>
    <meta name="description" content="Host your virtual events on konn3ct! It's Free!! Register Now!!!">
    <meta name="author" content="Newwaves Ecosystem Limited">

    <meta name="og:url" content="https://konn3ct.com">
    <meta name="og:description" content="Host your virtual events on konn3ct! It's Free!! Register Now!!!">
    <meta name="og:type" content="website">
    <meta name="og:title" content="konn3ct">
    <meta name="og:image" content="{{url('/')}}/assets/images/whiteboard.jpg">
    <meta name="og:locale" content="en_US">
    <meta name="twitter:card" content="summary_large_image">

    <meta property="og:title" content="konn3ct"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="https://konn3ct.com"/>
    <meta property="og:image" content="{{url('/')}}/assets/images/whiteboard.jpg"/>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/konn3ct.ico">
    <!-- Place favicon.ico in the root directory -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/054f0e0fe6.js" crossorigin="anonymous"></script>

    <style>
        #linkText {
            overflow: visible;
            white-space: nowrap;
            text-align: left;
            font-family: Poppins;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(1, 46, 137, 1);
        }
    </style>
</head>
<body>
<div id="main" class="container-fluid">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img class="img img-responsive" src="/assets/images/konn3ct_logo.png"
                                                  height="30" alt="Konn3ct logo"/></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
                    aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Solutions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact sales</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Plans & Pricing</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Join a Meeting Room</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Signin</a>
                        </li>
                    </ul>
                    <button class="btn btn-outline-success" type="button">Register</button>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer>
        <div class="row">
            <div class="col-4">
                <span>© 2021 konn3ct • All Rights Reserved</span>
            </div>

        </div>

        <div class="row">
            <div class="col-5">
                <span>© 2021 konn3ct • All Rights Reserved</span>
            </div>

            <div class="col-5 text-right">
                <span style="margin-right: 20px">Terms of use</span> | <span
                    style="margin-left: 20px">Privacy  Policy</span>
            </div>


        </div>
    </footer>

</div>

<!-- JS here -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>
</html>

