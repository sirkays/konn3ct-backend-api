@extends('layouts.layout')

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

                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8">
                        <div class="section-title text-center mb-5 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                                                    <span>You left the Meeting</span>--}}
                            <h2>You left the Meeting</h2>
                        </div>
                        <div class="text-center">
                            @auth
                                <a href="/room" class="btn btn-outline">Goto Meeting Room</a>
                            @else
                                <a href="/register" class="btn btn-outline">Click here to Register</a>
                            @endif

                            <br />
                            <a href="/contact" class="li">Submit Feedback</a>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <!-- pricing-area-end -->

    </main>
    <!-- main-area-end -->

@endsection
<script>
    import Button from "../js/Jetstream/Button";
    export default {
        components: {Button}
    }
</script>
