@extends('layouts.user-layout')

@section('content')

            <!-- Main content -->
            <section class="content">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="d-flex align-items-center">
                        <div class="w-p100 d-md-flex align-items-center justify-content-between">
                            <h3 class="page-title">Profile</h3>
                            <div class="d-inline-block align-items-center">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                        <li class="breadcrumb-item" aria-current="page">User</li>
                                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
                                <li><a class="active" href="#usertimeline" data-toggle="tab">Profile Information</a></li>
                                <li><a href="#up" data-toggle="tab">Update Password</a></li>
                                <li><a href="#fa2" data-toggle="tab">Two Factor Authentication</a></li>
                                <li><a href="#bs" data-toggle="tab">Browser Sessions</a></li>
                            </ul>

                            <div class="tab-content">

                                <div class="active tab-pane" id="usertimeline">
                                    @livewire('profile.update-profile-information-form')
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="fa2">

                                        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                                            <div class="mt-10 sm:mt-0">
                                                @livewire('profile.two-factor-authentication-form')
                                            </div>
                                        @endif

                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="bs">

                                    <div class="mt-10 sm:mt-0">
                                        @livewire('profile.logout-other-browser-sessions-form')
                                    </div>

                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="up">

                                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                                        <div class="mt-10 sm:mt-0">
                                            @livewire('profile.update-password-form')
                                        </div>
                                    @endif

                                </div>
                                <!-- /.tab-pane -->


                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- /.nav-tabs-custom -->
                    </div>
                    <!-- /.col -->

                    <div class="col-12 col-lg-5 col-xl-4">
                        <div class="box box-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-black" style="background: url('/user_assets/images/gallery/full/10.jpg') center center;">
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
                                            <p>Email :<span class="text-gray pl-10">{{\Illuminate\Support\Facades\Auth::user()->email}}</span> </p>
                                            <p>Phone :<span class="text-gray pl-10">{{\Illuminate\Support\Facades\Auth::user()->phone}}</span></p>
                                            <p>Name :<span class="text-gray pl-10">{{\Illuminate\Support\Facades\Auth::user()->name}}</span></p>
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
