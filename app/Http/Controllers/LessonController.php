<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function open_lesson($uuid)
    {
        $myId = Auth::user()->id;
        $classId = $uuid;
        $lesson = Lesson::where('uuid',$classId)->where('in_progress',true)->get();

        /** If the lesson has not been created or is no longer in_progress, redirect to 404 */
        if($lesson->isEmpty()){
            return redirect('404');
        }

        return view('class',compact('myId','classId'))    ; //the class view will open the text editor for now
    }
}
