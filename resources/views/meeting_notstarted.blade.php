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
            <h6 class="text-center" style="color: #012E89;">Welcome to {{$room->name ?? ''}} hosted
                by {{$owner->firstname ?? ''}} {{$owner->lastname ?? ''}}</h6>

            <div class="row mb-3 mt-5 ml-5 text-justify">
                <div class="col-2"></div>
                <div class="px-3 py-2 col-4 mr-2">
                    <div style="color: #012E89">
                        <span style="font-weight: bolder">Join via Phone? Dial</span> <br/>
                        <i class="fa fa-check"> </i>Phone No : {{$room->dial_number ?? ''}} <br/>
                    </div>
                </div>

                <div class="col-2">
                    <img src="/assets/images/animation_500_krj7eu2n.gif" alt="loader" width="85px"
                         height="85px"/>
                </div>

                <div class="px-3 py-2 col-4 mr-2">
                    <div style="color: #012E89">
                        <span style="font-weight: bolder">Meeting Status :</span> Meeting not started <br/>
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

            <form name="myForm" action="/ajoinroom" method="POST" style="display: none">
                @csrf
                <div class="text-center mb-60 wow fadeInUp animated" data-animation="fadeInDown animated"
                     data-delay=".2s">
                    <div class="form-group">
                        <input type="text" name="url" class="form-control" value="{{$url ?? ''}}"
                               placeholder="Paste Invite link or Enter Meeting Room Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" value="{{$name}}"
                               placeholder="Enter your name e.g Samji Diamond" required autofocus autocomplete="name">
                    </div>
                    <div class="form-group">
                        <input type="text" name="email" class="form-control" value="{{$email}}"
                               placeholder="Enter your Email Address e.g samjidiamond@gmail.com" required>
                    </div>
                </div>
            </form>


        </div>

    </div>
    <!-- main-area-end -->

    <script>
        function checkmeeting() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var sta = JSON.parse(this.responseText);
                    if (sta.status === 1) {
                        clearInterval(timeinterval);
                        document.myForm.submit();
                    }
                }else{
                    console.log(this.responseText);
                }
            };
            xhttp.open("GET", "/roomstatus/{{$url}}", true);
            xhttp.send();
        }

        const timeinterval =setInterval(checkmeeting, 30000);
    </script>


@endsection


@section('before-styles')
    @laravelPWA
@stop
