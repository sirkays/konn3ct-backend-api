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
                                            <a href="#"
                                               class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$room->name}}</a>

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
                                                </span>
                                    <br/>
                                    <div class="dropdown">
                                        <Button style="font-size: 12px" class="waves-effect waves-light btn btn-info"
                                                onclick="copyToClipboard('#c{{$room->id}}')">
                                            <i class="fa fa-copy"></i> Copy
                                        </Button>
                                        <a style="font-size: 12px"
                                           href="https://www.google.com/calendar/render?action=TEMPLATE&text={{$room->name}}&details=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}&location={{url('/join/')}}/{{$room->url}}"
                                           class="waves-effect waves-light btn btn-primary">
                                            Google Calender Invite
                                        </a>

                                        <a style="font-size: 12px"
                                           href="https://outlook.live.com/owa/?path=/calendar/action/compose&rru=addevent&subject={{$room->name}}&body=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}"
                                           class="waves-effect waves-light btn btn-primary">
                                            Outlook Calendar Invite
                                        </a>

                                        <button style="font-size: 12px" class="waves-effect waves-light btn btn-primary"
                                                data-toggle="modal" data-target=".invite-lg-{{$room->id}}">
                                            Konn3ct Invite
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                            Manage Room
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <Button type="button" class="dropdown-item" data-toggle="modal"
                                                    data-target="#accesscode{{$room->id}}-modal">
                                                Access Code
                                            </Button>

                                            <Button type="button" class="dropdown-item" data-toggle="modal"
                                                    data-target="#limituser{{$room->id}}-modal">
                                                Users Limit
                                            </Button>
                                            <Button type="button" class="dropdown-item" data-toggle="modal"
                                                    data-target="#roombanner{{$room->id}}-modal">
                                                Meeting Room Banner Upload
                                            </Button>
                                        </div>
                                    </div>

                                    <div class="modal accesscode-modal fade" id="accesscode{{$room->id}}-modal"
                                         tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
                                         aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-md">
                                            <form method="post" action="{{route('accesscode')}}">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="mySmallModalLabel">Manage Access
                                                            Code</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">×
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        You are about to change your current access code to new. <br/>
                                                        Enter your new access code below or click on "Auto
                                                        Generate"<br/><br/>

                                                        <div class="form-group">
                                                            <label>New Access Code:</label>
                                                            <input type="text" id="accesscode" name="accesscode"
                                                                   class="form-control"
                                                                   placeholder="Enter new access code" required/>
                                                            <input type="hidden" id="type" name="type"
                                                                   class="form-control" value="manual"/>
                                                            <input type="hidden" name="id" class="form-control"
                                                                   value="{{$room->id}}"/>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer modal-footer-uniform">
                                                        <button type="submit" class="btn bg-success float-left">Save
                                                        </button>
                                                        <button type="submit" class="btn bg-dark float-right"
                                                                onclick="document.getElementById('type').value='auto';document.getElementById('accesscode').value='.......';">
                                                            Auto Generate
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </form>
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

                                    <div class="modal limituser-modal fade" id="limituser{{$room->id}}-modal"
                                         tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
                                         aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-md">
                                            <form method="post" action="{{route('limituser')}}">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="mySmallModalLabel">Manage User
                                                            Limit</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">×
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        You are about to change your current user limit. <br/>
                                                        Choose your need carefully<br/><br/>

                                                        <div class="form-group">
                                                            <label>User Limit:</label>
                                                            <input type="number" id="users" name="users"
                                                                   aria-valuemin="2" min="2"
                                                                   max="{{$plan->participant}}"
                                                                   aria-valuemax="{{$plan->participant}}"
                                                                   max="{{$plan->participant}}"
                                                                   value="{{$room->max_participants}}"
                                                                   class="form-control"
                                                                   placeholder="Enter new access code" required/>
                                                            <input type="hidden" name="id" class="form-control"
                                                                   value="{{$room->id}}"/>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer modal-footer-uniform">
                                                        <button type="submit" class="btn bg-success float-left">Save
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </form>
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->


                                    <div class="modal roombanner-modal fade" id="roombanner{{$room->id}}-modal"
                                         tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
                                         aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-md">
                                            <form method="post" action="{{route('bannerupload')}}"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="mySmallModalLabel">Meeting Room
                                                            Banner</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">×
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Upload a customized banner for your meeting room. <br/>
                                                        Recommended: 485px by 153px <br/><br/>

                                                        <div class="form-group row">
                                                            <div class="col-lg-10">
                                                                <input type="hidden" name="id" class="form-control"
                                                                       value="{{$room->id}}"/>
                                                                <input type="file" class="form-control" name="banner"
                                                                       required>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer modal-footer-uniform">
                                                        <button type="submit" class="btn bg-success float-left">Upload
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </form>
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->


                                </td>

                                <td>
                                    <form action="/joinroom" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$room->id}}"/>
                                        <Button type="submit" class="waves-effect waves-light btn btn-success">
                                            <i class="fa fa-arrow-right"></i> Konn3ct Now
                                        </Button>
                                    </form>
                                </td>
                                @if(\Illuminate\Support\Facades\Auth::user()->plan!=1)
                                    @if($room->default_room!="yes")
                                        <td>
                                            <form action="/deleteroom" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$room->id}}"/>
                                                <Button type="submit" class="waves-effect waves-light btn btn-danger">
                                                    <i class="fa fa-trash"></i> Delete
                                                </Button>
                                            </form>
                                        </td>
                                    @endif
                                @endif
                            </tr>

                            <div class="modal fade invite-lg-{{$room->id}}" tabindex="-1" role="dialog"
                                 aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myLargeModalLabel">Konn3ct Invite</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                ×
                                            </button>
                                        </div>

                                        <form method="post" action="{{route('invite')}}">
                                            <div class="modal-body">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Meeting Title:</label>
                                                    <input type="text" name="title" class="form-control"
                                                           placeholder="Enter Title" value="" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Access Code:</label>

                                                    @if($room->password_attendee!="attendee")
                                                        <input type="text" name="accesscode" class="form-control"
                                                               placeholder="" value="{{$room->password_attendee}}"
                                                               readonly required>
                                                    @else
                                                        <input type="hidden" name="accesscode" class="form-control"
                                                               placeholder="" value="No Access Code">
                                                        Room is open
                                                    @endif

                                                </div>

                                                <div class="form-group">
                                                    <label>Room Link:</label>
                                                    <input type="text" class="form-control"
                                                           placeholder="e.g https://konn3ct..."
                                                           value="{{url('/join/')}}/{{$room->url}}" disabled required>
                                                    <input type="hidden" name="roomlink" class="form-control"
                                                           placeholder="e.g https://konn3ct..."
                                                           value="{{url('/join/')}}/{{$room->url}}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Host Name:</label>
                                                    <input type="hidden" name="roomname" class="form-control"
                                                           value="{{$room->name}}"/>
                                                    <input type="text" name="hostname" class="form-control"
                                                           placeholder="e.g Newwaves" required/>
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
                                                        <option>Asia/Beijing (UTC+08:00)</option>
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
                                                        <option>Asia/Solomon Is. (UTC+12:00)</option>
                                                        <option>Pacific/Auckland (UTC+12:00)</option>
                                                        <option>Pacific/Tongatapu (UTC+13:00)</option>
                                                    </select>
                                                </div>


                                                <div class="form-group">
                                                    <label>Additional Information</i>:</label>
                                                    <textarea name="additional" rows="4" class="form-control"
                                                              placeholder="e.g Meeting Agenda"> </textarea>
                                                </div>

                                                <div class="form-group">
                                                    <label>Guest Email(s)<i>Separated by commas</i>:</label>
                                                    <textarea maxlength="500" name="guest" rows="9" class="form-control"
                                                              placeholder="e.g info@newaves.com, info@konn3ct.com"
                                                              required></textarea>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger text-left"
                                                        data-dismiss="modal">Close
                                                </button>
                                                <button type="submit" class="btn btn-success text-left">Send Invite
                                                </button>
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
