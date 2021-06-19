<template>
    <div>
        <h1>This is the video component</h1>
        <input type="number" v-model="receiver">
        <button @click="sendMessageToAll">Broadcast Message</button>
        <button @click="sendMessageToOne">Send to one user</button>
    </div>
</template>

<script>
    export default {
        name: "VideoComponent.vue",
        data:function(){
            return{
                receiver:null
            }
        },
        methods:{
            async sendMessageToAll(){
                try {
                    let data = await axios.post('/api/send-msg-all',{msg:'Msg to all users except me'})
                    console.log('Msg sent',data)
                } catch(error) {
                    console.log(error)
                }
            },
            async sendMessageToOne(){
                try {
                    let data = await axios.post('/api/send-msg-one',{msg:'Message to user 2',receiverId:this.receiver})
                    console.log('Msg to one user sent',data)
                } catch(error) {
                    console.log(error)
                }
            },
        }
    }
</script>

<style scoped>

</style>
