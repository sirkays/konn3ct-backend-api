@extends('layouts.user-layout')

@section('content')

    <!-- Main content -->
    <section class="content">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="w-p100 d-md-flex align-items-center justify-content-between">
                    <h3 class="page-title">Addon List</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Referral</li>
                                <li class="breadcrumb-item active" aria-current="page">Referee List</li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-12">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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

                <div class="box">
                    <div class="box-header with-border">
                        <h6 class="box-subtitle">The table below show the list addon available for you</h6>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">

                            <table id="example" class="table table-lg invoice-archive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($addons as $data)
                                    <tr>
                                        <td>#{{$i++}}</td>
                                        <td>
                                            <h6 class="mb-0">
                                                <a href="#">{{$data->name}}</a>
                                            </h6>
                                        </td>
                                        <td><span class="d-block text-muted"> {{$data->description}}</span>
                                        </td>
                                        <td>
                                            @if($data->name == "Whatsapp Invite")
                                                <span class="badge badge-pill badge-danger">
                                                        @if(\Illuminate\Support\Facades\Auth::user()->whatsapp_invite=="0")
                                                        Not yet activated
                                                    @else
                                                        @if(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->whatsapp_invite), false) > 0)
                                                            Expires
                                                            in {{\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->whatsapp_invite), false)}}
                                                            days
                                                            @else
                                                            Expired
                                                            @endif
                                                    @endif
                                                </span>
                                            @else
                                                Available
                                            @endif
                                        </td>
                                        <td>
                                            {{$data->price}}
                                        </td>
                                        <td>
                                            <button class="btn btn-primary"
                                                    onclick="makePayment({{$data->price}}, {{$data->id}})">Subscribe Now
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            {{--                    <div class="col-xl-2 col-lg-3 col-12">--}}
            {{--                        <div class="box box-inverse box-success">--}}
            {{--                            <div class="box-body">--}}
            {{--                                <div class="flexbox">--}}
            {{--                                    <h5>Payments</h5>--}}
            {{--                                </div>--}}

            {{--                                <div class="text-center my-2">--}}
            {{--                                    <div class="font-size-60">{{$tp}}</div>--}}
            {{--                                    <span>Total Payments</span>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                        <div class="box box-inverse box-primary">--}}
            {{--                            <div class="box-body">--}}
            {{--                                <div class="flexbox">--}}
            {{--                                    <h5>Payments</h5>--}}
            {{--                                </div>--}}

            {{--                                <div class="text-center my-2">--}}
            {{--                                    <div class="font-size-60">{{$sp}}</div>--}}
            {{--                                    <span>Sum Payment</span>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

            {{--                    </div>--}}
        </div>
    </section>
    <!-- /.content -->

    <script src="https://checkout.flutterwave.com/v3.js"></script>

    <script>
        function makePayment(amount, id) {
            FlutterwaveCheckout({
                public_key: "{{env('RAVE_PUB_KEY')}}",
                tx_ref: "konn3ct_{{rand().time()}}",
                amount: amount,
                currency: "NGN",
                country: "NG",
                payment_options: "card, mobilemoneyghana, ussd",
                customer: {
                    email: "{{\Illuminate\Support\Facades\Auth::user()->email}}",
                    phone_number: "{{\Illuminate\Support\Facades\Auth::user()->phone}}",
                    name: "{{\Illuminate\Support\Facades\Auth::user()->firstname}} {{\Illuminate\Support\Facades\Auth::user()->lastname}}",
                },
                callback: function (data) {
                    console.log(data);
                    window.location.href = "/addonpayment/" + id + "/transid/" + data.transaction_id;
                },
                onclose: function () {
                    // close modal
                    // window.location.href = "/payment/2/transid/3456789";
                },
                customizations: {
                    title: "Konn3ct Addon",
                    description: "Payment for Addon",
                    logo: "https://konn3ct.com/assets/images/konn3ctIcon.png",
                },
            });
        }
    </script>

@endsection

