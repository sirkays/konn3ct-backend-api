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
                            Pre-Registered Users
                            {{--                                    <small class="subtitle">This table show the list of meetings joined </small>--}}
                        </h4>

                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-lg invoice-archive">
                                <thead>
                                <tr class="text-uppercase bg-lightest">
                                    <th><span class="text-fade">S/N</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Name</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Email</span></th>
                                    <th style="min-width: 10px"><span class="text-fade">Phone</span></th>
                                    {{--                                            <th style="min-width: 10px"><span class="text-fade">Status</span></th>--}}
                                    <th style="min-width: 10px"><span class="text-fade">Date & Time</span></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td class="pl-0 py-8">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <a href="#"
                                                       class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$user->name}}</a>
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
                                            <span>{{$user->created_at}}</span>
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
