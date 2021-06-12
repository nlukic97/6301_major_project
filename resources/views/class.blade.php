@extends('layouts.class')

@section('content')
    <div id="my-div"></div>
    <div id="my-div-2"></div>
    <!-- So iframe is where the DOM output of the js code will be displayed-->
    <iframe frameborder="1" id="i-frame" style="width: 100%;height:40vh;"></iframe>

    <button id="exe-btn">Execute</button>
@endsection
