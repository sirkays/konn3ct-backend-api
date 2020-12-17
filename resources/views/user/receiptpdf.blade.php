<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/assets/images/konn3cticon.ico">

    <title>Konn3ct - Home</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="/user_assets/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="/user_assets/css/horizontal-menu.css">
    <link rel="stylesheet" href="/user_assets/css/style.css">
    <link rel="stylesheet" href="/user_assets/css/skin_color.css">
    <link rel="stylesheet" href="/user_assets/assets/icons/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">
</head>

<body class="layout-top-nav light-skin theme-primary">

<div class="wrapper">

    <!-- Main content -->
            <section class="invoice printableArea">
                <div class="row">
                    <div class="col-12">
                        <div class="bb-1 clearFix">
                            <div class="text-right pb-15">
                                <a href="exportreceipt" class="btn btn-success"> <span><i class="fa fa-download"></i> Download</span> </a>
                                <button id="print2" class="btn btn-warning" type="button" onclick="window.print();"> <span><i class="fa fa-print"></i> Print</span> </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="page-header">
                            <h2 class="d-inline"><span class="font-size-30">Payment Receipt</span></h2>
                            <div class="pull-right text-right">
                                <h3>{{\Carbon\Carbon::parse($payment->date)->toFormattedDateString()}}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row invoice-info">
                    <div class="col-md-6 invoice-col">
                        <strong>From</strong>
                        <address>
                            <strong class="text-blue font-size-24">Newwaves Ecosystem Limited - konn3ct</strong><br>
                            <strong class="d-inline">220B, Eti-Osa Way, Ikoyi, Lagos, Nigeria</strong><br>
                            <strong>Phone: (234) 803 304 6408 <br />Email: info@newwavesecosystem.com</strong>
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6 invoice-col text-right">
                        <strong>To</strong>
                        <address>
                            <strong class="text-blue font-size-24">{{\Illuminate\Support\Facades\Auth::user()->name}}</strong><br>
{{--                            124 Lorem Ipsum, Suite 478, Dummuy, USA 123456<br>--}}
                            <strong>Phone: {{\Illuminate\Support\Facades\Auth::user()->phone}} <br />Email: {{\Illuminate\Support\Facades\Auth::user()->email}}</strong>
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-12 invoice-col mb-15">
                        <div class="invoice-details row no-margin">
                            <div class="col-md-6 col-lg-3"><b>Receipt </b>#{{$payment->id}}</div>
                            <div class="col-md-6 col-lg-3"><b>Payment ID:</b> {{$payment->gateway_reference}}</div>
                            <div class="col-md-6 col-lg-3"><b>Payment Date:</b> {{\Carbon\Carbon::parse($payment->date)->toDateString()}}</div>
                            <div class="col-md-6 col-lg-3"><b>Gateway:</b> {{$payment->gateway}}</div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th class="choose-wrap">Description</th>
                                <th>Plan</th>
                                <th class="text-right">Validity</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            <tr>
                                <td>Being payment for <br/>konn3ct Plan</td>
                                <td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->plan==1) Basic
                                    @elseif(\Illuminate\Support\Facades\Auth::user()->plan==2) Lite @elseif(\Illuminate\Support\Facades\Auth::user()->plan==3) Pro @endif
                                </td>
                                <td class="text-right">{{$payment->duration}}</td>
                                <td class="text-right">{{$payment->currency}}{{$payment->amount}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12 text-right">
{{--                        <p class="lead"><b>Payment Due</b><span class="text-danger"> 14/08/2018 </span></p>--}}

                        <div>
                            <p>Sub - Total amount  :  {{$payment->currency}}{{$payment->amount}}</p>
                            <p style="text-decoration: line-through">Tax (0.75%)  :  0.00</p>
                        </div>
                        <div class="total-payment">
                            <h3><b>Total :</b> {{$payment->currency}}{{$payment->amount}}</h3>
                        </div>

                    </div>
                    <!-- /.col -->
                </div>
{{--                <div class="row no-print">--}}
{{--                    <div class="col-12">--}}
{{--                        <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </section>
            <!-- /.content -->
</div>
</body>

</html>
