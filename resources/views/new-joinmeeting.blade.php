@extends('layouts.new-layout')
@section('content')
    <div class="row mt-5">
        <div class="col-md-12 col-lg-12">
            <h2 class="text-center mb-4" style="color: #012E89">JOIN A MEETING ROOM</h2>

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

            <form action="/ajoinroom" method="POST">
                @csrf
                <div class="mb-1" style="margin-left: 20%; margin-right: 20%">
                    <div class="row">
                        <div class="px-3 py-2 col-12 mr-2">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="url" class="form-control"
                                       value="{{$url ?? ''}}" placeholder="Paste invite link or Enter meeting room name"
                                       aria-label="First Name" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-1" style="margin-left: 20%; margin-right: 20%">
                    <div class="row">
                        <div class="px-3 py-2 col-12 mr-2">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="name" value=""
                                       placeholder="Enter your name e.g. Samji Diamond" aria-label="First Name"
                                       aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-1" style="margin-left: 20%; margin-right: 20%">
                    <div class="row">
                        <div class="px-3 py-2 col-12 mr-2">
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="Enter your email address"
                                       value="{{\Illuminate\Support\Facades\Auth::user()->email ?? old('email')}}"
                                       aria-label="email" aria-describedby="basic-addon1">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2" style="margin-left: 20%; margin-right: 20%">
                    <button type="submit" class="btn px-3 py-3 mr-3 mt-2"
                            style="border-radius: 10px; background-color: #012E89; color: white; font-weight: bolder">
                        Konn3ct
                    </button>
                </div>

            </form>

        </div>

    </div>
@endsection

