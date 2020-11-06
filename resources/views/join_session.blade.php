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
                    <div class="col-xl-7 col-lg-8">
                        <div class="section-title text-center mb-80 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
                                                    <span>Join a Meeting Room</span>
{{--                            <h2>Pricing & Plans​</h2>--}}
                            <br/>
                            <form action="/ajoinroom" method="POST">
                                @csrf
                                <div class="text-center mb-60 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                                    <div class="form-group">
                                        <input type="text" name="url" class="form-control" value="{{$url ?? ''}}" placeholder="Paste Invite link or Enter Meeting Room Name" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" value="" placeholder="Enter your name e.g Samji Diamond" required autofocus autocomplete="name" >
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="email" class="form-control" value="{{\Illuminate\Support\Facades\Auth::user()->email ?? ''}}" placeholder="Enter your Email Address e.g samjidiamond@gmail.com" required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary">Konn3ct</button>
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
