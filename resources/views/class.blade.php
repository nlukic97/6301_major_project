@extends('layouts.class')

@section('content') {{--This is not within the Vue, because it is not in a div with id: #app --}}
{{--    <div id="app">
        <div id="my-div"></div>
        <div id="my-div-2"></div>
        <!-- So iframe is where the DOM output of the js code will be displayed-->
        <iframe frameborder="1" id="i-frame" style="width: 100%;height:40vh;"></iframe>

        <button id="exe-btn">Execute</button>
    </div>--}}


{{-- Another option, looks better --}}
<div id='app'>
    <button id="msg-btn">sendMsg</button>
    <video-component></video-component>
    <text-editor-component></text-editor-component>
</div>
@endsection
