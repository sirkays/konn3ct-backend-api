@extends('layouts.layout')

@section('content')
<!-- main-area -->
<main>

    <!-- choose-area -->
    <section class="choose-area pt-120 pb-120 p-relative" style="background:#f5f8fa;">
        <div class="wow fadeInRight animated" data-animation="fadeInRight animated" data-delay=".2s" style="background-image:url(/assets/img/bg/about.jpg)"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-6">
                    <div class="choose-wrap">
                        <div class="section-title w-title left-align mb-35 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h2>Meeting, Chat & Calling​</h2>
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
                                        <span>Enjoy HD Audio & Video in meetings for up-to 1000 participants​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Full-Featured Admin Controls​​​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>Multi-User Whiteboard (with Zoom In & Out)​​​​</span>
                                    </li>
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
                        <div class="section-title w-title left-align mb-35 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h2>Webinar & Conferencing​</h2>
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
                        <div class="section-title w-title left-align mb-35 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h2>E-Learning​</h2>
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
                        <div class="section-title w-title left-align mb-35 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h2>Work Remotely</h2>
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
                        <div class="section-title w-title left-align mb-35 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h2>Security & Compliance</h2>
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
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>100% Host Node &  Network Uptime​</span>
                                    </li>
                                    <li>
                                        <i class="icon dripicons-checkmark"></i>
                                        <span>SOC 1 Type I , SOC 1 Type II, SOC 2 Type II, ISO 27001 & PCI-DSS​​</span>
                                    </li>
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
                        <div class="section-title w-title left-align mb-35 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
{{--                            <span>Creative Landingpage</span>--}}
                            <h2>Others</h2>
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
    </section>
    <!-- choose-area-end -->

@endsection
