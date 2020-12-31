<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Konn3ct Invite </title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="https://konn3ct.com/user_assets/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="https://konn3ct.com/user_assets/css/style.css">
    <link rel="stylesheet" href="https://konn3ct.com/user_assets/css/skin_color.css">

</head>

<body class="hold-transition theme-primary bg-img" style="background-image: url(https://konn3ct.com/user_assets/images/gallery/bg-1.jpg)">

<div class="container h-p100">
    <div class="row align-items-center justify-content-md-center h-p100">

        <div class="col-12">
            <div class="row justify-content-center no-gutters">
                <div class="col-lg-5 col-md-5 col-12">
                    <div class="bg-white rounded30 shadow-lg">
                        <div class="content-top-agile p-20 pb-0 text-left">
                            <h2 class="mb-25">Hello, <br/></h2>
                            <p class="mb-10 mt-15">You have been invited by {{$ihost??''}} to attend {{$iroom??''}} scheduled as follows: <br />
                                <strong>
                                    Date: {{$idate??''}}<br/>
                                    Time: {{$itime??''}}
                                </strong>
                                <br/>
                                <br/>
                            </p>
                            <p class="mb-5 mt-lg-10">Copy this link <span class="text-bold text-dark"> {{$ilink??'sammy'}}</span> and paste in your browser to join or click on the button below</p>
                                <div class="text-center">
                                <a class="btn btn-primary" href="{{$ilink??'sammy'}}">{{$ilink??'sammy'}}</a>
                            </div>

                            <p class="mb-15 mt-10">Thank you.</p>
                            <p class="mb-4">You received this mail because you were invited by a user on konn3ct<br/>
                                Visit https://konn3ct.com<br/>
                                ...Amazing Virtual Experience<br/><br/></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Vendor JS -->
<script src="https://konn3ct.com/js/vendors.min.js"></script>
<script src="https://konn3ct.com/assets/icons/feather-icons/feather.min.js"></script>

</body>

</html>
