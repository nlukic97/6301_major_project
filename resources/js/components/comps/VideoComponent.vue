<template>
        <div class="d-flex align-items-center justify-content-center" style="border:1px solid red; width:250px;">
            <video id="myvideo" ref="myvideo" muted></video>
            <video id="myvideo2" ref="myvideo2"></video>
        </div>
</template>

<script>
    export default {
        name: "VideoComponent.vue",
        props:['other_peer_video'],
        watch:{
            other_peer_video:function(){
              this.otherVideoStream = this.other_peer_video
              this.addOtherVideoStream(this.otherVideoStream)
          }
        },
        data:function(){
            return{
                myVideoStream:null,
                otherVideoStream:null
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
            },
            async addOtherVideoStream(stream){
                // console.log('stream passed')
                this.$refs.myvideo2.srcObject = stream
                this.$refs.myvideo2.play()
            }
        },
        mounted(){
            this.getMediaStream()
        }
    }
</script>

<style scoped>

    #myvideo {
        width:100px;
        position: absolute;
        bottom:0;
        left:0;
    }

    #myvideo2 {
        width:250px;
    }
</style>
