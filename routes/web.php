<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


/** Routes available both to teachers and students*/
Route::middleware('auth')->get('/class/{id}', function($id){
    $myId = Auth::user()->id;
    $classId = $id;
    return view('class',compact('myId','classId'))    ; //the class view will open the text editor for now
});


/** Routes only available to teachers, otherwise 403 error. */
Route::middleware(['auth','teacher'])->get('/class', function(){
    return redirect('/start-lesson');
});

Route::middleware(['auth','teacher'])->get('/all-slides',[\App\Http\Controllers\SlideController::class,'index']);
Route::middleware(['auth','teacher'])->get('/new-slides',[\App\Http\Controllers\SlideController::class,'new_slides']);
Route::middleware(['auth','teacher'])->get('/edit-slides/{id}',[\App\Http\Controllers\SlideController::class,'edit_lesson']);


Route::middleware(['auth','teacher'])->get('/start-lesson',[\App\Http\Controllers\CreateLessonController::class,'create_new_lesson']);
