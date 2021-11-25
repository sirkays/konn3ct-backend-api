@extends('layouts.new-layout')
@section('content')
    <div class="row mt-5">
        <div class="col-md-12 col-lg-6 ml-4 text-center">

            <h2 class="text-center mt-5" style="color: #012E89">Amount to Debit : {{$amount}}USD</h2>
            <h6 class="text-center" style="color: grey">Payment Reference: {{$ref}}</h6>

            <p class="text-dark my-10 font-size-16">
                Instruction: Kindly fill in your card details and click on Pay button.
            </p>


        </div>
        <div class="col-md-12 col-lg-6 mt-1">

            @if (session('status'))
                <div class="mb-1 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-1 font-medium text-sm text-danger text-center">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert-danger alert">
                    <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>

                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="mt-2" method="POST" action="{{route('makePayment.Mastercard')}}">
                @csrf
                <input type="hidden" value="{{$plan}}" name="plan"/>
                <input type="hidden" value="{{$type}}" name="type"/>
                <input type="hidden" value="{{$ref}}" name="ref"/>
                <div class="mb-2">

                    <div class="row justify-content-start" style="color: grey; font-weight: bold">
                        <div class="px-3 py-2 col-12 mr-2">
                            <label for="exampleInputEmail1" class="form-label text-left">Card Number</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Enter Card Number"
                                       name="cardnumber" required autofocus>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-start" style="color: grey; font-weight: bold">
                        <div class="px-3 py-2 col-6 mr-2">
                            <label for="exampleInputEmail1" class="form-label text-left">Expiry Month</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="MM"
                                       name="expiryMonth" maxlength="2" required>
                            </div>
                        </div>

                        <div class="px-3 py-2 col-6 ml-2">
                            <label for="exampleInputEmail1" class="form-label">Expiry Year</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="YY" maxlength="2"
                                       name="expiryYear" required>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-start" style="color: grey; font-weight: bold">
                        <div class="px-3 py-2 col-6 ml-2">
                            <label for="exampleInputEmail1" class="form-label">CVV</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="***" maxlength="3"
                                       name="cvv" required>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="d-grid gap-2 mt-1" style="margin-left: 20%; margin-right: 20%">
                    <button type="submit" class="btn px-3 py-3 mr-3 mt-2"
                            style="border-radius: 10px; background-color: #012E89; color: white; font-weight: bolder">
                        Pay
                    </button>
                </div>


                <div class="col-12 text-center mt-4" style="color: grey">
                    Cancel and <a href="{{route('dashboard')}}" style="color: grey">go Home?</a>
                </div>

                <div class="col-12 text-center mt-5" style="color: grey">
                    <div class="text-center">
                        <img src="/assets/images/visa_mastercard.png" class="col-2" alt="pix"/>
                        <br/>
                        <span style="font-weight: bolder">Mastercard and Visa cards are accepted here</span>
                    </div>
                </div>

            </form>

        </div>

    </div>

@endsection
