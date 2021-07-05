@extends('layouts.app')

@section('content')
    <div id="app">
        <h1>Biraj bato</h1>

        <select-lesson all_slides="{{$slides}}" csrf="{{csrf_token()}}"></select-lesson>
        @error('id')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
@endsection
