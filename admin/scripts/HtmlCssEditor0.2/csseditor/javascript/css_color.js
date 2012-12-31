var go = "";
var field;

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
    if (eval(field.value) < 255) {
        field.value = eval(field.value) + 1;
        setColor();
    }
}

function sub() {
    if (eval(field.value) > 0) {
        field.value = eval(field.value) - 1;
        setColor();
    }
}

function setColor() {
    var red = document.getElementById("red");  
    var green = document.getElementById("green");  
    var blue = document.getElementById("blue");  
    var colordiv = document.getElementById("colordiv");
    
    red.style.backgroundColor = "#" + getHexString(red.value) + "0000";
    green.style.backgroundColor = "#00" + getHexString(green.value) + "00";
    blue.style.backgroundColor = "#0000" + getHexString(blue.value);
    colordiv.style.backgroundColor = "#" + getHexString(red.value) + getHexString(green.value) + getHexString(blue.value);  
}

function getHexString(num) {
    var inum = eval(num);
    if (inum < 0) {
        return "00";
    } else if (inum > 255) {
        return "ff";    
    }

    var snum;
    var temp = inum.toString(16);
    if (inum <= 0xf) {
        snum = "0" + temp;
    } else {
        snum = temp; 
    }
    return snum;
}

function ok() {
    setColor();
    var col = document.getElementById("colordiv").style.backgroundColor;
    
    window.returnValue = col;
    window.close();
}

function cancel() {
    window.returnValue = "";
    window.close();
}
