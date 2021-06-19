<template>
    <div>
        <text-editor-component v-if="this.displayTextEditor"></text-editor-component>
        <video-component></video-component>
    </div>
</template>

<script>

    import echoInit from '../../laravel-echo.js' //Module for Echo listeners

    export default {
        name: "Classroom",
        props: ['user_id'],
        data:function () {
            return{
                displayTextEditor:false,
                id: null
            }
        },
        methods:{

        },
        beforeMount(){
            this.id = parseInt(this.user_id)
            console.log('Your id and channel id: ' + this.id)

            echoInit.init(this.id)
        }
    }
</script>

<style scoped>

</style>
