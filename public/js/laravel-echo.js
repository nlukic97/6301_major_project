/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/laravel-echo.js":
/*!**************************************!*\
  !*** ./resources/js/laravel-echo.js ***!
  \**************************************/
/***/ ((module) => {

/** @@@ Laravel Echo
    imported into Classroom.vue
 */
function init(id) {
  Echo.join("home").here(function (e) {
    //who is here when I join
    console.log(e, 'You are here');
  }).joining(function (e) {
    //who is joining
    console.log(e, Echo.socketId() + ' has joined');
  }).leaving(function (e) {
    console.log(e, Echo.socketId() + ' has left');
  }).listen('NewMessage', function (e) {
    console.log('NewMessage:', e);
  });
  /** @@@
   * Each user will have this be their personal channel for receiving messages.
   * id is passed as a prop in the views/class.blade.php view in the classroom-component
   * */

  Echo["private"]("user.".concat(id)) //so each user should be subscribed to their own channel (maybe a hash from the db?)
  .listen('NewPrivateMessage', function (e) {
    console.log('New Private message:', e);
  });
}

module.exports = {
  init: init
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
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/js/laravel-echo.js");
/******/ 	
/******/ })()
;