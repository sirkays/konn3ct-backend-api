@extends('layouts.user-layout')

@section('content')

    <!-- Main content -->
    <section class="content">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="w-p100 d-md-flex align-items-center justify-content-between">
                    <h3 class="page-title">User</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Users</li>
                                <li class="breadcrumb-item active" aria-current="page">{{$user->firstname}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-7 col-xl-8">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        {{--                                <li><a class="active" href="#usertimeline" data-toggle="tab">Profile Information</a></li>--}}
                        <li><a class="active" href="#up" data-toggle="tab">Meeting Room(s)</a></li>
                        <li><a href="#fa2" data-toggle="tab">Payment(s)</a></li>
                        <li><a href="#bs" data-toggle="tab">Recording(s)</a></li>
                    </ul>

                    <div class="tab-content">

                    {{--                                <div class="active tab-pane" id="usertimeline">--}}
                    {{--                                    @livewire('profile.update-profile-information-form')--}}
                    {{--                                </div>--}}
                    <!-- /.tab-pane -->


                        <!-- /.tab-pane -->

                        <div class="tab-pane active" id="up">


                            {{--                    Mobile View--}}
                            <div class="row hidden-lg-up hidden-sm-up hidden-xl-up">
                                <div class="col-12">
                                    <div class="box">
                                        <div class="box-header">

                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">

                                                <table class="table no-border font-size-12">
                                                    <tbody>
                                                    @foreach($rooms as $room)
                                                        <tr>
                                                            <td>
                                                                <a href="#"
                                                                   class="text-dark hover-primary mb-1"><strong>Name:</strong> {{$room->name}}
                                                                </a>
                                                                <span class="text-dark d-block">
                                                  <strong>Link:</strong> <span
                                                                        id="c{{$room->id}}">{{url('/join/')}}/{{$room->url}}</span>
                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2">

                                </div>
                            </div>


                            {{--                    Desktop View--}}
                            <div class="row hidden-xs-down">
                                <div class="col-12">
                                    <div class="box">
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="table no-border">
                                                    <thead>
                                                    <tr>
                                                        <th>Room Name</th>
                                                        <th>Room URL</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($rooms as $room)
                                                        <tr>
                                                            <td class="pl-0 py-8">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <a href="#"
                                                                           class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$room->name}}</a>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                <span id="c{{$room->id}}"
                                                      class="text-dark font-weight-600 d-block font-size-16">
                                                    {{url('/join/')}}/{{$room->url}}
                                                </span>

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2">

                                </div>
                            </div>

                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="fa2">


                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="bs">


                        </div>


                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->

            <div class="col-12 col-lg-5 col-xl-4">
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-black"
                         style="background: url('/user_assets/images/gallery/full/10.jpg') center center;">
                        <h3 class="widget-user-username">{{\Illuminate\Support\Facades\Auth::user()->name}}</h3>
                        <h6 class="widget-user-desc">
                            @if(\Illuminate\Support\Facades\Auth::user()->plan==1)
                                Basic
                            @elseif(\Illuminate\Support\Facades\Auth::user()->plan==2)
                                Lite
                            @else
                                Pro
                            @endif
                            Plan
                        </h6>
                    </div>
                    <div class="widget-user-image">
                        <img class="rounded-circle" src="/user_assets/images/user3-128x128.jpg" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">{{$rm}}</h5>
                                    <span class="description-text">Rooms</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 br-1 bl-1">
                                <div class="description-block">
                                    {{--                                            <h5 class="description-header">550</h5>--}}
                                    {{--                                            <span class="description-text">FOLLOWERS</span>--}}
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">{{$p}}</h5>
                                    <span class="description-text">Payments</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <div class="box">
                    <div class="box-body box-profile">
                        <div class="row">
                            <div class="col-12">
                                <div>
                                    <p>Email :<span
                                            class="text-gray pl-10">{{\Illuminate\Support\Facades\Auth::user()->email}}</span>
                                    </p>
                                    <p>Phone :<span
                                            class="text-gray pl-10">{{\Illuminate\Support\Facades\Auth::user()->phone}}</span>
                                    </p>
                                    <p>Name :<span
                                            class="text-gray pl-10">{{\Illuminate\Support\Facades\Auth::user()->lastname}} {{\Illuminate\Support\Facades\Auth::user()->firstname}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>

        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
@endsection
