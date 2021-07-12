@extends('layouts.app') {{--Changed this to 'class' instead of 'app'. Check if I even need the class thing--}}

@section('content')
    <div id="app">
        <h1>My slides</h1>


        <a class='btn btn-primary' href="/new-slides">Create new presentation</a>

        @foreach($allSlides as $slide)
            <div class="d-flex justify-content-between">
                @if($slide->title)
                    <a class='d-inline-block' href="/edit-slides/{{$slide->id}}"><h2>{{$slide->title}}</h2></a>
                @else
                    <a class='d-inline-block' href="/edit-slides/{{$slide->id}}"><h2>untitled</h2></a>
                    @endif
                <a class='btn btn-danger' href="/delete-slides/{{$slide->id}}">Delete</a>
            </div>
        @endforeach
    </div>
@endsection
