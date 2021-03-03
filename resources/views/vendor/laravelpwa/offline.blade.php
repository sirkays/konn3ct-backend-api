<html>
<head>
<style>
    @import url('https://fonts.googleapis.com/css?family=Rubik&display=swap');

    body {
        font-family: 'Rubik', sans-serif;
        background: linear-gradient(180deg, rgb(255, 166, 166), #fff);
        background-repeat: no-repeat
    }

    .container {
        margin-top: 100px !important
    }

    h3 {
        color: #9E9E9E;
        font-size: calc(20px + 6 * ((100vw - 320px) / 680))
    }

    button:focus {
        box-shadow: none !important;
        outline-width: 0
    }

    .card {
        border-radius: 0;
        width: calc(475px + 10 * ((100vw - 320px) / 680));
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.8)
    }

    .card-header {
        background-color: #2A7D05 !important;
        color: #fff
    }

    img {
        width: 180px !important
    }

    .btn-primary {
        background: #2A7D05;
        color: #fff !important;
        border-radius: 0 !important;
        letter-spacing: 1px
    }

    .btn-primary:hover {
        background: #042c69
    }

    .btn-primary:focus {
        background: #042c69 !important
    }

    .btn-success {
        border: 1px solid #2A7D05;
        padding: 8px 20px 8px 20px !important;
        border-radius: 20px !important
    }

    .btn-success:hover {
        background: #2A7D05;
        color: #fff !important;
        border-color: #B52AB4 !important
    }

    .btn-success:focus {
        background: #042c69 !important;
        color: #fff !important
    }

    .btn-success {
        background: #2A7D05 !important
    }

    .inner li {
        list-style-type: disc !important
    }

    .fa-times-circle {
        vertical-align: middle !important;
        cursor: pointer !important
    }

    @media (max-width: 654px) {
        .card {
            width: unset
        }
    }
</style>
</head>

<body class="text-center">

<div class="container text-center my-5 d-flex justify-content-center">
    <div class="row text-center justify-content-center ">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 bg-white border-0 text-center px-sm-4">
                    <h6 class="text-left mt-4 font-weight-bold mb-0"><span><i class="fa fa-times-circle fa-2x mr-3 " aria-hidden="true"></i> </span> No internet connection</h6> <span class="img-1 text-center"><img src="https://i.imgur.com/cGXM38s.png" class="img-fluid my-4 " /></span>
                </div>
                <div class="card-body px-sm-4 mb-3">
                    <ul class="list-unstyled text-muted">
                        <li>Please re-connect to the internet to continue use Footsteps.</li>
                        <li>If you encounter problems:</li>
                        <ul class="mt-2 inner">
                            <li>Try restarting wireless connection on this device.</li>
                            <li>Move clouse to your wireless access point.</li>
                        </ul>
                    </ul>
                    <div class="row justify-content-end mt-4 ">
                        <div class="col-auto"><button type="button" class="btn btn-success"><span>Try Again</span></button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
