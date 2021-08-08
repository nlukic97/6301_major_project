@extends('layouts.app')

@section('content') {{--This is not within the Vue, because it is not in a div with id: #app --}}

<div id='app'>
    <div class="container text-center text-light">
        <div class="bg-info d-inline-block p-4 rounded">
            <h2>Whoops! This page is unavailable.</h2>
            <h5>The link might not be valid, or it may have expired.</h5>
            <h5>Please check if you entered the correct link.</h5>
        </div>
    </div>
</div>
@endsection

