<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/all-users',[App\Http\Controllers\UserController::class,'getAllUsers']);

/** Send messages in Laravel Echo */
Route::middleware('auth:api')->post('/send-msg-all',[App\Http\Controllers\UserController::class,'sendMsgToAll']);
Route::middleware('auth:api')->post('/send-msg-one',[App\Http\Controllers\UserController::class,'sendMsgToOne']);

/** Slides */
Route::middleware(['auth:api','teacher'])->post('/update-slides',[App\Http\Controllers\SlideController::class,'update_slides']);
Route::middleware(['auth:api','teacher'])->post('/end-lesson',[App\Http\Controllers\LessonController::class,'end_lesson']);

/** For clearing out the local storage if a lesson has been completed. */
Route::middleware(['auth:api'])->post('/clear-finished-lessons',[App\Http\Controllers\LessonController::class,'clear_finished_lessons']);
