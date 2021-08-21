@extends('layouts.website-layout')
@section('content')
    <div class="row mt-5">
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

        <div class="col-md-12 col-lg-12" style="color: #012E89;">
            <h2 class="text-center" style="color: #012E89; font-weight: bold">MEETING ROOM PREVIEW</h2>
            <h6 class="text-center" style="color: #012E89;">Welcome to {{$meetingname}} hosted by {{$meetinghost}}</h6>

            <form action="{{route('konn3ct')}}" method="POST">

                <div class="row mb-3 mt-5 ml-5 text-justify">
                    <div class="col-2"></div>
                    <div class="px-3 py-2 col-4 mr-2">
                        <div style="color: #012E89">
                            <span style="font-weight: bolder">Join via Phone? Dial</span> <br/>
                            <i class="fa fa-check"> </i>Phone No : {{$dialNumber}} <br/>
                            Pin: @if($acode) xxx @else {{$pin}} @endif
                        </div>
                    </div>

                    <div class="col-2">
                        <img src="/assets/images/animation_500_krj7eu2n.gif" alt="loader" width="85px"
                             height="85px"/>
                    </div>

                    <div class="px-3 py-2 col-4 mr-2">
                        <div style="color: #012E89">
                            <span style="font-weight: bolder">Meeting Status :</span> {{$status}} <br/>
                            Participants: {{$pcount}} <br/>
                            Roll-Call: {{substr($participants, 0,  80)??''}}
                        </div>
                    </div>

                </div>

                <div class="row mt-5 mb-3">
                    @if($acode)
                        <div class="col-12 text-center">
                            This meeting room is restricted. <br/>
                            To join, kindly input the Room Access Code
                        </div>
                    @else
                        <div class="col-12 text-center">
                            This meeting room is unrestricted.
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4 text-center">
                        <div class="input-group mb-3 w-100">
                            @csrf
                            <input type="text" name="accesscode" class="form-control" placeholder="For Example: 2134"
                                   value="" autofocus @if($acode) required @endif />
                        </div>
                    </div>
                    <div class="col-4"></div>
                </div>


                <div class="row mt-4">
                    <div class="col-3"></div>
                    <div class="d-grid gap-2 col-3 mx-auto">
                        <button class="btn" type="submit" style="background-color: #012E89; color: white">Join</button>
                    </div>
                    <div class="d-grid gap-2 col-3 mx-auto">
                        <a href="{{url('/')}}" class="btn" style="color: white; background-color: #669340">Go Home</a>
                    </div>
                    <div class="col-3"></div>
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                    <span
                        class="text-muted mt-2">Kindly note that on joining this room an account may be created for you</span>

                    </div>

                </div>

            </form>

        </div>

    </div>
@endsection

