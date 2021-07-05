<template>
    <div>
        <form action="/class" method="POST">
            {{ }}
            <div>
                <label for="slide_id">Select slides</label>
                <select name="slide_id" id="slide_id">
                    <option value=""></option>
                    <option v-for="(slide,index) in slides" :value="slide.id" >{{slide.title}}</option>
                </select>

                <button type="submit">Start lesson</button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "SelectLesson",
        props:['all_slides'],
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
