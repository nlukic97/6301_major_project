<template>
    <div>
        <h1>This is the video component</h1>
        <button @click="sendMessage">Broadcast Message</button>
    </div>
</template>

<script>
    export default {
        name: "VideoComponent.vue",
        data:function(){
            return{

            }
        },
        methods:{
            async sendMessage(){
                try {
                    let data = await axios.post('/api/send-msg',{msg:'Gas is going'})
                    console.log('Msg sent',data)
                } catch {
                    console.log('Error',e)
                }
            }
        }
    }
</script>

<style scoped>

</style>
