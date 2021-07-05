@extends('layouts.class')

@section('content') {{--This is not within the Vue, because it is not in a div with id: #app --}}

<div id='app'>
    <classroom-component user_id="{{$myId}}" class_id="{{$classId}}" slides_id="{{$slideID}}"></classroom-component>
</div>
@endsection
