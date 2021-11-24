@extends('layouts.website-layout')
<?php // @extends('errors::illustrated-layout') ?>
@section('title',  __('Page Not Found'))

@section('code', '404')

@section('content')
    <div class="hold-transition bg-img text-center" style="background-image: url(/assets/images/pathgroup.png)"
         data-overlay="5">
        <div class="container h-p100">
            <div class="row justify-content-md-center align-items-center h-p100">
                <div class="col-md-8 col-12">
                    <div class="box box-transparent no-border no-shadow">
                        <div class="box-body text-center">
                            <h1 class="mt-20 font-size-60 text-white">Oops! <br/>Page Not Found</h1>

                            <h3 class="mb-20 text-white">The page you were looking for could not be found, please <a
                                    href="mailto:support@newwavesecosystem.odoo.com">contact us</a> to report this
                                issue.</h3>

                            <p class="gap-items-2 mb-35">

                            </p>
                            <!--timer-->
                            <div class="examples my-35">
                                <div id="countdown" class="row justify-content-md-center text-white"></div>
                            </div>
                            <!--//timer-->
                            <div class="flexbox justify-content-center">
                                <a href="javascript:history.go(-1)" class="btn btn-danger btn-md mb-5"><i
                                        class="mdi mdi-skip-backward"></i> Go Back </a>
                                <a href="{{route('welcome')}}" class="btn btn-warning btn-md mb-5"><i
                                        class="mdi mdi-home"></i> Home </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
