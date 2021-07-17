<template>
    <div>
        <div class="d-flex">

            <div id="slides-component">
                <div class="mt-2" id="slide-toggle-btns">
                    <div>
                        <span class="btn btn-primary" @click="presentPrevSlide(currentSlideIndex)">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <span class="btn btn-primary" @click="presentNextSlide(currentSlideIndex)">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span class="btn btn-success" @click="resetLocalStorage(true)">
                            <i class="fas fa-sync"></i>
                            Reset all
                        </span>
                    </div>
                </div>

                <ul id="slide-list" class="text-light">
                    <li v-for="(slide,index) in slides" :key="index">

                        <strong v-if="index === currentSlideIndex">
                            <span @click="jumpToSlide(index)">{{index + 1}} {{shortenText(index)}}</span>
                        </strong>

                        <span v-else @click="jumpToSlide(index)">{{index + 1}} {{shortenText(index)}}</span>

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

            <!-- When it is an exercise -->
            <text-editor-component
                v-if="this.slides.length > 0" :class="{hidden: !hideSlide}"

                :state="text_editor_state"
                v-on:javaScriptChange="updateJavaScript"
                v-on:xmlChange="updateXML"
                v-bind:code_props="codeMirrorProps"

                v-on:whisper-code-execute="whisper_code_execution"
                v-bind:execute_the_code="signal_code_execution"
                v-on:code-executed="$emit('code-executed')"
            ></text-editor-component> <!-- Adding a class binding directly to the component does not display the text of the component until I click on it -->


        </div>
    </div>
</template>

<script>
    import marked from '../../marked.js';

    export default {
        name: "CreateLesson",
        props:[
            'load_slides',
            'class_uuid',
            'local_storage_from_peer',
            'change_slide',
            'reset_slide_whisper',
            'signal_code_execution'
        ],
        watch:{
            local_storage_from_peer(){
                console.log('the data has changed')
                let data = JSON.parse(this.local_storage_from_peer)
                localStorage.setItem(this.class_uuid,data)
                this.slides = data.slides;
                this.slideTypeHandler(data.currIndex)
            },
            change_slide(){
                console.log('time to change slide', this.change_slide)
                this.slideTypeHandler(this.change_slide)
            },
            reset_slide_whisper(){
                if(this.reset_slide_whisper === true){
                    this.resetLocalStorage(false)
                }

                this.$emit('slides-reset')
            }
        },
        data: function(){
            return {
                currentSlideIndex:0,
                hideSlide:false,
                slides:[],
                activeSlideText:``,
                text_editor_state: null,
                codeMirrorProps:{
                    xml:null,
                    javaScript:null
                }
            }
        },
        methods:{
            /** Local storage checking methods */
            checkLocalStorage(uuid){
                let prevClass = JSON.parse(localStorage.getItem(uuid));

                if(prevClass === null){
                    let rowFromDb = JSON.parse(this.load_slides);
                    let slidesFromDb = JSON.parse(rowFromDb.data);
                    this.slides = slidesFromDb

                    localStorage.setItem(uuid,JSON.stringify({slides: this.slides,currIndex: this.currentSlideIndex}));
                } else {
                    this.slides = prevClass.slides
                    this.currentSlideIndex = prevClass.currIndex
                }
            },

            resetLocalStorage(emitToOther){
                localStorage.removeItem(this.class_uuid);

                let rowFromDb = JSON.parse(this.load_slides);
                let slidesFromDb = JSON.parse(rowFromDb.data);
                this.slides = slidesFromDb

                localStorage.setItem(this.class_uuid,JSON.stringify({slides: this.slides,currIndex: this.currentSlideIndex}));
                this.slideTypeHandler(this.currentSlideIndex)

                if(emitToOther === true){
                    this.$emit('reset-slides'); /** Only tell other user if the 1st user pressed the button to do so */
                }
            },

            /** Presentation methods }*/
            displaySlide(text){
                this.activeSlideText = marked(text) //converts the entered xml into html to be displayed
                this.markdownValue = text
            },
            /**  @@@
             * This will check if the user has already commenced with typing new content
             * both in regular slides and text editor slides.
             * */
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

                localStorage.setItem(this.class_uuid,JSON.stringify({slides: this.slides,currIndex: this.currentSlideIndex}));

                this.$emit('slide-changed',this.currentSlideIndex);
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

            /** When the javascript or xml window are updated from textEditorComponent */
            updateJavaScript(data,emitToOtherUser){
                this.slides[this.currentSlideIndex].data.javaScript = data

                localStorage.setItem(this.class_uuid,JSON.stringify({slides: this.slides,currIndex: this.currentSlideIndex}))
                if(emitToOtherUser === 'whisper-to-other-user'){
                    this.$emit('live-code-update',this.slides)
                }
            },
            updateXML(data, emitToOtherUser){
                this.slides[this.currentSlideIndex].data.xml = data

                localStorage.setItem(this.class_uuid,JSON.stringify({slides: this.slides,currIndex: this.currentSlideIndex}))
                if(emitToOtherUser === 'whisper-to-other-user'){
                    this.$emit('live-code-update',this.slides)
                }
            },
            whisper_code_execution(){
                this.$emit('whisper-code-execute')
            }
        },
        beforeMount(){
            this.checkLocalStorage(this.class_uuid)

        },
        mounted(){
            if(this.slides.length > 0){ /** If the array returned is zero */
                this.slideTypeHandler(this.currentSlideIndex)
            }
        }
    }
</script>

<style scoped>
    #slides-component {
        margin-top:200px;
        min-width:250px;
    }

    #slide-toggle-btns {
        position: fixed;
    }

    #slide-list {
        position:fixed;
        padding:5px;
        margin:45px 0 0 5px;
    }

    #slide-list li {
        list-style-type: none;
        margin:0;
        padding:3px 0;
    }

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
