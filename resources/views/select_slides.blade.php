@extends('layouts.class') {{--Changed this to 'class' instead of 'app'. Check if I even need the class thing--}}

@section('content')
    <div id="app">
        <h1>Biraj bato</h1>

        <select-lesson all_slides="{{$slides}}">
            {{!! csrf_field() !!}}
        </select-lesson>
    </div>
@endsection
