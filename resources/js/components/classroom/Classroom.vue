<template>
    <div>
<!--        <text-editor-component v-if="this.displayTextEditor" state="shown"></text-editor-component>-->
<!--        <video-component></video-component>-->

        <input type="number" v-model="receiver">
        <button @click="sendMessageToAll">Broadcast Message</button>
        <button @click="sendMessageToOne">Send to one user</button>
    </div>
</template>

<script>

    //import echoInit from '../../laravel-echo.js' //Module for Echo listeners

    export default {
        name: "Classroom",
        props: ['user_id','class_id','room_id'], /** Passed down from the database*/
        data:function () {
            return{
                displayTextEditor:true,
                userId: null,
                roomId:null,
                receiver:null,
                users:[]

            }
        },
        methods:{
            async sendMessageToAll(){
                try {
                    let data = await axios.post('/api/send-msg-all',{
                        msg:'Msg to all users except me',
                        roomId: this.roomId
                    })
                    console.log('Msg sent',data)
                } catch(error) {
                    console.log(error)
                }
            },
            async sendMessageToOne(){
                try {
                    let data = await axios.post('/api/send-msg-one',{
                        msg:'Message to user 2',
                        roomId: this.roomId,
                        receiverId:this.receiver
                    })
                    console.log('Msg to one user sent',data)
                } catch(error) {
                    console.log(error)
                }
            },


            /** @@@
             * Laravel Echo Init - based on passed props from views\class.blade.php, this will:
             * - join a presence channel for roomId
             * - join a private channel for roomId and userId*/
            async EchoInit(roomId,userId) {
                await Echo.join(`home.${roomId}`)
                    .here(e => {  //who is here when I join
                        console.log(e, ' is/are the users here, including you.')
                        this.users = e;
                    })
                    .joining(e => { //who is joining
                        console.log(e, ' has joined')
                        this.users.push(e)
                        console.log(this.users,' are the users who are here')
                        console.log(this.users.indexOf(e))
                    })
                    .leaving(e => {
                        console.log(e, ' has left')
                        let index = this.users.indexOf(e)
                        this.users.splice(index,1)
                        console.log(this.users,' are the users left')

                    })
                    .listen('NewMessage', (e) => {
                        console.log('NewMessage:', e)
                    });

                /** @@@
                 * Personal channel for receiving private messages.
                 * */
                await Echo.private(`user.${roomId}.${userId}`) //so each user should be subscribed to their own channel (maybe a hash from the db?)
                    .listen('NewPrivateMessage', e => {
                        console.log('New Private message:', e)
                    })
            }
        },
        beforeMount(){
            this.userId = parseInt(this.user_id)
            this.roomId = parseInt(this.class_id)
        },
        mounted() {
            this.EchoInit(this.roomId, this.userId)
            console.log(`You are user ${this.userId} in room ${this.roomId}`)
        }
    }
</script>

<style scoped>

</style>
