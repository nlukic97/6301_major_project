var xml = require('./xml.js');
var javaScript = require('./javaScript.js'); //I might need to remove the thing that removes everything from the dom. Maybe that should be inside the xml module

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


/* @@@ Event broadcasting test */
let btn2 = document.getElementById('msg-btn')
btn2.addEventListener('click',function(){

    axios.post('/api/send-msg',{msg:'Gas is going'})
        .then(e=>{
            console.log('Msg sent',e)
        })
        .catch(e=>{
            console.log('Error',e)
        })
})
/* @@@ Event broadcasting test */




//Instantiating the windows
xml.$_initialize_XML_editor_$()
javaScript.$_initialize_text_editor_$() //must be called for this to work


//Event listeners for changes in javascript and xml text editors
javaScript.getInstance().on('change',(instance,change)=>{
    console.log(instance,change)
})

xml.getInstance().on('change',(instance,change)=>{
    console.log(instance,change)
})

/* @@@
    Laravel Echo listening
* */

// Echo.private('home')
//     .listen('NewMessage',e=>{
//         console.log(e.message)
//         console.log(Echo.socketId())
//     })


//I will need this to know for information
Echo.join(`home`)
    .here(e=>{  //who is here when I join
        console.log(e, 'You are here')
    })
    .joining(e=>{ //who is joining
        console.log(e,Echo.socketId() + ' has joined')
    })
    .leaving(e=>{
        console.log(e, Echo.socketId() + ' has left')

    })
    .listen('NewMessage', (e) => {
        console.log(e.message)
    });
