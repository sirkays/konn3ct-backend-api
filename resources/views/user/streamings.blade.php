@extends('layouts.user-layout')

@section('content')

    <!-- Main content -->
    <section class="content">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="w-p100 d-md-flex align-items-center justify-content-between">
                    <h3 class="page-title">Streaming</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Streaming</li>
                                <li class="breadcrumb-item active" aria-current="page">Streaming List</li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">

            @if (session('success'))
                <div class="alert alert-success">
                    {!! session('success') !!}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Streaming List</h4>
                        <h6 class="box-subtitle">You can stop your streaming here </h6>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">

                            <table id="example" class="table table-lg invoice-archive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Room Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Started At</th>
                                    <th>Ended At</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($streams as $data)
                                    <tr>
                                        <td>#{{$i++}}</td>
                                        <td>{{$data->room->name}}</td>
                                        <td><span class="d-block text-muted"> {{$data->type}}</span>
                                        </td>
                                        <td>
                                            @if($data->status == 0)
                                                <span class="badge badge-pill badge-success">Ended</span>
                                            @else
                                                <span class="badge badge-pill badge-danger">Running</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{$data->created_at}}
                                        </td>
                                        <td>
                                            {{$data->ended_at}}
                                        </td>
                                        <td>
                                            @if($data->status == 1)
                                                <a class="btn btn-success" href="{{route('stopStreaming', $data->id)}}"
                                                   data-toggle="tooltip" data-placement="top" title="Stop Streaming">Stop
                                                    Streaming</a>
                                            @endif
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

@endsection

