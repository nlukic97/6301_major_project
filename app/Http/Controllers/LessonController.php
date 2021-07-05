<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function join_lesson($uuid)
    {
        $myId = Auth::user()->id;
        $classId = $uuid;
        $lesson = Lesson::where('uuid',$classId)->where('in_progress',true)->first();

        /** If the lesson has not been created or is no longer in_progress, redirect to 404. */
        if(!$lesson){
            return redirect('404');
        }

        $slideID = $lesson->slide_id;

        /** Checking if the teacher who started the lesson is
         * trying to access it, or if the student is trying to. */
        if($lesson->teacher_id === Auth::id()){
            $isTeacher = true;
        } else {
            $isTeacher = false;
        }

        return view('class',compact('myId','classId','slideID','isTeacher'));



        /**
         * Some type of check to determine who can and can't
         * change the slides would be good right about here
         * (if the user is the teacher_id of this class or not).
         */



    }
}
