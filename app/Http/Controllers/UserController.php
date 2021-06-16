<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUsers()
    {
        return User::all();
    }


    public function sendMsgToAll(Request $request)
    {
//        \App\Events\NewMessage::dispatch($request->all()['msg']); //broadcast to absolutely everytone
        broadcast(new \App\Events\NewMessage($request->all()['msg']))->toOthers(); //If I am sending to everyonce except me
    }
}
