<template>
    <div>
        <div style="display: flex;">

            <div>
                <div>
                    <div>
                        <button @click="presentPrevSlide(currentSlideIndex)">Previous slide</button>
                        <button @click="presentNextSlide(currentSlideIndex)">Next slide</button>
                        <button @click="resetAllExercises()">Reset All slides</button>
                    </div>
                </div>

                <ul v-for="(slide,index) in slides" :key="index" id="slide-list">
                    <li v-if="index === currentSlideIndex">
                        <strong>
                            <span @click="jumpToSlide(index)">{{index + 1}} {{shortenText(index)}}</span>
                        </strong>
                    </li>

                    <li v-else>
                        <span @click="jumpToSlide(index)">{{index + 1}} {{shortenText(index)}}</span>
                    </li>
                </ul>
            </div>

            <!-- When it is a regular display slide-->
            <div v-if="this.slides.length > 0" id="content" :class="{hidden: hideSlide}">
                <div
                    class="slide"
                    id="display-slide"
                    v-html="activeSlideText"
                ></div>
            </div>

            <!-- When it is an exercise-->
            <div v-if="this.slides.length > 0" :class="{hidden: !hideSlide}">
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
                slides:[],
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
            /** Local storage checking methods */
            checkCurrIndexLocal(){
                let index = parseInt(localStorage.getItem('currentSlideIndex'))
                console.log(index)
                if(index != null){
                    this.currentSlideIndex = index;
                    console.log('its not nul')
                }
            },
            updateIndexLocal(index){ //this.currentSlideIndex, it will happen upon slide change
                localStorage.setItem('currentSlideIndex',index)
            },
            checkCurrSlidesLocal(){
                let slides = JSON.parse(localStorage.getItem('slides'))
                console.log(slides)
                if(slides !== null){
                    this.slides = slides
                } else {
                    let rowFromDb = JSON.parse(this.load_slides);
                    let slidesFromDb = JSON.parse(rowFromDb.data);
                    this.slides = slidesFromDb

                }
            },
            updateCurrSlidesLocal(){
                localStorage.setItem('slides',JSON.stringify(this.slides))
            },

            resetAllExercises(){
                let rowFromDb = JSON.parse(this.load_slides);
                let slidesFromDb = JSON.parse(rowFromDb.data);
                this.slides = slidesFromDb

                localStorage.removeItem('slides');
                this.slideTypeHandler(this.currentSlideIndex)
            },


            /** Presentation methods }*/
            displaySlide(text){
                this.activeSlideText = marked(text) //converts the entered xml into html to be displayed
                this.markdownValue = text
            },
            updateTitle(){
                /** The title is already saved with a v-model. But upon typing, we want to update the row
                 in the database with the slides and with the title */
                // this.typing()
            },

            /**  @@@
             * This will check if the user has already commenced with typing new content
             * both in regular slides and text editor slides.
             * */
            typing(){
                console.log('typing')
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
                            console.log('not typing')
                        }
                    },1000)

                } else {
                    /** @@@@
                     *  If a user types, it will reset the clock to 0
                     *  but continue the previously set interval.
                     *  */
                    this.secondsEditing = 0;
                    this.updateCurrSlidesLocal()
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
                this.updateIndexLocal(index)
            },

            shortenText(index){
                let content = this.slides[index].content
                if(content.length > 10){
                    /** displaying only part of the slide text within the slide list */
                    return (this.slides[index].content).substring(0,10).trim() + '...'
                } else {
                    return content
                }
            },

            /** When the javascript or xml window are updated */
            updateJavaScript(data){
                this.slides[this.currentSlideIndex].data.javaScript = data
                this.typing()
            },
            updateXML(data){
                this.slides[this.currentSlideIndex].data.xml = data
                this.typing()
            }
        },
        beforeMount(){
            this.checkCurrSlidesLocal()
            this.checkCurrIndexLocal()
        },
        mounted(){
            if(this.slides.length > 0){ /** If the array returned is zero */
                this.slideTypeHandler(this.currentSlideIndex)
            }
        }
    }
</script>

<style>
    #slide-list li span{
        cursor: pointer;
    }

    #slide-list li span:hover {
        cursor: pointer;
    }

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
        display: none!important;
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
