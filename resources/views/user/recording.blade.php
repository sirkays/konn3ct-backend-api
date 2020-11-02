@extends('layouts.user-layout')

@section('content')

            <!-- Main content -->
            <section class="content">

{{--                Mobile View--}}
{{--                <div class="row hidden-lg-up hidden-sm-up hidden-xl-up">--}}
{{--                    <div class="col-12">--}}
{{--                        <div class="box">--}}
{{--                            <div class="box-header">--}}
{{--                                <h4 class="box-title align-items-start flex-column">--}}
{{--                                    Recording--}}
{{--                                    <small class="subtitle">Below are your meeting recording(s)</small>--}}
{{--                                </h4>--}}
{{--                            </div>--}}
{{--                            <div class="box-body">--}}
{{--                                <div class="table-responsive">--}}
{{--                                    <table class="table no-border">--}}
{{--                                        <thead>--}}
{{--                                        <tr class="text-uppercase bg-lightest font-size-10">--}}
{{--                                            <th style="min-width: 250px"><span class="text-fade">Room Name</span></th>--}}
{{--                                            <th style="min-width: 100px"><span class="text-fade">Name</span></th>--}}
{{--                                            <th style="min-width: 100px"><span class="text-fade">Parameters</span></th>--}}
{{--                                            <th style="min-width: 100px"><span class="text-fade"></span></th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        @foreach($recordings as $record)--}}
{{--                                        <tr>--}}
{{--                                            <td class="pl-0 py-8">--}}
{{--                                                <div class="d-flex align-items-center">--}}
{{--                                                    <div>--}}
{{--                                                        <a href="#" class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$record['name']}}</a>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}

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

{{--                                            <td><span>{{$record['participants']}} Users</span> <br/>--}}
{{--                                                <span class="text-dark">{{$record['playback']['format']['length']}} Minutes</span> <br/>--}}
{{--                                                <span class="text-dark">--}}
{{--													{{ number_format(($record['size']/1000000))."MB"}}--}}
{{--												</span>--}}
{{--                                            </td>--}}
{{--                                            <td>--}}
{{--                                                <a class="waves-effect waves-light btn btn-success font-size-10" href="{{$record['playback']['format']['url']}}">--}}
{{--                                                    Play--}}
{{--                                                </a>--}}

{{--                                                <br/>--}}
{{--                                                <br/>--}}

{{--                                                <input type="hidden" id="c{{$i}}" value="{{$record['playback']['format']['url']}}"/>--}}

{{--                                                <div class="dropdown">--}}
{{--                                                    <button class="btn btn-outline-primary dropdown-toggle font-size-10" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                                                        Manage--}}
{{--                                                    </button>--}}
{{--                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                                                        <Button class="dropdown-item" class="waves-effect waves-light btn" onclick="myFunction('c{{$i++}}')">--}}
{{--                                                            Copy--}}
{{--                                                        </Button>--}}

{{--                                                        <a class="dropdown-item" href="mailto:?Subject=My Recording on Konn3ct&amp;Body=Hi, view my recording on konn3ct using this link {{$record['playback']['format']['url']}}" class="waves-effect waves-light btn btn-primary">--}}
{{--                                                            Email Recording--}}
{{--                                                        </a>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}
{{--                                            <td class="text-right">--}}
{{--                                                <a class="waves-effect waves-light btn btn-danger" href="#">--}}
{{--                                                    Delete--}}
{{--                                                </a>--}}
{{--                                            </td>--}}
{{--                                        </tr>--}}
{{--                                        @endforeach--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                Desktop view--}}
                <div class="row">
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
                                    <table class="table no-border">
                                        <thead>
                                        <tr class="text-uppercase bg-lightest">
                                            <th style="min-width: 250px"><span class="text-fade">Room Name</span></th>
                                            <th style="min-width: 100px"><span class="text-fade">Preview</span></th>
                                            <th style="min-width: 100px"><span class="text-fade">Size</span></th>
                                            <th style="min-width: 100px"><span class="text-fade">Duration</span></th>
                                            <th style="min-width: 130px"><span class="text-fade">Users</span></th>
                                            <th style="min-width: 120px"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($recordings as $record)
                                        <tr>
                                            <td class="pl-0 py-8">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <a href="#" class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$record['name']}}</a>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="pl-0 py-8">
                                                        @foreach($record['playback']['format']['preview']['images']['image'] as $im)
                                                        <img src="{{$im}}" class="img img-thumbnail">
                                                        @endforeach
                                            </td>


{{--                                            <td class="pl-0 py-8">--}}
{{--                                                @if(isset($record['playback']['format']['preview']['images']['image']))--}}
{{--                                                    <img src="{{$record['playback']['format']['preview']['images']['image']}}" class="img img-thumbnail">--}}
{{--                                                @else--}}
{{--                                                    No Image Preview--}}
{{--                                                @endif--}}
{{--                                            </td>--}}

                                            <td>
                                                <span class="text-dark font-weight-600 d-block font-size-16">
													{{ number_format(($record['size']/1000000))."MB"}}
												</span>
                                            </td>
                                            <td>
                                                <span class="text-dark font-weight-600 d-block font-size-16">{{$record['playback']['format']['length']}} Minutes</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success badge-lg">{{$record['participants']}}</span>
                                                <input type="hidden" id="c{{$i}}" value="{{$record['playback']['format']['url']}}"/>
                                            </td>
                                            <td class="text-right">
                                                <a class="waves-effect waves-light btn btn-success" href="{{$record['playback']['format']['url']}}">
                                                    View
                                                </a>
                                                <Button class="waves-effect waves-light btn btn-primary" onclick="myFunction('c{{$i++}}')">
                                                    Copy
                                                </Button>

                                                <a href="mailto:?Subject=My Recording on Konn3ct&amp;Body=Hi, view my recording on konn3ct using this link {{$record['playback']['format']['url']}}" class="waves-effect waves-light btn btn-primary">
                                                    Email Recording
                                                </a>
                                                <a class="waves-effect waves-light btn btn-danger" href="#">
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
            </section>
            <!-- /.content -->

            <script>
                function myFunction(id) {
                    /* Get the text field */
                    var copyText = document.getElementById(id);

                    copyText.type='text';

                    /* Select the text field */
                    copyText.select();
                    copyText.setSelectionRange(0, 99999); /*For mobile devices*/

                    /* Copy the text inside the text field */
                    document.execCommand("copy");

                    copyText.type='hidden';

                    /* Alert the copied text */
                    alert("Copied the text: " + copyText.value);
                }
            </script>
@endsection
