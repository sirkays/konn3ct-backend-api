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

                    <span class="badge badge-info" style="margin-bottom: 10px; font-weight: bolder">Your Referral Code: {{\Illuminate\Support\Facades\Auth::user()->referral_code}}</span>

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
                                                <span class="text-dark d-block">
                                                  <strong>Link:</strong> <span id="c{{$room->id}}">{{url('/join/')}}/{{$room->url}}</span>
                                                </span>

                                                <br/>

                                                <form action="/joinroom" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$room->id}}" />

                                                    <div class="dropdown">
                                                        <Button type="submit" class="waves-effect waves-light font-size-10 btn btn-success">
                                                            Konn3ct Now
                                                        </Button>

                                                </form>

                                                        <button class="btn btn-outline-primary dropdown-toggle font-size-10" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Manage
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                            <Button type="button" class="dropdown-item" class="waves-effect waves-light btn" onclick="copyToClipboard('#c{{$room->id}}')">
                                                                Copy
                                                            </Button>

                                                            <a class="dropdown-item" href="https://www.google.com/calendar/render?action=TEMPLATE&text={{$room->name}}&details=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}&location={{url('/join/')}}/{{$room->url}}" class="waves-effect waves-light btn btn-primary">
                                                                Add to Google Calender
                                                            </a>
                                                            <a class="dropdown-item" href="https://outlook.live.com/owa/?path=/calendar/action/compose&rru=addevent&subject={{$room->name}}&body=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}" class="waves-effect waves-light btn btn-primary">
                                                                Add to Outlook Calender
                                                            </a>
                                                            <form action="/deleteroom" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{$room->id}}" />
                                                                <Button type="submit" class="waves-effect waves-light btn">
                                                                    Delete
                                                                </Button>
                                                            </form>
                                                        </div>
                                                    </div>



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
                                            <th style="min-width: 10px"><span class="text-fade"></span></th>
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
                                                <span id="c{{$room->id}}" class="text-dark font-weight-600 d-block font-size-16">
                                                    {{url('/join/')}}/{{$room->url}}
                                                </span>
                                                <br/>
                                                <Button style="font-size: 12px" class="waves-effect waves-light btn btn-info" onclick="copyToClipboard('#c{{$room->id}}')">
                                                    <i class="fa fa-copy"></i> Copy
                                                </Button>
                                                <a style="font-size: 12px" href="https://www.google.com/calendar/render?action=TEMPLATE&text={{$room->name}}&details=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}&location={{url('/join/')}}/{{$room->url}}" class="waves-effect waves-light btn btn-primary">
                                                    Add to Google Calender
                                                </a>

                                                <a style="font-size: 12px" href="https://outlook.live.com/owa/?path=/calendar/action/compose&rru=addevent&subject={{$room->name}}&body=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}" class="waves-effect waves-light btn btn-primary">
                                                   Add to Outlook Calender
                                                </a>

                                                <button style="font-size: 12px"  class="waves-effect waves-light btn btn-primary" data-toggle="modal" data-target=".invite-lg-{{$room->id}}">
                                                   Invite Participant
                                                </button>

                                            </td>
                                            <td>
                                                <form action="/joinroom" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$room->id}}" />
                                                    <Button type="submit" class="waves-effect waves-light btn btn-success">
                                                        <i class="fa fa-arrow-right"></i> Konn3ct Now
                                                    </Button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="/deleteroom" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$room->id}}" />
                                                    <Button type="submit" class="waves-effect waves-light btn btn-danger">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </Button>
                                                </form>
                                            </td>
                                        </tr>

                                        <div class="modal fade invite-lg-{{$room->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myLargeModalLabel">Invite Participant</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                    </div>

                                                    <form method="post" action="{{route('invite')}}">
                                                    <div class="modal-body">
                                                            @csrf
                                                        <div class="form-group">
                                                            <label>Room Name:</label>
                                                            <input type="text" name="roomname" class="form-control" placeholder="e.g My Room" value="{{$room->name}}" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Room Link:</label>
                                                            <input type="text"class="form-control" placeholder="e.g https://konn3ct..." value="{{url('/join/')}}/{{$room->url}}" disabled required>
                                                            <input type="hidden" name="roomlink" class="form-control" placeholder="e.g https://konn3ct..." value="{{url('/join/')}}/{{$room->url}}"required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Host Name:</label>
                                                            <input type="text" name="hostname" class="form-control" placeholder="e.g Newwaves" required>
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
                                                            <label>Guest Email(s)<i>Separated by commas</i>:</label>
                                                            <textarea name="guest" rows="9" class="form-control" placeholder="e.g info@newaves.com, info@konn3ct.com" required></textarea>
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
                                                    <option>+1 970-245-1026</option>
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
                                                <label for="checkbox_34" class="block">Disable public chat</label>
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
                                                <label for="checkbox_41" class="block">Disable Shared Note</label>
                                            </div>

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
                    </div>
                </div>
                </form>
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
    </script>

@endsection
