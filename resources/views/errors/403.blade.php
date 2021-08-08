@extends('layouts.app')

@section('content') {{--This is not within the Vue, because it is not in a div with id: #app --}}

<div id='app'>

    <div class="container text-center text-light">
        <div class="bg-danger d-inline-block p-4 rounded">
            <h2>404 - Permission denied</h2>
            <h5>You do not have permission to view this page.</h5>
            <h5>Please return to the <a href="/"> homepage</a>.</h5>
        </div>
    </div>
</div>
@endsection
