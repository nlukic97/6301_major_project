<template>
    <div>
        <h1>This is the video component</h1>
        <video ref="myvideo" muted></video>
    </div>
</template>

<script>
    export default {
        name: "VideoComponent.vue",
        props:['other_peer_video'],
        watch:{
          //  my_video_stream:function(){
          //     this.myVideoStream = this.my_video_stream
          //     this.addVideoStream(this.myVideoStream)
          // }
        },
        data:function(){
            return{
                myVideoStream:null,
            }
        },
        methods:{
            /** Video stream functions */
            getMediaStream(){
                /** 'navigator' only works with https / secure connections. For development, also works if you
                 *   serve this application with the following terminal command:
                 *
                 php artisan serve --port=443
                 *
                 * */
                navigator.mediaDevices.getUserMedia({
                    video:true,
                    audio:true
                }).then(stream=>{
                    this.myVideoStream = stream
                    this.addVideoStream(this.myVideoStream)
                })
            },
            async addVideoStream(stream){
                this.$refs.myvideo.srcObject = stream
                this.$refs.myvideo.play()

                //sending the stream to the Classroom.vue component so that it could be available for making the call
                this.$emit('myOwnVideoStream',stream)
            }
        },
        mounted(){
            this.getMediaStream()
        }
    }
</script>

<style scoped>
</style>
