<?php

namespace App\Http\Controllers;
use App\Models\Lesson;
use App\Models\Slide;
use App\Models\User;
use App\Rules\OwnsSlide;
use Dotenv\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        $request->validate([
            'id'=>[
                'required',
                'integer',
                'exists:slides,id',
                new OwnsSlide()  /** Check if Auth::id() is the owner of the slide with this id */
            ]
        ],
        [
            'id.required'=>'The slide field is required.',
            'id.integer'=>'This slide does not exist.',
            'id.exists'=>'This slide is not available.',
        ]);

        $slideID = $request->only('id')['id'];
        $uuid = Str::uuid();

        //add this lesson with the selected slides to the database, and then open the class id
        Lesson::create([
            'uuid'=>$uuid,
            'teacher_id'=>Auth::id(),
            'slide_id'=>$slideID,
            'in_progress'=>true
        ]);

        return redirect("/class/{$uuid}");
    }
}
