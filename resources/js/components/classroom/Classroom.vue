<template>
    <div>
<!--        <text-editor-component v-if="this.displayTextEditor"></text-editor-component>-->
        <video-component></video-component>
    </div>
</template>

<script>

    import echoInit from '../../laravel-echo.js' //Module for Echo listeners

    export default {
        name: "Classroom",
        props: ['user_id','class_id'],
        data:function () {
            return{
                displayTextEditor:true,
                id: null,
                class:null

            }
        },
        methods:{

        },
        beforeMount(){
            this.id = parseInt(this.user_id)
            this.class = this.class_id
            echoInit.init(this.id)

        },
        mounted() {
        }
    }
</script>

<style scoped>

</style>
