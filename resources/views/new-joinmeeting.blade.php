@extends('layouts.new-layout')
@section('content')
    <div class="row mt-5">
        <div class="col-md-12 col-lg-12">
            <h2 class="text-center">JOIN A MEETING ROOM</h2>

            <form>
                <div class="mb-3" style="margin-left: 20%; margin-right: 20%">
                    <div class="row">
                        <div class="px-3 py-2 col-12 mr-2">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control"
                                       placeholder="Paste invite link or Enter meeting room name"
                                       aria-label="First Name" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3" style="margin-left: 20%; margin-right: 20%">
                    <div class="row">
                        <div class="px-3 py-2 col-12 mr-2">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Enter your name e.g. Samji Diamond"
                                       aria-label="First Name" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3" style="margin-left: 20%; margin-right: 20%">
                    <div class="row">
                        <div class="px-3 py-2 col-12 mr-2">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Enter your email address"
                                       aria-label="First Name" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2" style="margin-left: 20%; margin-right: 20%">
                    <button class="btn btn-primary" type="button">Konn3ct</button>
                </div>

            </form>

        </div>

    </div>
@endsection

