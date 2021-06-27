<template>
    <div class="d-flex">
        <div style="width:50%">
            <div id="my-div-2"></div>
            <div id="my-div"></div>
        </div>
        <div style="width:50%">
            <!-- So iframe is where the DOM output of the js code will be displayed-->
            <button id="exe-btn" @click="$_codeExecute">Execute</button>
            <iframe frameborder="1" id="i-frame" style="width: 100%;height:40vh;"></iframe>
        </div>
    </div>
</template>

<script>
    import xml from '../../codeMirror/xml.js';
    import javaScript from '../../codeMirror/javaScript';

    export default {
        name: "TextEditorComponent",
        data:function(){
            return{
                initialized:false
            }
        },
        props:['state'],
        watch:{
            state: function(){
                this.initialize()
            }
        },
        methods:{
            $_removeIframeDom(frame_id){
                var $_ifrm_$ = document.getElementById(frame_id)
                $_ifrm_$.contentWindow.document.body.textContent='';
            },
            $_codeExecute(){
                this.$_removeIframeDom('i-frame')
                xml.$_xmlToIframe_$('i-frame',xml.$_getXMLValue_$())
                javaScript.$_scriptToIframe_$('i-frame',javaScript.$_getJsValue_$())
            },
            initialize(){
                if(this.state == 'shown' && this.initialized === false){
                    /** Text editor initializations*/
                    xml.$_initialize_XML_editor_$()
                    javaScript.$_initialize_text_editor_$()

                    /** Listeners for code changes*/
                    javaScript.getInstance().on('change',(instance,change)=>{
                        this.$emit('javaScriptChange',javaScript.$_getJsValue_$())
                      xml.getInstance().setValue(javaScript.$_getJsValue_$())
                    })
                    xml.getInstance().on('change',(instance,change)=>{
                        this.$emit('xmlChange',xml.$_getXMLValue_$())
                        /** Can't be uncommented like this at the same time as line 51 to set the XML distance
                         * because we make an infinite reaction loop.
                         * We type something in javascript, the change registers it and updates it on the other users side.
                         * However, the other user registers a change in javascript, so it will send us back the change, which
                         * will cause or editor to change, and the loop goes on. So a keypress reaction would be better. */
                        // javaScript.getInstance().setValue(xml.$_getXMLValue_$())
                    })

                    this.initialized = true /** This will prevent from reinitialization,
                     since we only need it to happen once per page load. Reinitialization causes multiple
                     text editor div's to be appended. It will only happen if this.state is shown
                     which is a prop passed in from the CreateLesson.vue component */
                }
            }
        },
        mounted(){
            console.log('Mounted text editor')
            console.log(this.state)

            this.initialize()


        }
    }
</script>

<style scoped>

</style>
