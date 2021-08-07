@extends('layouts.user-layout')

@section('content')

    <!-- Main content -->
    <section class="content">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="w-p100 d-md-flex align-items-center justify-content-between">
                    <h3 class="page-title">Invites History</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Invites</li>
                                <li class="breadcrumb-item active" aria-current="page">History</li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

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

        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h6 class="box-subtitle">The table below show the list of invites sent from the system</h6>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">

                            <table id="example" class="table table-lg invoice-archive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Room Name</th>
                                    <th>Guest</th>
                                    <th>Date Sent</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($invites as $data)
                                    <tr>
                                        <td>#{{$i++}}</td>
                                        <td>
                                            <h6 class="mb-0">
                                                <a href="#">{{$data->type}}</a>
                                            </h6>
                                        </td>
                                        <td><span class="d-block text-muted"> {{$data->roomname}}</span>
                                        </td>
                                        <td> {{$data->guest}} </td>
                                        <td>
                                            {{\Carbon\Carbon::parse($data->created_at)->toFormattedDateString()}}
                                        </td>
                                        <td><a href="{{route('resendinvites', $data->id)}}" class="btn btn-primary">Send Again</a></td>
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

