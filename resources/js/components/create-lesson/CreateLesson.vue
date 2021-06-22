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
            <button id="present-slide" @click="presentPrevSlide(currentSlideIndex)">Prev slide</button>
            <button id="present-slide" @click="presentNextSlide(currentSlideIndex)">Next slide</button>

            <ul v-for="(slide,index) in slides" :key="index">
                <li @click="jumpToSlide(index)">{{index}} {{shortenText(index)}}</li>
            </ul>

        </div>
        <div id="content">

            <div class="slide"
                 id="display-slide"
                 v-html="activeSlideText"
                 :class="{hidden: hideSlide}"
            ></div>

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
            },
            clearSlide(){
                this.activeSlideText = ''
            },
            presentNextSlide(index){
                index++;
                if(index > this.slides.length - 1){
                    index = 0; //to start the loop all over again
                }
                this.slideTypeHandler(index)
            },
            presentPrevSlide(index){
                index--
                if(index < 0){
                    index = this.slides.length - 1
                }

                this.slideTypeHandler(index)
            },
            slideTypeHandler(index){
                if(this.slides[index].type == 'slide'){
                    this.clearSlide()
                    this.hideSlide = false
                    this.displaySlide(this.slides[index].content)

                } else if(this.slides[index].type == 'exercise'){
                    this.hideSlide = true //this will hide the slide

                    //insert code to add the CodeMirror exercise here
                    console.log('Here is the exercise for slide of index ' + index +'. We will not display this slide but just show an empty canvas');
                }

                this.currentSlideIndex = index;
            },
            jumpToSlide(index){
                if(index != this.currentSlideIndex){
                    this.displaySlide(this.slides[index].content)
                    this.currentSlideIndex = index
                }
            },
            shortenText(index){
                let content = this.slides[index].content.substring(0,23).trim()
                if(content.length > 23){
                    return this.slides[index].content.trim()
                } else {
                    return content + '...'
                }
            }
        },
        mounted(){
            //testing purposes
            this.slides = JSON.parse("[{\"type\":\"slide\",\"content\":\"# slide 1\"},{\"type\":\"slide\",\"content\":\"$lide 2\"},{\"type\":\"exercise\",\"content\":\"# This is an exercise \\n\\n ## Buckle up \"}]")
            this.displaySlide(this.slides[this.currentSlideIndex].content)
        }
    }
</script>

<style scoped>
    #content {
        width: 100%;
        padding: 0 30px;
    }

    .slide {
        background-color: #002b36;
        color:#fff;
        font-family: 'Arial',sans-serif;
        width:575px;
        height:380px;
        display: flex;
        flex-direction: column;
        align-items: flex-start; /*default to having it on the left*/
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

    ul li {
        list-style-type: none;
    }

    #content #display-slide  { /** Unable to select the h1 inside this element*/
        color: orange;
    }

    .hidden {
        display: none;
    }
</style>
