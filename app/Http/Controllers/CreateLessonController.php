<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CreateLessonController extends Controller
{
    public function create_new_lesson(){
        $num = Str::random(30);
        return redirect('/class/'.$num);
    }
}
