<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Requiered for the text editor to work -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/theme/monokai.min.css">

</head>
<body>
<div id="my-div"></div>

<!-- So this is how an iframe would work-->

<iframe frameborder="1" id="i-frame" style="width: 100%;height:40vh;"></iframe>

<button id="exe-btn">Execute</button>


<!-- Requiered for the text editor to work -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/javascript/javascript.min.js"></script>
<!-- Requiered for the text editor to work -->


<!-- User code which will be executed -->
<script id="code-to-execute"></script>

<!-- Initializing the codemirror instance -->
<script>
    var $_CodeMirror_$;
    $_initialize_text_editor_$() //must be called for this to work


    let btn = document.getElementById('exe-btn')
    btn.addEventListener('click',function(){

        // //@@@ old code, now we use the new one
        // $_executeCode_$($_CodeMirror_$.getValue())
        // //--------------

        $_scriptToIfrm_$('i-frame',$_CodeMirror_$.getValue())
    })


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

        // // @@@ Old function call - deprecated
        // $_executeCode_$($_CodeMirror_$.getValue())
        // // @@@ Old function call - deprecated

        $_scriptToIfrm_$('i-frame',$_CodeMirror_$.getValue())
    }


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
</script>
</body>
</html>
