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
                                        <td><a href="{{route('resendinvites', $data->id)}}" class="btn btn-primary">Send
                                                Again</a></td>
                                    </tr>

                                    {{--                                    <div class="modal fade dk-whatsapinvite-lg-{{$room->id}}" tabindex="-1"--}}
                                    {{--                                         role="dialog" aria-labelledby="myLargeModalLabel"--}}
                                    {{--                                         aria-hidden="true" style="display: none;">--}}
                                    {{--                                        <div class="modal-dialog modal-lg">--}}
                                    {{--                                            <div class="modal-content">--}}
                                    {{--                                                <div class="modal-header">--}}
                                    {{--                                                    <h4 class="modal-title" id="myLargeModalLabel">Whatsapp--}}
                                    {{--                                                        Invite</h4>--}}
                                    {{--                                                    <button type="button" class="close" data-dismiss="modal"--}}
                                    {{--                                                            aria-hidden="true">×--}}
                                    {{--                                                    </button>--}}
                                    {{--                                                </div>--}}

                                    {{--                                                <form method="post" action="{{route('whatsappinvite')}}">--}}
                                    {{--                                                    <div class="modal-body">--}}
                                    {{--                                                        @csrf--}}
                                    {{--                                                        <div class="form-group">--}}
                                    {{--                                                            <label>Meeting Title:</label>--}}
                                    {{--                                                            <input type="text" name="title"--}}
                                    {{--                                                                   class="form-control"--}}
                                    {{--                                                                   placeholder="Enter Title" value=""--}}
                                    {{--                                                                   required>--}}
                                    {{--                                                        </div>--}}

                                    {{--                                                        <div class="form-group">--}}
                                    {{--                                                            <label>Access Code:</label>--}}

                                    {{--                                                            @if($room->password_attendee!="attendee")--}}
                                    {{--                                                                <input type="text" name="accesscode"--}}
                                    {{--                                                                       class="form-control" placeholder=""--}}
                                    {{--                                                                       value="{{$room->password_attendee}}"--}}
                                    {{--                                                                       readonly required>--}}
                                    {{--                                                            @else--}}
                                    {{--                                                                <input type="hidden" name="accesscode"--}}
                                    {{--                                                                       class="form-control" placeholder=""--}}
                                    {{--                                                                       value="No Access Code">--}}
                                    {{--                                                                Room is open--}}
                                    {{--                                                            @endif--}}

                                    {{--                                                        </div>--}}

                                    {{--                                                        <div class="form-group">--}}
                                    {{--                                                            <label>Room Link:</label>--}}
                                    {{--                                                            <input type="text" class="form-control"--}}
                                    {{--                                                                   placeholder="e.g https://konn3ct..."--}}
                                    {{--                                                                   value="{{url('/join/')}}/{{$room->url}}"--}}
                                    {{--                                                                   disabled required>--}}
                                    {{--                                                            <input type="hidden" name="roomlink"--}}
                                    {{--                                                                   class="form-control"--}}
                                    {{--                                                                   placeholder="e.g https://konn3ct..."--}}
                                    {{--                                                                   value="{{url('/join/')}}/{{$room->url}}"--}}
                                    {{--                                                                   required>--}}
                                    {{--                                                        </div>--}}

                                    {{--                                                        <div class="form-group">--}}
                                    {{--                                                            <label>Host Name:</label>--}}
                                    {{--                                                            <input type="hidden" name="roomname"--}}
                                    {{--                                                                   class="form-control"--}}
                                    {{--                                                                   value="{{$room->name}}"/>--}}
                                    {{--                                                            <input type="text" name="hostname"--}}
                                    {{--                                                                   class="form-control"--}}
                                    {{--                                                                   placeholder="e.g Newwaves" required/>--}}
                                    {{--                                                        </div>--}}

                                    {{--                                                        <div class="form-group">--}}
                                    {{--                                                            <label>Date:</label>--}}
                                    {{--                                                            <input type="date" name="date"--}}
                                    {{--                                                                   class="form-control" required>--}}
                                    {{--                                                        </div>--}}

                                    {{--                                                        <div class="form-group">--}}
                                    {{--                                                            <label>Time:</label>--}}
                                    {{--                                                            <input type="time" name="time"--}}
                                    {{--                                                                   class="form-control" required>--}}
                                    {{--                                                        </div>--}}

                                    {{--                                                        <div class="form-group">--}}
                                    {{--                                                            <label>Timezone:</label>--}}
                                    {{--                                                            <select class="form-control" id="timezone"--}}
                                    {{--                                                                    name="timezone">--}}
                                    {{--                                                                <option>Pacific/Midway (UTC-11:00)</option>--}}
                                    {{--                                                                <option>Pacific/Samoa (UTC-11:00)</option>--}}
                                    {{--                                                                <option>Pacific/Honolulu (UTC-10:00)--}}
                                    {{--                                                                    Hawaii--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>US/Alaska (UTC-09:00)</option>--}}
                                    {{--                                                                <option>America/Los_Angeles (UTC-08:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>America/Tijuana (UTC-08:00)</option>--}}
                                    {{--                                                                <option>US/Arizona (UTC-07:00)</option>--}}
                                    {{--                                                                <option>America/Chihuahua (UTC-07:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>America/Chihuahua (UTC-07:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>America/Mazatlan (UTC-07:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>US/Mountain (UTC-07:00)</option>--}}
                                    {{--                                                                <option>America/Managua (UTC-06:00)</option>--}}
                                    {{--                                                                <option>US/Central (UTC-06:00)</option>--}}
                                    {{--                                                                <option>America/Mexico_City (UTC-06:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>America/Mexico_City (UTC-06:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>America/Monterrey (UTC-06:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Canada/Saskatchewan (UTC-06:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>America/Bogota (UTC-05:00)</option>--}}
                                    {{--                                                                <option>US/Eastern (UTC-05:00)</option>--}}
                                    {{--                                                                <option>US/East-Indiana (UTC-05:00)</option>--}}
                                    {{--                                                                <option>America/Lima (UTC-05:00)</option>--}}
                                    {{--                                                                <option>America/Bogota (UTC-05:00)</option>--}}
                                    {{--                                                                <option>Canada/Atlantic (UTC-04:00)</option>--}}
                                    {{--                                                                <option>America/Caracas (UTC-04:30)</option>--}}
                                    {{--                                                                <option>America/La_Paz (UTC-04:00)</option>--}}
                                    {{--                                                                <option>America/Santiago (UTC-04:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Canada/Newfoundland (UTC-03:30)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>America/Sao_Paulo (UTC-03:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>America/Argentina/Buenos_Aires--}}
                                    {{--                                                                    (UTC-03:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>America/Argentina/Buenos_Aires--}}
                                    {{--                                                                    (UTC-03:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>America/Godthab (UTC-03:00)</option>--}}
                                    {{--                                                                <option>America/Noronha (UTC-02:00)</option>--}}
                                    {{--                                                                <option>Atlantic/Azores (UTC-01:00)</option>--}}
                                    {{--                                                                <option>Atlantic/Cape_Verde (UTC-01:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Africa/Casablanca (UTC+00:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Europe/London (UTC+00:00)</option>--}}
                                    {{--                                                                <option>Etc/Greenwich (UTC+00:00)</option>--}}
                                    {{--                                                                <option>Europe/Lisbon (UTC+00:00)</option>--}}
                                    {{--                                                                <option>Europe/London (UTC+00:00)</option>--}}
                                    {{--                                                                <option>Africa/Monrovia (UTC+00:00)</option>--}}
                                    {{--                                                                <option>UTC (UTC+00:00)</option>--}}
                                    {{--                                                                <option>Europe/Amsterdam (UTC+01:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Europe/Belgrade (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Berlin (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Bern (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Bratislava (UTC+01:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Europe/Brussels (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Budapest (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Copenhagen (UTC+01:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Europe/Ljubljana (UTC+01:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Europe/Madrid (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Paris (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Prague (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Rome (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Sarajevo (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Skopje (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Stockholm (UTC+01:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Europe/Vienna (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Warsaw (UTC+01:00)</option>--}}
                                    {{--                                                                <option selected="selected">Africa/Lagos--}}
                                    {{--                                                                    (UTC+01:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Europe/Zagreb (UTC+01:00)</option>--}}
                                    {{--                                                                <option>Europe/Athens (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Europe/Bucharest (UTC+02:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Africa/Cairo (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Africa/Harare (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Europe/Helsinki (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Europe/Istanbul (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Asia/Jerusalem (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Europe/Helsinki (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Africa/Johannesburg (UTC+02:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Europe/Riga (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Europe/Sofia (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Europe/Tallinn (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Europe/Vilnius (UTC+02:00)</option>--}}
                                    {{--                                                                <option>Asia/Baghdad (UTC+03:00)</option>--}}
                                    {{--                                                                <option>Asia/Kuwait (UTC+03:00)</option>--}}
                                    {{--                                                                <option>Europe/Minsk (UTC+03:00)</option>--}}
                                    {{--                                                                <option>Africa/Nairobi (UTC+03:00)</option>--}}
                                    {{--                                                                <option>Asia/Riyadh (UTC+03:00)</option>--}}
                                    {{--                                                                <option>Europe/Volgograd (UTC+03:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Asia/Tehran (UTC+03:30)</option>--}}
                                    {{--                                                                <option>Asia/Muscat (UTC+04:00)</option>--}}
                                    {{--                                                                <option>Asia/Baku (UTC+04:00)</option>--}}
                                    {{--                                                                <option>Europe/Moscow (UTC+04:00)</option>--}}
                                    {{--                                                                <option>Asia/Muscat (UTC+04:00)</option>--}}
                                    {{--                                                                <option>Europe/Moscow (UTC+04:00)</option>--}}
                                    {{--                                                                <option>Asia/Tbilisi (UTC+04:00)</option>--}}
                                    {{--                                                                <option>Asia/Yerevan (UTC+04:00)</option>--}}
                                    {{--                                                                <option>Asia/Kabul (UTC+04:30)</option>--}}
                                    {{--                                                                <option>Asia/Islamabad (UTC+05:00)</option>--}}
                                    {{--                                                                <option>Asia/Karachi (UTC+05:00)</option>--}}
                                    {{--                                                                <option>Asia/Tashkent (UTC+05:00)</option>--}}
                                    {{--                                                                <option>Asia/Calcutta/Chennai (UTC+05:30)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Asia/Kolkata (UTC+05:30)</option>--}}
                                    {{--                                                                <option>Asia/Mumbai (UTC+05:30)</option>--}}
                                    {{--                                                                <option>Asia/New Delhi (UTC+05:30)</option>--}}
                                    {{--                                                                <option>Asia/Sri Jayawardenepura--}}
                                    {{--                                                                    (UTC+05:30)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Asia/Katmandu (UTC+05:45)</option>--}}
                                    {{--                                                                <option>Asia/Almaty (UTC+06:00)</option>--}}
                                    {{--                                                                <option>Asia/Astana (UTC+06:00)</option>--}}
                                    {{--                                                                <option>Asia/Dhaka (UTC+06:00)</option>--}}
                                    {{--                                                                <option>Asia/Yekaterinburg (UTC+06:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Asia/Rangoon (UTC+06:30)</option>--}}
                                    {{--                                                                <option>Asia/Bangkok (UTC+07:00)</option>--}}
                                    {{--                                                                <option>Asia/Hanoi (UTC+07:00)</option>--}}
                                    {{--                                                                <option>Asia/Jakarta (UTC+07:00) Jakarta--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Asia/Novosibirsk (UTC+07:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Asia/Beijing (UTC+08:00)</option>--}}
                                    {{--                                                                <option>Asia/Chongqing (UTC+08:00)</option>--}}
                                    {{--                                                                <option>Asia/Hong_Kong (UTC+08:00)</option>--}}
                                    {{--                                                                <option>Asia/Krasnoyarsk (UTC+08:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Asia/Kuala_Lumpur (UTC+08:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Australia/Perth (UTC+08:00)</option>--}}
                                    {{--                                                                <option>Asia/Singapore (UTC+08:00)</option>--}}
                                    {{--                                                                <option>Asia/Taipei (UTC+08:00)</option>--}}
                                    {{--                                                                <option>Asia/Ulan_Bator (UTC+08:00)</option>--}}
                                    {{--                                                                <option>Asia/Urumqi (UTC+08:00)</option>--}}
                                    {{--                                                                <option>Asia/Irkutsk (UTC+09:00)</option>--}}
                                    {{--                                                                <option>Asia/Tokyo (UTC+09:00)</option>--}}
                                    {{--                                                                <option>Asia/Sapporo (UTC+09:00)</option>--}}
                                    {{--                                                                <option>Asia/Seoul (UTC+09:00)</option>--}}
                                    {{--                                                                <option>Asia/Tokyo (UTC+09:00)</option>--}}
                                    {{--                                                                <option>Australia/Adelaide (UTC+09:30)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Australia/Darwin (UTC+09:30)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Australia/Brisbane (UTC+10:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Australia/Canberra (UTC+10:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Pacific/Guam (UTC+10:00)</option>--}}
                                    {{--                                                                <option>Australia/Hobart (UTC+10:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Australia/Melbourne (UTC+10:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Pacific/Port_Moresby (UTC+10:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Australia/Sydney (UTC+10:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Asia/Yakutsk (UTC+10:00)</option>--}}
                                    {{--                                                                <option>Asia/Vladivostok (UTC+11:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Pacific/Auckland (UTC+12:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Pacific/Fiji (UTC+12:00)</option>--}}
                                    {{--                                                                <option>Pacific/Kwajalein (UTC+12:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Asia/Kamchatka (UTC+12:00)</option>--}}
                                    {{--                                                                <option>Asia/Magadan (UTC+12:00)</option>--}}
                                    {{--                                                                <option>Pacific/Fiji (UTC+12:00)</option>--}}
                                    {{--                                                                <option>Asia/Magadan (UTC+12:00)</option>--}}
                                    {{--                                                                <option>Asia/Solomon Is. (UTC+12:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Pacific/Auckland (UTC+12:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                                <option>Pacific/Tongatapu (UTC+13:00)--}}
                                    {{--                                                                </option>--}}
                                    {{--                                                            </select>--}}
                                    {{--                                                        </div>--}}

                                    {{--                                                        <div class="form-group">--}}
                                    {{--                                                            <label>Guest Phone Number(s)<i>Separated by--}}
                                    {{--                                                                    commas</i>:</label>--}}
                                    {{--                                                            <br\><span class="text-danger">Note: The phone number should start with country code e.g 234 for Nigeria</span>--}}
                                    {{--                                                            <textarea maxlength="500" name="guest" rows="9"--}}
                                    {{--                                                                      class="form-control"--}}
                                    {{--                                                                      placeholder="2348166....,"--}}
                                    {{--                                                                      required></textarea>--}}
                                    {{--                                                        </div>--}}

                                    {{--                                                    </div>--}}
                                    {{--                                                    <div class="modal-footer">--}}
                                    {{--                                                        <button type="button"--}}
                                    {{--                                                                class="btn btn-danger text-left"--}}
                                    {{--                                                                data-dismiss="modal">Close--}}
                                    {{--                                                        </button>--}}
                                    {{--                                                        <button type="submit"--}}
                                    {{--                                                                class="btn btn-success text-left">Send--}}
                                    {{--                                                            Invite--}}
                                    {{--                                                        </button>--}}
                                    {{--                                                    </div>--}}
                                    {{--                                                </form>--}}
                                    {{--                                            </div>--}}
                                    {{--                                            <!-- /.modal-content -->--}}
                                    {{--                                        </div>--}}
                                    {{--                                        <!-- /.modal-dialog -->--}}
                                    {{--                                    </div>--}}
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

