@extends('layouts.user-layout')

@section('content')

    <!-- Main content -->
    <section class="content">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="w-p100 d-md-flex align-items-center justify-content-between">
                    <h3 class="page-title">Make Payment</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Payment</li>
                                <li class="breadcrumb-item active" aria-current="page">Make Payment</li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <h2 style="font-weight: bolder">
                    Current Plan : Pro
                </h2>

                <h3>
                    Days Remaining : 400days
                </h3>

                <div class="mt-5">
                    <button class="btn btn-primary">Topup 30days Subscription</button>
                </div>

                <div class="mt-5">
                    <button class="btn btn-primary">Topup 365days Subscription</button>
                </div>

            </div>
            <div class="col-6">

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
                        <h6 class="box-subtitle">Upgrade/Downgrade Plan</h6>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">

                            <table id="example" class="table table-lg invoice-archive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Monthly Cost</th>
                                    <th>Yearly Cost</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($plans as $plan)
                                    <tr>
                                        <td>
                                            {{$plan->name}}
                                        </td>
                                        <td>
                                            {{$plan->monthlycost}}
                                        </td>
                                        <td>
                                            {{$plan->yearlycost}}
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-primary">Change Now</a>
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
@endsection

