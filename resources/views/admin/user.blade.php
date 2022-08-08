@extends('layouts.admin-layout')

@section('content')

    <!-- Main content -->
    <section class="content">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="w-p100 d-md-flex align-items-center justify-content-between">
                    <h3 class="page-title">User Details</h3>
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

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        {{--                                <li><a class="active" href="#usertimeline" data-toggle="tab">Profile Information</a></li>--}}
                        <li><a class="active" href="#mr" data-toggle="tab">Meeting Room(s)</a></li>
                        <li><a href="#fa2" data-toggle="tab">Payment(s)</a></li>
                        <li><a href="#bs" data-toggle="tab">Recording(s)</a></li>
                        <li><a href="#ms" data-toggle="tab">Meeting(s) Joined</a></li>
                        <li><a href="#up" data-toggle="tab">Upgrade Plan</a></li>
                        <li><a href="#ot" data-toggle="tab">Others</a></li>
                    </ul>

                    <div class="tab-content">


                        <div class="tab-pane" id="up">


                            {{--                    Mobile View--}}
                            <div class="row hidden-lg-up hidden-sm-up hidden-xl-up">
                                <div class="col-12">
                                    <div class="box">
                                        <div class="box-header">

                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">

                                                <form action="{{route('admin.upgradeplan')}}" method="POST">
                                                    @csrf
                                                        <input type="hidden" name="user" value="{{$user->id}}">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Select Plan:</label>
                                                        <select class="form-control" name="plan">
                                                            @foreach($plans as $plan)
                                                                @if($plan->id=="3")
                                                                    <option value="{{$plan->id}}" selected>{{$plan->name}}</option>
                                                                @else
                                                                    <option value="{{$plan->id}}">{{$plan->name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Months:</label>
                                                        <select class="form-control" name="duration">
                                                            <option>1</option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                            <option>5</option>
                                                            <option>6</option>
                                                        </select>
                                                    </div>

                                                    <button type="submit" class="btn bg-gradient-success">Upgrade Now</button>

                                                </form>
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

                                                <form action="{{route('admin.upgradeplan')}}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="user" value="{{$user->id}}">
                                                    <!-- select -->
                                                    <div class="form-group">
                                                        <label>Select Plan:</label>
                                                        <select class="form-control" name="plan">
                                                            @foreach($plans as $plan)
                                                                @if($plan->id=="3")
                                                                    <option value="{{$plan->id}}" selected>{{$plan->name}}</option>
                                                                @else
                                                                    <option value="{{$plan->id}}">{{$plan->name}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Months:</label>
                                                        <select class="form-control" name="duration">
                                                            <option>1</option>
                                                            <option>2</option>
                                                            <option>3</option>
                                                            <option>4</option>
                                                            <option>5</option>
                                                            <option>6</option>
                                                        </select>
                                                    </div>

                                                    <button type="submit" class="btn bg-gradient-success">Upgrade Now</button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2">

                                </div>
                            </div>

                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="ot">

                            <div class="row">
                                <div class="col-12">
                                    @if($user->referral_code == null)
                                        <a href="{{route('admin.generateReferralCode', $user->id)}}"
                                           class="btn bg-gradient-primary">Generate Referral Code</a>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <!-- /.tab-pane -->

                        <!-- /.tab-pane -->

                        <div class="tab-pane active" id="mr">


                            {{--                    Mobile View--}}
                            <div class="row hidden-lg-up hidden-sm-up hidden-xl-up">
                                <div class="col-12">
                                    <div class="box">
                                        <div class="box-header">

                                        </div>
                                        <div class="box-body">
                                            <div class="table-responsive">

                                                <table class="table no-border font-size-12" id="complex_header">
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
                                                <table class="table no-border" id="complex_header">
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
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($payments as $data)
                                            <tr>
                                                <td>#{{$data->id}}</td>
                                                <td>{{\Carbon\Carbon::parse($user->subscription)->toFormattedDateString()}}</td>
                                                <td>
                                                    <h6 class="mb-0">
                                                        <a href="#">{{$user->firstname}} {{$user->lastname}}</a>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <span class="d-block text-muted">{{$data->gateway}}</span>
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
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>


                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="bs">

                            {{--                Mobile View--}}
                            <div class="row hidden-lg-up hidden-sm-up hidden-xl-up">
                                <div class="col-12">
                                    <div class="box">
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="table no-border" id="complex_header">
                                                    <thead>
                                                    <tr class="text-uppercase bg-lightest font-size-10">
                                                        <th><span class="text-fade">Name</span></th>
                                                        <th><span class="text-fade">Parameters</span></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($recordings as $record)
                                                        <tr>
                                                            <td class="pl-0 py-8">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        @if(isset($record['playback']['format']['preview']['images']['image'][0]))
                                                                            <img
                                                                                src="{{$record['playback']['format']['preview']['images']['image'][0]}}"
                                                                                class="img img-thumbnail">
                                                                        @else
                                                                            No Image Preview
                                                                        @endif
                                                                        <br/>
                                                                        <a href="#"
                                                                           class="text-dark font-weight-600 hover-primary mb-1 font-size-10">{{$record['name']}}</a>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            {{--                                            <td class="pl-0 py-8">--}}
                                                            {{--                                                        @foreach($record['playback']['format']['preview']['images']['image'] as $im)--}}
                                                            {{--                                                        <img src="{{$im}}" class="img img-thumbnail">--}}
                                                            {{--                                                        @endforeach--}}
                                                            {{--                                            </td>--}}


                                                            {{--                                            <td class="pl-0 py-8">--}}
                                                            {{--                                                @if(isset($record['playback']['format']['preview']['images']['image']))--}}
                                                            {{--                                                    <img src="{{$record['playback']['format']['preview']['images']['image']}}" class="img img-thumbnail">--}}
                                                            {{--                                                @else--}}
                                                            {{--                                                    No Image Preview--}}
                                                            {{--                                                @endif--}}
                                                            {{--                                            </td>--}}

                                                            <td>
                                                <span class="text-dark font-weight-600 d-block font-size-10">
													{{$record['participants']}} Participants
												</span>
                                                                <span
                                                                    class="text-dark font-weight-600 d-block font-size-10">
													{{$record['playback']['format']['length']}} Minutes
												</span>
{{--                                                                <span--}}
{{--                                                                    class="text-dark font-weight-600 d-block font-size-10">--}}
{{--													{{ number_format(($record['size']/1000000))."MB"}}--}}
{{--												</span>--}}
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

                            {{--                Desktop view--}}
                            <div class="row hidden-xs-down">
                                <div class="col-12">
                                    <div class="box">
                                        <div class="box-body">
                                            <div class="table-responsive">
                                                <table class="table no-border" id="complex_header">
                                                    <thead>
                                                    <tr class="text-uppercase bg-lightest">
                                                        <th style="min-width: 20px; max-width: 50px"><span
                                                                class="text-fade">Meeting Name</span></th>
                                                        <th style="min-width: 20px; max-width: 50px"><span
                                                                class="text-fade">Parameters</span></th>
                                                        <th style="min-width: 50px; max-width: 100px"><span
                                                                class="text-fade">Link</span></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($recordings as $record)
                                                        <tr>
                                                            <td style="min-width: 20px; max-width: 50px">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <a href="#"
                                                                           class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$record['name']}}</a>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            {{--                                            <td class="pl-0 py-8">--}}
                                                            {{--                                                        @foreach($record['playback']['format']['preview']['images']['image'] as $im)--}}
                                                            {{--                                                        <img src="{{$im}}" class="img img-thumbnail">--}}
                                                            {{--                                                        @endforeach--}}
                                                            {{--                                            </td>--}}


                                                            {{--                                            <td class="pl-0 py-8">--}}
                                                            {{--                                                @if(isset($record['playback']['format']['preview']['images']['image']))--}}
                                                            {{--                                                    <img src="{{$record['playback']['format']['preview']['images']['image']}}" class="img img-thumbnail">--}}
                                                            {{--                                                @else--}}
                                                            {{--                                                    No Image Preview--}}
                                                            {{--                                                @endif--}}
                                                            {{--                                            </td>--}}

                                                            <td style="min-width: 20px; max-width: 50px">
                                                <span class="text-dark font-weight-600 d-block font-size-16">
													{{$record['participants']}} Participants
												</span>
                                                                <span
                                                                    class="text-dark font-weight-600 d-block font-size-16">
													{{$record['playback']['format']['length']}} Minutes
												</span>
{{--                                                                <span--}}
{{--                                                                    class="text-dark font-weight-600 d-block font-size-16">--}}
{{--													{{ number_format(($record['size']/1000000))."MB"}}--}}
{{--												</span>--}}
                                                            </td>
                                                            <td style="min-width: 50px; max-width: 150px">
                                                                <span
                                                                    class="text-dark font-weight-600 d-block font-size-16">{{$record['playback']['format']['url']}}</span>
                                                                <input type="hidden"
                                                                       value="{{$record['playback']['format']['url']}}"/>
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
                        </div>


                        <div class="tab-pane" id="ms">
                            <div class="box-body">
                                <div class="table-responsive">

                                    <table id="example" class="table table-lg invoice-archive">
                                        <thead>
                                        <tr>
                                            {{--                                            <th>#</th>--}}
                                            <th>Meeting Name</th>
                                            <th>User Name</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($meetings as $meeting)
                                            <tr>
                                                {{--                                                <td>#{{$i=0; $i++;}}</td>--}}
                                                <td>{{$meeting->roomname}}</td>
                                                <td>
                                                    {{$meeting->name}}
                                                </td>
                                                <td>
                                                    {{$meeting->status}}
                                                </td>
                                                <td>
                                                    {{$meeting->created_at}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>


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
                        <h3 class="widget-user-username">{{$user->firstname}}</h3>
                        <h6 class="widget-user-desc">
                            @if($user->plan==1)
                                Basic
                            @elseif($user->plan==2)
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
                                    <h5 class="description-header">{{$user_plan->rooms + $user->room_bundles}}</h5>
                                    <span class="description-text">Maximum Rooms</span>
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
                        <div class="col-12" style="background-color: black">
                            @if($referredby!="")
                                <span style="color: white">Referred by {{$referredby}}</span>
                            @endif
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
                                            class="text-gray pl-10">{{$user->email}}</span>
                                    </p>
                                    <p>Phone :<span
                                            class="text-gray pl-10">{{$user->phone}}</span>
                                    </p>
                                    <p>Name :<span
                                            class="text-gray pl-10">{{$user->firstname}} {{$user->lastname}}</span>
                                    </p>
                                    <p>Referral Code :<span
                                            class="text-gray pl-10">{{$user->referral_code}}</span>
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
