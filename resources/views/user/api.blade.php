@extends('layouts.user-layout')

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="col-12">
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title align-items-start flex-column">
                        API's
                        <small class="subtitle">Create or Manage your API Token(s) below</small>
                    </h4>
                </div>
                <div class="box-body">
                    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                        @livewire('api.api-token-manager')
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

    <script type="application/javascript">
        function myFunction(id) {
            /* Get the text field */
            var copyText = document.getElementById(id);

            copyText.type = 'text';

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /*For mobile devices*/

            /* Copy the text inside the text field */
            document.execCommand("copy");

            copyText.type = 'hidden';

            /* Alert the copied text */
            alert("Copied the text: " + copyText.value);
        }
    </script>
@endsection
