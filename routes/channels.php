<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('home',function($user){
    return true; /* @ All *authenticated* users will have access to this channel.*/

//    return Auth::user() === $user;
/* @@@  line above is unnecessary, because unauthenticated users
        cannot make a broadcast/auth api post request -> 403 error */

});
