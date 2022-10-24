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
                            <span class="font-size-30 countnm">{{$users_count}}</span>
                            <h6 class="text-uppercase text-dark-50 mb-0">Total Users</h6>
                        </div>
                        <span class="icon-Angle-Grinder font-size-80 text-info"><span class="path1"></span><span
                                class="path2"></span></span>
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
                                Preregs
                                <small class="subtitle">This table show the list of users registered with your
                                    link </small>
                            </h4>

                        </div>
                        {{--                    <div class="box-body">--}}
                        {{--                        <div class="table-responsive">--}}
                        {{--                            <table class="table no-border table-striped" id="example" style="width:100%">--}}
                        {{--                                <thead>--}}
                        {{--                                <tr class="text-uppercase bg-lightest">--}}
                        {{--                                    <th><span class="text-fade">S/N</span></th>--}}
                        {{--                                    <th style="min-width: 50px"><span class="text-fade">Name</span></th>--}}
                        {{--                                    <th style="min-width: 70px"><span class="text-fade">Email</span></th>--}}
                        {{--                                    <th style="min-width: 10px"><span class="text-fade">Phone</span></th>--}}
                        {{--                                    <th style="min-width: 10px"><span class="text-fade">Date & Time</span></th>--}}
                        {{--                                </tr>--}}
                        {{--                                </thead>--}}
                        {{--                                <tbody>--}}
                        {{--                                @foreach($users as $data)--}}
                        {{--                                    <tr>--}}
                        {{--                                        <td>{{$i++}}</td>--}}
                        {{--                                        <td class="pl-0 py-8">--}}
                        {{--                                            <div class="d-flex align-items-center">--}}
                        {{--                                                <div>--}}
                        {{--                                                    <a href="#"--}}
                        {{--                                                       class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$data->name}}</a>--}}
                        {{--                                                </div>--}}
                        {{--                                            </div>--}}
                        {{--                                        </td>--}}

                        {{--                                        <td>--}}
                        {{--                                            <span>{{$data->email}}</span>--}}
                        {{--                                        </td>--}}

                        {{--                                        <td>--}}
                        {{--                                            <span>{{$data->phone}}</span>--}}
                        {{--                                        </td>--}}

                        {{--                                        <td>--}}
                        {{--                                            <span>{{$data->created_at}}</span>--}}
                        {{--                                        </td>--}}
                        {{--                                    </tr>--}}
                        {{--                                @endforeach--}}
                        {{--                                </tbody>--}}
                        {{--                            </table>--}}
                        {{--                            {{$users->links()}}--}}
                        {{--                        </div>--}}
                        {{--                    </div>--}}
                    </div>
                </div>


            </div>
    </section>
    <!-- /.content -->

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
