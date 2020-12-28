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
                                        <span class="font-size-30 countnm">{{$meetingstc}}</span>
                                        <h6 class="text-uppercase text-dark-50 mb-0">Meetings</h6>
                                    </div>
                                    <span class="icon-Angle-Grinder font-size-80 text-info"><span class="path1"></span><span class="path2"></span></span>
                                </div>
                            </div>
                        </div>
{{--                        <div class="col-4">--}}
{{--                            <div class="box box-body pull-up">--}}
{{--                                <div class="flexbox align-items-end pt-30">--}}
{{--                                    <div>--}}
{{--                                        <span class="font-size-30 countnm">{{$meetingsdc}}</span>--}}
{{--                                        <h6 class="text-uppercase text-dark-50 mb-0">Discount Plan</h6>--}}
{{--                                    </div>--}}
{{--                                    <span class="iconsmind-Eye font-size-80 text-primary"><span class="path1"></span><span class="path2"></span></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-4">--}}
{{--                            <div class="box box-body pull-up">--}}
{{--                                <div class="flexbox align-items-end pt-30">--}}
{{--                                    <div>--}}
{{--                                        <span class="font-size-30 countnm">0</span>--}}
{{--                                        <h6 class="text-uppercase text-dark-50 mb-0">Inactive Rooms</h6>--}}
{{--                                    </div>--}}
{{--                                    <span class="iconsmind-Eye-Blind font-size-80 text-danger"><span class="path1"></span><span class="path2"></span></span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

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
                                    Meeting Participant(s)
{{--                                    <small class="subtitle">This table show the list of meetings joined </small>--}}
                                </h4>

                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table no-border" id="complex_header" style="width:100%">
                                        <thead>
                                        <tr class="text-uppercase bg-lightest">
                                            <th style="min-width: 50px"><span class="text-fade">Room Name</span></th>
                                            <th style="min-width: 70px"><span class="text-fade">Room URL</span></th>
                                            <th style="min-width: 10px"><span class="text-fade">Participant Name</span></th>
                                            <th style="min-width: 10px"><span class="text-fade">Participant Email</span></th>
                                            <th style="min-width: 10px"><span class="text-fade">Status</span></th>
                                            <th style="min-width: 10px"><span class="text-fade">Date & Time</span></th>
{{--                                            <th></th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($meetings as $meeting)
                                        <tr>
                                            <td class="pl-0 py-8">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <a href="#" class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$meeting->room_name}}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-dark font-weight-600 d-block font-size-16">
													{{$meeting->room_url}}
												</span>
                                            </td>
                                            <td>
                                                <span>{{$meeting->name}}</span>
                                            </td>
                                            <td>
                                                <span>{{$meeting->email}}</span>
                                            </td>
                                            <td>
                                                <span>{{$meeting->status}}</span>
                                            </td>
                                            <td>
                                                <span>{{$meeting->created_at}}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {{$meetings->links()}}
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </section>
            <!-- /.content -->

@endsection

