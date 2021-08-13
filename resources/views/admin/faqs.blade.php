@extends('layouts.admin-layout')

@section('content')

    <!-- Main content -->
    <section class="content">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="w-p100 d-md-flex align-items-center justify-content-between">
                    <h3 class="page-title">Faqs</h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                <li class="breadcrumb-item" aria-current="page">Admin</li>
                                <li class="breadcrumb-item active" aria-current="page">Faqs</li>
                            </ol>
                        </nav>
                    </div>
                </div>

            </div>
        </div>

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
            <div class="col-2">
                <div class="box box-body pull-up">
                    <Button class="waves-effect waves-light btn btn-app btn-info btn-" data-toggle="modal"
                            data-target="#modal-left">
                        <i class="fa fa-plus"></i> Add Faq
                    </Button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h6 class="box-subtitle">Below is the list of Faqs</h6>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">

                            <table class="table table-lg invoice-archive">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Status</th>
                                    <th>Date created</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($faqs as $data)
                                    <tr>
                                        <td>#{{$i++}}</td>
                                        <td>
                                            <h6 class="mb-0">
                                                <a href="#">{{$data->title}}</a>
                                            </h6>
                                        </td>
                                        <td> {{$data->content}} </td>
                                        <td>
                                            {{\Carbon\Carbon::parse($data->created_at)->toFormattedDateString()}}
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

