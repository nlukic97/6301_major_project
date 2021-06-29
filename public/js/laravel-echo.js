/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************!*\
  !*** ./resources/js/laravel-echo.js ***!
  \**************************************/
/** @@@ Laravel Echo
    imported into Classroom.vue. But not in use anymore.
 */

/*
function init(roomId,userId){
    Echo.join(`home.${roomId}`)
        .here(e=>{  //who is here when I join
            console.log(e, 'You are here')
        })
        .joining(e=>{ //who is joining
            console.log(e,Echo.socketId() + ' has joined')
        })
        .leaving(e=>{
            console.log(e, Echo.socketId() + ' has left')

        })
        .listen('NewMessage', (e) => {
            console.log('NewMessage:',e)
        });


    Echo.private(`user.${roomId}.${userId}`) //so each user should be subscribed to their own channel (maybe a hash from the db?)
        .listen('NewPrivateMessage',e=>{
            console.log('New Private message:',e)
        })
}

module.exports = {
    init
}
*/
/******/ })()
;