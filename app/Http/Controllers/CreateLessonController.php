<?php

namespace App\Http\Controllers;
use App\Models\Lesson;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CreateLessonController extends Controller
{
    public function select_slides()
    {
        $slides = Auth::user()->slides()->get();
        if($slides->isEmpty()){
            dd('sorry, you have no slides. Please create some and then start your lesson');
            return redirect('404');
        }

        return view('select_slides',compact('slides'));
    }


    public function create_new_lesson(Request $request){

        dd($request);
        $uuid = Str::uuid();
        //add this lesson with the selected slides to the database, and then open the class id
        Lesson::create([
            'uuid'=>$uuid,
            'teacher_id'=>Auth::id(),
            'slide_id'=>1,
            'in_progress'=>true
        ]);

        return redirect("/class/{$uuid}");
    }
}
