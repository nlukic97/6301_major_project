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

            <button @click="addNewSlide('slide')">New Slide</button>
            <button @click="addNewSlide('exercise')">Exercise Slide</button>
            <button @click="presentPrevSlide(currentSlideIndex)">Prev slide</button>
            <button @click="presentNextSlide(currentSlideIndex)">Next slide</button>

            <ul v-for="(slide,index) in slides" :key="index">
                <li v-if="index === currentSlideIndex">
                    <strong>
                        <span @click="jumpToSlide(index)">{{index + 1}} {{shortenText(index)}}</span>
                    </strong>#

                    <span class="x-btn" @click="removeSlide(index)">x</span>
                </li>

                <li v-else>
                    <span @click="jumpToSlide(index)">{{index + 1}} {{shortenText(index)}}</span>
                    <span class="x-btn" @click="removeSlide(index)">x</span>
                </li>
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
                this.currentSlideIndex = this.slides.length - 1
                this.displaySlide(this.slides[this.currentSlideIndex].content)
                console.log(JSON.stringify(this.slides))
            },
            displaySlide(text){
                this.activeSlideText = marked(text) //converts the entered xml into html
            },
            clearSlide(){
                this.activeSlideText = ''
            },
            presentNextSlide(index){
                if(this.slides.length <= 0){
                    return null;
                }
                index++;
                if(index > this.slides.length - 1){
                    index = 0; //to switch from the last slide back to the 1st slide
                }
                this.slideTypeHandler(index)
            },
            presentPrevSlide(index){
                if(this.slides.length <= 0){
                    return null;
                }
                index--
                if(index < 0){
                    index = this.slides.length - 1 //to go from the 1st slide all the way to the last one
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
                let content = this.slides[index].content
                if(content.length > 23){
                    return (this.slides[index].content).substring(0,20).trim() + '...'
                } else {
                    return content
                }
            },
            removeSlide(index){ //klikni zadnju, i onda briis od prve. Nastace greska
                if(index === this.slides.length - 1 && this.currentSlideIndex === index){
                    this.currentSlideIndex--;
                }
                console.log(`Remove slide of index ${index}`)
                this.slides.splice(index,1);
                if(this.slides.length > 0){
                    this.displaySlide(this.slides[this.currentSlideIndex].content)
                } else {
                    this.displaySlide('')
                }
            }
        },
        mounted(){
            //testing purposes - this is where we will make a get request to my slides. If we are making a new one, it will make a new record
            this.slides = JSON.parse("[{\"type\":\"slide\",\"content\":\"# slide 1\"},{\"type\":\"slide\",\"content\":\"$lide 2\"},{\"type\":\"exercise\",\"content\":\"# This is an exercise \\n\\n ## Buckle up \"},{\"type\":\"slide\",\"content\":\"# e \\n- a \\n- e\\n- a\\n\\n1. 2 \\n2. 3\\n3. a\"}]")
            this.displaySlide(this.slides[this.currentSlideIndex].content)
        }
    }
</script>

<style>
    #content {
        width: 100%;
    }

    #content .slide {
        background-color: #002b36;
        color:#fff;
        font-family: 'Arial',sans-serif;
        display: flex;
        flex-direction: column;
        align-items: flex-start; /*default to having it on the left*/
        padding:2.22vw 2.22vw;
    }

    #content .slide *
    {
        width:100%;
        word-break: break-word;
        text-align: left;
        margin:0;
    }

    #content .slide ul {
        display: inline-block;
        width:100%;
        padding:1.66vh 2.22vw;
    }

    #content .slide ul * {
        font-size:1.35vw;
    }

    #content .slide ol {
        display: inline-block;
        width:100%;
        padding:0 2.2vw;
    }

    #content .slide ol * {
        font-size:1.1vw;
    }

    #content .slide h1  {
        font-size: 2.50vw;
    }

    #content .slide h2  {
        font-size: 2vw;
    }
    #content .slide h3  {
        font-size: 1.75vw;
    }
    #content .slide h4  {
        font-size: 1.5vw;
    }
    #content .slide h5  {
        font-size: 1.35vw;
    }
    #content .slide h6  {
        font-size: 1vw;
    }

    #content .slide p  {
        font-size: 1.11vw;
    }

    .hidden {
        display: none;
    }

    .x-btn {
        background-color: red;
        color:#fff;
        border-radius: 50%;
        border:1px solid #000;
        padding:0 8px;
        display: inline-block;
    }
</style>
