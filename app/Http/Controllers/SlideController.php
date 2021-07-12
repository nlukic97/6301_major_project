<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SlideController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $allSlides = Slide::where('owner_id',Auth::id())->get();
        return view('slides',compact('allSlides'));
    }

    public function edit_lesson($id)
    {
        $slides = Slide::find($id);
        if($slides === null){
            return abort(404);
        }

        if($slides->owner_id !== Auth::user()->id) {
            return abort(404);
        }
        return view('edit-slides',compact('slides'));
    }


    public function new_slides()
    {
        $newSlides = Slide::create([
            'owner_id'=>Auth::id(),
            'title'=>null,
            'data'=>'[]'
        ]);

        return redirect('/edit-slides/'.$newSlides->id);
    }


    public function update_slides(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:slides',
            'data' => 'required|string',
            'title' => 'nullable|string'
        ]);

        $slide = Slide::findOrFail($request->all()['id']);
        if ($slide->owner_id !== Auth::id()) {
            return abort(403);
        }

        $data = $request->except('id');
        $data['owner_id'] = Auth::id();

        $slide->update($data);
        $slide->save();
    }


    public function delete($id){
        $slide = Slide::find($id);
        if($slide != null && $slide->owner_id === Auth::id()){
            $slide->delete();
        }
        return redirect('/all-slides');
    }
}
