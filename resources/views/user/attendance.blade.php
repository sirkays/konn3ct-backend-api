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
            <div class="col-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title align-items-start flex-column">
                            Meetings Attendance
                            {{--                                    <small class="subtitle">This table show the list of meetings joined </small>--}}
                        </h4>

                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-border" id="complex_header" style="width:100%">
                                <thead>
                                <tr class="text-uppercase bg-lightest">
                                    <th><span class="text-fade">S/N</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Meeting Host</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Meeting Date & Time</span></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($meetings as $meeting)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td class="pl-0 py-8">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a href="#"
                                                       class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$meeting->name}}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span>{{$meeting->created_at}}</span>
                                        </td>
                                        <td>
                                            <a href="{{route('participants', $meeting->identifier)}}"
                                               class="btn btn-primary btn-sm">View Participant(s)</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{--                                    {{$meetings->links()}}--}}
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
    <!-- /.content -->


@endsection
