function init() {
    window.returnValue= "";
}

function ok() {
    var identifier = document.getElementById("identifier").value;
    var number = document.getElementById("number").value;
    
    window.returnValue = identifier + " " + number;
    window.close();
}

function cancel() {
    window.returnValue = "";
    window.close();
}



