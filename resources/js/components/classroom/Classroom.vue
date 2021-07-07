<template>
    <div>
<!--        <text-editor-component v-if="this.displayTextEditor" state="shown"></text-editor-component>-->
        <slot></slot>
        <class-slides
            v-bind:load_slides="pass_down_slide"
            v-bind:class_uuid="class_id"
            v-bind:local_storage_from_peer="otherLocalFromPeer"

            v-bind:reset_slide_whisper="signalSlideReset"
            v-on:slides-reset="reset_signalSlideReset"

            v-bind:change_slide="changeSlide"
            v-on:slide-changed="whisperSlideChange"

            v-on:live-code-update="live_code_update"

            v-on:reset-slides="reset_slides"

            v-on:whisper-code-execute="whisperCodeExecute"
            v-bind:signal_code_execution="signalCodeExecution"
            v-on:code-executed="reset_signalCodeExecution"
        ></class-slides>

        <video-component
            :other_peer_video="otherPeerStream"
            v-on:myOwnVideoStream="saveMyVideoStream"
        ></video-component>



        <input type="number" v-model="receiver">
        <button @click="sendMessageToAll">Broadcast Message</button>
        <button @click="sendMessageToOne">Send to one user</button>
        <button @click="whisper">Whisper</button>
        <button @click="closeCall">close call</button>
        <button @click="endClass">End class</button>

        <button @click="whisperMyPeerId">Send other peer my peerId</button>
    </div>
</template>

<script>

    export default {
        name: "Classroom",
        props: [
            'user_id',
            'class_id',
            'is_teacher',
            'pass_down_slide'
        ],
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
                teacher:null,
                otherLocalFromPeer:null,
                changeSlide:null,
                signalSlideReset:false,
                signalCodeExecution:false

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
            closeCall(){
                this.call.close()
            },
            whisper(){
                this.channel.whisper('click',{
                    id: this.userId
                })
            },


            /** @@@
             * Laravel Echo Init - based on passed props from views\class.blade.php, this will:
             * - join a presence channel for roomId
             * - join a private channel for roomId and userId
             *
             * This is activated after the users video feed is established (see VideoComponent.vue)
             * */
            async EchoInit(roomId,userId) {
                // console.log(`starting function to join channel: home.${roomId}`)
                this.channel = await Echo.join(`home.${roomId}`)
                    .here(e => {  //who is here when I join
                        console.log(e, ' is/are the users here, including you.')
                        this.users = e;

                    })

                    .joining(e => { //who is joining
                        console.log(e, ' has joined')
                        this.users.push(e)
                        console.log(this.users,' are the users who are here')
                        console.log(this.users.indexOf(e))
                        if(this.users.length == 2){
                            console.log('you are the 1st user to join, give other guy your slide info')
                            this.whisperMyPeerId('peer-to-connect-with')
                            this.whisperSlideLocalStorage('local-storage-class-session')
                        }

                    })

                    .leaving(e => {
                        console.log(e, ' has left')
                        let index = this.users.indexOf(e)
                        this.users.splice(index,1)
                        console.log(this.users,' are the users left')
                        this.otherPeerStream = null

                    })

                    .listen('NewMessage', (e) => {
                        console.log('NewMessage:', e)
                    })

                    .listenForWhisper('click',e=>{
                        console.log(e.id + ' is typing.')
                    })

                    .listenForWhisper('peer-to-connect-with',e=>{
                        console.log('The user who joined first is whispering his ID to you, call them !')
                        this.otherPeerId = e.otherPeerId;
                        // console.log('calling this peer:' + this.otherPeerId)

                        // console.log('My video stream:' + this.myVideoStream)
                        this.call = this.peer.call(this.otherPeerId,this.myVideoStream)
                        this.call.on('stream',stream=>{
                            // console.log('call answered')
                            this.otherPeerStream = stream;
                        })
                    })

                    .listenForWhisper('peer-to-connect-back',e=>{
                        console.log('User 2 is whispering back to you...')

                            this.otherPeerId = e.otherPeerId
                            // console.log('Other peer id',this.otherPeerId)
                            // this.connectToPeer() //This could be good for sending messages using peer rather than laravel websockets, take some load off the server
                        })

                    .listenForWhisper('local-storage-class-session',e=>{
                        this.otherLocalFromPeer = e.data
                        // console.log(this.otherLocalFromPeer)
                    })

                    .listenForWhisper('slide-change',e=>{
                        let currIndex = JSON.parse(localStorage.getItem(this.class_id)).currIndex

                        /** If the user is already on the slide, we will not change the prop,
                         * because this would cause an endless loop of laravel echo whispers*/
                        if(e.index != currIndex){
                            this.changeSlide = e.index //this will update the prop in the ClassSlides, changing the slide
                        }
                    })

                    .listenForWhisper('reset-slides',e=>{
                        console.log('time to reset slides')
                        this.signalSlideReset = true
                    })


                    .listenForWhisper('execute-code',e=>{
                        this.signalCodeExecution = true
                    })


                    .listenForWhisper('end-class',e=>{
                        localStorage.removeItem(this.class_id);
                        location.href = 'https://hoopshooters.club'
                    });


                /** @@@
                 * Personal channel for receiving private messages.
                 * */
                // console.log(`starting function to join private channel: home.${roomId}.${userId}`)
                await Echo.private(`user.${roomId}.${userId}`) //so each user should be subscribed to their own channel (maybe a hash from the db?)
                    .listen('NewPrivateMessage', e => {
                        console.log('New Private message:', e)
                    })

                // console.log('Here is the public channel you have subscribed to',this.channel)
            },


            /** Peer functions */
            async peerInit(){
                this.peer = await new Peer();

                this.peer.on('open',(id)=>{
                    // console.log('here is your peer id:')
                    this.myPeerId = id
                    // console.log(this.myPeerId)
                })

                this.peer.on('call',call=>{
                    // console.log('Someone is calling: ',call)
                    call.answer(this.myVideoStream)
                    call.on('stream',stream=>{
                        this.otherPeerStream = stream
                    })
                })
            },

            /** This will happen when the 2nd user joins, which will tell the 1st user to place the video call
             * (laravel echo '.joining' presence channel listener)*/
            whisperMyPeerId(whisperName){
                console.log('telling the other person my peer id')
                this.channel.whisper(whisperName,{
                    otherPeerId: this.myPeerId
                })
            },

            whisperSlideLocalStorage(whisperName){
                let data = localStorage.getItem(this.class_id)
                if(data != null){
                    console.log('sending my local storage')
                    this.channel.whisper(whisperName,{
                        data: data
                    })
                }
            },

            /** Called when a slide is changed from the ClassSlides.vue */
            whisperSlideChange(data){
                if(this.users.length == 2){
                    console.log('I will tell the other guy to change his slide',data)
                    this.channel.whisper('slide-change',{index: data});
                }
            },

            live_code_update(data){
                if(this.users.length == 2){
                    console.log('JS of XML have been changed update here:', data)
                    this.whisperSlideLocalStorage('local-storage-class-session',data)
                }
            },

            reset_slides(){
                this.channel.whisper('reset-slides')
            },

            reset_signalSlideReset(){
                this.signalSlideReset = false
            },

            whisperCodeExecute(){
                this.channel.whisper('execute-code');
            },

            reset_signalCodeExecution(){
                this.signalCodeExecution = false
            },


            /** Called on emit from VideoComponent.vue*/
            saveMyVideoStream(stream){
                this.myVideoStream = stream

                /**After the user video is available for manipulation, then we initialize laravel echo and peer js. */
                this.peerInit()

                //timeout is necessary to avoid having the laravel echo instance called before the peer is established.
                // Maybe I should make peerInit into a promise, and after which I would call the EchoInit function ?
                setTimeout(()=>{
                    this.EchoInit(this.roomId, this.userId)
                },500)
            },



            /** testing out call functions*/
            callPeer(){
                this.call = this.peer.call(this.otherPeerId,this.myVideoStream)
            },

            endClass(){
                localStorage.removeItem(this.class_id)
                this.channel.whisper('end-class')
                location.href = 'https://hoopshooters.club'
            }
        },
        beforeMount(){
            this.slidesForProp = this.pass_down_slide
            this.userId = parseInt(this.user_id)
            this.roomId = this.class_id
            this.teacher = this.is_teacher
            //console.log(this.teacher)
        },
        mounted() {
        }
    }
</script>

<style scoped>

</style>
