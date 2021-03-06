@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <h2 class="text-light">Hello {{Auth::user()->name}}.</h2>
            <div class="card">

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in.') }}
                </div>

{{--                <a class='btn btn-primary mt-1' href="/new-lesson">Start new class</a>--}}
{{--                <a class='btn btn-primary mt-1' href="/all-slides">Slides</a>--}}
            </div>
        </div>
    </div>
</div>
@endsection
