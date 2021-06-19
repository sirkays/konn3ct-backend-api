@extends('layouts.user-layout')

@section('content')

    <!-- Main content -->
    <section class="content">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="w-p100 d-md-flex align-items-center justify-content-between">
                    <h3 class="page-title">Referee List</h3>
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
                <div class="box">
                    <div class="box-header with-border">
                        <h6 class="box-subtitle">The table below show the list of people who has joined our platform
                            using your referral code</h6>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">

                            <table id="example" class="table table-lg invoice-archive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Plan</th>
                                    <th>Date Registered</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($referee as $data)
                                    <tr>
                                        <td>#{{$i++}}</td>
                                        <td>
                                            <h6 class="mb-0">
                                                <a href="#">{{$data->firstname}} {{$data->lastname}}</a>
                                            </h6>
                                        </td>
                                        <td><span class="d-block text-muted"> {{$data->email}}</span>
                                        </td>
                                        <td>
                                                <span class="badge badge-pill badge-success">
                                                    @if($data->plan==1)
                                                        Basic
                                                    @elseif($data->plan==2)
                                                        Lite
                                                    @else
                                                        Pro
                                                    @endif
                                                </span>
                                        </td>
                                        <td>
                                            {{\Carbon\Carbon::parse($data->created_at)->toFormattedDateString()}}
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

