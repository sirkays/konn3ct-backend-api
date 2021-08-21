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
            <h6 class="text-center" style="color: #012E89;">Welcome to {{$meetingname ?? ''}} hosted
                by {{$meetinghost ?? ''}}</h6>

            <form action="{{route('konn3ct')}}" method="POST">

                <div class="row mb-3 mt-5 ml-5 text-justify">
                    <div class="col-2"></div>
                    <div class="px-3 py-2 col-4 mr-2">
                        <div style="color: #012E89">
                            <span style="font-weight: bolder">Join via Phone? Dial</span> <br/>
                            <i class="fa fa-check"> </i>Phone No : {{$dialNumber ?? ''}} <br/>
                        </div>
                    </div>

                    <div class="col-2">
                        <img src="/assets/images/animation_500_krj7eu2n.gif" alt="loader" width="85px"
                             height="85px"/>
                    </div>

                    <div class="px-3 py-2 col-4 mr-2">
                        <div style="color: #012E89">
                            <span style="font-weight: bolder">Meeting Status :</span> {{$status ?? ''}} <br/>
                        </div>
                    </div>

                </div>

                <div class="row mt-5 mb-2">
                    <div class="col-12 text-center" style="font-weight: bolder">
                        Waiting for Room to Start
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <div class="d-grid gap-2 mt-5 mx-auto">
                            <button class="btn" type="button" onclick="checkmeeting()"
                                    style="background-color: #012E89; color: white">Re-Konn3ct
                            </button>
                        </div>
                    </div>
                    <div class="col-4"></div>
                </div>

            </form>

        </div>

    </div>
@endsection

