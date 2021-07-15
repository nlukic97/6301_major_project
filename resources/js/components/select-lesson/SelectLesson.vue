<template>
    <div>
        <form action="/new-lesson" method="POST" id="start-lesson-form">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-group">

                <select name="id" id="id" class="custom-select">
                    <option value=""></option>
                    <option v-for="(slide,index) in slides" v-bind:value="slide.id" >{{filterSlideName(slide.title)}}</option>
                </select>

                <div class="text-center">
                    <a class="btn btn-primary btn-block mt-1"
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
        methods:{
          filterSlideName(slidename){
              if(slidename === null){
                  return 'untitled'
              }
              return slidename
          }
        },
        mounted() {
            this.slides = JSON.parse(this.all_slides)
        }
    }
</script>

<style scoped>

</style>
