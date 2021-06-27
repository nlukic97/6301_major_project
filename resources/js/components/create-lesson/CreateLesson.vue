<template>
    <div>
        <div>
            <label for="slide_title">Slide title</label>
            <input type="text" id="slide_title" v-model="slideTitle" @keyup="updateTitle()">
        </div>
        <div style="display: flex;">

            <div>
                <div>
                    <div>
                        <button @click="addNewSlide('slide')">New Slide</button>
                        <button @click="addNewSlide('exercise')">Exercise Slide</button>
                    </div>
                    <div>
                        <button @click="presentPrevSlide(currentSlideIndex)">Previous slide</button>
                        <button @click="presentNextSlide(currentSlideIndex)">Next slide</button>
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

                <textarea v-if="this.slides.length > 0"
                          id="the-slide"
                          cols="30"
                          rows="10"
                          v-model="markdownValue"
                          @keyup="updateSlide(markdownValue)"
                          :class="{hidden: hideSlide}"
                ></textarea>
            </div>

            <!-- When it is a slide-->
            <div v-if="this.slides.length > 0" id="content" :class="{hidden: hideSlide}">
                <span class="x-btn" @click="removeSlide()">x</span>
                <div
                    class="slide"
                     id="display-slide"
                     v-html="activeSlideText"
                ></div>
            </div>

            <!-- When it is an exercise-->
            <div v-if="this.slides.length > 0" :class="{hidden: !hideSlide}">
                <span class="x-btn" @click="removeSlide()">x</span>

                <text-editor-component
                    :state="text_editor_state"
                    v-on:javaScriptChange="updateJavaScript"
                    v-on:xmlChange="updateXML"
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
                text_editor_state: null
            }
        },
        methods:{
            addNewSlide(slideType){
                var newSlide;
                if(slideType === 'exercise'){
                    newSlide = {type:slideType,content:'Exercise slide',data:{xml:'someHtml',javaScript:'someJs'}}
                } else {
                    newSlide = {type:slideType,content:''}
                }
                this.slides.push(newSlide)

                this.markdownValue = ''
                this.currentSlideIndex = this.slides.length - 1

                this.slideTypeHandler(this.currentSlideIndex)
                this.saveSlidesToDb()
            },

            displaySlide(text){
                this.activeSlideText = marked(text) //converts the entered xml into html to be displayed
                this.markdownValue = text
            },

            /** Activated when a slide is edited.*/
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

            typing(){
                /** This will check if the user has already commenced with typing */
                if(this.editing === false){

                    /** If they haven't started editing, start the interval counter*/
                    this.editing = true;

                    let int = setInterval( ()=> {
                        this.secondsEditing++;
                        if(this.secondsEditing >= 1){ /** When clock reaches 4, it will stop counting, reset, and send the axios request to the database */
                            clearInterval(int)
                            this.editing = false;
                            this.secondsEditing = 0;
                            this.saveSlidesToDb()
                        }
                    },500)

                } else {
                    /** If a user types, it will reset the clock to 0 but continue the previously set interval.
                        Because this.editing is true, it means they have already started typing */
                    this.secondsEditing = 0;
                }
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
                    this.text_editor_state = 'not_shown'

                } else if(this.slides[index].type == 'exercise'){
                    this.text_editor_state = 'shown'
                    this.hideSlide = true //this will hide the slide
                    this.markdownValue = this.slides[index].content
                    //insert code to add the CodeMirror exercise here
                    console.log('Here is the exercise for slide of index ' + index +'. We will not display this slide but just show an empty canvas');
                }

                this.currentSlideIndex = index;
            },

            jumpToSlide(index){
                if(index != this.currentSlideIndex){
                    // this.displaySlide(this.slides[index].content)
                    this.slideTypeHandler(index) /** This seems to work as of now*/
                    this.currentSlideIndex = index
                }
            },

            shortenText(index){
                let content = this.slides[index].content
                if(content.length > 10){
                    return (this.slides[index].content).substring(0,10).trim() + '...'
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

                    // this.displaySlide(this.slides[this.currentSlideIndex].content)
                    this.slideTypeHandler(this.currentSlideIndex)
                } else {
                    this.displaySlide('')
                }

                this.saveSlidesToDb()
            },

            updateJavaScript(data){
                this.slides[this.currentSlideIndex].data.javaScript = data
                console.log('updated javascript',this.slides)
            },

            updateXML(data){
                this.slides[this.currentSlideIndex].data.xml = data
                console.log('updated xml',this.slides)
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
            // this.slides = JSON.parse("[{\"type\":\"slide\",\"content\":\"# slide 1\"},{\"type\":\"slide\",\"content\":\"$lide 2\"},{\"type\":\"exercise\",\"content\":\"# This is an exercise \\n\\n ## Buckle up \"},{\"type\":\"slide\",\"content\":\"# e \\n- a \\n- e\\n- a\\n\\n1. 2 \\n2. 3\\n3. a\"}]")
            let rowFromDb = JSON.parse(this.load_slides);
            let slidesFromDb = JSON.parse(rowFromDb.data);

            this.slides = slidesFromDb
            this.slideTitle = rowFromDb.title
            this.slideId = rowFromDb.id /** used later for post requests*/

            if(this.slides.length > 0){ /** If the array returned is zero */
                // this.displaySlide(this.slides[this.currentSlideIndex].content)
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
