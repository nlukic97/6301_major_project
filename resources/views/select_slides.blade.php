@extends('layouts.app')

@section('content')
    <div id="app" class="container-lg">
        <h3 class="text-center">New lesson</h3>

        @if(!$errors->any())
        <div class="text-center alert alert-info">
            Please select the slides you would like to use.
        </div>
        @endif

        <select-lesson all_slides="{{$slides}}" csrf="{{csrf_token()}}">

            @error('id')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror

        </select-lesson>

    </div>
@endsection
