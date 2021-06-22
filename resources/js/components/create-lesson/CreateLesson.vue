<template>
    <div style="display: flex;">
        <div>
            <textarea
                id="the-slide"
                cols="30"
                rows="10"
                v-model="markdownValue"
                @keyup="displaySlide(markdownValue)"
            ></textarea>

            <button id="add-slide" @click="addNewSlide('slide')">New Slide</button>
            <button id="exer-slide" @click="addNewSlide('exercise')">Exercise Slide</button>
            <button id="present-slide" @click="presentNextSlide()">Present</button>
        </div>
        <div id="content" style="width: 100%;padding:0 30px;">

            <div class="slide"
                 id="display-slide"
                 v-html="activeSlideText"
                 :class="{hidden: hideSlide}"
            ></div>

<!--            <iframe frameborder="0"-->
<!--                    class="slide"-->
<!--                    id="slide-iframe"-->
<!--                    v-html="activeSlideText"-->
<!--                    :class="{hidden: hideSlide}"-->
<!--            ></iframe>-->

        </div>
    </div>
</template>

<script>
    import marked from '../../marked.js';

    export default {
        name: "CreateLesson",
        data: function(){
            return {
                currentSlideIndex:0,
                hideSlide:false,
                slides:[],
                markdownValue:'',
                activeSlideText:``,
            }
        },
        methods:{
            addNewSlide(slideType){
                let newSlide = {type:slideType,content:this.markdownValue}
                this.slides.push(newSlide)
                console.log(this.slides)
                this.markdownValue = ''
            },
            displaySlide(text){
                this.activeSlideText = marked(text) //converts the entered xml into html
                // document.getElementById('slide-iframe').contentWindow.document.body.innerHTML = marked(text)
            },
            clearSlide(){
                this.activeSlideText = ''
                // document.getElementById('slide-iframe').contentWindow.document.body.innerHTML = ''
            },
            presentNextSlide(){
                if(this.currentSlideIndex > this.slides.length - 1){
                    this.currentSlideIndex = 0; //to start the loop all over again
                }

                if(this.slides[this.currentSlideIndex].type == 'slide'){
                    this.clearSlide()
                    this.hideSlide = false

                    this.displaySlide(this.slides[this.currentSlideIndex].content)

                } else if(this.slides[this.currentSlideIndex].type == 'exercise'){
                    this.hideSlide = true //this will hide the slide

                    //insert code to add the CodeMirror exercise here
                    console.log('Here is the exercise for slide of index ' + this.currentSlideIndex +'. We will not display this slide but just show an empty canvas');
                }
                this.currentSlideIndex++
            }
        },
        mounted(){
            //testing purposes
            this.slides = JSON.parse("[{\"type\":\"slide\",\"content\":\"# slide 1\"},{\"type\":\"slide\",\"content\":\"$lide 2\"},{\"type\":\"exercise\",\"content\":\"# This is an exercise \\n\\n ## Buckle up\"},{\"type\":\"slide\",\"content\":\"slide 4\"},{\"type\":\"slide\",\"content\":\"slide 5\"},{\"type\":\"exercise\",\"content\":\"# This is an exercise \\n\\n ## Buckle up\"},{\"type\":\"exercise\",\"content\":\"# This is an exercise \\n\\n ## Buckle up\"},{\"type\":\"exercise\",\"content\":\"# This is an exercise \\n\\n ## Buckle up\"},{\"type\":\"slide\",\"content\":\"slide 9\"}]")
        }
    }
</script>

<style scoped>
    .slide {
        background-color: #002b36;
        color:#fff;
        font-family: 'Arial',sans-serif;
        width:575px;
        height:380px;
        display: flex;
        flex-direction: column;
        align-items: left; /*default to having it on the left*/
        padding:10px 20px;
    }

    .slide *
    {
        display: block;
        width:100%;
        word-break: break-word;
        text-align: left;
    }

    .slide ul {
        display: block;
        width:100%;
    }

    .hidden {
        display: none;
    }
</style>
