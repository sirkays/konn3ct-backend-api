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
                        <br>
                        <a href="#planssi" onclick="myFunction()" id="myBtn">Click me to See Annual Price</a>
                    </div>
                </div>
            </div>
            <div class="row" id="planssi">
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
                                <li>Full-Featured Admin Controls - Yes</li>
                                <li>Share Webcam - Yes</li>
                                <li>Shared Notes - Yes</li>
                                <li>Pop-Up & Tone Notifications - Yes</li>
                                <li>Chat (Private & Public) - Yes</li>
                                <li>Waiting Room - Yes</li>
                                <li>Save Participants’ List​ - Yes</li>
                                <li>Download Chats in multi-formats - Yes</li>
                                <li>Conduct Polls - Yes</li>
                                <li>Web App - Yes</li>
                                <li>Live Chat - Yes</li>
                                <li>SSL Encryption - Yes</li>
                                <li>TLS Encryption - Yes</li>
                                <li>AES-256 Encryption - Yes</li>
                                <li>100% Host Node &  Network Uptime - Yes</li>
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
                                <span id="monthly">
                                    <h2><small>$</small>10.99 <span>/ Monthly</span></h2>
                                    <h5>&#x20A6;4,000 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
                                </span>

                                <span class="more" id="yearly">
                                    <h2><small>$</small>120 <span>/ Yearly</span></h2>
                                    <h5>&#x20A6;46,000 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
                                </span>
                            </div>
                        </div>
                        <div class="pricing-body mb-40 text-left">
{{--                            <p>It is a long established fact that a reader will be distracted.</p>--}}
                            <ul>
                                <li>Participant - 100</li>
                                <li>Session Timeout - 10 hour</li>
                                <li>Cloud Storage - 5GB</li>
                                <li>Number of Rooms - 5</li>
                                <li>Audio & Video Preview Window - Yes</li>
                                <li>Screen Sharing - Yes</li>
                                <li>Whiteboard & Annotation Tools - Yes</li>
                                <li>User Status - Yes</li>
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
                            <a id="r1" href="/register/2" class="btn">Choose Plan</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="pricing-box text-center mb-60 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <div class="pricing-head">
                            <h4>Pro Plan</h4>
                            <div class="price-count mb-30">
                                <span id="monthly2">
                                    <h2><small>$</small>15.99 <span>/ Monthly</span></h2>
                                    <h5>&#x20A6;6,000 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
                                </span>

                                <span class="more" id="yearly2">
                                    <h2><small>$</small>175 <span>/ Yearly</span></h2>
                                    <h5>&#x20A6;67,000 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h5>
                                </span>
                            </div>
                        </div>
                        <div class="pricing-body mb-40 text-left">
{{--                            <p>It is a long established fact that a reader will be distracted.</p>--}}
                            <ul>
                                <li>Participant - 250</li>
                                <li>Session Timeout - 24 hours</li>
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
                            <a id="r2" href="/register/3" class="btn">Choose Plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- pricing-area-end -->

</main>
<!-- main-area-end -->

<script>
    function myFunction() {
        var dots = document.getElementById("monthly");
        var moreText = document.getElementById("yearly");
        var register = document.getElementById("r1");
        var dots2 = document.getElementById("monthly2");
        var moreText2 = document.getElementById("yearly2");
        var register2 = document.getElementById("r2");
        var btnText = document.getElementById("myBtn");

        if (dots.style.display === "none") {
            btnText.innerHTML = "See Annual Price";

            dots.style.display = "inline";
            moreText.style.display = "none";

            var att = document.createAttribute("href");        // Create a "href" attribute
            att.value = "/register/2";            // Set the value of the href attribute
            register.setAttributeNode(att);

            dots2.style.display = "inline";
            moreText2.style.display = "none";

            var att2 = document.createAttribute("href");        // Create a "href" attribute
            att2.value = "/register/3";            // Set the value of the href attribute
            register2.setAttributeNode(att2);

        } else {
            btnText.innerHTML = "See Monthly Price";

            dots.style.display = "none";
            moreText.style.display = "inline";

            var att = document.createAttribute("href");        // Create a "href" attribute
            att.value = "/register/21";            // Set the value of the href attribute
            register.setAttributeNode(att);

            dots2.style.display = "none";
            moreText2.style.display = "inline";

            var att2 = document.createAttribute("href");        // Create a "href" attribute
            att2.value = "/register/31";            // Set the value of the href attribute
            register2.setAttributeNode(att2);
        }
    }
</script>
@endsection
