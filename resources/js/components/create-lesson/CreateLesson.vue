<template>
    <div>
        <div class="container-fluid position-sticky">
            <div class="row pt-2 pl-2 pr-2">
                <input class="form-control-plaintext font-weight-bold text-light pl-2 pr-2" type="text" id="slide_title" v-model="slideTitle" @keyup="updateTitle()">
            </div>
        </div>

        <div class="d-flex">
            <div class="position-fixed">
                <div class="mt-2">
                    <div>
                        <span class="btn btn-primary" @click="addNewSlide('slide')"><i class="fas fa-plus"></i> Text</span>
                        <span class="btn btn-primary" @click="addNewSlide('exercise')"><i class="fas fa-plus"></i> Exercise</span>
                        <span class="btn btn-danger" @click="removeSlide()"><i class="fas fa-times"></i></span>
                    </div>

                    <div class="mt-2">
                        <span class="btn btn-primary" @click="presentPrevSlide(currentSlideIndex)">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <span class="btn btn-primary" @click="presentNextSlide(currentSlideIndex)">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </div>

                </div>

                <ul id="slide-list">
                    <li v-for="(slide,index) in slides" :key="index">
                        <strong v-if="index === currentSlideIndex">
                            <span @click="jumpToSlide(index)">{{index + 1}} {{shortenText(index)}}</span>
                        </strong>
                        <span v-else @click="jumpToSlide(index)">{{index + 1}} {{shortenText(index)}}</span>
                    </li>

                </ul>

                <div class="d-flex justify-content-center">
                    <textarea v-if="this.slides.length > 0"
                              id="the-slide"
                              v-model="markdownValue"
                              @keyup="updateSlide(markdownValue)"
                              :class="{hidden: hideSlide}"
                    ></textarea>
                </div>
            </div>

            <!-- When it is a regular display slide-->
            <div v-if="this.slides.length > 0" id="content" :class="{hidden: hideSlide}" class="mt-2">
                <div
                    class="slide"
                     id="display-slide"
                     v-html="activeSlideText"
                ></div>
            </div>

            <!-- When it is an exercise-->
            <div v-if="this.slides.length > 0" :class="{hidden: !hideSlide}" id="text-editor" class="mt-2">
                <text-editor-component
                    :state="text_editor_state"
                    v-on:javaScriptChange="updateJavaScript"
                    v-on:xmlChange="updateXML"
                    v-bind:code_props="codeMirrorProps"
                ></text-editor-component> <!-- Adding a class binding directly to the component does not display the text of the component until I click on it -->

            </div>
        </div>
    </div>
</template>

<script>
    import marked from '../../marked.js';

    export default {
        name: "CreateLesson",
        props:[
            'load_slides'
        ],
        data: function(){
            return {
                currentSlideIndex:0,
                hideSlide:false,
                slideId:null,
                slideTitle:'',
                slides:[],
                markdownValue:'',
                activeSlideText:``,
                editing:false, /** For the interval in this.typing() */
                secondsEditing:0, /** For the interval in this.typing() */
                text_editor_state: null,
                codeMirrorProps:{
                    xml:null,
                    javaScript:null
                }
            }
        },
        methods:{
            addNewSlide(slideType){
                var newSlide;
                if(slideType === 'exercise'){
                    newSlide = {type:slideType,content:'Exercise',data:{
                        /** text must be unindented here like this so it can
                         *  be added with no indentation in the text editor*/
                        xml:`<!DOCTYPE html>
<html>
<head>
  <title>JS Bin</title>

  <style>
  </style>
</head>
<body>
    <!-- type some HTML... -->

</body>
</html>`,
                     javaScript:`//type in some javaScript [o_0] ...`
                    }}
                } else {
                    newSlide = {type:slideType,content:''}
                }
                this.slides.push(newSlide)

                this.markdownValue = ''
                this.currentSlideIndex = this.slides.length - 1

                this.slideTypeHandler(this.currentSlideIndex)
                this.saveSlidesToDb()


                console.log(this.slides)
            },

            displaySlide(text){
                this.activeSlideText = marked(text) //converts the entered xml into html to be displayed
                this.markdownValue = text
            },

            /** Activated when a regular slide is edited (not exercise one,
             * that is done directly in TextEditorVue). */
            updateSlide(text){
                this.activeSlideText = marked(text)
                this.slides[this.currentSlideIndex].content = text

                this.typing()

            },
            updateTitle(){
                /** The title is already saved with a v-model. But upon typing, we want to update the row
                    in the database with the slides and with the title */
                this.typing()
            },

            /**  @@@
             * This will check if the user has already commenced with typing new content
             * both in regular slides and text editor slides.
             * */
            typing(){
                if(this.editing === false){
                    /** If they haven't started editing, start the interval counter*/
                    this.editing = true;

                    let int = setInterval( ()=> {
                        this.secondsEditing++;

                        /**
                         * When clock reaches 2, it will stop
                         * counting, reset, and will send the axios
                         * request to the database to save all the slides */
                        if(this.secondsEditing >= 2){
                            clearInterval(int)
                            this.editing = false;
                            this.secondsEditing = 0;
                            this.saveSlidesToDb()
                        }
                    },1000)

                } else {
                    /** @@@@
                     *  If a user types, it will reset the clock to 0
                     *  but continue the previously set interval.
                     *  */
                    this.secondsEditing = 0;
                }
            },

            clearSlide(){
                this.activeSlideText = ''
            },


            /** @@@
             * Slide navigation
             * */
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
            jumpToSlide(index){
                if(index != this.currentSlideIndex){
                    this.slideTypeHandler(index) /** This seems to work as of now*/
                    this.currentSlideIndex = index
                }
            },

            /** @@@@
             * Deciding how to display the data of the
             * current slide based on its type.
             * */
            slideTypeHandler(index){
                if(this.slides[index].type == 'slide'){
                    /**
                        1. show slide and hide the TextEditorComponent.vue
                     */
                    this.clearSlide()
                    this.hideSlide = false
                    this.text_editor_state = 'not_shown'

                    this.displaySlide(this.slides[index].content)

                } else if(this.slides[index].type == 'exercise'){
                    /**
                        2. Opposite of previous,and we pass the js and xml data
                           to this.codeMirrorProps, bound to the prop titled
                            code_props on the TextEditorComponent.vue
                     */
                    this.hideSlide = true //this will hide the slide
                    this.text_editor_state = 'shown'

                    this.codeMirrorProps = {
                        xml: this.slides[index].data.xml,
                        javaScript: this.slides[index].data.javaScript
                    }

                }

                this.currentSlideIndex = index;
            },

            shortenText(index){
                let content = this.slides[index].content
                if(content.length > 10){
                    /** displaying only part of the slide text within the slide list */
                    return (this.slides[index].content).substring(0,25).trim() + '...'
                } else {
                    return content
                }
            },

            removeSlide(){
                let deleteIndex = this.currentSlideIndex;

                if(this.slides.length - 1 === deleteIndex){ //if we are deleting the last slide
                    this.currentSlideIndex--;
                }
                this.slides.splice(deleteIndex,1);

                if(this.slides.length > 0){ //if there are still slides to be shown
                    this.slideTypeHandler(this.currentSlideIndex)
                } else {
                    this.displaySlide('') //which will display nothing
                }

                this.saveSlidesToDb()
            },

            /** When the javascript or xml window are updated */
            updateJavaScript(data){
                this.slides[this.currentSlideIndex].data.javaScript = data
                this.typing()
            },
            updateXML(data){
                this.slides[this.currentSlideIndex].data.xml = data
                this.typing()
            },

            async saveSlidesToDb(){
                try {
                    let ans = await axios.post('/api/update-slides',{
                        id: this.slideId,
                        data: JSON.stringify(this.slides),
                        title: this.slideTitle
                    })
                    console.log('Saved to db');
                } catch(e){
                    console.log(e)
                }
            }
        },
        mounted(){
            let rowFromDb = JSON.parse(this.load_slides);
            let slidesFromDb = JSON.parse(rowFromDb.data);

            this.slides = slidesFromDb
            this.slideTitle = rowFromDb.title
            this.slideId = rowFromDb.id /** used later for post requests*/

            if(this.slides.length > 0){ /** If the array returned is zero */
                this.slideTypeHandler(this.currentSlideIndex)
            }
        }
    }
</script>

<style scoped>
    #slide_title {
        font-size: 16px;
    }

    #slide_title:focus {
        background-color: white!important;
        color:black!important;
        font-weight: normal!important;
    }

    #slide-list li span{
        cursor: pointer;
    }

    #slide-list li span:hover {
        cursor: pointer;
    }

    #the-slide {
        width: 95%;
        height:200px;
        resize:none;
    }

    #text-editor {
        width: 100%;
        margin-left: 250px;
    }

    #content {
        width: 100%;
        margin-left: 250px;
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
        display: none!important;
    }
</style>
