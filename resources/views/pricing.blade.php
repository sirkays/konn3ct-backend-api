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
                                <h2><span>Free forever</span></h2>
                            </div>
                        </div>
                        <div class="pricing-body mb-40 text-left">
{{--                            <p>It is a long established fact that a reader will be distracted.</p>--}}
                            <ul>
                                <li>Participant - 100</li>
                                <li>Session Timeout - 1 hour</li>
                                <li>Cloud Storage - 1GB</li>
                                <li>Number of Rooms - 1</li>
                                <li>Audio & Video Preview Window - Yes</li>
                                <li>Screen Sharing - Yes</li>
                                <li>Whiteboard & Annotation Tools - Yes</li>
                                <li>User Status - Yes</li>
                                <li style="text-decoration: line-through">Customize link - No</li>
                                <li>Breakout Rooms - Yes</li>
                                <li>Recording - Yes</li>
                                <li>Full-Featured Admin Controls - Yes</li>
                                <li>Share Webcam - Yes</li>
                                <li>Shared Notes - Yes</li>
                                <li>Share YouTube Videos - Yes</li>
                                <li class="list-group-item-danger">Preload Presentations - No</li>
                                <li>Pop-Up & Tone Notifications - Yes</li>
                                <li>Dial In - No</li>
                                <li>Chat (Private & Public) - Yes</li>
                                <li>Waiting Room - Yes</li>
                                <li>Save Participants’ List​ - Yes</li>
                                <li>Download Chats in multi-formats - Yes</li>
                                <li>Conduct Polls - Yes</li>
                                <li>Web App - Yes</li>
                                <li>Access Code - No</li>
                                <li>Live Chat & Phone Support - Yes</li>
                                <li>SSL Encryption - Yes</li>
                                <li>TLS Encryption - Yes</li>
                                <li>AES-256 Encryption - Yes</li>
                                <li>100% Host Node &  Network Uptime - Yes</li>
                                <li>Data Centre Compliance​
                                    SOC 1 Type I , SOC 1 Type II, SOC 2 Type II, ISO 27001 & PCI-DSS - Yes</li>
                            </ul>
                        </div>
                        <div class="pricing-btn">
                            <a href="/register" class="btn">Choose Plan</a>
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
                                <li>Audio & Video Preview Window - Yes</li>
                                <li>Screen Sharing - Yes</li>
                                <li>Whiteboard & Annotation Tools - Yes</li>
                                <li>User Status - Yes</li>
                                <li>Customize link - Yes</li>
                                <li>Breakout Rooms - Yes</li>
                                <li>Recording - Yes</li>
                                <li>Full-Featured Admin Controls - Yes</li>
                                <li>Share Webcam - Yes</li>
                                <li>Shared Notes - Yes</li>
                                <li>Share YouTube Videos - Yes</li>
                                <li>Preload Presentations - Yes</li>
                                <li>Pop-Up & Tone Notifications - Yes</li>
                                <li>Dial In - Yes</li>
                                <li>Chat (Private & Public) - Yes</li>
                                <li>Waiting Room - Yes</li>
                                <li>Save Participants’ List​ - Yes</li>
                                <li>Download Chats in multi-formats - Yes</li>
                                <li>Conduct Polls - Yes</li>
                                <li>Web App - Yes</li>
                                <li>Access Code - Yes</li>
                                <li>Live Chat & Phone Support - Yes</li>
                                <li>SSL Encryption - Yes</li>
                                <li>TLS Encryption - Yes</li>
                                <li>AES-256 Encryption - Yes</li>
                                <li>100% Host Node &  Network Uptime - Yes</li>
                                <li>Data Centre Compliance​
                                    SOC 1 Type I , SOC 1 Type II, SOC 2 Type II, ISO 27001 & PCI-DSS - Yes</li>
                            </ul>
                        </div>
                        <div class="pricing-btn">
                            <a href="/register/2" class="btn">Choose Plan</a>
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
                                <li>Session Timeout - Unlimited</li>
                                <li>Cloud Storage - 15GB</li>
                                <li>Number of Rooms - Unlimited</li>
                                <li>Audio & Video Preview Window - Yes</li>
                                <li>Screen Sharing - Yes</li>
                                <li>Whiteboard & Annotation Tools - Yes</li>
                                <li>User Status - Yes</li>
                                <li>Customize link - Yes</li>
                                <li>Breakout Rooms - Yes</li>
                                <li>Recording - Yes</li>
                                <li>Full-Featured Admin Controls - Yes</li>
                                <li>Share Webcam - Yes</li>
                                <li>Shared Notes - Yes</li>
                                <li>Share YouTube Videos - Yes</li>
                                <li>Preload Presentations - Yes</li>
                                <li>Pop-Up & Tone Notifications - Yes</li>
                                <li>Dial In - Yes</li>
                                <li>Chat (Private & Public) - Yes</li>
                                <li>Waiting Room - Yes</li>
                                <li>Save Participants’ List​ - Yes</li>
                                <li>Download Chats in multi-formats - Yes</li>
                                <li>Conduct Polls - Yes</li>
                                <li>Web App - Yes</li>
                                <li>Access Code - Yes</li>
                                <li>Live Chat & Phone Support - Yes</li>
                                <li>SSL Encryption - Yes</li>
                                <li>TLS Encryption - Yes</li>
                                <li>AES-256 Encryption - Yes</li>
                                <li>100% Host Node &  Network Uptime - Yes</li>
                                <li>Data Centre Compliance​
                                    SOC 1 Type I , SOC 1 Type II, SOC 2 Type II, ISO 27001 & PCI-DSS - Yes</li>
                            </ul>
                        </div>
                        <div class="pricing-btn">
                            <a href="/register/3" class="btn">Choose Plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- pricing-area-end -->

</main>
<!-- main-area-end -->

<form>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <button type="button" onClick="makePayment()">Pay Now</button>
</form>

<script>
    function makePayment() {
        FlutterwaveCheckout({
            public_key: "FLWPUBK_TEST-SANDBOXDEMOKEY-X",
            tx_ref: "hooli-tx-1920bbtyt",
            amount: 54600,
            currency: "NGN",
            country: "NG",
            payment_options: "card, mobilemoneyghana, ussd",
            redirect_url: // specified redirect URL
                "https://callbacks.piedpiper.com/flutterwave.aspx?ismobile=34",
            meta: {
                consumer_id: 23,
                consumer_mac: "92a3-912ba-1192a",
            },
            customer: {
                email: "user@gmail.com",
                phone_number: "08102909304",
                name: "yemi desola",
            },
            callback: function (data) {
                console.log(data);
            },
            onclose: function() {
                // close modal
            },
            customizations: {
                title: "My store",
                description: "Payment for items in cart",
                logo: "https://assets.piedpiper.com/logo.png",
            },
        });
    }
</script>
@endsection
