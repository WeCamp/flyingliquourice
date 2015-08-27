/**
 * Created by Flying liquorice.
 */

var wsUri = 'ws://shop.dev.mijndomein.nl:8080';
var output;
function init() {
    output = document.getElementById("output");
    console.log('connecting');
    testWebSocket();
    console.log('connected');
}
function testWebSocket() {
    websocket = new WebSocket(wsUri);

    websocket.onopen = function (evt) {
        console.log('open');
        onOpen(evt);
    };
    websocket.onclose = function (evt) {
        console.log('close');
        onClose(evt);
    };
    websocket.onmessage = function (evt) {
        console.log('msg');
        onMessage(evt);
    };
    websocket.onerror = function (evt) {
        console.log('error');
        onError(evt);
    };
    console.log('end');
}
function onOpen(evt) {
    writeToScreen("CONNECTED");
    doSend("WebSocket rocks");
}
function onClose(evt) {
    writeToScreen("DISCONNECTED");
}
function onMessage(evt) {
    writeToScreen('<span style="color: blue;">RESPONSE: ' + evt.data + '</span>');
    websocket.close();
}
function onError(evt) {
    writeToScreen('<span style="color: red;">ERROR:</span> ' + evt.data);
}
function doSend(message) {
    writeToScreen("SENT: " + message);
    websocket.send(message);
}
function writeToScreen(message) {
    console.log(message);
    var pre = document.createElement("p");
    pre.style.wordWrap = "break-word";
    pre.innerHTML = message;
    output.appendChild(pre);
}
window.addEventListener("load", init, false);
