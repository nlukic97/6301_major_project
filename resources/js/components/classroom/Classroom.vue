<template>
    <div>
<!--        <text-editor-component v-if="this.displayTextEditor" state="shown"></text-editor-component>-->
        <video-component
            :other_peer_video="otherPeerStream"
            v-on:myOwnVideoStream="saveMyVideoStream"
        ></video-component>

        <input type="number" v-model="receiver">
        <button @click="sendMessageToAll">Broadcast Message</button>
        <button @click="sendMessageToOne">Send to one user</button>
        <button @click="whisper">Whisper</button>

        <button @click="whisperMyPeerId">Send other peer my peerId</button>
    </div>
</template>

<script>

    export default {
        name: "Classroom",
        props: ['user_id','class_id','room_id'], /** Passed down from the database*/
        data:function () {
            return{
                displayTextEditor:true,
                userId: null,
                myPeerId:null,
                otherPeerId:null,
                roomId:'',
                receiver:null,
                users:[],
                channel:null,
                otherPeer:null,
                myVideoStream:null,
                otherPeerStream:null,
                call:null,

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

            whisper(){
                this.channel.whisper('click',{
                    id: this.userId
                })
            },


            /** @@@
             * Laravel Echo Init - based on passed props from views\class.blade.php, this will:
             * - join a presence channel for roomId
             * - join a private channel for roomId and userId*/
            async EchoInit(roomId,userId) {
                console.log('initiating echo')
                this.channel = await Echo.join(`home.${roomId}`)
                    .here(e => {  //who is here when I join
                        console.log(e, ' is/are the users here, including you.')
                        this.users = e;
                        console.log(`You are user ${this.userId} in room ${this.roomId}`);

                    })

                    .joining(e => { //who is joining
                        console.log(e, ' has joined')
                        this.users.push(e)
                        console.log(this.users,' are the users who are here')
                        console.log(this.users.indexOf(e))

                        this.whisperMyPeerId('peer-to-connect-with')
                    })

                    .leaving(e => {
                        console.log(e, ' has left')
                        let index = this.users.indexOf(e)
                        this.users.splice(index,1)
                        console.log(this.users,' are the users left')
                        this.otherPeerStream = null

                    })

                    .listen('.NewMessage', (e) => {
                        console.log('NewMessage:', e)
                    })

                    .listenForWhisper('click',e=>{
                        console.log(e.id + ' is typing.')
                    })

                    .listenForWhisper('peer-to-connect-with',e=>{
                        console.log('The user who joined first is whispering his ID to you, call them !')
                        this.otherPeerId = e.otherPeerId

                        this.call = this.peer.call(this.otherPeerId,this.myVideoStream)

                        this.call.on('stream',stream=>{
                            console.log('call answered')
                            this.otherPeerStream = stream;
                        })
                    })

                    // .listenForWhisper('peer-to-connect-back',e=>{
                    //     console.log('User 2 is whispering back to you...')
                    //
                    //         this.otherPeerId = e.otherPeerId
                    //         console.log('Other peer id',this.otherPeerId)
                    //         // this.connectToPeer()
                    //     });

                /** @@@
                 * Personal channel for receiving private messages.
                 * */
                await Echo.private(`user.${roomId}.${userId}`) //so each user should be subscribed to their own channel (maybe a hash from the db?)
                    .listen('.NewPrivateMessage', e => {
                        console.log('New Private message:', e)
                    })

                console.log('Here is the public channel you have subscribed to',this.channel)
            },


            /** Peer functions */
            async peerInit(){
                this.peer = await new Peer();

                this.peer.on('open',(id)=>{
                    this.myPeerId = id
                    console.log('my peer id: ' + id)
                })

                this.peer.on('call',call=>{
                    console.log('Someone is calling: ',call)
                    call.answer(this.myVideoStream)
                    call.on('stream',stream=>{
                        this.otherPeerStream = stream
                    })
                })
            },

            /** This will happen when the 2nd user joins, which will tell the 1st user to place the video call
             * (laravel echo '.joining' presence channel listener)*/
            whisperMyPeerId(whisperName){
                  if(this.users.length === 2){
                      this.channel.whisper(whisperName,{
                          otherPeerId: this.myPeerId
                      })
                  }
            },

            /** Called on emit from VideoComponent.vue*/
            saveMyVideoStream(stream){
                this.myVideoStream = stream
                console.log('stream added')

                /**After the user video is available for manipulation, then we initialize laravel echo and peer js. */
                this.EchoInit(this.roomId, this.userId)
                this.peerInit()
            },



            /** testing out call functions*/
            callPeer(){
                this.call = this.peer.call(this.otherPeerId,this.myVideoStream)
            }
        },
        beforeMount(){
            this.userId = parseInt(this.user_id)
            this.roomId = this.class_id
        },
        mounted() {
            /** Had this like this, but decided to change it just in case a user's video does not work - there is no point in connecting at all.*/
            /*
            this.EchoInit(this.roomId, this.userId)
            this.peerInit()
            */
        }
    }
</script>

<style scoped>

</style>
