@extends('layouts.new-layout')
@section('content')
    <div class="row mt-5">
        <div class="col-md-12 col-lg-12">
            <h2 class="text-center">Ready to start with <br/>Konn3ct?</h2>
            <div class="text-center">Choose the package that suits you.</div>

            <div class="col-12 justify-content-center">
                <div class="form-check form-switch">
                    <label class="form-check-label" for="flexSwitchCheckChecked">Monthly</label>
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                    <label class="form-check-label" for="flexSwitchCheckChecked">Yearly</label>
                </div>
            </div>

            <div class="row">
                <div class="col-4 justify-content-center">
                    <div class="col-12 justify-content-center bg-primary">
                        <div>BASIC PLAN</div>
                        <div>Free forever</div>
                    </div>

                    <ul>
                        <li>Participant - 100</li>
                        <li>Session Timeout - 1 hour</li>
                    </ul>

                </div>

            </div>


        </div>

    </div>
@endsection

