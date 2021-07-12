<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use http\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function join_lesson($uuid, Request $request)
    {
        $myId = Auth::user()->id;
        $classId = $uuid;
        $lesson = Lesson::where('uuid',$classId)->where('in_progress',true)->first();

        /** If the lesson has not been created or is no longer in_progress, redirect to 404. */
        if(!$lesson){
            return view('class-not-found');
        }

        $slide = $lesson->slide()->first();

        $slideID = $lesson->slide_id;

        /** Checking if the teacher who started the lesson is
         * trying to access it, or if the student is trying to. */
        if($lesson->teacher_id === Auth::id()){
            $isTeacher = true;
        } else {
            $isTeacher = false;
        }

        return view('class',compact('myId','classId','slideID','isTeacher','slide'));



        /**
         * Some type of check to determine who can and can't
         * change the slides would be good right about here
         * (if the user is the teacher_id of this class or not).
         */
    }


    public function end_lesson(Request $request)
    {
        $request->validate([
            'uuid'=>'required|uuid|exists:lessons,uuid'
        ]);

        $uuid = $request->all()['uuid'];

        $lesson = Lesson::where('teacher_id',Auth::id())
            ->where('in_progress',true)
            ->where('uuid',$uuid)->first();

        if(!$lesson){
            return abort(403);
        }

        $lesson->in_progress = false;
        $lesson->save();
        return response('Success', 200)
            ->header('Content-Type', 'text/plain');
    }


    public function clear_finished_lessons(Request $request)
    {

        $browserUUIDs = $request->validate([
            '*'=>'string'
        ]);
        $finished_lessons = [];


        foreach ($browserUUIDs as $key=> $item){
            if(Str::isUuid($item) === true){
                $lesson = Lesson::where('uuid',$item)->first();

                if($lesson != null && $lesson['in_progress'] === 0){
                    array_push($finished_lessons,$item);
                }
            }
        }
        return $finished_lessons;

    }
}
