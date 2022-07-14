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
            <div class="col-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title align-items-start flex-column">
                            Resellers
                            <small class="subtitle">This table show the list of resellers we have </small>
                        </h4>

                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-lg invoice-archive" style="width:100%">
                                <thead>
                                <tr class="text-uppercase bg-lightest">
                                    <th><span class="text-fade">S/N</span></th>
                                    <th style="min-width: 50px"><span class="text-fade">Name</span></th>
                                    <th style="min-width: 70px"><span class="text-fade">Commission</span></th>
                                    <th style="min-width: 70px"><span class="text-fade">Type</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Status</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Date & Time</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Actions</span></th>
                                    {{--                                            <th></th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($datas as $data)
                                    <tr>
                                        <td>{{$data->id}}</td>
                                        <td class="pl-0 py-8">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a href="#"
                                                       class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$data->name}}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span>{{$data->commission}}</span>
                                        </td>
                                        <td>
                                            @if($data->commission_type == 1)
                                                <span class="badge badge-default badge-lg">Percentage</span>
                                            @else
                                                <span class="badge badge-default badge-lg">Flat</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span>
                                                @if($data->status == 1)
                                                    <span class="badge badge-success badge-lg">Active</span>
                                                @else
                                                    <span class="badge badge-warning badge-lg">Inactive</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span>{{$data->created_at}}</span>
                                        </td>
                                        <td class="text-right">
                                            <a class="btn btn-primary"
                                               href="{{route('admin.resellers-users', $data->id)}}">View Users</a>
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
                                            <option>+1 970-245-1026</option>
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
