/** @@@ Laravel Echo
    imported into Classroom.vue
 */

function init(id){
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
            console.log('NewMessage:',e)
        });

    /** @@@
     * Each user will have this be their personal channel for receiving messages.
     * id is passed as a prop in the views/class.blade.php view in the classroom-component
     * */
    Echo.private(`user.${id}`) //so each user should be subscribed to their own channel (maybe a hash from the db?)
        .listen('NewPrivateMessage',e=>{
            console.log('New Private message:',e)
        })
}

module.exports = {
    init
}
