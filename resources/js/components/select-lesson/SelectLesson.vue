<template>
    <div>
        <form action="/new-lesson" method="POST" id="start-lesson-form">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-group">
                <label for="id" class="form-check-label">Select slides</label>
                <select name="id" id="id" class="custom-select">
                    <option value=""></option>
                    <option v-for="(slide,index) in slides" v-bind:value="slide.id" >{{slide.title}}</option>
                </select>

                <slot></slot>

                <div class="text-center">
                    <a class="btn btn-primary"
                       onclick="document.getElementById('start-lesson-form').submit();">
                        Start lesson
                    </a>
                </div>
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
