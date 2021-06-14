var $_CodeMirrorJavaScript_$;

function $_getJsValue_$(){
    return $_CodeMirrorJavaScript_$.getValue();
}

//Initializing the CodeMirror instance, and injecting pre typed code.
function $_initialize_text_editor_$(){
    $_CodeMirrorJavaScript_$ = CodeMirror(document.querySelector('#my-div'), {
        lineNumbers: true,
        tabSize: 4,
        value: `
(function addHeading(){
  var num = 3
  var interval = setInterval(()=>{
  if(num <= 0){
    clearInterval(interval)
      document.querySelectorAll('.added').forEach(el=>{
      el.remove()
      })
    } else {
    let a = document.createElement('h2')
    a.className = 'added'
   a.innerText = \'This text will be gone in \' + num + \' seconds\'
    document.body.appendChild(a)
    num--
    }
    console.log('done')
  },1000)
})()`, // @@@ text is like this so that the new lines would not be indented in the browser.
        mode: 'javascript',
        theme:'monokai', // @@@ additional cdn is used for this.
        // readOnly:true // @@@ can be used for 'view only' mode of the lesson
    });

    $_scriptToIframe_$('i-frame',$_CodeMirrorJavaScript_$.getValue())
}



//adding user entered script to the iframe, which will execute it in there.
function $_scriptToIframe_$(frame_id,text){
    var $_ifrm_$ = document.getElementById(frame_id)

    try {
        var scriptTag = $_ifrm_$.contentWindow.document.createElement('script')

        // @@@ Scoping the script
        // @
        //
        // Containing the scope of the code
        // entered by the user. Not doing this will
        // result in an error if the same code is re-executed
        // using the same names for const and let again (redeclaration error)
        //
        // @@

        scriptTag.innerHTML = `(function(){
            ${text}
        })()`;

        scriptTag.setAttribute('id','code-to-execute')
        $_ifrm_$.contentWindow.document.body.appendChild(scriptTag)
    } catch(er){
        console.log(er);
    }
}

function getInstance(){
    return $_CodeMirrorJavaScript_$
}

module.exports = {
    $_getJsValue_$,
    $_initialize_text_editor_$,
    $_scriptToIframe_$,
    getInstance
}
