@extends('layouts.website-layout')

@section('content')
    <!-- main-area -->
    <main>
        <!-- pricing-area -->
        <section id="pricing" class="pricing-area pt-113 pb-90">
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

                <div class="row justify-content-center mt-5">
                    <div class="col-xl-7 col-lg-8">
                        <h2 class="text-center mb-4" style="color: #012E89">You left the Meeting</h2>

                        <div class="d-grid gap-2" style="margin-left: 20%; margin-right: 20%">
                            @auth
                                <a href="{{route('rooms')}}" class="btn px-3 py-3 mr-3 mt-2"
                                   style="border-radius: 10px; background-color: #012E89; color: white; font-weight: bolder">
                                    Goto Meeting Room
                                </a>
                            @else
                                <a href="{{route('rooms')}}" class="btn px-3 py-3 mr-3 mt-2"
                                   style="border-radius: 10px; background-color: #012E89; color: white; font-weight: bolder">
                                    Goto Meeting Room
                                </a>
                            @endif


                            <a href="/contact" class="btn px-3 py-3 mr-3 mt-2"
                               style="border-radius: 10px; background-color: #085523; color: white; font-weight: bolder">
                                Submit Feedback
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- pricing-area-end -->

    </main>
    <!-- main-area-end -->

    <p style="margin-top: 20px"></p>

@endsection

@section('before-styles')
    @laravelPWA
@stop

