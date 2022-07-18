@extends('layouts.user-layout')

@section('content')

            <!-- Main content -->
            <section class="content">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="d-flex align-items-center">
                        <div class="w-p100 d-md-flex align-items-center justify-content-between">
                            <h3 class="page-title">Payment</h3>
                            <div class="d-inline-block align-items-center">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a>
                                        </li>
                                        <li class="breadcrumb-item" aria-current="page">Payment</li>
                                        <li class="breadcrumb-item active" aria-current="page">Payment List</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-2 col-lg-3 col-12">
                        <div class="box box-inverse box-success">
                            <div class="box-body">
                                <div class="flexbox">
                                    <h5>Current Plan</h5>
                                </div>

                                <div class="text-center my-2">
                                    <div class="font-size-60">
                                        @if(\Illuminate\Support\Facades\Auth::user()->plan==1)
                                            Basic
                                        @elseif(\Illuminate\Support\Facades\Auth::user()->plan==2)
                                            Lite
                                        @else
                                            Pro
                                        @endif
                                    </div>
                                    {{--                                    <span>Total Payments</span>--}}
                                </div>
                            </div>
                        </div>


                        <div class="mt-5">
                            @if(\Illuminate\Support\Facades\Auth::user()->plan!=2)
                                <a class="btn btn-success btn-rounded" href="/changeplan/2">Topup Subscription</a>
                            @else
                                <a class="btn btn-success btn-rounded" href="/changeplan/3">Topup Subscription</a>
                            @endif

                            <a href="#" class="btn btn-secondary btn-rounded mt-3" data-toggle="modal"
                               data-target="#modal-fill">Change Plan</a>
                        </div>

                    </div>

                    {{--                    <div class="col-4">--}}
                    {{--                        <h2 style="font-weight: bolder">--}}
                    {{--                            Current Plan : Pro--}}
                    {{--                        </h2>--}}

                    {{--                        <h3>--}}
                    {{--                            Days Remaining : 400days--}}
                    {{--                        </h3>--}}

                    {{--                        <div class="mt-5">--}}
                    {{--                            <button class="btn btn-primary">Topup 30days Subscription</button>--}}
                    {{--                        </div>--}}

                    {{--                        <div class="mt-5">--}}
                    {{--                            <button class="btn btn-primary">Topup 365days Subscription</button>--}}
                    {{--                        </div>--}}

                    {{--                    </div>--}}

                    <div class="col-xl-10 col-lg-9 col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Payment List</h4>
                                <h6 class="box-subtitle">Export Invoice List to Copy, CSV, Excel, PDF & Print</h6>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">

                                    <table id="example" class="table table-lg invoice-archive">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Expiry Date</th>
                                            <th>Issued to</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                            <th>Payment date</th>
                                            <th>Plan</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($payments as $data)
                                        <tr>
                                            <td>#1</td>
                                            <td>{{\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->subscription)->toFormattedDateString()}}</td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <a href="#">{{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}</a>
                                                     </h6>
                                            </td>
<td><span class="d-block text-muted"> {{$data->gateway}}</span>
                                          </td>
                                            <td>
                                                <span class="badge badge-pill badge-success">Success</span>
                                            </td>
                                            <td>
                                                {{$data->date}}
                                            </td>
                                            <td>
                                                {{$data->plan}}
                                            </td>
                                            <td>
                                                <h6 class="mb-0 font-weight-bold">{{$data->amount}}</h6>
                                            </td>
                                            <td>
                                                <a class="btn" href="/receipt" data-toggle="tooltip" data-placement="top" title="View receipt of payment"> View Receipt</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
            <!-- /.content -->
@endsection

