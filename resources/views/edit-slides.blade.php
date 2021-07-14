@extends('layouts.slides') {{--Changed this to 'class' instead of 'app'. Check if I even need the class thing--}}

@section('content')
    <div id="app">
        <create-lesson load_slides="{{$slides}}"></create-lesson>
    </div>
@endsection
