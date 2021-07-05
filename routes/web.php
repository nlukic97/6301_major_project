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



/** Routes available both to teachers and students for accessing the lesson with url */
Route::middleware('auth')->get('/class/{uuid}',[\App\Http\Controllers\LessonController::class,'open_lesson']);


/** Slide editing routes */
Route::middleware(['auth','teacher'])->get('/all-slides',[\App\Http\Controllers\SlideController::class,'index']);
Route::middleware(['auth','teacher'])->get('/new-slides',[\App\Http\Controllers\SlideController::class,'new_slides']);
Route::middleware(['auth','teacher'])->get('/edit-slides/{id}',[\App\Http\Controllers\SlideController::class,'edit_lesson']);
Route::middleware(['auth','teacher'])->get('/delete-slides/{id}',[\App\Http\Controllers\SlideController::class,'delete']);

/** Lesson starting route for teachers */
Route::middleware(['auth','teacher'])->get('/start-lesson',[\App\Http\Controllers\CreateLessonController::class,'select_slides']);
Route::middleware(['auth','teacher'])->post('/class',[\App\Http\Controllers\CreateLessonController::class,'create_new_lesson']);


