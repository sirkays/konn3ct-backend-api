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
                    <div class="col-4">
                        <h2>Demo Meeting</h2>
                        <p>Dial Number: 613-555-1234</p>
                        <p>Pin: 70066</p>
                        <p>Participant: 4</p>

                    </div>
                    <div class="col-8">
                        <div class="section-title text-center mb-80 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">



                            <h3>Meeting is currently on</h3>

                            <p>Samji, olawole, bidemi</p>

                            <br/>
                            <form action="/ajoinroom" method="POST">
                                @csrf
                                <div class="text-center mb-60 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                                    <div class="form-group">
                                        <input type="hidden" name="url" class="form-control" value="{{$url ?? ''}}" placeholder="Paste Invite link or Enter Meeting Room Name" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="name" class="form-control" value="" placeholder="Enter your name e.g Samji Diamond" required autofocus autocomplete="name" >
                                    </div>
                                    <div class="form-group">
                                        <input type="hidden" name="email" class="form-control" value="{{\Illuminate\Support\Facades\Auth::user()->email ?? ''}}" placeholder="Enter your Email Address e.g samjidiamond@gmail.com" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="code" class="" value="" placeholder="For Example: 37323" required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn su">Konn3ct</button>
                                        <button class="btn btn-outline-danger">Close</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- pricing-area-end -->

    </main>
    <!-- main-area-end -->

@endsection
