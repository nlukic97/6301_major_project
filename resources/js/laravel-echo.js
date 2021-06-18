/** @@@ Laravel Echo
    imported directly into
    layouts\class.blade.php
 */

Echo.join(`home`)
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
        console.log(e.message)
    });
