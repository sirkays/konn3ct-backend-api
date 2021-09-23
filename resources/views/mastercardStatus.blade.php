@extends('layouts.new-layout')
@section('content')
    <div class="row mt-5">
        <div class="col-md-12 col-lg-12 ml-4 text-center">

            {{--            <h2 class="text-center mt-5" style="color: green">Your payment is successful</h2>--}}
            <h2 class="text-center mt-5" style="color: red">Transaction cancelled by user</h2>
            {{--            <h2 class="text-center mt-5" style="color: #012E89">Debit Amount : 5USD</h2>--}}
            <h2 class="text-center mt-5" style="color: #012E89">Amount : 5USD</h2>
            <h6 class="text-center" style="color: grey">Transaction Reference: 1247856921434128</h6>

            <p class="text-dark my-10 font-size-16">
                Proceed to <a href="#">Dashboard</a>
            </p>


        </div>
    </div>

@endsection
