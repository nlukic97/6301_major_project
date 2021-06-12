var xml = require('./xml.js');
var javaScript = require('./javaScript.js'); //I might need to remove the thing that removes everything from the dom. Maybe that should be inside the xml module

xml.$_initialize_XML_editor_$()
javaScript.$_initialize_text_editor_$() //must be called for this to work


function $_removeIframeDom(frame_id){
    var $_ifrm_$ = document.getElementById(frame_id)
    $_ifrm_$.contentWindow.document.body.textContent='';
}

let btn = document.getElementById('exe-btn')
btn.addEventListener('click',function(){
    $_removeIframeDom('i-frame')

    //First adding the input html. After that, we are adding the JavaScript
    xml.$_xmlToIframe_$('i-frame',xml.$_getXMLValue_$())
    javaScript.$_scriptToIframe_$('i-frame',javaScript.$_getJsValue_$())
})
