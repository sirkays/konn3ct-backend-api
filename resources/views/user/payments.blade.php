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
                            @if(\Illuminate\Support\Facades\Auth::user()->plan!=1)
                                <a class="btn btn-success btn-rounded"
                                   href="/changeplan/{{\Illuminate\Support\Facades\Auth::user()->plan}}">Topup
                                    Subscription</a>
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
                                                {{$data->planDetails->name}}
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

            <!-- Modal -->
            <div class="modal modal-fill fade" data-backdrop="false" id="modal-fill" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Change Plan</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            {{--                <div class="row">--}}
                            {{--                    <div class="col-4">--}}
                            {{--                        <div class="box box-inverse bg-gradient-primary">--}}
                            {{--                            <div class="box-body text-center">--}}
                            {{--                                <h5 class="text-uppercase text-muted">Basic</h5>--}}
                            {{--                                <br>--}}
                            {{--                                <p>--}}
                            {{--                                    <strong>--}}
                            {{--                                        Free Forever--}}
                            {{--                                    </strong>--}}
                            {{--                                </p>--}}
                            {{--                                <p></p>--}}
                            {{--                                <br/>--}}

                            {{--                                <hr>--}}

                            {{--                                <p><strong>Participant - </strong> 100</p>--}}
                            {{--                                <p><strong>Session Timeout - </strong> 1 hour</p>--}}
                            {{--                                <p><strong>Number of Rooms - </strong> 1</p>--}}

                            {{--                                <br><br>--}}
                            {{--                                @if(\Illuminate\Support\Facades\Auth::user()->plan!=1)--}}
                            {{--                                    <a class="btn btn-outline btn-white" href="/changeplan/1">Select Plan</a>--}}
                            {{--                                @else--}}
                            {{--                                    <a class="btn btn-white" href="#">Current Plan</a>--}}
                            {{--                                @endif--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            {{--                    </div>--}}

                            {{--                    <div class="col-4">--}}
                            {{--                        <div class="box card-shadowed box-inverse bg-gradient-danger">--}}
                            {{--                            <div class="box-body text-center">--}}
                            {{--                                <h5 class="text-uppercase text-muted">Lite</h5>--}}
                            {{--                                <br>--}}
                            {{--                                <p>--}}
                            {{--                                    <strong>--}}
                            {{--                                    $10.99/&#x20A6;4000<sup>Monthly</sup> <br/>--}}
                            {{--                                    $120/&#x20A6;46,000<sup>Yearly</sup>--}}
                            {{--                                    </strong>--}}
                            {{--                                </p>--}}
                            {{--                                <p></p>--}}
                            {{--                                <br/>--}}

                            {{--                                <hr>--}}
                            {{--                                <p><strong>Participant - </strong> 100</p>--}}
                            {{--                                <p><strong>Session Timeout - </strong> 10 hours</p>--}}
                            {{--                                <p><strong>Cloud Storage - </strong> 5 GB</p>--}}
                            {{--                                <p><strong>Number of Rooms - </strong> 5</p>--}}


                            {{--                                <br><br>--}}
                            {{--                                @if(\Illuminate\Support\Facades\Auth::user()->plan!=2)--}}
                            {{--                                    <a class="btn btn-outline btn-white" href="/changeplan/2">Select Plan</a>--}}
                            {{--                                @else--}}
                            {{--                                    <a class="btn btn-dark btn-white" href="#">Current Plan</a>--}}
                            {{--                                @endif--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            {{--                    </div>--}}

                            {{--                    <div class="col-4">--}}
                            {{--                        <div class="box box-inverse bg-gradient-success">--}}
                            {{--                            <div class="box-body text-center">--}}
                            {{--                                <h5 class="text-uppercase text-muted">Pro</h5>--}}
                            {{--                                <br>--}}
                            {{--                                <p>--}}
                            {{--                                    <strong>--}}
                            {{--                                        $15.99/&#x20A6;6000<sup>Monthly</sup> <br/>--}}
                            {{--                                        $175/&#x20A6;67,000<sup>Yearly</sup>--}}
                            {{--                                    </strong>--}}
                            {{--                                </p>--}}
                            {{--                                <p></p>--}}
                            {{--                                <br/>--}}

                            {{--                                <hr>--}}
                            {{--                                <p><strong>Participant - </strong> 250</p>--}}
                            {{--                                <p><strong>Session Timeout - </strong> 24 hours</p>--}}
                            {{--                                <p><strong>Cloud Storage </strong> 15 GB</p>--}}
                            {{--                                <p><strong>Number of Rooms</strong> Unlimited</p>--}}

                            {{--                                <br><br>--}}
                            {{--                                @if(\Illuminate\Support\Facades\Auth::user()->plan!=3)--}}
                            {{--                                    <a class="btn btn-outline btn-white" href="/changeplan/3">Select plan</a>--}}
                            {{--                                @else--}}
                            {{--                                    <a class="btn btn-dark btn-white" href="#">Current Plan</a>--}}
                            {{--                                @endif--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            {{--                    </div>--}}

                            {{--                </div>--}}
                            <div class="row">
                                <div class="col-4">
                                    <div class="box box-inverse bg-gradient-primary">
                                        <div class="box-body text-center">
                                            <h5 class="text-uppercase text-muted">Basic</h5>
                                            <br>
                                            <p>
                                                <strong>
                                                    Free Forever
                                                </strong>
                                            </p>
                                            <p></p>
                                            <br/>

                                            <hr>

                                            <p><strong>Participant - </strong> 100</p>
                                            <p><strong>Session Timeout - </strong> 1 hour</p>
                                            <p><strong>Number of Rooms - </strong> 1</p>

                                            <br><br>
                                            @if(\Illuminate\Support\Facades\Auth::user()->plan!=1)
                                                <a data-toggle="modal" data-target="#basicplan-modal"
                                                   class="btn btn-outline btn-white">Select Plan</a>
                                            @else
                                                <a class="btn btn-white" href="#">Current Plan</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="box card-shadowed box-inverse bg-gradient-danger">
                                        <div class="box-body text-center">
                                            <h5 class="text-uppercase text-muted">Lite</h5>
                                            <br>
                                            <p>
                                                <strong>
                                                    {{$lite_monthly}}<sup>Monthly</sup> <br/>
                                                    {{$lite_yearly}}<sup>Yearly</sup>
                                                </strong>
                                            </p>
                                            <p></p>
                                            <br/>

                                            <hr>
                                            <p><strong>Participant - </strong> 100</p>
                                            <p><strong>Session Timeout - </strong> 10 hours</p>
                                            <p><strong>Cloud Storage - </strong> 5 GB</p>
                                            <p><strong>Number of Rooms - </strong> 2</p>
                                            <p><strong>Breakout Rooms</strong> <i class="fa fa-check-circle"></i></p>
                                            <p><strong>Recording</strong> <i class="fa fa-check-circle"></i></p>


                                            <br><br>
                                            @if(\Illuminate\Support\Facades\Auth::user()->plan!=2)
                                                <a class="btn btn-outline btn-white" href="/changeplan/2">Select
                                                    Plan</a>
                                            @else
                                                <a class="btn btn-primary btn-white" href="/changeplan/2">Current
                                                    Plan</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="box box-inverse bg-gradient-success">
                                        <div class="box-body text-center">
                                            <h5 class="text-uppercase text-muted">Pro</h5>
                                            <br>
                                            <p>
                                                <strong>
                                                    {{$pro_monthly}}<sup>Monthly</sup> <br/>
                                                    {{$pro_yearly}}<sup>Yearly</sup>
                                                </strong>
                                            </p>
                                            <p></p>
                                            <br/>

                                            <hr>
                                            <p><strong>Participant - </strong> 250</p>
                                            <p><strong>Session Timeout - </strong> 24 hours</p>
                                            <p><strong>Cloud Storage </strong> 15 GB</p>
                                            <p><strong>Number of Rooms</strong> 3</p>
                                            <p><strong>Breakout Rooms</strong> <i class="fa fa-check-circle"></i></p>
                                            <p><strong>Recording</strong> <i class="fa fa-check-circle"></i></p>

                                            <br><br>
                                            @if(\Illuminate\Support\Facades\Auth::user()->plan!=3)
                                                <a class="btn btn-outline btn-white" href="/changeplan/3">Select
                                                    plan</a>
                                            @else
                                                <a class="btn btn-primary btn-white" href="/changeplan/3">Current
                                                    Plan</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal -->

@endsection

