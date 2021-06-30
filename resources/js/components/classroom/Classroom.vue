<template>
    <div>
<!--        <text-editor-component v-if="this.displayTextEditor" state="shown"></text-editor-component>-->
        <video-component :other_peer_video="otherPeerVideo"></video-component>

        <input type="number" v-model="receiver">
        <button @click="sendMessageToAll">Broadcast Message</button>
        <button @click="sendMessageToOne">Send to one user</button>
        <button @click="whisper">Whisper</button>

        <input type="text" v-model="otherPeer">
        <button @click="connectToPeer">Connect to peer</button>
        <button @click="sendPeerMsg">Send msg</button>
        <button @click="whisperMyPeerId">Send other peer my peerId</button>
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
                myPeerId:null,
                otherPeerId:null,
                roomId:'',
                receiver:null,
                users:[],
                channel:null,
                otherPeer:null,
                otherPeerVideo:null

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

                    })

                    .listen('NewMessage', (e) => {
                        console.log('NewMessage:', e)
                    })

                    .listenForWhisper('click',e=>{
                        console.log(e.id + ' is typing.')
                    })

                    .listenForWhisper('peer-to-connect-with',e=>{
                        console.log('User 1 is whispering to you...')
                        this.otherPeerId = e.otherPeerId
                        console.log('Other peer id',this.otherPeerId)

                        console.log('Whispering back to user 1...')
                        this.whisperMyPeerId('peer-to-connect-back')
                        this.connectToPeer()
                    })

                    .listenForWhisper('peer-to-connect-back',e=>{
                        console.log('User 2 is whispering back to you...')

                            this.otherPeerId = e.otherPeerId
                            console.log('Other peer id',this.otherPeerId)
                            this.connectToPeer()
                        });

                /** @@@
                 * Personal channel for receiving private messages.
                 * */
                await Echo.private(`user.${roomId}.${userId}`) //so each user should be subscribed to their own channel (maybe a hash from the db?)
                    .listen('NewPrivateMessage', e => {
                        console.log('New Private message:', e)
                    })
            },


            /** Peer functions */
            async peerInit(){
                this.peer = await new Peer();

                this.peer.on('open',(id)=>{
                    this.myPeerId = id
                    // console.log('added my peer id', this.myPeerId)
                })

                this.peer.on('connection',function(conn){
                    this.conn = conn
                    console.log('Connected to this guy:')
                    console.log(this.conn)
                    this.conn.on('open',()=>{
                        this.conn.on('data',function(data){
                            console.log('Received data:', data)
                        })
                    })
                })
            },

            /** This will happen when the 2nd user joins, the 1st
             * will commence the connection process
             * (laravel echo '.joining' presence channel listener)*/
            whisperMyPeerId(whisperName){
                  if(this.users.length === 2){
                      this.channel.whisper(whisperName,{
                          otherPeerId: this.myPeerId
                      })
                  }
            },

            connectToPeer(){
                this.conn = this.peer.connect(this.otherPeerId)
            },
            sendPeerMsg(){
                this.conn.send('Hi !')
            }
        },
        beforeMount(){
            this.userId = parseInt(this.user_id)
            this.roomId = this.class_id
        },
        mounted() {
            this.EchoInit(this.roomId, this.userId)
            this.peerInit()
        }
    }
</script>

<style scoped>

</style>
