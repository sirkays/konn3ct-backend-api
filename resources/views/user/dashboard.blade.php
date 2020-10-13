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

                <div class="row">

                    <div class="col-xl-3 col-12">
                        <div class="box box-body pull-up">
{{--                            <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-success"><i class="fa fa-edit"></i> Add</button>--}}
                            <Button class="waves-effect waves-light btn btn-app btn-info btn-" data-toggle="modal" data-target="#modal-left">
                                <i class="fa fa-edit"></i> Create a Room
                            </Button>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-10">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title align-items-start flex-column">
                                    Rooms
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
                                            <th style="min-width: 10px"><span class="text-fade">Status</span></th>
                                            <th></th>
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
                                            </td>
                                            <td>
                                                <span class="badge badge-success badge-lg">Active</span>
                                            </td>
                                            <td class="text-right">
                                                <form action="/joinroom" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$room->id}}" />
                                                <Button type="submit" class="waves-effect waves-light btn btn-app btn-success">
                                                    <i class="fa fa-arrow-right"></i> Konn3ct Now
                                                </Button>
                                                </form>

                                                <Button class="waves-effect waves-light btn btn-app btn-info" onclick="copyToClipboard('#c{{$room->id}}')">
                                                    <i class="fa fa-copy"></i> Copy
                                                </Button>

                                                <a href="https://www.google.com/calendar/render?action=TEMPLATE&text={{$room->name}}&details=Let%27s+konn3ct+in+my+room+using+{{url('/join/')}}/{{$room->url}}&location={{url('/join/')}}/{{$room->url}}" class="waves-effect waves-light btn btn-app btn-primary">
                                                    <i class="fa fa-calendar-check-o "></i> Schedule Now
                                                </a>

                                                <form action="/deleteroom" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$room->id}}" />
                                                <Button type="submit" class="waves-effect waves-light btn btn-app btn-danger">
                                                    <i class="fa fa-trash"></i> Delete
                                                </Button>
                                                </form>
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
                        <div class="col-12">
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
                        <div class="col-12">
                            <div class="box box-body pull-up">
                                <div class="flexbox align-items-end pt-30">
                                    <div>
                                        <span class="font-size-30 countnm">{{$roomstc}}</span>
                                        <h6 class="text-uppercase text-dark-50 mb-0">Active Rooms</h6>
                                    </div>
                                    <span class="iconsmind-Eye font-size-80 text-primary"><span class="path1"></span><span class="path2"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="box box-body pull-up">
                                <div class="flexbox align-items-end pt-30">
                                    <div>
                                        <span class="font-size-30 countnm">0</span>
                                        <h6 class="text-uppercase text-dark-50 mb-0">Inactive Rooms</h6>
                                    </div>
                                    <span class="iconsmind-Eye-Blind font-size-80 text-danger"><span class="path1"></span><span class="path2"></span></span>
                                </div>
                            </div>
                        </div>
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

                                            <div class="form-group">
                                                <label>Room URL:</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="background-color: #7193AA">konn3ct.com/</span>
                                                    </div>
                                                    <input type="text" name="url" class="form-control" placeholder="myroom (optional)">
                                                </div>
                                            </div>

                                            <!-- select -->
                                            <div class="form-group">
                                                <label>Dial Number:</label>
                                                <select class="form-control" name="dial_number">
                                                    <option>+1 970-519-2253</option>
                                                    <option>+1 970-245-1026</option>
                                                </select>
                                            </div>

{{--                                            <div class="c-inputs-stacked">--}}
{{--                                                <input type="checkbox" id="checkbox_123">--}}
{{--                                                <label for="checkbox_123" class="block">Allow any user to start meeting</label>--}}
{{--                                            </div>--}}
{{--                                            <div class="c-inputs-stacked">--}}
{{--                                                <input type="checkbox" id="checkbox_234">--}}
{{--                                                <label for="checkbox_234" class="block">All user join as moderator</label>--}}
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
<script>
    import Button from "../../js/Jetstream/Button";
    export default {
        components: {Button}
    }

</script>
