@extends('layouts.layout')

@section('content')
<!-- main-area -->
<main>
    <!-- pricing-area -->
    <section id="pricing" class="pricing-area pt-113 pb-90">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="section-title text-center mb-80 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                        <span>Pricing List</span>--}}
                        <h2>Pricing & Plans​</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-box text-center mb-60 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <div class="pricing-head">
                            <h4>Basic Plan</h4>
                            <div class="price-count mb-30">
                                <h2><span>Free</span></h2>
                            </div>
                        </div>
                        <div class="pricing-body mb-40 text-left">
{{--                            <p>It is a long established fact that a reader will be distracted.</p>--}}
                            <ul>
                                <li>Participant - 100</li>
                                <li>Session Timeout - 1 hour</li>
                                <li>Cloud Storage - 1GB</li>
                                <li>Number of Rooms - 1</li>
                            </ul>
                        </div>
                        <div class="pricing-btn">
                            <a href="#" class="btn">Choose Plan</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-box active text-center mb-60 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <div class="pricing-head">
                            <h4>Lite Plan</h4>
                            <div class="price-count mb-30">
                                <h2><small>$</small>10.99 <span>/ Monthly</span></h2>
                            </div>
                        </div>
                        <div class="pricing-body mb-40 text-left">
{{--                            <p>It is a long established fact that a reader will be distracted.</p>--}}
                            <ul>
                                <li>Participant - 100</li>
                                <li>Session Timeout - 24 hour</li>
                                <li>Cloud Storage - 5GB</li>
                                <li>Number of Rooms - 5</li>
                            </ul>
                        </div>
                        <div class="pricing-btn">
                            <a href="#" class="btn">Choose Plan</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-box text-center mb-60 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <div class="pricing-head">
                            <h4>Pro Plan</h4>
                            <div class="price-count mb-30">
                                <h2><small>$</small>15.99 <span>/ Monthly</span></h2>
                            </div>
                        </div>
                        <div class="pricing-body mb-40 text-left">
{{--                            <p>It is a long established fact that a reader will be distracted.</p>--}}
                            <ul>
                                <li>Participant - 250</li>
                                <li>Session Timeout - 1 hour</li>
                                <li>Cloud Storage - 1GB</li>
                                <li>Number of Rooms - Unlimited</li>
                            </ul>
                        </div>
                        <div class="pricing-btn">
                            <a href="#" class="btn">Choose Plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- pricing-area-end -->

</main>
<!-- main-area-end -->

@endsection
