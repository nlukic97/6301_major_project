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

           @foreach($allSlides as $key=>$slide)
               <div class="row d-flex justify-content-center" id="slides-list">
                   <div class="col-lg-8 col-md-9 col-11 d-flex justify-content-between align-items-center pt-2 pb-2 one-slide">

                       <div class="text-light">
                           @if($slide->title)
                               {{$key+1}} - {{$slide->title}}
                           @else
                               {{$key+1}} - untitled
                           @endif
                       </div>

                       <div class="text-right slides-section-btns">
                           <a class='btn btn-primary' href="/edit-slides/{{$slide->id}}"><i class="fas fa-pen"></i></a>
                           <a class='btn btn-danger' href="/delete-slides/{{$slide->id}}"><i class="fas fa-times"></i></a>
                       </div>

                   </div>
               </div>
           @endforeach

       </div>
    </div>
@endsection
