@extends('layouts.class')

@section('content') {{--This is not within the Vue, because it is not in a div with id: #app --}}

<div id='app'>
    <video-component></video-component>
{{--    <text-editor-component></text-editor-component>--}}
</div>
@endsection
