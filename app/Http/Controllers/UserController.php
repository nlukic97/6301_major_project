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
        broadcast(new \App\Events\NewMessage(
            \Illuminate\Support\Facades\Auth::user()->id,
            $request->all()['msg'])
        )->toOthers(); //If I am sending to everyone except me
    }



    public function sendMsgToOne(Request $request)
    {
        broadcast(
            new \App\Events\NewPrivateMessage(
                \Illuminate\Support\Facades\Auth::user()->id,
                $request->all()['receiverId'],
                $request->all()['msg']
            )
        )->toOthers();//Obviously it will only be sent to ['receiverId'], but we have this just in case.
    }
}
