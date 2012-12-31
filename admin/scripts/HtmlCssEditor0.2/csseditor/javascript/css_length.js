var field;
var go = "";

function init() {
    window.returnValue= "";
}

function plus(color) {
    if (go == "") {
        field = document.getElementById(color);
        go = window.setInterval("add()", 100);
    } else {
        stop();
    }
}

function minus(color) {
    if (go == "") {
        field = document.getElementById(color);
        go = window.setInterval("sub()", 100);
    } else {
        stop();
    }
}

function stop() {
    if (go != "") {
        window.clearInterval(go);
        go = "";
    }
}


function add() {
    field.value = eval(field.value) + 1;
}

function sub() {
    field.value = eval(field.value) - 1;
}

function ok() {
    var vals = document.getElementById("x").value + document.getElementById("xunit").value;
    
    window.returnValue = vals;
    window.close();
}

function cancel() {
    window.returnValue = "";
    window.close();
}


