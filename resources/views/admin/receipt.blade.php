@extends('layouts.admin-layout')

@section('content')

            <!-- Main content -->
            <section class="invoice printableArea">
                <div class="row">
                    <div class="col-12">
                        <div class="bb-1 clearFix">
                            <div class="text-right pb-15">
{{--                                <button class="btn btn-success" type="button"> <span><i class="fa fa-print"></i> Save</span> </button>--}}
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
                            <strong class="text-blue font-size-24">Newwaves Ecosystem Limted</strong><br>
                            <strong class="d-inline">220b, Eti-osa way, Ikoyi, Lagos, Nigeria</strong><br>
                            <strong>Phone: (234) 803 304 6408 &nbsp;&nbsp;&nbsp;&nbsp; Email: info@newwavesecosystem.com</strong>
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6 invoice-col text-right">
                        <strong>To</strong>
                        <address>
                            <strong class="text-blue font-size-24">{{$payment->firstname}}</strong><br>
{{--                            124 Lorem Ipsum, Suite 478, Dummuy, USA 123456<br>--}}
                            <strong>Phone: {{$payment->phone}} &nbsp;&nbsp;&nbsp;&nbsp; Email: {{$payment->email}}</strong>
                        </address>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-12 invoice-col mb-15">
                        <div class="invoice-details row no-margin">
                            <div class="col-md-6 col-lg-3"><b>Invoice </b>#{{$payment->id}}</div>
                            <div class="col-md-6 col-lg-3"><b>Order ID:</b> {{$payment->reference}}</div>
                            <div class="col-md-6 col-lg-3"><b>Payment Date:</b> {{\Carbon\Carbon::parse($payment->date)->toDateString()}}</div>
                            <div class="col-md-6 col-lg-3"><b>Account:</b> {{$payment->gateway}}</div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Plan</th>
                                <th class="text-right">Duration</th>
                                <th class="text-right">Amount</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Being payment for konn3ct Plan</td>
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
@endsection
