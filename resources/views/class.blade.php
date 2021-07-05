@extends('layouts.class')

@section('content') {{--This is not within the Vue, because it is not in a div with id: #app --}}

<div id='app'>

    @if($isTeacher == true)
        <classroom-component
            user_id="{{$myId}}"
            class_id="{{$classId}}"
            pass_down_slide="{{$slide}}"
            is_teacher="{{true}}"
        >
            <h1>This person is the teacher</h1>
        </classroom-component>
    @else
        <classroom-component
            user_id="{{$myId}}"
            class_id="{{$classId}}"
            pass_down_slide="{{$slide}}"
            is_teacher="{{false}}"
        >
            <h1>This person is NOT a teacher</h1>
        </classroom-component>
    @endif




</div>
@endsection
