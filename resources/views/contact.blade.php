@extends('layouts.layout')

@section('content')
<!-- main-area -->
<main>

    <!-- contact-area -->
{{--    <section id="contact" class="contact-area contact-bg pt-120 pb-120 p-relative fix" style="background-image:url(img/bg/contact_bg.jpg)">--}}
        <div class="container mb-80">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="section-title text-center mb-30 wow fadeInDown animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <span>Contact</span>
                        <h2>Get In Touch</h2>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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

            <div class="row mb-90">
                <div class="col-lg-4">
                    <div class="single-cta pb-30 mb-30 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <div class="f-cta-icon">
                            <i class="far fa-mobile"></i>
                        </div>
                        <h5>Support Phone number</h5>
                        <p>+234 803 304 6408 <br>
                            +234 807 335 1737</p>
                    </div>
                    <div class="single-cta pb-30 mb-30 wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        <div class="f-cta-icon">
                            <i class="far fa-envelope-open"></i>
                        </div>
                        <h5>Message Us</h5>
                        <p>We are always with you to solve your problem
                            mail us : <a href="mailto:support@konn3ct.com">support@konn3ct.com</a></p>
                    </div>

                </div>

                <div class="col-lg-8 mb-40">
                    <form action="contact" method="post" class="contact-form wow fadeInUp animated" data-animation="fadeInDown animated" data-delay=".2s">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="contact-field p-relative c-name mb-40">
                                    <label>
                                        <input type="text" placeholder="Write your name here" name="name" required />
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="contact-field p-relative c-email mb-40">
                                    <label>
                                        <input type="text" placeholder="Write your email here" name="email" required />
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="contact-field p-relative c-subject mb-40">
                                    <label>
                                        <input type="text" placeholder="Subject" name="subject" required />
                                    </label>
                                </div>
                            </div>
{{--                            <div class="col-lg-6">--}}
{{--                                <div class="contact-field p-relative c-subject mb-40">--}}
{{--                                    <input type="file" name="attachment" placeholder="Attach file">--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-lg-12" style="margin-bottom: 50px">
                                <div class="contact-field p-relative c-message mb-45">
                                    <textarea name="content" id="message" cols="30" rows="10" placeholder="I would like to discuss on"></textarea>
                                </div>
                                <button type="submit" class="btn">Send Message</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>

{{--    </section>--}}
    <!-- contact-area-end -->

@endsection
