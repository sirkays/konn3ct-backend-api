@extends('layouts.admin-layout')

@section('content')

    <!-- Main content -->
    <section class="content">

        {{--                Mobile View--}}
        <div class="row hidden-lg-up hidden-sm-up hidden-xl-up">
            <div class="col-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title align-items-start flex-column">
                            Recording
                            <small class="subtitle">Below are your meeting recording(s)</small>
                        </h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-border" id="complex_header">
                                <thead>
                                <tr class="text-uppercase bg-lightest font-size-10">
                                    <th><span class="text-fade">Name</span></th>
                                    <th><span class="text-fade">Parameters</span></th>
                                    <th><span class="text-fade"></span></th>
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
                                                    <div
                                                        class="text-dark font-weight-600 hover-primary mb-1 font-size-10">
                                                        <a href="{{$record['playback']['format']['url']}}">{{$record['name']}}</a>
                                                        <br/><br/>
                                                        Date: {{\Carbon\Carbon::parseFromLocale($record['startTime']/1000)->format('Y/m/d')}}
                                                        <br/>
                                                        Time: {{\Carbon\Carbon::parseFromLocale($record['startTime']/1000)->format('H:i:s')}}
                                                    </div>
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
                                            <span class="text-dark font-weight-600 d-block font-size-10">
													{{$record['playback']['format']['length']}} Minutes
												</span>
                                            <span class="text-dark font-weight-600 d-block font-size-10">
													{{ number_format((($record['playback']['format']['size'] ?? 1)/1000000))."MB"}}
												</span>
                                        </td>

                                        <td>
                                            <a class="waves-effect waves-light btn btn-success font-size-10 mb-2"
                                               href="{{$record['playback']['format']['url']}}">
                                                Play
                                            </a>


                                            <input type="hidden" id="c{{$i}}"
                                                   value="{{$record['playback']['format']['url']}}"/>

                                            <div class="dropdown mb-2">
                                                <button class="btn btn-outline-primary dropdown-toggle font-size-10"
                                                        type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                    Manage
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <Button class="dropdown-item"
                                                            class="waves-effect waves-light btn font-size-10"
                                                            onclick="myFunction('c{{$i++}}')">
                                                        Copy
                                                    </Button>

                                                    <a class="dropdown-item font-size-10"
                                                       href="mailto:?Subject=My Recording on Konn3ct&amp;Body=Hi, view my recording on konn3ct using this link {{$record['playback']['format']['url']}}"
                                                       class="waves-effect waves-light btn btn-primary">
                                                        Email Recording
                                                    </a>
                                                </div>
                                            </div>

                                            <a class="waves-effect waves-light btn btn-danger font-size-10" href="#">
                                                Delete
                                            </a>
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
                    <div class="box-header">
                        <h4 class="box-title align-items-start flex-column">
                            Recording
                            <small class="subtitle">Below are your meeting recording(s)</small>
                        </h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-border table-responsive" id="complex_header">
                                <thead>
                                <tr class="text-uppercase bg-lightest">
                                    <th style="min-width: 50px; max-width: 90px"><span
                                            class="text-fade">Meeting Name</span></th>
                                    <th style="min-width: 40px; max-width: 50px"><span
                                            class="text-fade">Parameters</span></th>
                                    <th style="min-width: 20px; max-width: 50px; overflow-wrap: break-word;"><span
                                            class="text-fade">Date</span></th>
                                    <th style="min-width: 70px"><span class="text-fade">Options</span></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($recordings as $record)
                                    <tr>
                                        <td style="min-width: 20px; max-width: 50px">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a href="{{$record['playback']['format']['url']}}"
                                                       class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$record['name']}}</a>
                                                </div>
                                            </div>
                                        </td>

                                        {{--                                                                                    <td class="pl-0 py-8">--}}
                                        {{--                                                                                                @foreach($record['playback']['format']['preview']['images']['image'] as $im)--}}
                                        {{--                                                                                                <img src="{{$im}}" class="img img-thumbnail">--}}
                                        {{--                                                                                                @endforeach--}}
                                        {{--                                                                                    </td>--}}


                                        {{--                                                                                    <td class="pl-0 py-8">--}}
                                        {{--                                                                                        @if(isset($record['playback']['format']['preview']['images']['image']))--}}
                                        {{--                                                                                            <img src="{{$record['playback']['format']['preview']['images']['image'][0]}}" class="img img-thumbnail">--}}
                                        {{--                                                                                        @else--}}
                                        {{--                                                                                            No Image Preview--}}
                                        {{--                                                                                        @endif--}}
                                        {{--                                                                                    </td>--}}

                                        <td style="min-width: 20px; max-width: 50px">
                                                <span class="text-dark font-weight-600 d-block font-size-16">
													{{$record['participants']}} Participants
												</span>
                                            <span class="text-dark font-weight-600 d-block font-size-16">
													{{$record['playback']['format']['length']}} Minutes
												</span>
                                            <span class="text-dark font-weight-600 d-block font-size-16">
													{{ number_format((($record['playback']['format']['size']??1)/1000000))."MB"}}
												</span>
                                        </td>
                                        <td style="overflow-wrap: break-word; min-width: 50px; max-width: 150px;">
                                            <span
                                                class="text-dark font-weight-600 d-block font-size-16">{{\Carbon\Carbon::parseFromLocale($record['startTime']/1000)->format('Y/m/d H:i:s')}}</span>
                                            <input type="hidden" id="c{{$i}}"
                                                   value="{{$record['playback']['format']['url']}}"/>
                                        </td>
                                        <td style="min-width: 50px; max-width: 100px">

                                            <div class="dropdown">
                                                <a class="waves-effect waves-light btn btn-success"
                                                   href="{{$record['playback']['format']['url']}}">
                                                    Play Video
                                                </a>
                                                <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                    Manage
                                                </button>

                                                <Button type="button" class="waves-effect waves-light btn btn-danger"
                                                        data-toggle="modal" data-target="#deleterecording{{$i}}-modal">
                                                    Delete
                                                </Button>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <Button type="button" class="dropdown-item waves-effect waves-light"
                                                            onclick="myFunction('c{{$i}}')">
                                                        Copy
                                                    </Button>

                                                    <Button
                                                        href="mailto:?Subject=My Recording on Konn3ct&amp;Body=Hi, view my recording on konn3ct using this link {{$record['playback']['format']['url']}}"
                                                        class="dropdown-item waves-effect waves-light">
                                                        Email Recording
                                                    </Button>


                                                    {{--                                                        <form action="/deleteroom" method="POST">--}}
                                                    {{--                                                            @csrf--}}
                                                    {{--                                                            <input type="hidden" name="id" value="{{$i->id}}" />--}}
                                                    {{--                                                            <Button type="submit" class="waves-effect waves-light btn">--}}
                                                    {{--                                                                Delete--}}
                                                    {{--                                                            </Button>--}}
                                                    {{--                                                        </form>--}}
                                                </div>
                                            </div>

                                            {{--                                                <a class="waves-effect waves-light btn btn-danger" href="#">--}}
                                            {{--                                                   Delete--}}
                                            {{--                                                </a>--}}
                                        </td>
                                    </tr>
                                    <div class="modal deleterecording-modal fade" id="deleterecording{{$i++}}-modal"
                                         tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
                                         aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog modal-md">
                                            <form method="post" action="{{route('recording.delete')}}">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="mySmallModalLabel">Delete
                                                            Recording</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-hidden="true">×
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        You are about to delete your recording.<br/>
                                                        Once deleted you won't be able to access it again<br/><br/>
                                                    </div>
                                                    <div class="modal-footer modal-footer-uniform">
                                                        <input name="id" value="{{$record['recordID']}}" type="hidden"/>
                                                        <button type="submit"
                                                                class="waves-effect waves-light btn btn-danger float-left">
                                                            Delete
                                                        </button>
                                                        <button type="button" data-dismiss="modal"
                                                                class="btn bg-dark float-right">Close
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </form>
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                @endforeach
                                </tbody>
                            </table>
                            {{$rooms->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

    <script type="application/javascript">
        function myFunction(id) {
            /* Get the text field */
            var copyText = document.getElementById(id);

            copyText.type = 'text';

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /*For mobile devices*/

            /* Copy the text inside the text field */
            document.execCommand("copy");

            copyText.type = 'hidden';

            /* Alert the copied text */
            alert("Copied the text: " + copyText.value);
        }
    </script>

@endsection
