<template>
        <div>
            <div id="video-container" class="d-flex align-items-center justify-content-center">
                <video id="myvideo" ref="myvideo" muted></video>
                <video id="myvideo2" ref="myvideo2" :style="otherVideoCss"></video>
                <span class="btn btn-light" @click="toggleOtherVideoView" id="toggle-vid-btn">
                    <i class="fas fa-photo-video"></i>
                </span>
            </div>
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
                otherVideoStream:null,
                otherVideoCss:'height:100%;'
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
            },
            toggleOtherVideoView(){
                if(this.otherVideoCss === 'height:100%;'){
                    this.otherVideoCss = 'width:100%;'
                } else {
                    this.otherVideoCss = 'height:100%;'
                }
            }
        },
        mounted(){
            this.getMediaStream()
        }
    }
</script>

<style scoped>
    #video-container {
        width: 250px;
        height:200px;
        overflow: hidden;
    }

    #video-container::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
    }

    #myvideo {
        width:100px;
        position: absolute;
        bottom:0;
        left:0;
    }

    #toggle-vid-btn {
        position: absolute;
        top: 5px;
        left: 5px;
    }
</style>
