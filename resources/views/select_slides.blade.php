@extends('layouts.app')

@section('content')
    <div id="app" class="container-lg">
        <h3 class="text-center text-light">New lesson</h3>

        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-10">
                @if(!$errors->any())
                    <div class="text-center alert alert-info">
                        Please select the slides you would like to use.
                    </div>
                @endif

                @error('id')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-10">

                <select-lesson all_slides="{{$slides}}" csrf="{{csrf_token()}}"></select-lesson>

            </div>
        </div>

    </div>
@endsection
