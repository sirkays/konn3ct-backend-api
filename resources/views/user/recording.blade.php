@extends('layouts.user-layout')

@section('content')

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title align-items-start flex-column">
                                    Recording
                                    <small class="subtitle">Below are your meeting recording(s)</small>
                                </h4>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table no-border">
                                        <thead>
                                        <tr class="text-uppercase bg-lightest">
                                            <th style="min-width: 250px"><span class="text-fade">Room Name</span></th>
                                            <th style="min-width: 100px"><span class="text-fade">Size</span></th>
                                            <th style="min-width: 130px"><span class="text-fade">Users</span></th>
                                            <th style="min-width: 120px"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($recordings as $record)
                                        <tr>
                                            <td class="pl-0 py-8">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <a href="#" class="text-dark font-weight-600 hover-primary mb-1 font-size-16">{{$record['name']}}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-dark font-weight-600 d-block font-size-16">
													{{ number_format(($record['size']/1000000))}}
												</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success badge-lg">{{$record['participants']}}</span>
                                            </td>
                                            <td class="text-right">
                                                <a class="waves-effect waves-light btn btn-app btn-success" href="{{$record['playback']['format']['url']}}">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                                <a class="waves-effect waves-light btn btn-app btn-danger" href="#">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
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
