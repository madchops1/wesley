var field;
var go = "";

function init() {
    window.returnValue= "";
}

function ok() {
    var radios = document.forms[0]["radios"];
    var vals = "";
    var rad;
    
    for (i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            rad = radios[i].value;
            break;
        }
    }
    
    if (rad == "") {
        cancel();
    } else if (rad == "string") {
        vals = document.forms[0]["_string"].value;
    } else if (rad == "counter") {
        vals = "counter(" + document.forms[0]["_counter"].value + ")";
    } else if (rad == "counters") {
        vals = "counters(" + document.forms[0]["_counters"].value + ")";
    } else if (rad == "attr") {
        vals = "attr(" + document.forms[0]["_attr"].value + ")";
    }
    
    window.returnValue = vals;
    window.close();
}

function cancel() {
    window.returnValue = "";
    window.close();
}


