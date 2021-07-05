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
        props:['state','code_props'],
        watch:{
            state: function(){
                this.initialize()
            },
            code_props: function(){
                this.addCode(this.code_props)
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

                    /** Listeners for code changes, and emitting to CreateLesson.vue */
                    javaScript.getInstance().on('change',(instance,change)=>{
                        this.$emit('javaScriptChange',javaScript.$_getJsValue_$())
                    })
                   xml.getInstance().on('change',(instance,change)=>{
                       this.$emit('xmlChange',xml.$_getXMLValue_$())

                       //adding the typed in html straight away as the user types
                       xml.$_xmlToIframe_$('i-frame',xml.$_getXMLValue_$())
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
            console.log('Mounted text editor')

            this.initialize()


        }
    }
</script>

<style scoped>

</style>
