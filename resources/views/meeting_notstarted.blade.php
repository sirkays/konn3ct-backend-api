@extends('layouts.layout')

@section('content')
    <!-- main-area -->
    <main>
        <!-- pricing-area -->
        <section id="pricing" class="pricing-area pt-20 pb-20">
            <div class="container">

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

                <div class="row justify-content-center">

                    <div class="col-12 text-center">
                        <h2 class="text-success">Meeting Room Preview</h2>
                        <span>Welcome to {{$room->name}} hosted by {{$owner->firstname}} {{$owner->lastname}}</span>
                        <p></p>
                    </div>

                    <div class="col-6">
                        <h4>Join via Phone? Dial</h4>
                        <p><i class="fa fa-phone"></i> Phone No: {{$room->dial_number}}</p>
                    </div>

                    <div class="col-6 wow fadeInDown animated text-center" data-animation="fadeInDown animated" data-delay=".2s">
                            <h4>Meeting Status: Meeting Not Started</h4>
                    </div>

                    <div class="col-12 text-center mb-lg-15 mt-10">
                        <p></p>
                        <p><img src="/assets/images/brokencircle.gif"/></p>
                        <h3>Waiting for Room to Start</h3>
                        <br/>

                        <button class="btn su" onclick="checkmeeting()">re-Konn3ct</button>

                            <form name="myForm" action="/ajoinroom" method="POST" style="display: none">
                                @csrf
                                <div class="text-center mb-60 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                                    <div class="form-group">
                                        <input type="text" name="url" class="form-control" value="{{$url ?? ''}}" placeholder="Paste Invite link or Enter Meeting Room Name" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" value="{{$name}}" placeholder="Enter your name e.g Samji Diamond" required autofocus autocomplete="name" >
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="email" class="form-control" value="{{$email}}" placeholder="Enter your Email Address e.g samjidiamond@gmail.com" required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn su">Konn3ct</button>
                                    </div>
                                </div>
                            </form>

                    </div>

                </div>
            </div>
        </section>
        <!-- pricing-area-end -->

        <p style="margin-top: 10px">

        </p>

    </main>
    <!-- main-area-end -->

    <script>
        function checkmeeting() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    var sta=JSON.parse(this.responseText);
                    if(sta.status===1) {
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

        const timeinterval =setInterval(checkmeeting, 60000);
    </script>


@endsection
