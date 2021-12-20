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
            {{--            <div class="col-4">--}}
            {{--                <div class="box box-body pull-up">--}}
            {{--                    <div class="flexbox align-items-end pt-30">--}}
            {{--                        <div>--}}
            {{--                            <span class="font-size-30 countnm">{{$userstc}}</span>--}}
            {{--                            <h6 class="text-uppercase text-dark-50 mb-0">Total Users</h6>--}}
            {{--                        </div>--}}
            {{--                        <span class="icon-User font-size-80 text-info"><span class="path1"></span><span class="path2"></span></span>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--            <div class="col-4">--}}
            {{--                <div class="box box-body pull-up">--}}
            {{--                    <div class="flexbox align-items-end pt-30">--}}
            {{--                        <div>--}}
            {{--                            <span class="font-size-30 countnm">{{$userstc}}</span>--}}
            {{--                            <h6 class="text-uppercase text-dark-50 mb-0">Active Users</h6>--}}
            {{--                        </div>--}}
            {{--                        <span class="icon-User font-size-80 text-primary"><span class="path1"></span><span class="path2"></span></span>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--            <div class="col-4">--}}
            {{--                <div class="box box-body pull-up">--}}
            {{--                    <div class="flexbox align-items-end pt-30">--}}
            {{--                        <div>--}}
            {{--                            <span class="font-size-30 countnm">0</span>--}}
            {{--                            <h6 class="text-uppercase text-dark-50 mb-0">Inactive Users</h6>--}}
            {{--                        </div>--}}
            {{--                        <span class="icon-User font-size-80 text-danger"><span class="path1"></span><span class="path2"></span></span>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="col-xl-3 col-12">
                <div class="box box-body pull-up">
                    <Button class="waves-effect waves-light btn btn-app btn-info" data-toggle="modal"
                            data-target="#modal-left">
                        <i class="fa fa-edit"></i> Generate Coupon
                    </Button>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-12 col-lg-4">
                <form action="{{route('admin.coupon.create')}}" id="form_id" method="POST">
                    @csrf
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Create Coupon Code</h5>
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
                                                <label>Name:</label>
                                                <input type="text" name="name" class="form-control"
                                                       placeholder="e.g My Room" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Discount:</label>
                                                <input type="number" name="discount" class="form-control"
                                                       placeholder="e.g 10 means 10% discount" required>
                                            </div>


                                            <div class="form-group">
                                                <label>Code: (optional)</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" name="code" class="form-control" value=""
                                                           placeholder="Enter code incase you want customized" required>
                                                </div>
                                            </div>

                                            <!-- select -->
                                            <div class="form-group">
                                                <label>Type:</label>
                                                <select class="form-control" name="type">
                                                    <option value="2">Yearly Plan</option>
                                                    <option value="1">Monthly Plan</option>
                                                    <option value="0">All</option>
                                                </select>
                                            </div>

                                            <!-- select -->
                                            <div class="form-group">
                                                <label>Re-Occurring:</label>
                                                <select class="form-control" name="reoccuring">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>
                            </div>
                            <div class="modal-footer modal-footer-uniform">
                                <button type="button" onclick='document.getElementById("form_id").submit();'
                                        class="btn bg-gradient-success float-right">Create Coupon-Code
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-12 col-lg-8">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title align-items-start flex-column">
                            Coupon Codes
                            <small class="subtitle">This table show the list of available coupons </small>
                        </h4>

                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-lg invoice-archive">
                                <thead>
                                <tr class="text-uppercase bg-lightest">
                                    <th style="min-width: 10px"><span class="text-fade">S/N</span></th>
                                    <th style="min-width: 50px"><span class="text-fade">Name</span></th>
                                    <th style="min-width: 70px"><span class="text-fade">Code</span></th>
                                    <th style="min-width: 70px"><span class="text-fade">Discount</span></th>
                                    <th style="min-width: 70px"><span class="text-fade">Type</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Status</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Date & Time</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Actions</span></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($coupons as $coupon)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>
                                            <span>{{$coupon->name}}</span>
                                        </td>
                                        <td>
                                            <span>{{$coupon->code}}</span>
                                        </td>
                                        <td>
                                            <span>{{$coupon->discount}}%</span>
                                        </td>
                                        <td>
                                            <span>
                                                @if($coupon->type==2)
                                                    Yearly
                                                @elseif($coupon->type==1)
                                                    Monthly
                                                @else
                                                    All
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @if($coupon->status==1)
                                                <span class="badge badge-success badge-lg">Active</span>
                                            @else
                                                <span class="badge badge-danger badge-lg">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span>{{$coupon->created_at}}</span>
                                        </td>
                                        <td class="text-right">
                                            @if($coupon->status==1)
                                                <a href="{{route('admin.coupon.disable',$coupon->id)}}"
                                                   class="btn btn-danger dropdown-toggle">
                                                    Disable
                                                </a>
                                            @else
                                                <a href="{{route('admin.coupon.enable',$coupon->id)}}"
                                                   class="btn btn-primary dropdown-toggle">
                                                    Enable
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$coupons->links()}}
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
                                        <input type="text" name="name" class="form-control" placeholder="e.g My Room"
                                               required>
                                    </div>

                                    <div class="form-group">
                                        <label>Room URL:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background-color: #7193AA">konn3ct.com/</span>
                                            </div>
                                            <input type="text" name="url" class="form-control"
                                                   placeholder="myroom (optional)">
                                        </div>
                                    </div>

                                    <!-- select -->
                                    <div class="form-group">
                                        <label>Dial Number:</label>
                                        <select class="form-control" name="dial_number">
                                            <option>+1 970-519-2253</option>
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label>Access Code:</label>
                                        <div class="input-group mb-3">
                                            <input type="text" name="access_code" class="form-control" value=""
                                                   placeholder="Currently Open (optional)">
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
