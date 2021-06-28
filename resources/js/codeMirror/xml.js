var $_CodeMirrorXMl_$;

function $_getXMLValue_$(){
    return $_CodeMirrorXMl_$.getValue();
}

//Initializing the CodeMirror instance, and injecting pre typed code.
function $_initialize_XML_editor_$(){
    $_CodeMirrorXMl_$ = CodeMirror(document.querySelector('#my-div-2'), {
        lineNumbers: true,
        tabSize: 4,
        value: `<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>JS Bin</title>
</head>
<body>
    <!-- type some HTML... -->

</body>
</html>`, // @@@ text is like this so that the new lines would not be indented in the browser.
        mode: 'xml',
        theme:'monokai' // @@@ additional cdn is used for this.
    });

    $_xmlToIframe_$('i-frame',$_CodeMirrorXMl_$.getValue())
}



//adding user entered script to the iframe, which will execute it in there.
function $_xmlToIframe_$(frame_id,text){
    var $_ifrm_$ = document.getElementById(frame_id)
    $_ifrm_$.contentWindow.document.body.innerHTML=text;
}


function getInstance(){
    return $_CodeMirrorXMl_$
}

module.exports = {
    $_getXMLValue_$,
    $_initialize_XML_editor_$,
    $_xmlToIframe_$,
    getInstance
}
