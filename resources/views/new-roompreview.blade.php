@extends('layouts.new-layout')
@section('content')
    <div class="row mt-5">
        <div class="col-md-12 col-lg-12">
            <h2 class="text-center" style="color: #012E89">MEETING ROOM PREVIEW</h2>

            <form>
                <div class="mb-3">
                    <div class="row">
                        <div class="px-3 py-2 col-4 mr-2">
                            <div>
                                Join via Phone? Dial <br/>
                                Phone No : +1 970-519-2253
                            </div>
                        </div>

                        <div class="col-2">
                            <img src="/assets/images/animation_500_krj7eu2n.gif" alt="loader" width="85px"
                                 height="85px"/>
                        </div>

                        <div class="px-3 py-2 col-4 mr-2">
                            <div>
                                Meeting Status : <br/>
                                Meeting not started
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row mt-5 mb-3">
                    <div class="col-12 justify-content-center">
                        Waiting for Room to Start
                    </div>
                </div>

                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary" type="button">Re-Konn3ct</button>
                </div>

            </form>

        </div>

    </div>
@endsection

