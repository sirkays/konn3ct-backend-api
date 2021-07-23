@extends('layouts.user-layout')

@section('content')

            <!-- Main content -->
            <section class="content">

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

                    <div class="row mb-6">
                        <div class="col-6">
                    <span class="badge badge-info" style="margin-bottom: 10px; font-weight: bolder">Your Referral Code<br/> {{\Illuminate\Support\Facades\Auth::user()->referral_code}}</span>
                        </div>
                        <div class="col-6 text-right">
                    @if(\Illuminate\Support\Facades\Auth::user()->plan==1)
                        @if(!\Illuminate\Support\Facades\Auth::user()->freetrial)
                                <Button class="waves-effect waves-light btn btn-danger btn-sm" data-toggle="modal" data-target="#activatepro-modal">
                                    Activate Pro (Free Trial)
                                </Button>
                        @endif
                    @endif
                            </div>
                    </div>

                <div class="row hidden-xs-down">
                    <div class="col-3">
                        <div class="box box-body pull-up">
{{--                            <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-success"><i class="fa fa-edit"></i> Add</button>--}}
                            <Button class="waves-effect waves-light btn btn-app btn-info" data-toggle="modal" data-target="#modal-left">
                                <i class="fa fa-edit"></i> Create a Room
                            </Button>
                        </div>
                    </div>
                        <div class="col-3">
                            <div class="box box-body pull-up">
                                <div class="flexbox align-items-end pt-30">
                                    <div>
                                        <span class="font-size-30 countnm">{{$roomstc}}</span>
                                        <h6 class="text-uppercase text-dark-50 mb-0">Total Rooms</h6>
                                    </div>
                                    <span class="icon-Angle-Grinder font-size-80 text-info"><span class="path1"></span><span class="path2"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="box box-body pull-up">
                                <div class="flexbox align-items-end pt-30">
                                    <div>
                                        <span class="font-size-30 countnm">{{$active}}</span>
                                        <h6 class="text-uppercase text-dark-50 mb-0">Active Room</h6>
                                    </div>
                                    <span class="iconsmind-Eye font-size-80 text-primary"><span class="path1"></span><span class="path2"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="box box-body pull-up">
                                <div class="flexbox align-items-end pt-30">
                                    <div>
                                        <span class="font-size-30 countnm">{{$roomstc - $active }}</span>
                                        <h6 class="text-uppercase text-dark-50 mb-0">Inactive Room</h6>
                                    </div>
                                    <span class="iconsmind-Eye-Blind font-size-80 text-danger"><span class="path1"></span><span class="path2"></span></span>
                                </div>
                            </div>
                        </div>

                </div>

                    <div class="row hidden-lg-up hidden-sm-up hidden-xl-up">
                    <div class="col-12">
                        <div class="box box-body pull-up">
{{--                            <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-success"><i class="fa fa-edit"></i> Add</button>--}}
                            <Button class="waves-effect waves-light btn btn-app btn-info btn-" data-toggle="modal" data-target="#modal-left">
                                <i class="fa fa-edit"></i> Create a Room
                            </Button>
                        </div>
                    </div>

                        <div class="container font-size-8">
                            <div class="row">
                                <div class="col">
                                    <div class="box box-body pull-up">
                                    <span class="font-size-30 countnm">{{$roomstc}}</span>
                                    <h6 class="text-uppercase text-dark-50 mb-0">Total Rooms</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="box box-body pull-up">
                                    <span class="font-size-30 countnm">{{$active}}</span>
                                    <h6 class="text-uppercase text-dark-50 mb-0">Active Room</h6>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="box box-body pull-up">
                                    <span class="font-size-30 countnm">{{$roomstc - $active }}</span>
                                    <h6 class="text-uppercase text-dark-50 mb-0">Inactive Room</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>

{{--                    Mobile View--}}
                <div class="row hidden-lg-up hidden-sm-up hidden-xl-up">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title align-items-start flex-column">
                                    Meeting Room Manager
{{--                                    <small class="subtitle">More than 400+ new members</small>--}}
                                </h4>

                            </div>
                            <div class="box-body">
                                <div class="table-responsive">

                                    <table class="table no-border font-size-12">
                                        <thead>
                                        <tr class="text-uppercase bg-lightest">
                                            <th style="min-width: 50px"><span class="text-fade">Room</span></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rooms as $room)
                                                <tr>
                                                    <td>
                                                        <a href="#" class="text-dark hover-primary mb-1"><strong>Name:</strong> {{$room->name}}</a>
                                                        <span class="badge badge-info">Access Code:
                                                            @if($room->password_attendee=="attendee")
                                                                Unrestricted
                                                            @else
                                                                {{$room->password_attendee}}
                                                                @endif

                                                        </span>
                                                        <span class="text-dark d-block">
                                                          <strong>Link:</strong> <span id="c{{$room->id}}">{{url('/join/')}}/{{$room->url}} </span>
                                                        </span>

                                                        <br/>

                                                        <form action="/joinroom" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$room->id}}" />

                                                            <div class="dropdown">
                                                                    <Button type="submit" class="waves-effect waves-light font-size-10 btn btn-success" data-toggle="tooltip" data-placement="top" title="Start the meeting">Konn3ct Now <br>
                                                                        <span class="font-size-8">Start Meeting</span></Button>

                                                                </form>

                                                                <button class="btn btn-outline-primary dropdown-toggle font-size-10" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    Manage
                                                                </button>
                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                    <Button type="button" class="dropdown-item waves-effect waves-light btn" onclick="copyToClipboard('#c{{$room->id}}')" data-toggle="tooltip" data-placement="top" title="Copy Meeting Link">
                                                                        Copy
                                                                    </Button>

                                                                    <a class="dropdown-item" href="https://www.google.com/calendar/render?action=TEMPLATE&text={{$room->name}}&details=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}&location={{url('/join/')}}/{{$room->url}}"  data-toggle="tooltip" data-placement="top" title="Schedule Meeting on Google Calender">
                                                                        Google Calender Invite
                                                                    </a>
                                                                    <a class="dropdown-item" href="https://outlook.live.com/owa/?path=/calendar/action/compose&rru=addevent&subject={{$room->name}}&body=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}" data-toggle="tooltip" data-placement="top" title="Schedule Meeting on Outlook Calender">
                                                                        Outlook Calendar Invite
                                                                    </a>
                                                                    <button type="button" style="font-size: 12px"  class="dropdown-item" data-toggle="modal" data-target=".invite-lg-{{$room->id}}">
                                                                        Konn3ct Invite
                                                                    </button>

                                                                    <Button type="button" class="dropdown-item" data-toggle="modal" data-target="#accesscode{{$room->id}}-modal">
                                                                        Access Code
                                                                    </Button>

                                                                    <Button type="button" class="dropdown-item" data-toggle="modal" data-target="#limituser{{$room->id}}-modal">
                                                                        Users Limit
                                                                    </Button>
                                                                    <Button type="button" class="dropdown-item" data-toggle="modal" data-target="#roombanner{{$room->id}}-modal">
                                                                        Meeting Room Banner Upload
                                                                    </Button>

                                                                    @if(\Illuminate\Support\Facades\Auth::user()->plan!=1)
                                                                        <form action="/deleteroom" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="id" value="{{$room->id}}" />
                                                                            <Button type="submit" class="waves-effect waves-light btn"  data-toggle="tooltip" data-placement="top" title="Delete the meeting">
                                                                                Delete
                                                                            </Button>
                                                                        </form>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                    </td>
                                                </tr>

                                                <div class="modal accesscode-modal fade" id="accesscode{{$room->id}}-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-md">
                                                        <form method="post" action="{{route('accesscode')}}">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="mySmallModalLabel">Manage Access Code</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    You are about to change your current access code to new. <br/>
                                                                    Enter your new access code below or click on "Auto Generate"<br/><br/>

                                                                    <div class="form-group">
                                                                        <label>New Access Code:</label>
                                                                        <input type="text" id="accesscode{{$room->id}}" name="accesscode" class="form-control" placeholder="Enter new access code" required />
                                                                        <input type="hidden" id="type{{$room->id}}" name="type" class="form-control" value="manual"/>
                                                                        <input type="hidden" name="id" class="form-control" value="{{$room->id}}"/>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer modal-footer-uniform">
                                                                    <button type="submit" class="btn bg-success float-left">Save</button>
                                                                    <button type="button" class="btn bg-dark float-right" onclick="document.getElementById('dkaccesscode{{$room->id}}').value=getRandomString(10);">Auto Generate</button>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <div class="modal limituser-modal fade" id="limituser{{$room->id}}-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-md">
                                                        <form method="post" action="{{route('limituser')}}">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="mySmallModalLabel">Manage User Limit</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    You are about to change your current user limit. <br/>
                                                                    Choose your need carefully<br/><br/>

                                                                    <div class="form-group">
                                                                        <label>User Limit:</label>
                                                                        <input type="number" id="users" name="users" aria-valuemin="2" min="2" max="{{$plan->participant}}" aria-valuemax="{{$plan->participant}}" max="{{$plan->participant}}" value="{{$room->max_participants}}" class="form-control" placeholder="Enter new access code" required />
                                                                        <input type="hidden" name="id" class="form-control" value="{{$room->id}}"/>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer modal-footer-uniform">
                                                                    <button type="submit" class="btn bg-success float-left">Save</button>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <div class="modal roombanner-modal fade" id="roombanner{{$room->id}}-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-md">
                                                        <form method="post" action="{{route('bannerupload')}}" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="mySmallModalLabel">Meeting Room Banner</h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Upload a customized banner for your meeting room. <br/>
                                                                    Recommended: 485px by 153px <br/><br/>

                                                                    <div class="form-group row">
                                                                        <div class="col-lg-10">
                                                                            <input type="hidden" name="id" class="form-control" value="{{$room->id}}"/>
                                                                            <input type="file" class="form-control" name="banner" required>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer modal-footer-uniform">
                                                                    <button type="submit" class="btn bg-success float-left">Upload</button>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <div class="modal fade invite-lg-{{$room->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Konn3ct Invite</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>

                                                            <form method="post" action="{{route('invite')}}">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label>Meeting Title:</label>
                                                                        <input type="text" name="title" class="form-control" placeholder="Enter Title" value="" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Access Code:</label>

                                                                        @if($room->password_attendee!="attendee")
                                                                            <input type="text" name="accesscode" class="form-control" placeholder="" value="{{$room->password_attendee}}" readonly required>
                                                                        @else
                                                                            <input type="hidden" name="accesscode" class="form-control" placeholder="" value="No Access Code">
                                                                            Room is open
                                                                        @endif

                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Room Link:</label>
                                                                        <input type="text" class="form-control" placeholder="e.g https://konn3ct..." value="{{url('/join/')}}/{{$room->url}}" disabled required>
                                                                        <input type="hidden" name="roomlink" class="form-control" placeholder="e.g https://konn3ct..." value="{{url('/join/')}}/{{$room->url}}"required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Host Name:</label>
                                                                        <input type="hidden" name="roomname" class="form-control" value="{{$room->name}}" />
                                                                        <input type="text" name="hostname" class="form-control" placeholder="e.g Newwaves" required />
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Date:</label>
                                                                        <input type="date" name="date" class="form-control" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Time:</label>
                                                                        <input type="time" name="time" class="form-control" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Timezone:</label>
                                                                        <select class="form-control" id="timezone" name="timezone">
                                                                            <option>Pacific/Midway (UTC-11:00)</option>
                                                                            <option>Pacific/Samoa (UTC-11:00)</option>
                                                                            <option>Pacific/Honolulu (UTC-10:00) Hawaii</option>
                                                                            <option>US/Alaska (UTC-09:00)</option>
                                                                            <option>America/Los_Angeles (UTC-08:00)</option>
                                                                            <option>America/Tijuana (UTC-08:00)</option>
                                                                            <option>US/Arizona (UTC-07:00)</option>
                                                                            <option>America/Chihuahua (UTC-07:00)</option>
                                                                            <option>America/Chihuahua (UTC-07:00)</option>
                                                                            <option>America/Mazatlan (UTC-07:00)</option>
                                                                            <option>US/Mountain (UTC-07:00)</option>
                                                                            <option>America/Managua (UTC-06:00)</option>
                                                                            <option>US/Central (UTC-06:00)</option>
                                                                            <option>America/Mexico_City (UTC-06:00)</option>
                                                                            <option>America/Mexico_City (UTC-06:00)</option>
                                                                            <option>America/Monterrey (UTC-06:00)</option>
                                                                            <option>Canada/Saskatchewan (UTC-06:00)</option>
                                                                            <option>America/Bogota (UTC-05:00)</option>
                                                                            <option>US/Eastern (UTC-05:00)</option>
                                                                            <option>US/East-Indiana (UTC-05:00)</option>
                                                                            <option>America/Lima (UTC-05:00)</option>
                                                                            <option>America/Bogota (UTC-05:00)</option>
                                                                            <option>Canada/Atlantic (UTC-04:00)</option>
                                                                            <option>America/Caracas (UTC-04:30)</option>
                                                                            <option>America/La_Paz (UTC-04:00)</option>
                                                                            <option>America/Santiago (UTC-04:00)</option>
                                                                            <option>Canada/Newfoundland (UTC-03:30)</option>
                                                                            <option>America/Sao_Paulo (UTC-03:00)</option>
                                                                            <option>America/Argentina/Buenos_Aires (UTC-03:00)</option>
                                                                            <option>America/Argentina/Buenos_Aires (UTC-03:00)</option>
                                                                            <option>America/Godthab (UTC-03:00)</option>
                                                                            <option>America/Noronha (UTC-02:00)</option>
                                                                            <option>Atlantic/Azores (UTC-01:00)</option>
                                                                            <option>Atlantic/Cape_Verde (UTC-01:00)</option>
                                                                            <option>Africa/Casablanca (UTC+00:00)</option>
                                                                            <option>Europe/London (UTC+00:00)</option>
                                                                            <option>Etc/Greenwich (UTC+00:00)</option>
                                                                            <option>Europe/Lisbon (UTC+00:00)</option>
                                                                            <option>Europe/London (UTC+00:00)</option>
                                                                            <option>Africa/Monrovia (UTC+00:00)</option>
                                                                            <option>UTC (UTC+00:00)</option>
                                                                            <option>Europe/Amsterdam (UTC+01:00)</option>
                                                                            <option>Europe/Belgrade (UTC+01:00)</option>
                                                                            <option>Europe/Berlin (UTC+01:00)</option>
                                                                            <option>Europe/Bern (UTC+01:00)</option>
                                                                            <option>Europe/Bratislava (UTC+01:00)</option>
                                                                            <option>Europe/Brussels (UTC+01:00)</option>
                                                                            <option>Europe/Budapest (UTC+01:00)</option>
                                                                            <option>Europe/Copenhagen (UTC+01:00)</option>
                                                                            <option>Europe/Ljubljana (UTC+01:00)</option>
                                                                            <option>Europe/Madrid (UTC+01:00)</option>
                                                                            <option>Europe/Paris (UTC+01:00)</option>
                                                                            <option>Europe/Prague (UTC+01:00)</option>
                                                                            <option>Europe/Rome (UTC+01:00)</option>
                                                                            <option>Europe/Sarajevo (UTC+01:00)</option>
                                                                            <option>Europe/Skopje (UTC+01:00)</option>
                                                                            <option>Europe/Stockholm (UTC+01:00)</option>
                                                                            <option>Europe/Vienna (UTC+01:00)</option>
                                                                            <option>Europe/Warsaw (UTC+01:00)</option>
                                                                            <option selected="selected">Africa/Lagos (UTC+01:00)</option>
                                                                            <option>Europe/Zagreb (UTC+01:00)</option>
                                                                            <option>Europe/Athens (UTC+02:00)</option>
                                                                            <option>Europe/Bucharest (UTC+02:00)</option>
                                                                            <option>Africa/Cairo (UTC+02:00)</option>
                                                                            <option>Africa/Harare (UTC+02:00)</option>
                                                                            <option>Europe/Helsinki (UTC+02:00)</option>
                                                                            <option>Europe/Istanbul (UTC+02:00)</option>
                                                                            <option>Asia/Jerusalem (UTC+02:00)</option>
                                                                            <option>Europe/Helsinki (UTC+02:00)</option>
                                                                            <option>Africa/Johannesburg (UTC+02:00)</option>
                                                                            <option>Europe/Riga (UTC+02:00)</option>
                                                                            <option>Europe/Sofia (UTC+02:00)</option>
                                                                            <option>Europe/Tallinn (UTC+02:00)</option>
                                                                            <option>Europe/Vilnius (UTC+02:00)</option>
                                                                            <option>Asia/Baghdad (UTC+03:00)</option>
                                                                            <option>Asia/Kuwait (UTC+03:00)</option>
                                                                            <option>Europe/Minsk (UTC+03:00)</option>
                                                                            <option>Africa/Nairobi (UTC+03:00)</option>
                                                                            <option>Asia/Riyadh (UTC+03:00)</option>
                                                                            <option>Europe/Volgograd (UTC+03:00)</option>
                                                                            <option>Asia/Tehran (UTC+03:30)</option>
                                                                            <option>Asia/Muscat (UTC+04:00)</option>
                                                                            <option>Asia/Baku (UTC+04:00)</option>
                                                                            <option>Europe/Moscow (UTC+04:00)</option>
                                                                            <option>Asia/Muscat (UTC+04:00)</option>
                                                                            <option>Europe/Moscow (UTC+04:00)</option>
                                                                            <option>Asia/Tbilisi (UTC+04:00)</option>
                                                                            <option>Asia/Yerevan (UTC+04:00)</option>
                                                                            <option>Asia/Kabul (UTC+04:30)</option>
                                                                            <option>Asia/Islamabad (UTC+05:00)</option>
                                                                            <option>Asia/Karachi (UTC+05:00)</option>
                                                                            <option>Asia/Tashkent (UTC+05:00)</option>
                                                                            <option>Asia/Calcutta/Chennai (UTC+05:30)</option>
                                                                            <option>Asia/Kolkata (UTC+05:30)</option>
                                                                            <option>Asia/Mumbai (UTC+05:30)</option>
                                                                            <option>Asia/New Delhi (UTC+05:30)</option>
                                                                            <option>Asia/Sri Jayawardenepura (UTC+05:30)</option>
                                                                            <option>Asia/Katmandu (UTC+05:45)</option>
                                                                            <option>Asia/Almaty (UTC+06:00)</option>
                                                                            <option>Asia/Astana (UTC+06:00)</option>
                                                                            <option>Asia/Dhaka (UTC+06:00)</option>
                                                                            <option>Asia/Yekaterinburg (UTC+06:00)</option>
                                                                            <option>Asia/Rangoon (UTC+06:30)</option>
                                                                            <option>Asia/Bangkok (UTC+07:00)</option>
                                                                            <option>Asia/Hanoi (UTC+07:00)</option>
                                                                            <option>Asia/Jakarta (UTC+07:00) Jakarta</option>
                                                                            <option>Asia/Novosibirsk (UTC+07:00)</option>
                                                                            <option>Asia/Beijing (UTC+08:00) </option>
                                                                            <option>Asia/Chongqing (UTC+08:00)</option>
                                                                            <option>Asia/Hong_Kong (UTC+08:00)</option>
                                                                            <option>Asia/Krasnoyarsk (UTC+08:00)</option>
                                                                            <option>Asia/Kuala_Lumpur (UTC+08:00)</option>
                                                                            <option>Australia/Perth (UTC+08:00)</option>
                                                                            <option>Asia/Singapore (UTC+08:00)</option>
                                                                            <option>Asia/Taipei (UTC+08:00)</option>
                                                                            <option>Asia/Ulan_Bator (UTC+08:00)</option>
                                                                            <option>Asia/Urumqi (UTC+08:00)</option>
                                                                            <option>Asia/Irkutsk (UTC+09:00)</option>
                                                                            <option>Asia/Tokyo (UTC+09:00)</option>
                                                                            <option>Asia/Sapporo (UTC+09:00)</option>
                                                                            <option>Asia/Seoul (UTC+09:00)</option>
                                                                            <option>Asia/Tokyo (UTC+09:00)</option>
                                                                            <option>Australia/Adelaide (UTC+09:30)</option>
                                                                            <option>Australia/Darwin (UTC+09:30)</option>
                                                                            <option>Australia/Brisbane (UTC+10:00)</option>
                                                                            <option>Australia/Canberra (UTC+10:00)</option>
                                                                            <option>Pacific/Guam (UTC+10:00)</option>
                                                                            <option>Australia/Hobart (UTC+10:00)</option>
                                                                            <option>Australia/Melbourne (UTC+10:00)</option>
                                                                            <option>Pacific/Port_Moresby (UTC+10:00)</option>
                                                                            <option>Australia/Sydney (UTC+10:00)</option>
                                                                            <option>Asia/Yakutsk (UTC+10:00)</option>
                                                                            <option>Asia/Vladivostok (UTC+11:00)</option>
                                                                            <option>Pacific/Auckland (UTC+12:00)</option>
                                                                            <option>Pacific/Fiji (UTC+12:00)</option>
                                                                            <option>Pacific/Kwajalein (UTC+12:00)</option>
                                                                            <option>Asia/Kamchatka (UTC+12:00)</option>
                                                                            <option>Asia/Magadan (UTC+12:00)</option>
                                                                            <option>Pacific/Fiji (UTC+12:00)</option>
                                                                            <option>Asia/Magadan (UTC+12:00)</option>
                                                                            <option>Asia/Solomon Is. (UTC+12:00) </option>
                                                                            <option>Pacific/Auckland (UTC+12:00)</option>
                                                                            <option>Pacific/Tongatapu (UTC+13:00)</option>
                                                                        </select>
                                                                    </div>


                                                                    <div class="form-group">
                                                                        <label>Additional Information</i>:</label>
                                                                        <textarea name="additional" rows="4" class="form-control" placeholder="e.g Meeting Agenda"> </textarea>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Guest Email(s)<i>Separated by commas</i>:</label>
                                                                        <textarea maxlength="500" name="guest" rows="9" class="form-control" placeholder="e.g info@newaves.com, info@konn3ct.com" required></textarea>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger text-left" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-success text-left">Send Invite</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>

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
                            <div class="box-header">
                                <h4 class="box-title align-items-start flex-column">
                                    Meeting Room Manager
{{--                                    <small class="subtitle">More than 400+ new members</small>--}}
                                </h4>

                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table no-border">
                                        <thead>
                                        <tr class="text-uppercase bg-lightest">
                                            <th style="min-width: 50px"><span class="text-fade">Room Name</span></th>
                                            <th style="min-width: 70px"><span class="text-fade">Room URL</span></th>
                                            <th style="min-width: 10px"><span class="text-fade"></span></th>
                                            @if(\Illuminate\Support\Facades\Auth::user()->plan!=1)
                                                <th style="min-width: 10px"><span class="text-fade"></span></th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($rooms as $room)
                                        <tr>
                                            <td class="pl-0 py-8">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <a href="#" class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$room->name}}</a>

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span id="c{{$room->id}}"
                                                      class="text-dark font-weight-600 d-block font-size-16">
                                                    {{url('/join/')}}/{{$room->url}}

                                                    <span class="badge badge-info">Access Code:
                                                        @if($room->password_attendee=="attendee")
                                                            Unrestricted
                                                        @else
                                                            {{$room->password_attendee}}
                                                        @endif
                                                    </span>
                                                    <br/>
                                                    @if($room->prereg!=NULL)
                                                        <a href="{{url('/preregistration/')}}/{{$room->prereg}}"
                                                           style="font-size: 10px">{{url('/preregistration/')}}/{{$room->prereg}}</a>
                                                    @endif
                                                </span>
                                                <br/>
                                                <div class="dropdown">
                                                    <Button style="font-size: 12px"
                                                            class="waves-effect waves-light btn btn-info"
                                                            onclick="copyToClipboard('#c{{$room->id}}')"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Copy Meeting Link">
                                                        <i class="fa fa-copy"></i> Copy
                                                    </Button>
                                                    <a style="font-size: 12px"
                                                       href="https://www.google.com/calendar/render?action=TEMPLATE&text={{$room->name}}&details=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}&location={{url('/join/')}}/{{$room->url}}"
                                                       class="waves-effect waves-light btn btn-primary"
                                                       data-toggle="tooltip" data-placement="top"
                                                       title="Schedule Meeting on Google Invite">
                                                        Google Calender Invite
                                                    </a>

                                                    <a style="font-size: 12px"
                                                       href="https://outlook.live.com/owa/?path=/calendar/action/compose&rru=addevent&subject={{$room->name}}&body=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}"
                                                       class="waves-effect waves-light btn btn-primary"
                                                       data-toggle="tooltip" data-placement="top"
                                                       title="Schedule Meeting on Outlook Calender">
                                                        Outlook Calendar Invite
                                                    </a>

                                                    <button style="font-size: 12px"
                                                            class="waves-effect waves-light btn btn-primary"
                                                            data-toggle="modal"
                                                            data-target=".dk-maininvite-lg-{{$room->id}}"
                                                            data-placement="top"
                                                            title="Schedule meeting with guests email">
                                                        Konn3ct Invite
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                            type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                            aria-haspopup="true" aria-expanded="false"
                                                            data-placement="top" title="Do more with meeting room">
                                                        Manage Room
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <Button type="button" class="dropdown-item" data-toggle="modal"
                                                                data-target="#dk-accesscode{{$room->id}}-modal"
                                                                data-placement="top" title="Add or remove access code">
                                                            Access Code
                                                        </Button>

                                                        <Button type="button" class="dropdown-item" data-toggle="modal"
                                                                data-target="#dk-limituser{{$room->id}}-modal"
                                                                data-placement="top"
                                                                title="Increase or decrease users size for meeting">
                                                            Users Limit
                                                        </Button>
                                                        <Button type="button" class="dropdown-item" data-toggle="modal"
                                                                data-target="#dk-roombanner{{$room->id}}-modal"
                                                                data-placement="top"
                                                                title="Upload a desired meeting banner">
                                                            Meeting Room Banner Upload
                                                        </Button>
                                                        <a href="{{route('attendance', $room->id)}}" type="button"
                                                           class="dropdown-item" data-placement="top"
                                                           title="Upload a desired meeting banner">
                                                            Attendance
                                                        </a>
                                                        @if($room->prereg==NULL)
                                                            <Button type="button" class="dropdown-item"
                                                                    data-toggle="modal"
                                                                    data-target=".dk-prereg-lg-{{$room->id}}"
                                                                    data-placement="top"
                                                                    title="Enable pre-registration">
                                                                Enable Pre-Registration
                                                            </Button>
                                                        @else
                                                            <a href="{{route("prereParticipants", $room->prereg)}}"
                                                               type="button" class="dropdown-item" data-placement="top"
                                                               title="View pre-registered users">
                                                                Pre-Registered Users
                                                            </a>
                                                            <a href="{{route("dprereg", $room->prereg)}}" type="button"
                                                               class="dropdown-item" data-placement="top"
                                                               title="Disable pre-registration">
                                                                Disable Pre-Registration
                                                            </a>
                                                        @endif

                                                    </div>
                                                </div>

                                                <div class="modal accesscode-modal fade"
                                                     id="dk-accesscode{{$room->id}}-modal" tabindex="-1" role="dialog"
                                                     aria-labelledby="mySmallModalLabel" aria-hidden="true"
                                                     style="display: none;">
                                                    <div class="modal-dialog modal-md">
                                                        <form method="post" action="{{route('accesscode')}}">
                                                            @csrf
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <h4 class="modal-title" id="mySmallModalLabel">Manage Access Code</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                You are about to change your current access code to new. <br/>
                                                                Enter your new access code below or click on "Auto Generate"<br/><br/>

                                                                <div class="form-group">
                                                                    <label>New Access Code:</label>
                                                                    <input type="text" id="dkaccesscode{{$room->id}}" name="accesscode" class="form-control" placeholder="Enter new access code" required />
                                                                    <input type="hidden" id="type{{$room->id}}" name="type" class="form-control" value="manual"/>
                                                                    <input type="hidden" name="id" class="form-control" value="{{$room->id}}"/>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer modal-footer-uniform">
                                                                <button type="submit" class="btn bg-success float-left">Save</button>
                                                                <button type="button" class="btn bg-dark float-right" onclick="document.getElementById('dkaccesscode{{$room->id}}').value=getRandomString(10);">Auto Generate</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <div class="modal limituser-modal fade" id="dk-limituser{{$room->id}}-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-md">
                                                        <form method="post" action="{{route('limituser')}}">
                                                            @csrf
                                                         <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="mySmallModalLabel">Manage User Limit</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                You are about to change your current user limit. <br/>
                                                                Choose your need carefully<br/><br/>

                                                                <div class="form-group">
                                                                    <label>User Limit:</label>
                                                                    <input type="number" id="users" name="users" aria-valuemin="2" min="2" max="{{$plan->participant}}" aria-valuemax="{{$plan->participant}}" max="{{$plan->participant}}" value="{{$room->max_participants}}" class="form-control" placeholder="Enter new access code" required />
                                                                    <input type="hidden" name="id" class="form-control" value="{{$room->id}}"/>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer modal-footer-uniform">
                                                                <button type="submit" class="btn bg-success float-left">Save</button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <div class="modal roombanner-modal fade" id="dk-roombanner{{$room->id}}-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-md">
                                                        <form method="post" action="{{route('bannerupload')}}" enctype="multipart/form-data">
                                                            @csrf
                                                         <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="mySmallModalLabel">Meeting Room Banner</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Upload a customized banner for your meeting room. <br/>
                                                                Recommended: 485px by 153px <br/><br/>

                                                                <div class="form-group row">
                                                                    <div class="col-lg-10">
                                                                        <input type="hidden" name="id" class="form-control" value="{{$room->id}}"/>
                                                                        <input type="file" class="form-control"
                                                                               name="banner" required>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                             <div class="modal-footer modal-footer-uniform">
                                                                 <button type="submit"
                                                                         class="btn bg-success float-left">
                                                                     Upload
                                                                 </button>
                                                             </div>
                                                         </div>
                                                            <!-- /.modal-content -->
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->


                                                <div class="modal dk-maininvite-lg-{{$room->id}} fade" tabindex="-1"
                                                     role="dialog" aria-labelledby="mySmallModalLabel"
                                                     aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-md">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="mySmallModalLabel">Invite
                                                                    Attendees</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                You can invite attendees via email or
                                                                whatsapp.<br/><br/>

                                                            </div>
                                                            <div class="modal-footer modal-footer-uniform">
                                                                <button class="btn bg-primary float-left"
                                                                        data-toggle="modal"
                                                                        data-target=".dk-invite-lg-{{$room->id}}"
                                                                        data-placement="top"
                                                                        title="Schedule meeting with guests email">Email
                                                                </button>
                                                                <button class="btn bg-success float-left"
                                                                        data-toggle="modal"
                                                                        data-target=".dk-whatsapinvite-lg-{{$room->id}}"
                                                                        data-placement="top"
                                                                        title="Schedule meeting with guests via whatsapp">
                                                                    Whatsapp
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->

                                                <div class="modal fade dk-invite-lg-{{$room->id}}" tabindex="-1"
                                                     role="dialog" aria-labelledby="myLargeModalLabel"
                                                     aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Konn3ct
                                                                    Invite</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>

                                                            <form method="post" action="{{route('invite')}}">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label>Meeting Title:</label>
                                                                        <input type="text" name="title" class="form-control" placeholder="Enter Title" value="" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Access Code:</label>

                                                                        @if($room->password_attendee!="attendee")
                                                                            <input type="text" name="accesscode" class="form-control" placeholder="" value="{{$room->password_attendee}}" readonly required>
                                                                        @else
                                                                            <input type="hidden" name="accesscode" class="form-control" placeholder="" value="No Access Code">
                                                                            Room is open
                                                                        @endif

                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Room Link:</label>
                                                                        <input type="text" class="form-control" placeholder="e.g https://konn3ct..." value="{{url('/join/')}}/{{$room->url}}" disabled required>
                                                                        <input type="hidden" name="roomlink" class="form-control" placeholder="e.g https://konn3ct..." value="{{url('/join/')}}/{{$room->url}}"required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Host Name:</label>
                                                                        <input type="hidden" name="roomname" class="form-control" value="{{$room->name}}" />
                                                                        <input type="text" name="hostname" class="form-control" placeholder="e.g Newwaves" required />
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Date:</label>
                                                                        <input type="date" name="date" class="form-control" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Time:</label>
                                                                        <input type="time" name="time" class="form-control" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Timezone:</label>
                                                                        <select class="form-control" id="timezone" name="timezone">
                                                                            <option>Pacific/Midway (UTC-11:00)</option>
                                                                            <option>Pacific/Samoa (UTC-11:00)</option>
                                                                            <option>Pacific/Honolulu (UTC-10:00) Hawaii</option>
                                                                            <option>US/Alaska (UTC-09:00)</option>
                                                                            <option>America/Los_Angeles (UTC-08:00)</option>
                                                                            <option>America/Tijuana (UTC-08:00)</option>
                                                                            <option>US/Arizona (UTC-07:00)</option>
                                                                            <option>America/Chihuahua (UTC-07:00)</option>
                                                                            <option>America/Chihuahua (UTC-07:00)</option>
                                                                            <option>America/Mazatlan (UTC-07:00)</option>
                                                                            <option>US/Mountain (UTC-07:00)</option>
                                                                            <option>America/Managua (UTC-06:00)</option>
                                                                            <option>US/Central (UTC-06:00)</option>
                                                                            <option>America/Mexico_City (UTC-06:00)</option>
                                                                            <option>America/Mexico_City (UTC-06:00)</option>
                                                                            <option>America/Monterrey (UTC-06:00)</option>
                                                                            <option>Canada/Saskatchewan (UTC-06:00)</option>
                                                                            <option>America/Bogota (UTC-05:00)</option>
                                                                            <option>US/Eastern (UTC-05:00)</option>
                                                                            <option>US/East-Indiana (UTC-05:00)</option>
                                                                            <option>America/Lima (UTC-05:00)</option>
                                                                            <option>America/Bogota (UTC-05:00)</option>
                                                                            <option>Canada/Atlantic (UTC-04:00)</option>
                                                                            <option>America/Caracas (UTC-04:30)</option>
                                                                            <option>America/La_Paz (UTC-04:00)</option>
                                                                            <option>America/Santiago (UTC-04:00)</option>
                                                                            <option>Canada/Newfoundland (UTC-03:30)</option>
                                                                            <option>America/Sao_Paulo (UTC-03:00)</option>
                                                                            <option>America/Argentina/Buenos_Aires (UTC-03:00)</option>
                                                                            <option>America/Argentina/Buenos_Aires (UTC-03:00)</option>
                                                                            <option>America/Godthab (UTC-03:00)</option>
                                                                            <option>America/Noronha (UTC-02:00)</option>
                                                                            <option>Atlantic/Azores (UTC-01:00)</option>
                                                                            <option>Atlantic/Cape_Verde (UTC-01:00)</option>
                                                                            <option>Africa/Casablanca (UTC+00:00)</option>
                                                                            <option>Europe/London (UTC+00:00)</option>
                                                                            <option>Etc/Greenwich (UTC+00:00)</option>
                                                                            <option>Europe/Lisbon (UTC+00:00)</option>
                                                                            <option>Europe/London (UTC+00:00)</option>
                                                                            <option>Africa/Monrovia (UTC+00:00)</option>
                                                                            <option>UTC (UTC+00:00)</option>
                                                                            <option>Europe/Amsterdam (UTC+01:00)</option>
                                                                            <option>Europe/Belgrade (UTC+01:00)</option>
                                                                            <option>Europe/Berlin (UTC+01:00)</option>
                                                                            <option>Europe/Bern (UTC+01:00)</option>
                                                                            <option>Europe/Bratislava (UTC+01:00)</option>
                                                                            <option>Europe/Brussels (UTC+01:00)</option>
                                                                            <option>Europe/Budapest (UTC+01:00)</option>
                                                                            <option>Europe/Copenhagen (UTC+01:00)</option>
                                                                            <option>Europe/Ljubljana (UTC+01:00)</option>
                                                                            <option>Europe/Madrid (UTC+01:00)</option>
                                                                            <option>Europe/Paris (UTC+01:00)</option>
                                                                            <option>Europe/Prague (UTC+01:00)</option>
                                                                            <option>Europe/Rome (UTC+01:00)</option>
                                                                            <option>Europe/Sarajevo (UTC+01:00)</option>
                                                                            <option>Europe/Skopje (UTC+01:00)</option>
                                                                            <option>Europe/Stockholm (UTC+01:00)</option>
                                                                            <option>Europe/Vienna (UTC+01:00)</option>
                                                                            <option>Europe/Warsaw (UTC+01:00)</option>
                                                                            <option selected="selected">Africa/Lagos (UTC+01:00)</option>
                                                                            <option>Europe/Zagreb (UTC+01:00)</option>
                                                                            <option>Europe/Athens (UTC+02:00)</option>
                                                                            <option>Europe/Bucharest (UTC+02:00)</option>
                                                                            <option>Africa/Cairo (UTC+02:00)</option>
                                                                            <option>Africa/Harare (UTC+02:00)</option>
                                                                            <option>Europe/Helsinki (UTC+02:00)</option>
                                                                            <option>Europe/Istanbul (UTC+02:00)</option>
                                                                            <option>Asia/Jerusalem (UTC+02:00)</option>
                                                                            <option>Europe/Helsinki (UTC+02:00)</option>
                                                                            <option>Africa/Johannesburg (UTC+02:00)</option>
                                                                            <option>Europe/Riga (UTC+02:00)</option>
                                                                            <option>Europe/Sofia (UTC+02:00)</option>
                                                                            <option>Europe/Tallinn (UTC+02:00)</option>
                                                                            <option>Europe/Vilnius (UTC+02:00)</option>
                                                                            <option>Asia/Baghdad (UTC+03:00)</option>
                                                                            <option>Asia/Kuwait (UTC+03:00)</option>
                                                                            <option>Europe/Minsk (UTC+03:00)</option>
                                                                            <option>Africa/Nairobi (UTC+03:00)</option>
                                                                            <option>Asia/Riyadh (UTC+03:00)</option>
                                                                            <option>Europe/Volgograd (UTC+03:00)</option>
                                                                            <option>Asia/Tehran (UTC+03:30)</option>
                                                                            <option>Asia/Muscat (UTC+04:00)</option>
                                                                            <option>Asia/Baku (UTC+04:00)</option>
                                                                            <option>Europe/Moscow (UTC+04:00)</option>
                                                                            <option>Asia/Muscat (UTC+04:00)</option>
                                                                            <option>Europe/Moscow (UTC+04:00)</option>
                                                                            <option>Asia/Tbilisi (UTC+04:00)</option>
                                                                            <option>Asia/Yerevan (UTC+04:00)</option>
                                                                            <option>Asia/Kabul (UTC+04:30)</option>
                                                                            <option>Asia/Islamabad (UTC+05:00)</option>
                                                                            <option>Asia/Karachi (UTC+05:00)</option>
                                                                            <option>Asia/Tashkent (UTC+05:00)</option>
                                                                            <option>Asia/Calcutta/Chennai (UTC+05:30)</option>
                                                                            <option>Asia/Kolkata (UTC+05:30)</option>
                                                                            <option>Asia/Mumbai (UTC+05:30)</option>
                                                                            <option>Asia/New Delhi (UTC+05:30)</option>
                                                                            <option>Asia/Sri Jayawardenepura (UTC+05:30)</option>
                                                                            <option>Asia/Katmandu (UTC+05:45)</option>
                                                                            <option>Asia/Almaty (UTC+06:00)</option>
                                                                            <option>Asia/Astana (UTC+06:00)</option>
                                                                            <option>Asia/Dhaka (UTC+06:00)</option>
                                                                            <option>Asia/Yekaterinburg (UTC+06:00)</option>
                                                                            <option>Asia/Rangoon (UTC+06:30)</option>
                                                                            <option>Asia/Bangkok (UTC+07:00)</option>
                                                                            <option>Asia/Hanoi (UTC+07:00)</option>
                                                                            <option>Asia/Jakarta (UTC+07:00) Jakarta</option>
                                                                            <option>Asia/Novosibirsk (UTC+07:00)</option>
                                                                            <option>Asia/Beijing (UTC+08:00) </option>
                                                                            <option>Asia/Chongqing (UTC+08:00)</option>
                                                                            <option>Asia/Hong_Kong (UTC+08:00)</option>
                                                                            <option>Asia/Krasnoyarsk (UTC+08:00)</option>
                                                                            <option>Asia/Kuala_Lumpur (UTC+08:00)</option>
                                                                            <option>Australia/Perth (UTC+08:00)</option>
                                                                            <option>Asia/Singapore (UTC+08:00)</option>
                                                                            <option>Asia/Taipei (UTC+08:00)</option>
                                                                            <option>Asia/Ulan_Bator (UTC+08:00)</option>
                                                                            <option>Asia/Urumqi (UTC+08:00)</option>
                                                                            <option>Asia/Irkutsk (UTC+09:00)</option>
                                                                            <option>Asia/Tokyo (UTC+09:00)</option>
                                                                            <option>Asia/Sapporo (UTC+09:00)</option>
                                                                            <option>Asia/Seoul (UTC+09:00)</option>
                                                                            <option>Asia/Tokyo (UTC+09:00)</option>
                                                                            <option>Australia/Adelaide (UTC+09:30)</option>
                                                                            <option>Australia/Darwin (UTC+09:30)</option>
                                                                            <option>Australia/Brisbane (UTC+10:00)</option>
                                                                            <option>Australia/Canberra (UTC+10:00)</option>
                                                                            <option>Pacific/Guam (UTC+10:00)</option>
                                                                            <option>Australia/Hobart (UTC+10:00)</option>
                                                                            <option>Australia/Melbourne (UTC+10:00)</option>
                                                                            <option>Pacific/Port_Moresby (UTC+10:00)</option>
                                                                            <option>Australia/Sydney (UTC+10:00)</option>
                                                                            <option>Asia/Yakutsk (UTC+10:00)</option>
                                                                            <option>Asia/Vladivostok (UTC+11:00)</option>
                                                                            <option>Pacific/Auckland (UTC+12:00)</option>
                                                                            <option>Pacific/Fiji (UTC+12:00)</option>
                                                                            <option>Pacific/Kwajalein (UTC+12:00)</option>
                                                                            <option>Asia/Kamchatka (UTC+12:00)</option>
                                                                            <option>Asia/Magadan (UTC+12:00)</option>
                                                                            <option>Pacific/Fiji (UTC+12:00)</option>
                                                                            <option>Asia/Magadan (UTC+12:00)</option>
                                                                            <option>Asia/Solomon Is. (UTC+12:00) </option>
                                                                            <option>Pacific/Auckland (UTC+12:00)</option>
                                                                            <option>Pacific/Tongatapu (UTC+13:00)</option>
                                                                        </select>
                                                                    </div>


                                                                    <div class="form-group">
                                                                        <label>Additional Information</i>:</label>
                                                                        <textarea name="additional" rows="4" class="form-control" placeholder="e.g Meeting Agenda"> </textarea>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Guest Email(s)<i>Separated by commas</i>:</label>
                                                                        <textarea maxlength="500" name="guest" rows="9" class="form-control" placeholder="e.g info@newaves.com, info@konn3ct.com" required></textarea>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-danger text-left"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <button type="submit"
                                                                            class="btn btn-success text-left">Send
                                                                        Invite
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>

                                                <div class="modal fade dk-whatsapinvite-lg-{{$room->id}}" tabindex="-1"
                                                     role="dialog" aria-labelledby="myLargeModalLabelw"
                                                     aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Konn3ct
                                                                    Invite</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>

                                                            <form method="post" action="{{route('whatsappinvite')}}">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <input type="hidden" name="room"
                                                                           value="{{$room->name}}">

                                                                    <div class="form-group">
                                                                        <label>Message</i>:</label>
                                                                        <textarea name="text" rows="4"
                                                                                  class="form-control"
                                                                                  placeholder="e.g We are ">*Hello*,\nYou have been invited by {{\Illuminate\Support\Facades\Auth::user()->firstname}} to attend {{$room->name}} Meeting scheduled as follows:\n\nMeeting Room Name: {{$room->name}}\nDate: {{\Carbon\Carbon::now()->toDateString()}}\nTime: {{\Carbon\Carbon::now()->toTimeString()}} Africa/Lagos (UTC+01:00)\n\nClick this link {{url('/')}}/join/{{$room->url}} to join or copy and paste in your preferred browser.\n\nThank you.\n...............\nVisit https://konn3ct.com\n...Amazing Virtual Experience</textarea>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Guest Number(s)<i>Separated by commas</i>:</label>
                                                                        <br\><span class="text-danger">Note: The phone number should start with country code e.g 234 for Nigeria</span>
                                                                        <textarea maxlength="500" name="guest" rows="9"
                                                                                  class="form-control"
                                                                                  placeholder="08166....," required>{{\Illuminate\Support\Facades\Auth::user()->phone}},</textarea>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-danger text-left"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <button type="submit"
                                                                            class="btn btn-success text-left">Send
                                                                        Invite
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>

                                                <div class="modal fade dk-prereg-lg-{{$room->id}}" tabindex="-1"
                                                     role="dialog" aria-labelledby="myLargeModalLabel"
                                                     aria-hidden="true" style="display: none;">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myLargeModalLabel">Pre
                                                                    Registration</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-hidden="true">×
                                                                </button>
                                                            </div>

                                                            <form method="post" action="{{route('prereg')}}">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label>Event Title:</label>
                                                                        <input type="text" name="title"
                                                                               class="form-control"
                                                                               placeholder="Enter Title" value=""
                                                                               required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Room Link:</label>
                                                                        <input type="text" class="form-control"
                                                                               placeholder="e.g https://konn3ct..."
                                                                               value="{{url('/join/')}}/{{$room->url}}"
                                                                               disabled required>
                                                                        <input type="hidden" name="roomlink"
                                                                               class="form-control"
                                                                               placeholder="e.g https://konn3ct..."
                                                                               value="{{url('/join/')}}/{{$room->url}}"
                                                                               required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Host Name:</label>
                                                                        <input type="hidden" name="id"
                                                                               class="form-control"
                                                                               value="{{$room->id}}"/>
                                                                        <input type="text" name="hostname"
                                                                               class="form-control"
                                                                               placeholder="e.g Newwaves" required/>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Event Date:</label>
                                                                        <input type="date" name="date"
                                                                               class="form-control" required>
                                                                        <span class="text-danger font-size-12">Joining of session on this date will be restricted to people that registered through the pre-registration link</span>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Event Time:</label>
                                                                        <input type="time" name="time"
                                                                               class="form-control" required>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>Timezone:</label>
                                                                        <select class="form-control" id="timezone"
                                                                                name="timezone">
                                                                            <option>Pacific/Midway (UTC-11:00)</option>
                                                                            <option>Pacific/Samoa (UTC-11:00)</option>
                                                                            <option>Pacific/Honolulu (UTC-10:00)
                                                                                Hawaii
                                                                            </option>
                                                                            <option>US/Alaska (UTC-09:00)</option>
                                                                            <option>America/Los_Angeles (UTC-08:00)
                                                                            </option>
                                                                            <option>America/Tijuana (UTC-08:00)</option>
                                                                            <option>US/Arizona (UTC-07:00)</option>
                                                                            <option>America/Chihuahua (UTC-07:00)
                                                                            </option>
                                                                            <option>America/Chihuahua (UTC-07:00)
                                                                            </option>
                                                                            <option>America/Mazatlan (UTC-07:00)
                                                                            </option>
                                                                            <option>US/Mountain (UTC-07:00)</option>
                                                                            <option>America/Managua (UTC-06:00)</option>
                                                                            <option>US/Central (UTC-06:00)</option>
                                                                            <option>America/Mexico_City (UTC-06:00)
                                                                            </option>
                                                                            <option>America/Mexico_City (UTC-06:00)
                                                                            </option>
                                                                            <option>America/Monterrey (UTC-06:00)
                                                                            </option>
                                                                            <option>Canada/Saskatchewan (UTC-06:00)
                                                                            </option>
                                                                            <option>America/Bogota (UTC-05:00)</option>
                                                                            <option>US/Eastern (UTC-05:00)</option>
                                                                            <option>US/East-Indiana (UTC-05:00)</option>
                                                                            <option>America/Lima (UTC-05:00)</option>
                                                                            <option>America/Bogota (UTC-05:00)</option>
                                                                            <option>Canada/Atlantic (UTC-04:00)</option>
                                                                            <option>America/Caracas (UTC-04:30)</option>
                                                                            <option>America/La_Paz (UTC-04:00)</option>
                                                                            <option>America/Santiago (UTC-04:00)
                                                                            </option>
                                                                            <option>Canada/Newfoundland (UTC-03:30)
                                                                            </option>
                                                                            <option>America/Sao_Paulo (UTC-03:00)
                                                                            </option>
                                                                            <option>America/Argentina/Buenos_Aires
                                                                                (UTC-03:00)
                                                                            </option>
                                                                            <option>America/Argentina/Buenos_Aires
                                                                                (UTC-03:00)
                                                                            </option>
                                                                            <option>America/Godthab (UTC-03:00)</option>
                                                                            <option>America/Noronha (UTC-02:00)</option>
                                                                            <option>Atlantic/Azores (UTC-01:00)</option>
                                                                            <option>Atlantic/Cape_Verde (UTC-01:00)
                                                                            </option>
                                                                            <option>Africa/Casablanca (UTC+00:00)
                                                                            </option>
                                                                            <option>Europe/London (UTC+00:00)</option>
                                                                            <option>Etc/Greenwich (UTC+00:00)</option>
                                                                            <option>Europe/Lisbon (UTC+00:00)</option>
                                                                            <option>Europe/London (UTC+00:00)</option>
                                                                            <option>Africa/Monrovia (UTC+00:00)</option>
                                                                            <option>UTC (UTC+00:00)</option>
                                                                            <option>Europe/Amsterdam (UTC+01:00)
                                                                            </option>
                                                                            <option>Europe/Belgrade (UTC+01:00)</option>
                                                                            <option>Europe/Berlin (UTC+01:00)</option>
                                                                            <option>Europe/Bern (UTC+01:00)</option>
                                                                            <option>Europe/Bratislava (UTC+01:00)
                                                                            </option>
                                                                            <option>Europe/Brussels (UTC+01:00)</option>
                                                                            <option>Europe/Budapest (UTC+01:00)</option>
                                                                            <option>Europe/Copenhagen (UTC+01:00)
                                                                            </option>
                                                                            <option>Europe/Ljubljana (UTC+01:00)
                                                                            </option>
                                                                            <option>Europe/Madrid (UTC+01:00)</option>
                                                                            <option>Europe/Paris (UTC+01:00)</option>
                                                                            <option>Europe/Prague (UTC+01:00)</option>
                                                                            <option>Europe/Rome (UTC+01:00)</option>
                                                                            <option>Europe/Sarajevo (UTC+01:00)</option>
                                                                            <option>Europe/Skopje (UTC+01:00)</option>
                                                                            <option>Europe/Stockholm (UTC+01:00)
                                                                            </option>
                                                                            <option>Europe/Vienna (UTC+01:00)</option>
                                                                            <option>Europe/Warsaw (UTC+01:00)</option>
                                                                            <option selected="selected">Africa/Lagos
                                                                                (UTC+01:00)
                                                                            </option>
                                                                            <option>Europe/Zagreb (UTC+01:00)</option>
                                                                            <option>Europe/Athens (UTC+02:00)</option>
                                                                            <option>Europe/Bucharest (UTC+02:00)
                                                                            </option>
                                                                            <option>Africa/Cairo (UTC+02:00)</option>
                                                                            <option>Africa/Harare (UTC+02:00)</option>
                                                                            <option>Europe/Helsinki (UTC+02:00)</option>
                                                                            <option>Europe/Istanbul (UTC+02:00)</option>
                                                                            <option>Asia/Jerusalem (UTC+02:00)</option>
                                                                            <option>Europe/Helsinki (UTC+02:00)</option>
                                                                            <option>Africa/Johannesburg (UTC+02:00)
                                                                            </option>
                                                                            <option>Europe/Riga (UTC+02:00)</option>
                                                                            <option>Europe/Sofia (UTC+02:00)</option>
                                                                            <option>Europe/Tallinn (UTC+02:00)</option>
                                                                            <option>Europe/Vilnius (UTC+02:00)</option>
                                                                            <option>Asia/Baghdad (UTC+03:00)</option>
                                                                            <option>Asia/Kuwait (UTC+03:00)</option>
                                                                            <option>Europe/Minsk (UTC+03:00)</option>
                                                                            <option>Africa/Nairobi (UTC+03:00)</option>
                                                                            <option>Asia/Riyadh (UTC+03:00)</option>
                                                                            <option>Europe/Volgograd (UTC+03:00)
                                                                            </option>
                                                                            <option>Asia/Tehran (UTC+03:30)</option>
                                                                            <option>Asia/Muscat (UTC+04:00)</option>
                                                                            <option>Asia/Baku (UTC+04:00)</option>
                                                                            <option>Europe/Moscow (UTC+04:00)</option>
                                                                            <option>Asia/Muscat (UTC+04:00)</option>
                                                                            <option>Europe/Moscow (UTC+04:00)</option>
                                                                            <option>Asia/Tbilisi (UTC+04:00)</option>
                                                                            <option>Asia/Yerevan (UTC+04:00)</option>
                                                                            <option>Asia/Kabul (UTC+04:30)</option>
                                                                            <option>Asia/Islamabad (UTC+05:00)</option>
                                                                            <option>Asia/Karachi (UTC+05:00)</option>
                                                                            <option>Asia/Tashkent (UTC+05:00)</option>
                                                                            <option>Asia/Calcutta/Chennai (UTC+05:30)
                                                                            </option>
                                                                            <option>Asia/Kolkata (UTC+05:30)</option>
                                                                            <option>Asia/Mumbai (UTC+05:30)</option>
                                                                            <option>Asia/New Delhi (UTC+05:30)</option>
                                                                            <option>Asia/Sri Jayawardenepura
                                                                                (UTC+05:30)
                                                                            </option>
                                                                            <option>Asia/Katmandu (UTC+05:45)</option>
                                                                            <option>Asia/Almaty (UTC+06:00)</option>
                                                                            <option>Asia/Astana (UTC+06:00)</option>
                                                                            <option>Asia/Dhaka (UTC+06:00)</option>
                                                                            <option>Asia/Yekaterinburg (UTC+06:00)
                                                                            </option>
                                                                            <option>Asia/Rangoon (UTC+06:30)</option>
                                                                            <option>Asia/Bangkok (UTC+07:00)</option>
                                                                            <option>Asia/Hanoi (UTC+07:00)</option>
                                                                            <option>Asia/Jakarta (UTC+07:00) Jakarta
                                                                            </option>
                                                                            <option>Asia/Novosibirsk (UTC+07:00)
                                                                            </option>
                                                                            <option>Asia/Beijing (UTC+08:00)</option>
                                                                            <option>Asia/Chongqing (UTC+08:00)</option>
                                                                            <option>Asia/Hong_Kong (UTC+08:00)</option>
                                                                            <option>Asia/Krasnoyarsk (UTC+08:00)
                                                                            </option>
                                                                            <option>Asia/Kuala_Lumpur (UTC+08:00)
                                                                            </option>
                                                                            <option>Australia/Perth (UTC+08:00)</option>
                                                                            <option>Asia/Singapore (UTC+08:00)</option>
                                                                            <option>Asia/Taipei (UTC+08:00)</option>
                                                                            <option>Asia/Ulan_Bator (UTC+08:00)</option>
                                                                            <option>Asia/Urumqi (UTC+08:00)</option>
                                                                            <option>Asia/Irkutsk (UTC+09:00)</option>
                                                                            <option>Asia/Tokyo (UTC+09:00)</option>
                                                                            <option>Asia/Sapporo (UTC+09:00)</option>
                                                                            <option>Asia/Seoul (UTC+09:00)</option>
                                                                            <option>Asia/Tokyo (UTC+09:00)</option>
                                                                            <option>Australia/Adelaide (UTC+09:30)
                                                                            </option>
                                                                            <option>Australia/Darwin (UTC+09:30)
                                                                            </option>
                                                                            <option>Australia/Brisbane (UTC+10:00)
                                                                            </option>
                                                                            <option>Australia/Canberra (UTC+10:00)
                                                                            </option>
                                                                            <option>Pacific/Guam (UTC+10:00)</option>
                                                                            <option>Australia/Hobart (UTC+10:00)
                                                                            </option>
                                                                            <option>Australia/Melbourne (UTC+10:00)
                                                                            </option>
                                                                            <option>Pacific/Port_Moresby (UTC+10:00)
                                                                            </option>
                                                                            <option>Australia/Sydney (UTC+10:00)
                                                                            </option>
                                                                            <option>Asia/Yakutsk (UTC+10:00)</option>
                                                                            <option>Asia/Vladivostok (UTC+11:00)
                                                                            </option>
                                                                            <option>Pacific/Auckland (UTC+12:00)
                                                                            </option>
                                                                            <option>Pacific/Fiji (UTC+12:00)</option>
                                                                            <option>Pacific/Kwajalein (UTC+12:00)
                                                                            </option>
                                                                            <option>Asia/Kamchatka (UTC+12:00)</option>
                                                                            <option>Asia/Magadan (UTC+12:00)</option>
                                                                            <option>Pacific/Fiji (UTC+12:00)</option>
                                                                            <option>Asia/Magadan (UTC+12:00)</option>
                                                                            <option>Asia/Solomon Is. (UTC+12:00)
                                                                            </option>
                                                                            <option>Pacific/Auckland (UTC+12:00)
                                                                            </option>
                                                                            <option>Pacific/Tongatapu (UTC+13:00)
                                                                            </option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label>About the Event</i>:</label>
                                                                        <textarea name="about" rows="4"
                                                                                  class="form-control"
                                                                                  placeholder="e.g Meeting Agenda"> </textarea>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                            class="btn btn-danger text-left"
                                                                            data-dismiss="modal">Close
                                                                    </button>
                                                                    <button type="submit"
                                                                            class="btn btn-success text-left">Submit
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>


                                            </td>

                                            <td>
                                                <form action="/joinroom" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$room->id}}"/>
                                                    <Button type="submit"
                                                            class="waves-effect waves-light btn btn-success"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Start the meeting">
                                                        <i class="fa fa-arrow-right"></i> Konn3ct Now <br>
                                                        <span class="font-size-10">Start Meeting</span>
                                                    </Button>
                                                </form>
                                            </td>
                                            @if(\Illuminate\Support\Facades\Auth::user()->plan!=1)
                                                @if($room->default_room!="yes")
                                                    <td>
                                                        <form action="/deleteroom" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$room->id}}" />
                                                            <Button type="submit" class="waves-effect waves-light btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete the meeting">
                                                                <i class="fa fa-trash"></i> Delete
                                                            </Button>
                                                        </form>
                                                    </td>
                                                @endif
                                            @endif
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
            </section>
            <!-- /.content -->

            <!-- Modal -->
            <div class="modal modal-left fade" id="modal-left" tabindex="-1">
                <form action="/createroom" method="POST">
                    @csrf

                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create A New Room</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        @if($plan->id!=1)
                            <div class="modal-body">
                                <div class="col-12">
                                    <!-- Basic Forms -->
                                    <div class="box">
                                        <!-- /.box-header -->
                                            <div class="box-body">
    {{--                                            <h4 class="mt-0 mb-20">1. Customer Info:</h4>--}}
                                                <div class="form-group">
                                                    <label>Room Name:</label>
                                                    <input type="text" name="name" class="form-control" placeholder="e.g My Room" required>
                                                </div>

                                                <div class="form-group @if(!$plan->customize_link) hidden @endif">
                                                    <label>Room URL:</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" style="background-color: #7193AA">konn3ct.com/</span>
                                                        </div>
                                                        <input type="text" name="url" class="form-control" placeholder="myroom (optional)">
                                                    </div>
                                                </div>

                                                <!-- select -->
                                                <div class="form-group @if(!$plan->dialin) hidden @endif">
                                                    <label>Dial Number:</label>
                                                    <select class="form-control" name="dial_number">
                                                        <option>+1 970-519-2253</option>
                                                    </select>
                                                </div>


                                                <div class="form-group @if(!$plan->access_code) hidden @endif">
                                                    <label>Access Code:</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" name="access_code" class="form-control" value="" placeholder="Currently Open (optional)">
                                                    </div>
                                                </div>

                                                <div class="c-inputs-stacked">
                                                    <input type="checkbox" name="muj" id="checkbox_123">
                                                    <label for="checkbox_123" class="block">Mute user on join</label>
                                                </div>
                                                <div class="c-inputs-stacked">
                                                    <input type="checkbox" name="aujam" id="checkbox_234">
                                                    <label for="checkbox_234" class="block">All user join as moderator</label>
                                                </div>
                                                <div class="c-inputs-stacked">
                                                    <input type="checkbox" name="dpuc" id="checkbox_34">
                                                    <label for="checkbox_34" class="block">Disable Group Chat</label>
                                                </div>
    {{--                                            <div class="c-inputs-stacked">--}}
    {{--                                                <input type="checkbox" name="dprc" id="checkbox_4">--}}
    {{--                                                <label for="checkbox_4" class="block">Disable private chat</label>--}}
    {{--                                            </div>--}}
                                                <div class="c-inputs-stacked">
                                                    <input type="checkbox" name="ewma" id="checkbox_5">
                                                    <label for="checkbox_5" class="block">Enable Webcam for Moderator alone</label>
                                                </div>
                                                <div class="c-inputs-stacked">
                                                    <input type="checkbox" name="dum" id="checkbox_31">
                                                    <label for="checkbox_31" class="block">Disable User Microphone</label>
                                                </div>
                                                <div class="c-inputs-stacked">
                                                    <input type="checkbox" name="dsn" id="checkbox_41">
                                                    <label for="checkbox_41" class="block">Disable Konn3ct Doc</label>
                                                </div>
    {{--                                            <div class="c-inputs-stacked">--}}
    {{--                                                <input type="checkbox" name="dwr" id="checkbox_42">--}}
    {{--                                                <label for="checkbox_42" class="block">Disable Waiting Room</label>--}}
    {{--                                            </div>--}}

                                            </div>
                                            <!-- /.box-body -->
                                            {{--<div class="box-footer">
                                                <button type="submit" class="btn btn-rounded btn-danger">Cancel</button>
                                                <button type="submit" class="btn btn-rounded btn-success pull-right">Submit</button>
                                            </div>--}}
                                    </div>
                                    <!-- /.box -->
                                </div>
                            </div>
                            <div class="modal-footer modal-footer-uniform">
                                <button type="button" class="btn bg-gradient-danger" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn bg-gradient-success float-right">Create Room</button>
                            </div>
                        @else
                            <div class="modal-body">
                                <div class="col-12">
                                    <!-- Basic Forms -->
                                    <div class="box text-center">
                                        <!-- /.box-header -->
                                        Only available to Lite, Pro & Enterprise Plans. <br> <a class="btn btn-success" href="{{route('changeplan',3)}}">Upgrade Now</a>.
                                    </div>
                                    <!-- /.box -->
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                </form>
            </div>
            <!-- /.modal -->

            <div class="modal activatepro-modal fade" id="activatepro-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="mySmallModalLabel">FREE TRIAL ACTIVATION</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            You are about to upgrade your plan from Basic to Pro for a period of seven (7) days only. At the expiration of the trial period, you have the choice of upgrading to Lite/Pro Plan or maintaining Basic Plan.
                        </div>
                        <div class="modal-footer modal-footer-uniform">
                            <a href="/activateft" class="btn bg-success float-left">Activate</a>
                            <button type="button" class="btn bg-dark float-right" data-dismiss="modal">Later</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->


            <script>
                function copyToClipboard(element) {
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val($(element).text()).select();
                    document.execCommand("copy");
                    $temp.remove();
                }

                export default {
                    components: {Input}
                }
            </script>

            <script>
                function getRandomString(length) {
                    var randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                    var result = '';
                    for (var i = 0; i < length; i++) {
                        result += randomChars.charAt(Math.floor(Math.random() * randomChars.length));
                    }
                    return result;
                }
            </script>

@endsection
