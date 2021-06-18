/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**************************************!*\
  !*** ./resources/js/laravel-echo.js ***!
  \**************************************/
/** @@@ Laravel Echo
    imported directly into
    layouts\class.blade.php
 */
Echo.join("home").here(function (e) {
  //who is here when I join
  console.log(e, 'You are here');
}).joining(function (e) {
  //who is joining
  console.log(e, Echo.socketId() + ' has joined');
}).leaving(function (e) {
  console.log(e, Echo.socketId() + ' has left');
}).listen('NewMessage', function (e) {
  console.log(e.message);
});
/******/ })()
;