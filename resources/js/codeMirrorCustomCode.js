var $_CodeMirror_$;
$_initialize_text_editor_$() //must be called for this to work


let btn = document.getElementById('exe-btn')

btn.addEventListener('click',function(){
    $_scriptToIfrm_$('i-frame',$_CodeMirror_$.getValue())
})



//Initializing the CodeMirror instance, and injecting pre typed code.
function $_initialize_text_editor_$(){
    $_CodeMirror_$ = CodeMirror(document.querySelector('#my-div'), {
        lineNumbers: true,
        tabSize: 1,
        value: `let a = document.createElement('h1')
a.innerHTML = "Testing <span style='color:red'>the</span> custom editor"
document.body.appendChild(a)
let div = document.createElement('div')
div.style='width:50px;height:50px;background-color:red'
div.innerHTML = '<p style="color:white">Hello!</p>'
document.body.appendChild(div)
setTimeout(function(){
    alert("Time\'s up!")
},2000)`, // @@@ text is like this so that the new lines would not be indented in the browser.
        mode: 'javascript',
        theme:'monokai' // @@@ additional cdn is used for this.
    });

    $_scriptToIfrm_$('i-frame',$_CodeMirror_$.getValue())
}



//adding user entered script to the iframe, which will execute it in there.
function $_scriptToIfrm_$(frame_id,text){
    var $_ifrm_$ = document.getElementById(frame_id)
    $_ifrm_$.contentWindow.document.body.textContent='';

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
