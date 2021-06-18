/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/codeMirror/javaScript.js":
/*!***********************************************!*\
  !*** ./resources/js/codeMirror/javaScript.js ***!
  \***********************************************/
/***/ ((module) => {

var $_CodeMirrorJavaScript_$;

function $_getJsValue_$() {
  return $_CodeMirrorJavaScript_$.getValue();
} //Initializing the CodeMirror instance, and injecting pre typed code.


function $_initialize_text_editor_$() {
  $_CodeMirrorJavaScript_$ = CodeMirror(document.querySelector('#my-div'), {
    lineNumbers: true,
    tabSize: 4,
    value: "\n(function addHeading(){\n  var num = 3\n  var interval = setInterval(()=>{\n  if(num <= 0){\n    clearInterval(interval)\n      document.querySelectorAll('.added').forEach(el=>{\n      el.remove()\n      })\n    } else {\n    let a = document.createElement('h2')\n    a.className = 'added'\n   a.innerText = 'This text will be gone in ' + num + ' seconds'\n    document.body.appendChild(a)\n    num--\n    }\n    console.log('done')\n  },1000)\n})()",
    // @@@ text is like this so that the new lines would not be indented in the browser.
    mode: 'javascript',
    theme: 'monokai' // @@@ additional cdn is used for this.
    // readOnly:true // @@@ can be used for 'view only' mode of the lesson

  });
  $_scriptToIframe_$('i-frame', $_CodeMirrorJavaScript_$.getValue());
} //adding user entered script to the iframe, which will execute it in there.


function $_scriptToIframe_$(frame_id, text) {
  var $_ifrm_$ = document.getElementById(frame_id);

  try {
    var scriptTag = $_ifrm_$.contentWindow.document.createElement('script'); // @@@ Scoping the script
    // @
    //
    // Containing the scope of the code
    // entered by the user. Not doing this will
    // result in an error if the same code is re-executed
    // using the same names for const and let again (redeclaration error)
    //
    // @@

    scriptTag.innerHTML = "(function(){\n            ".concat(text, "\n        })()");
    scriptTag.setAttribute('id', 'code-to-execute');
    $_ifrm_$.contentWindow.document.body.appendChild(scriptTag);
  } catch (er) {
    console.log(er);
  }
}

function getInstance() {
  return $_CodeMirrorJavaScript_$;
}

module.exports = {
  $_getJsValue_$: $_getJsValue_$,
  $_initialize_text_editor_$: $_initialize_text_editor_$,
  $_scriptToIframe_$: $_scriptToIframe_$,
  getInstance: getInstance
};

/***/ }),

/***/ "./resources/js/codeMirror/xml.js":
/*!****************************************!*\
  !*** ./resources/js/codeMirror/xml.js ***!
  \****************************************/
/***/ ((module) => {

var $_CodeMirrorXMl_$;

function $_getXMLValue_$() {
  return $_CodeMirrorXMl_$.getValue();
} //Initializing the CodeMirror instance, and injecting pre typed code.


function $_initialize_XML_editor_$() {
  $_CodeMirrorXMl_$ = CodeMirror(document.querySelector('#my-div-2'), {
    lineNumbers: true,
    tabSize: 4,
    value: "<!DOCTYPE html>\n<html>\n<head>\n  <meta charset=\"utf-8\">\n  <meta name=\"viewport\" content=\"width=device-width\">\n  <title>JS Bin</title>\n</head>\n<body>\n    <h1>How is it going?</h1>\n\n</body>\n</html>",
    // @@@ text is like this so that the new lines would not be indented in the browser.
    mode: 'xml',
    theme: 'monokai' // @@@ additional cdn is used for this.

  });
  $_xmlToIframe_$('i-frame', $_CodeMirrorXMl_$.getValue());
} //adding user entered script to the iframe, which will execute it in there.


function $_xmlToIframe_$(frame_id, text) {
  var $_ifrm_$ = document.getElementById(frame_id);
  $_ifrm_$.contentWindow.document.body.innerHTML = text;
}

function getInstance() {
  return $_CodeMirrorXMl_$;
}

module.exports = {
  $_getXMLValue_$: $_getXMLValue_$,
  $_initialize_XML_editor_$: $_initialize_XML_editor_$,
  $_xmlToIframe_$: $_xmlToIframe_$,
  getInstance: getInstance
};

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**************************************************!*\
  !*** ./resources/js/codeMirror/codeExecution.js ***!
  \**************************************************/
var xml = __webpack_require__(/*! ./xml.js */ "./resources/js/codeMirror/xml.js");

var javaScript = __webpack_require__(/*! ./javaScript.js */ "./resources/js/codeMirror/javaScript.js"); //I might need to remove the thing that removes everything from the dom. Maybe that should be inside the xml module


function $_removeIframeDom(frame_id) {
  var $_ifrm_$ = document.getElementById(frame_id);
  $_ifrm_$.contentWindow.document.body.textContent = '';
}

var btn = document.getElementById('exe-btn');
btn.addEventListener('click', function () {
  $_removeIframeDom('i-frame'); //First adding the input html. After that, we are adding the JavaScript

  xml.$_xmlToIframe_$('i-frame', xml.$_getXMLValue_$());
  javaScript.$_scriptToIframe_$('i-frame', javaScript.$_getJsValue_$());
}); //Instantiating the windows

xml.$_initialize_XML_editor_$();
javaScript.$_initialize_text_editor_$(); //must be called for this to work
//Event listeners for changes in javascript and xml text editors

javaScript.getInstance().on('change', function (instance, change) {
  console.log(instance, change);
});
xml.getInstance().on('change', function (instance, change) {
  console.log(instance, change);
});
})();

/******/ })()
;