<template>
    <div>
        <h1>This is the video component</h1>
        <video ref="myvideo" muted></video>
    </div>
</template>

<script>
    export default {
        name: "VideoComponent.vue",
        props:['my_video_stream','other_peer_video'],
        watch:{
           my_video_stream:function(){
              this.myVideoStream = this.my_video_stream
              this.addVideoStream(this.myVideoStream)
          }
        },
        data:function(){
            return{
                myVideoStream:null,
            }
        },
        methods:{
            addVideoStream(stream){
                this.$refs.myvideo.srcObject = stream
                this.$refs.myvideo.play()
            }
        },
        mounted(){
            // this.getMediaStream()
        }
    }
</script>

<style scoped>
</style>
