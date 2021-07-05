<template>
    <div>
        <form action="/new-lesson" method="POST">
            <input type="hidden" name="_token" :value="csrf">
            <div>
                <label for="slide_id">Select slides</label>
                <select name="slide_id" id="slide_id">
                    <option value=""></option>
                    <option value="2">2</option>
                    <option value="22">22</option>
                    <option v-for="(slide,index) in slides" v-bind:value="slide.id" >{{slide.title}}</option>
                </select>

                <button type="submit">Start lesson</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "SelectLesson",
        props:['all_slides','csrf'],
        data:function(){
            return {
                slides:null
            }
        },
        mounted() {
            this.slides = JSON.parse(this.all_slides)
        }
    }
</script>

<style scoped>

</style>
