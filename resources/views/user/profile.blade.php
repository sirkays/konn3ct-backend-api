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
                                <li><a href="#settings" data-toggle="tab">Settings</a></li>
                            </ul>

                            <div class="tab-content">

                                <div class="active tab-pane" id="usertimeline">
                                    @livewire('profile.update-profile-information-form')
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="settings">

                                    <div class="box p-15">
                                        <form class="form-horizontal form-element col-12">
                                            <div class="form-group row">
                                                <label for="inputName" class="col-sm-2 control-label">Name</label>

                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control" id="inputName" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control" id="inputEmail" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputPhone" class="col-sm-2 control-label">Phone</label>

                                                <div class="col-sm-10">
                                                    <input type="tel" class="form-control" id="inputPhone" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="inputExperience" placeholder=""></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="inputSkills" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="ml-auto col-sm-10">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="basic_checkbox_1" checked="">
                                                        <label for="basic_checkbox_1"> I agree to the</label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;<a href="#">Terms and Conditions</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="ml-auto col-sm-10">
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
                                <h6 class="widget-user-desc">Designer</h6>
                            </div>
                            <div class="widget-user-image">
                                <img class="rounded-circle" src="/user_assets/images/user3-128x128.jpg" alt="User Avatar">
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="description-block">
                                            <h5 class="description-header">12K</h5>
                                            <span class="description-text">FOLLOWERS</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 br-1 bl-1">
                                        <div class="description-block">
                                            <h5 class="description-header">550</h5>
                                            <span class="description-text">FOLLOWERS</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4">
                                        <div class="description-block">
                                            <h5 class="description-header">158</h5>
                                            <span class="description-text">TWEETS</span>
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
                                            <p>Email :<span class="text-gray pl-10">David@yahoo.com</span> </p>
                                            <p>Phone :<span class="text-gray pl-10">+11 123 456 7890</span></p>
                                            <p>Address :<span class="text-gray pl-10">123, Lorem Ipsum, Florida, USA</span></p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="pb-15">
                                            <p class="mb-10">Social Profile</p>
                                            <div class="user-social-acount">
                                                <button class="btn btn-circle btn-social-icon btn-facebook"><i class="fa fa-facebook"></i></button>
                                                <button class="btn btn-circle btn-social-icon btn-twitter"><i class="fa fa-twitter"></i></button>
                                                <button class="btn btn-circle btn-social-icon btn-instagram"><i class="fa fa-instagram"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <div class="map-box">
                                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2805244.1745767146!2d-86.32675167439648!3d29.383165774894163!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c1766591562abf%3A0xf72e13d35bc74ed0!2sFlorida%2C+USA!5e0!3m2!1sen!2sin!4v1501665415329" width="100%" height="100" frameborder="0" style="border:0" allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="box">
                            <div class="box-body">
                                <div class="flexbox align-items-baseline mb-20">
                                    <h6 class="text-uppercase ls-2">Friends</h6>
                                    <small>20</small>
                                </div>
                                <div class="gap-items-2 gap-y">
                                    <a class="avatar" href="#"><img src="/user_assets/images/avatar/1.jpg" alt="..."></a>
                                    <a class="avatar" href="#"><img src="/user_assets/images/avatar/3.jpg" alt="..."></a>
                                    <a class="avatar" href="#"><img src="/user_assets/images/avatar/4.jpg" alt="..."></a>
                                    <a class="avatar" href="#"><img src="/user_assets/images/avatar/5.jpg" alt="..."></a>
                                    <a class="avatar" href="#"><img src="/user_assets/images/avatar/6.jpg" alt="..."></a>
                                    <a class="avatar" href="#"><img src="/user_assets/images/avatar/7.jpg" alt="..."></a>
                                    <a class="avatar" href="#"><img src="/user_assets/images/avatar/8.jpg" alt="..."></a>
                                    <a class="avatar avatar-more" href="#">+15</a>
                                </div>
                            </div>
                            <div class="box-footer">
                                <a class="text-uppercase d-blockls-1 text-fade" href="#">Invite People</a>
                            </div>
                        </div>
                        <div class="box box-inverse" style="background-color: #3b5998">
                            <div class="box-header no-border">
                                <span class="fa fa-facebook font-size-30"></span>
                                <div class="box-tools pull-right">
                                    <h5 class="box-title box-title-bold">Facebook feed</h5>
                                </div>
                            </div>

                            <blockquote class="blockquote blockquote-inverse no-border m-0 py-15">
                                <p>Holisticly benchmark plug imperatives for multifunctional deliverables. Seamlessly incubate cross functional action.</p>
                                <div class="flexbox">
                                    <time class="text-white" datetime="2017-11-21 20:00">21 November, 2017</time>
                                    <span><i class="fa fa-heart"></i> 75</span>
                                </div>
                            </blockquote>
                        </div>

                    </div>

                </div>
                <!-- /.row -->

            </section>
            <!-- /.content -->
@endsection
