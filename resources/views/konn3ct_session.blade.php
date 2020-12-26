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
                        <span>Welcome to {{$meetingname}} hosted by {{$meetinghost}}</span>
                        <p></p>
                    </div>

                    <div class="col-6">
                        <h4>Join via Phone? Dial</h4>
                        <p><i class="fa fa-phone"></i> Phone No: {{$dialNumber}}</p>
                        <p><i class="fa fa-user-secret"></i> Pin: {{$pin}}</p>
                    </div>

                    <div class="col-6 wow fadeInDown animated text-center" data-animation="fadeInDown animated" data-delay=".2s">
                            <h4>Meeting Status: {{$status}}</h4>

                            <p><i class="fa fa-users"></i> Participants: {{$pcount}}</p>
                            <p><i class="fa fa-user-plus"></i> Roll-call: {{$participants??''}}</p>
                    </div>

                    <div class="col-12 text-center">
                        <p></p>
                        <p></p>
                        @if($acode)
                        This meeting room is restricted. <br/>
                        To join, kindly input the Room Access Code
                        <br/>

                            <form action="{{route('konn3ct')}}" method="POST">
                            @csrf
                            <div class="text-center mb-60 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                                <div class="form-group">
                                    <input type="text" name="code" class="" value="" placeholder="For Example: 37323" required>
                                </div>
                                <div class="form-group">
                                    <button class="btn su">Join</button>
                                    <a href="{{back()}}" class="btn btn-outline-danger">Go Back</a>
                                </div>
                            </div>
                        </form>
                        @else
                            <span>This meeting room is unrestricted.</span>
                        <form action="{{route('konn3ct')}}" method="post">
                            <div class="form-group">
                                <button class="btn su">Join</button>
                                <a href="{{back()}}" class="btn btn-outline-danger">Go Back</a>
                            </div>
                        </form>
                        @endif
                    </div>

                </div>
            </div>
        </section>
        <!-- pricing-area-end -->

    </main>
    <!-- main-area-end -->

@endsection
