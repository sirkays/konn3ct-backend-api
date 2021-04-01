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
                                    <a href="#"
                                       class="text-dark hover-primary mb-1"><strong>Name:</strong> {{$room->name}}</a>
                                    <span class="badge badge-info">Access Code:
                                                    @if($room->password_attendee=="attendee")
                                            Unrestricted
                                        @else
                                            {{$room->password_attendee}}
                                        @endif

                                                </span>
                                    <span class="text-dark d-block">
                                                  <strong>Link:</strong> <span
                                            id="c{{$room->id}}">{{url('/join/')}}/{{$room->url}} </span>
                                                </span>

                                    <br/>

                                    <form action="/joinroom" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$room->id}}"/>

                                        <div class="dropdown">
                                            <Button type="submit"
                                                    class="waves-effect waves-light font-size-10 btn btn-success">
                                                Konn3ct Now
                                            </Button>

                                    </form>

                                    <button class="btn btn-outline-primary dropdown-toggle font-size-10" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        Manage
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <Button type="button" class="dropdown-item" class="waves-effect waves-light btn"
                                                onclick="copyToClipboard('#c{{$room->id}}')">
                                            Copy
                                        </Button>

                                        <a class="dropdown-item"
                                           href="https://www.google.com/calendar/render?action=TEMPLATE&text={{$room->name}}&details=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}&location={{url('/join/')}}/{{$room->url}}"
                                           class="waves-effect waves-light btn btn-primary">
                                            Google Calender Invite
                                        </a>
                                        <a class="dropdown-item"
                                           href="https://outlook.live.com/owa/?path=/calendar/action/compose&rru=addevent&subject={{$room->name}}&body=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}"
                                           class="waves-effect waves-light btn btn-primary">
                                            Outlook Calendar Invite
                                        </a>
                                        <button type="button" style="font-size: 12px" class="dropdown-item"
                                                data-toggle="modal" data-target=".invite-lg-{{$room->id}}">
                                            Konn3ct Invite
                                        </button>
                                        @if(\Illuminate\Support\Facades\Auth::user()->plan!=1)
                                            <form action="/deleteroom" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$room->id}}"/>
                                                <Button type="submit" class="waves-effect waves-light btn">
                                                    Delete
                                                </Button>
                                            </form>
                                        @endif
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
