<template>
    <div class="d-flex" id="container">
        <div style="width:100%">
            <div id="my-div-2"></div>
            <div id="my-div"></div>
        </div>
        <div id="execution-div">
            <!-- So iframe is where the DOM output of the js code will be displayed-->
            <div class="d-flex justify-content-between">
                <h3>Output</h3>
                <span class="btn btn-success" id="exe-btn" @click="$_codeExecute(true)">
                    <i class="fas fa-play"></i>
                    Execute
                </span>
            </div>
            
            <iframe frameborder="0" id="i-frame"></iframe>
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
        props:['state','code_props','execute_the_code'],
        watch:{
            state: function(){
                this.initialize()
            },
            code_props: function(){
                this.addCode(this.code_props)
            },
            execute_the_code: function(){
                if(this.execute_the_code === true){
                    this.$_codeExecute()
                    this.$emit('code-executed')
                }
            }
        },
        methods:{
            $_removeIframeDom(frame_id){
                var $_ifrm_$ = document.getElementById(frame_id)
                $_ifrm_$.contentWindow.document.body.textContent='';
            },
            $_codeExecute(emitToOther){
                this.$_removeIframeDom('i-frame')
                xml.$_xmlToIframe_$('i-frame',xml.$_getXMLValue_$())
                javaScript.$_scriptToIframe_$('i-frame',javaScript.$_getJsValue_$())

                if(emitToOther === true){
                    this.$emit('whisper-code-execute')
                }


            },
            initialize(){
                if(this.state == 'shown' && this.initialized === false){
                    /** Text editor initializations*/
                    xml.$_initialize_XML_editor_$()
                    javaScript.$_initialize_text_editor_$()

                    /** Listeners for code changes, and emitting to CreateLesson.vue
                     * - false means we don't want to whisper this change to the other user
                     * - they most likely whispered it to us. */
                    javaScript.getInstance().on('change',(instance,change)=>{
                        this.$emit('javaScriptChange',javaScript.$_getJsValue_$())
                    })
                   xml.getInstance().on('change',(instance,change)=>{
                       this.$emit('xmlChange',xml.$_getXMLValue_$())
                       //adding the typed in html straight away as the user types
                       xml.$_xmlToIframe_$('i-frame',xml.$_getXMLValue_$())
                    })


                    /** When a user types something into the box themselves,
                     * it will be sent to the other users because of the ''whisper-to-other-user'' parameter */
                    javaScript.getInstance().on('keyup',e=>{
                        this.$emit('javaScriptChange',javaScript.$_getJsValue_$(),'whisper-to-other-user')
                        console.log('key pressed',e)
                    })

                    xml.getInstance().on('keyup',e=>{
                        this.$emit('xmlChange',xml.$_getXMLValue_$(),'whisper-to-other-user')
                        console.log('key pressed',e)
                    })

                    this.initialized = true
                    /** This will prevent from reinitialization, which would cause
                     * more than two coding text boxes to be generated. */
                    this.addCode(this.code_props)
                }
            },
            /** Activated when the code_props prop changes (when a new slide is loaded from the CreateLesson.vue component)*/
            addCode(props){
                javaScript.getInstance().setValue(props.javaScript)
                xml.getInstance().setValue(props.xml)
            }
        },
        mounted(){
            this.initialize()
        }
    }
</script>

<style scoped>
    #container {
        width: 100%;
    }

    #execution-div {
        width:100%;
    }

    #i-frame {
        height:90vh;
        width: 100%;
    }

    #my-div, #my-div-2 {
        margin-bottom: 5px;
    }

    #my-div ::-webkit-scrollbar,
    #my-div-2 ::-webkit-scrollbar {
        width: 10px;
    }

    #my-div ::-webkit-scrollbar-track,
    #my-div-2 ::-webkit-scrollbar-track {
        border-radius: 5px;
        box-shadow: inset 0 0 10px #5c5e4e;
    }

    #my-div ::-webkit-scrollbar-thumb,
    #my-div-2 ::-webkit-scrollbar-thumb{
        border-radius: 5px;
        background-color: #6a6d63;
    }
</style>
