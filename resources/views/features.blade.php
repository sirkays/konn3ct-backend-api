@extends('layouts.layout')

@section('content')
<!-- main-area -->
<main class="mb-95">
{{--    <a href="/register" id="freg">--}}
{{--        <a href="/" id="t freg" class="fa my-float btn btn-primary" style="margin-top:22px;">Register Now</a>--}}
{{--    </a>--}}

    <!-- choose-area -->
{{--    <section class="choose-area pt-120 pb-120 p-relative" style="background:#f5f8fa;">--}}
        <div class="wow fadeInRight animated" data-animation="fadeInRight animated" data-delay=".2s" style="background-image:url(/assets/img/bg/about.jpg)"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="choose-wrap">
                        <div class="section-title w-title left-align mb-10 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h5>Meeting, Chat & Calling​</h5>
                        </div>
                        <div class="choose-content wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <p>Pellentesque habitant morbi tristique senectus et netus et fames acturpis egestas. Vestibulum tortor quam, feugiat vitae, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. mivitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien.</p>--}}

                            <div class="choose-list mb-45">
                                <ul>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Start a meeting in 5 secs</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Customize link​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Enjoy HD Audio & Video in meetings for up-to 1000 students​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Full-Featured Admin Controls​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Multi-User Whiteboard (with Zoom In & Out)​​​​</span>
                                    </li>

                                    <span id="dots1"></span>
                                    <span class="more" id="more1">
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Multiple participants can co-annotate​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Do unlimited recording of your meeting​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Set User status to away, hand raised, undecided, confused, sad, happy, applaud, thumbs up & Thumbs down​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Chat a specific participant privately​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Chat the team on public chat​​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Join meeting without internet by calling from your phone​​​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Dial In people to join the session on their phones​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Dial Out allows you call people to join sessions via phone call​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Call Out calls participants on your behalf to join a session​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Share YouTube videos​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Create Breakout Rooms in-session​​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Manage notifications​​​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Audio Test​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Lock Users to allow you control participants actions​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Save Participants’ name lists​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Recording on Cloud and share via email​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Alert System (Pop-up & tone)​​​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Session Timeout​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Meeting Lock​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Waiting Room​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Audio & Video Preview Window​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Conduct Polls​​​​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Conduct  Q&A​</span>
                                    </li>
                                        </span>
                                    <button class="btn btn-info" onclick="myFunction(1)" id="myBtn1">Read more</button>

                                </ul>
                            </div>
{{--                            <div class="choose-btn">--}}
{{--                                <a href="#" class="btn">Work With us</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="choose-wrap">
                        <div class="section-title w-title left-align mb-10 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h5>Webinar & Conferencing​</h5>
                        </div>
                        <div class="choose-content wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <p>Pellentesque habitant morbi tristique senectus et netus et fames acturpis egestas. Vestibulum tortor quam, feugiat vitae, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. mivitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien.</p>--}}

                            <div class="choose-list mb-45">
                                <ul>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>All features of Meeting, Chat & Calling​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Customize link​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Host events with up to 100 HD audio & video participants/panelists​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Host events of up to 20,000 view-only participants with only the Presenter’s video on​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Control  participation using Access Code​</span>
                                    </li>

                                    <span id="dots2"></span>
                                    <span class="more" id="more2">
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Control participants  with mute/unmute, audio & video enablement​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Conduct Polls​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Conduct  Q&A​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Live Stream events on YouTube & Facebook​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Charge  for your events​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Audio Test​​​</span>
                                    </li>
                                        </span>
                                    <button class="btn btn-info" onclick="myFunction(2)" id="myBtn2">Read more</button>
                                </ul>
                            </div>
{{--                            <div class="choose-btn">--}}
{{--                                <a href="#" class="btn">Work With us</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="choose-wrap">
                        <div class="section-title w-title left-align mb-10 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h5>E-Learning​</h5>
                        </div>
                        <div class="choose-content wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <p>Pellentesque habitant morbi tristique senectus et netus et fames acturpis egestas. Vestibulum tortor quam, feugiat vitae, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. mivitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien.</p>--}}

                            <div class="choose-list mb-45">
                                <ul>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>All features of Meeting, Chat & Calling​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Start a meeting in 5 secs​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Customize link​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Enjoy HD Audio & Video in meetings for up-to 1000 participants​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Multiple participants can co-annotate​​​​</span>
                                    </li>

                                    <span id="dots3"></span>
                                    <span class="more" id="more3">
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Do unlimited recording of your meeting​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Set User status to away, hand raised, undecided, confused, sad, happy, applaud, thumbs up & Thumbs down​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Chat a specific participant privately​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Chat a specific participant privately​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Chat the team on public chat​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Join meeting without internet by calling from your phone​​​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Call in people to join the session on their phones​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Call Out when session starts​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Share YouTube videos​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Create Breakout Rooms in-session​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Encryption</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Integrates very well with LMS​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Users can collaborate with other Users from other schools​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Administrators & Teachers can meet for reviews​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Manage notifications​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Audio Test​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Session Timeout​​</span>
                                    </li>
                                         </span>
                                    <button class="btn btn-info" onclick="myFunction(3)" id="myBtn3">Read more</button>
                                </ul>
                            </div>
{{--                            <div class="choose-btn">--}}
{{--                                <a href="#" class="btn">Work With us</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="choose-wrap">
                        <div class="section-title w-title left-align mb-10 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h5>Work Remotely</h5>
                        </div>
                        <div class="choose-content wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <p>Pellentesque habitant morbi tristique senectus et netus et fames acturpis egestas. Vestibulum tortor quam, feugiat vitae, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. mivitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien.</p>--}}

                            <div class="choose-list mb-45">
                                <ul>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Pre-load presentations​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Share slides​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Share screen​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Collaborate on document using Shared Notes​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Open up to 9 whiteboards​</span>
                                    </li>

                                    <span id="dots4"></span>
                                    <span class="more" id="more4">

                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Share screen in flexible aspect ratios for optimal visualization​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Share webcam (Picture-in-Picture, Cast & Loop)​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Works on PC & mobile​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Record sessions​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Download Shared Notes in preferred formats​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Breakout rooms​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Polls</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Session Timeout​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Save, Copy & Delete Chats​</span>
                                    </li>
                                        </span>
                                    <button class="btn btn-info" onclick="myFunction(4)" id="myBtn4">Read more</button>
                                </ul>
                            </div>
{{--                            <div class="choose-btn">--}}
{{--                                <a href="#" class="btn">Work With us</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="choose-wrap">
                        <div class="section-title w-title left-align mb-10 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h5>Security & Compliance</h5>
                        </div>
                        <div class="choose-content wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <p>Pellentesque habitant morbi tristique senectus et netus et fames acturpis egestas. Vestibulum tortor quam, feugiat vitae, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. mivitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien.</p>--}}

                            <div class="choose-list mb-45">
                                <ul>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>SSL Encryption​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>TLS Encryption​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>AES-256 Encryption​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>LTI Integration​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Data Region​​​​</span>
                                    </li>

                                    <span id="dots5"></span>
                                    <span class="more" id="more5">

                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>100% Host Node &  Network Uptime​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>SOC 1 Type I , SOC 1 Type II, SOC 2 Type II, ISO 27001 & PCI-DSS​​</span>
                                    </li>
                                        </span>
                                    <button class="btn btn-info" onclick="myFunction(5)" id="myBtn5">Read more</button>
                                </ul>
                            </div>
{{--                            <div class="choose-btn">--}}
{{--                                <a href="#" class="btn">Work With us</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="choose-wrap">
                        <div class="section-title w-title left-align mb-10 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h5>Others</h5>
                        </div>
                        <div class="choose-content wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <p>Pellentesque habitant morbi tristique senectus et netus et fames acturpis egestas. Vestibulum tortor quam, feugiat vitae, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. mivitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien.</p>--}}

                            <div class="choose-list mb-45">
                                <ul>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Tooltip​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>API​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Branding & Whitelisting​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Managed Domains​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Single Sign On​​​​​</span>
                                    </li>

                                    <span id="dots6"></span>
                                    <span class="more" id="more6">
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Unlimited Cloud Storage​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Dedicated Customer Success Manager​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Ticket, Live Chat & Phone Support​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Payment Gateway​</span>
                                    </li>
                                        </span>
                                    <button class="btn btn-info" onclick="myFunction(6)" id="myBtn6">Read more</button>
                                </ul>
                            </div>
{{--                            <div class="choose-btn">--}}
{{--                                <a href="#" class="btn">Work With us</a>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                </div>


            </div>
        </div>
{{--    </section>--}}
    <!-- choose-area-end -->

    <script>
        function myFunction(i) {
            var dots = document.getElementById("dots"+i);
            var moreText = document.getElementById("more"+i);
            var btnText = document.getElementById("myBtn"+i);

            if (dots.style.display === "none") {
                dots.style.display = "inline";
                btnText.innerHTML = "Read more";
                moreText.style.display = "none";
            } else {
                dots.style.display = "none";
                btnText.innerHTML = "Read less";
                moreText.style.display = "inline";
            }
        }
    </script>

@endsection
