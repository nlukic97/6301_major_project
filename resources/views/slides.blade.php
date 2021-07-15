@extends('layouts.app') {{--Changed this to 'class' instead of 'app'. Check if I even need the class thing--}}

@section('content')
    <div id="app">
       <div class="container">
           <div class="row">
               <div class="col-12">
                   <h2 class="text-light text-center">Slides</h2>
                   <div class="text-center">
                       <a class='btn btn-primary' href="/new-slides"><i class="fas fa-plus"></i> New slides</a>
                   </div>
               </div>
           </div>

           @foreach($allSlides as $slide)
               <div class="row border border-secondary mb-2 mt-4 pt-2 pb-2">
                   <div class="col-10">
                       <a class='btn btn-block text-info text-left' href="/edit-slides/{{$slide->id}}">
                           @if($slide->title)
                               {{$slide->title}}
                           @else
                               untitled
                           @endif
                       </a>
                   </div>

                   <div class="col-2 text-center">
                       <span class='btn btn-danger float-right' href="/delete-slides/{{$slide->id}}"><i class="fas fa-times"></i></span>
                   </div>
               </div>
           @endforeach

       </div>
    </div>
@endsection
