function init() {
    window.returnValue= "";
}

function ok() {
    var opening = document.getElementById("opening").value;
    var closing = document.getElementById("closing").value;
    
    window.returnValue = opening + " " + closing;
    window.close();
}

function cancel() {
    window.returnValue = "";
    window.close();
}



