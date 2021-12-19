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


                    <div class="col-4">
                        <div class="box box-body pull-up">
                            <div class="flexbox align-items-end pt-30">
                                <div>
                                    <span class="font-size-30 countnm">{{$roomstc}}</span>
                                    <h6 class="text-uppercase text-dark-50 mb-0">Total Rooms</h6>
                                </div>
                                <span class="icon-Angle-Grinder font-size-80 text-info"><span class="path1"></span><span
                                        class="path2"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="box box-body pull-up">
                            <div class="flexbox align-items-end pt-30">
                                <div>
                                    <span class="font-size-30 countnm">{{$active}}</span>
                                    <h6 class="text-uppercase text-dark-50 mb-0">Active Rooms</h6>
                                </div>
                                <span class="iconsmind-Eye font-size-80 text-primary"><span class="path1"></span><span
                                        class="path2"></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="box box-body pull-up">
                            <div class="flexbox align-items-end pt-30">
                                <div>
                                    <span class="font-size-30 countnm">{{$roomstc - $active }}</span>
                                    <h6 class="text-uppercase text-dark-50 mb-0">Inactive Rooms</h6>
                                </div>
                                <span class="iconsmind-Eye-Blind font-size-80 text-danger"><span
                                        class="path1"></span><span class="path2"></span></span>
                            </div>
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

