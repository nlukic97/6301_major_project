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
    import xml from '../../../codeMirror/xml.js';
    import javaScript from '../../../codeMirror/javaScript';

    export default {
        name: "TextEditorComponent",
        data:function(){
            return{
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
            }
        },
        mounted(){
            console.log('Mounted text editor')

            /** Text editor initializations*/
            xml.$_initialize_XML_editor_$()
            javaScript.$_initialize_text_editor_$()

            /** Listeners for code changes*/
            javaScript.getInstance().on('change',(instance,change)=>{
                console.log(instance,change)
            })
            xml.getInstance().on('change',(instance,change)=>{
                console.log(instance,change)
            })
        }
    }
</script>

<style scoped>

</style>
