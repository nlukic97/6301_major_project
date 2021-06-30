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
        data:function(){
            return{
                myVideoStream:null,
            }
        },
        methods:{
            getMediaStream(){
                /** 'navigator' only works with https / secure connections.
                 * For development, also works if you serve this application
                 * with the following terminal command:
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
            addVideoStream(stream){
                this.$refs.myvideo.srcObject = stream
                this.$refs.myvideo.play()
            }
        },
        mounted(){
            this.getMediaStream()
        }
    }
</script>

<style scoped>
</style>
