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
        $lesson = Lesson::where('uuid',$classId)->where('in_progress',true)->first();

        /**
         * If the lesson has not been created or
         * is no longer in_progress, redirect to 404.
         */
        if(!$lesson){
            return redirect('404');
        }

        /**
         * Some type of check to determine who can and can't
         * change the slides would be good right about here
         * (if the user is the teacher_id of this class or not).
         */

        $slideID = $lesson->slide_id;

        return view('class',compact('myId','classId','slideID'))    ; //the class view will open the text editor for now
    }
}
