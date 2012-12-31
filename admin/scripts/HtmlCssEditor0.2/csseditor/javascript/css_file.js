function init() {
    window.returnValue= "";
}

function ok() {
    var fil = document.getElementById("file").value;
    
    window.returnValue = "url(" + fil + ")";
    window.close();
}

function cancel() {
    window.returnValue = "";
    window.close();
}



