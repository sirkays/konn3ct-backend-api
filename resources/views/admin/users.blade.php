@extends('layouts.admin-layout')

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
            <div class="col-4">
                <div class="box box-body pull-up">
                    <div class="flexbox align-items-end pt-30">
                        <div>
                            <span class="font-size-30 countnm">{{$userstc}}</span>
                            <h6 class="text-uppercase text-dark-50 mb-0">Total Users</h6>
                        </div>
                        <span class="icon-User font-size-80 text-info"><span class="path1"></span><span class="path2"></span></span>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="box box-body pull-up">
                    <div class="flexbox align-items-end pt-30">
                        <div>
                            <span class="font-size-30 countnm">{{$userstc}}</span>
                            <h6 class="text-uppercase text-dark-50 mb-0">Active Users</h6>
                        </div>
                        <span class="icon-User font-size-80 text-primary"><span class="path1"></span><span class="path2"></span></span>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="box box-body pull-up">
                    <div class="flexbox align-items-end pt-30">
                        <div>
                            <span class="font-size-30 countnm">0</span>
                            <h6 class="text-uppercase text-dark-50 mb-0">Inactive Users</h6>
                        </div>
                        <span class="icon-User font-size-80 text-danger"><span class="path1"></span><span class="path2"></span></span>
                    </div>
                </div>
            </div>

            {{--                    <div class="col-xl-3 col-12">--}}
            {{--                        <div class="box box-body pull-up">--}}
            {{--                            <button type="button" class="waves-effect waves-light btn mb-5 bg-gradient-success"><i class="fa fa-edit"></i> Add</button>--}}
            {{--                            <Button class="waves-effect waves-light btn btn-app btn-info btn-" data-toggle="modal" data-target="#modal-left">--}}
            {{--                                <i class="fa fa-edit"></i> Create a Room--}}
            {{--                            </Button>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}

        </div>

        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title align-items-start flex-column">
                            Users
                            <small class="subtitle">This table show the list of users created </small>
                        </h4>

                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-border" id="complex_header" style="width:100%">
                                <thead>
                                <tr class="text-uppercase bg-lightest">
                                    <th><span class="text-fade">S/N</span></th>
                                    <th style="min-width: 50px"><span class="text-fade">Name</span></th>
                                    <th style="min-width: 70px"><span class="text-fade">Email</span></th>
                                    <th style="min-width: 70px"><span class="text-fade">Phone Number</span></th>
                                    <th style="min-width: 70px"><span class="text-fade">Plan</span></th>
                                    <th style="min-width: 70px"><span class="text-fade">Subscription</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Status</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">2FA</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Date & Time</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Actions</span></th>
                                    {{--                                            <th></th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td class="pl-0 py-8">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a href="#" class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$user->firstname}} {{$user->lastname}}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span>{{$user->email}}</span>
                                        </td>
                                        <td>
                                            <span>{{$user->phone}}</span>
                                        </td>
                                        <td>
                                            <span>
                                                @if($user->plan==1)
                                                    Basic
                                                @elseif($user->plan==2)
                                                    Lite
                                                @else
                                                    Pro
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span>
                                                @if($user->plan==1)
                                                    Forever
                                                @elseif($user->plan==2)
                                                    @if($user->subscription=='new')
                                                        Not yet Subscribed
                                                    @else
                                                        Expires in {{\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($user->subscription), false)}} days
                                                    @endif
                                                @else
                                                    @if($user->subscription=='new')
                                                        Not yet Subscribed
                                                    @else
                                                        Expires in {{\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($user->subscription), false)}} days
                                                    @endif
                                                @endif
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge badge-success badge-lg">Active</span>
                                        </td>
                                        <td>
                                            <span>
                                            @if($user->two_factor_secret!="")
                                                Yes
                                                @else
                                                No
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span>{{$user->created_at}}</span>
                                        </td>
                                                                                    <td class="text-right">
                                                                                        <a href="user/{{$user->id}}" class="btn btn-outline-primary dropdown-toggle">
                                                                                            Manage
                                                                                        </a>
                                                                                    </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
{{--                            {{$users->links()}}--}}
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
    <!-- /.content -->

    <iframe
        src="https://konn3ct.com/monitoring/d-solo/HIbd_CXZz8/bigbluebutton-server-instance-node_exporter?orgId=1&refresh=10s&var-datasource=default&var-job=bbb&var-job_node_exporter=bbb_node_exporter&var-instance=localhost&var-interface=br-soffice&from=1621316676042&to=1621338276042&panelId=2"
        width="450" height="200" frameborder="0"></iframe>

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

                                    <div class="form-group">
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
                                    <div class="c-inputs-stacked">
                                        <input type="checkbox" name="dprc" id="checkbox_4">
                                        <label for="checkbox_4" class="block">Disable private chat</label>
                                    </div>
                                    <div class="c-inputs-stacked">
                                        <input type="checkbox" name="ewma" id="checkbox_5">
                                        <label for="checkbox_5" class="block">Enable Webcam for Moderator alone</label>
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
<script>
    import Button from "../../js/Jetstream/Button";
    export default {
        components: {Button}
    }

</script>
