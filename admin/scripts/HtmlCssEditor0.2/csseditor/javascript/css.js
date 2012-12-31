/// Public functions

/// Insert the editor in a given HTML element 
function insertEditor(element) {
	var xmlFile = "csseditor/xml/css.xml";
	var xslFile = "csseditor/xml/css2html.xsl";
	
	var moz = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined');
	var ie = (typeof window.ActiveXObject != 'undefined');

	if (ie) {
		var xml = new ActiveXObject("Microsoft.XMLDOM");
		xml.async = false;
		xml.load(xmlFile);
		
		var xsl = new ActiveXObject("Microsoft.XMLDOM");
		xsl.async = false;
		xsl.load(xslFile);
		
		element.innerHTML = xml.transformNode(xsl);
	} else if (moz) {
		var xsltProcessor = new XSLTProcessor();
		var xmlDoc = document.implementation.createDocument("", "xmldoc", null)
		
	}
	
	
    showdiv("Background");
}

/// Get an array of all style elements with values in form: key=value
function getStyle() {
    var el;
    var combos = document.forms["cssform"].elements;
    var vals = new Array();
    
    for (el = 0; el < combos.length; el++) {
        var elid = combos[el].id;
        var elval = combos[el].value;
        if (elid != "" && elval != "") {
            vals.push(elid + "=" + elval);
        }
    }
    
    return vals;
}

/// Clear all style elements 
function clearStyle() {
    var combos = getElementsWithValue();

    okToAlert = false;
    
    for (i = 0; i < combos.length; i++) {
        combos[i].selectedIndex = 0;
        changeStyle(combos[i]);
    }
    
    okToAlert = true;
}

/// Add a listener to combobox changes, which will call the given function
/// when changes are made in a combobox. 
function addChangesListener(func) {
    listeners.push(func);
}

/// Set the editor attributes given an array of css style attributes in the form
/// attribute=value
function setStyle(attributes) {
	clearStyle();
	var i;
	
	for (i = 0; i < attributes.length; i++) {
		var splitted = attributes[i].split("=");
		var attr = splitted[0];
		var value = splitted[1];
		
		var combos = document.forms["cssform"].elements;
		for (el = 0; el < combos.length; el++) {
			var elid = combos[el].id;
			if (attr == elid) {
				setComboValue(combos[el], value);
				break;
			}
		}
	}
}













/// Private variables

var listeners = new Array();
var okToAlert = true;

/// Private functions

/// alert all listeners upon a change made in a combobox
function alertListeners(combo) { 
    if (okToAlert) {
        for (i = 0; i < listeners.length; i++) {
            var lis = listeners[i];
            lis(combo);
        }
    }
}

/// get all comboboxes with values != ""
function getElementsWithValue() {
    var el;
    var combos = document.forms["cssform"].elements;
    var combosWithVals = new Array();
    
    for (el = 0; el < combos.length; el++) {
        var elid = combos[el].id;
        var elval = combos[el].value;
        if (elid != "" && elval != "") {
            combosWithVals.push(combos[el]);
        }
    }
    
    return combosWithVals;
}

/// show a given group of comboboxes
function showdiv(id) {
    var divs;
    var element;
    var i;
        
    
    
	// KARL's
    $("#cssform").each(function(){
		if($(this).attr("name") == "cssdiv"){
			$(this).css('display','none');
		}	
	});
	
	// ORIGINAL
	/*
	divs = document.forms("cssform").all;
	for (i = 0; i < divs.length; i++) {
        if (divs[i].name == "cssdiv") {
            divs[i].style.display = "none";
        }
    }
	*/
	
	document.getElementById(id).style.display = "inline";
	
	
	
}

/// check if a special keyword was selected in a combobox, which requires a dialog
function checkSpecial(combo) {
    var val = combo.value;
    var ret;
    var params = "edge: Raised; center: Yes; help: No; resizable: No; status: No;";
    
    if (val == "color...") {
        ret = showModalDialog("csseditor/html/colorchooser.html", "color_dialog", "dialogWidth: 353px; dialogHeight: 220px; " + params);
    } else if (val == "file...") {
        ret = showModalDialog("csseditor/html/filechooser.html", "file_dialog", "dialogWidth: 300px; dialogHeight: 120px; " + params);
    } else if (val == "position...") {
        ret = showModalDialog("csseditor/html/positionchooser.html", "position_dialog", "dialogWidth: 240px; dialogHeight: 170px; " + params);
    } else if (val == "size...") {
        ret = showModalDialog("csseditor/html/sizechooser.html", "size_dialog", "dialogWidth: 240px; dialogHeight: 120px; " + params);
    } else if (val == "length...") {
        ret = showModalDialog("csseditor/html/lengthchooser.html", "length_dialog", "dialogWidth: 240px; dialogHeight: 120px; " + params);
    } else if (val == "number...") {
        ret = showModalDialog("csseditor/html/numberchooser.html", "number_dialog", "dialogWidth: 240px; dialogHeight: 120px; " + params);
    } else if (val == "string...") {
        ret = showModalDialog("csseditor/html/stringchooser.html", "string_dialog", "dialogWidth: 450px; dialogHeight: 350px; " + params);
    } else if (val == "counter...") {
        ret = showModalDialog("csseditor/html/counterchooser.html", "counter_dialog", "dialogWidth: 300px; dialogHeight: 200px; " + params);
    } else if (val == "quotes...") {
        ret = showModalDialog("csseditor/html/quoteschooser.html", "quotes_dialog", "dialogWidth: 380px; dialogHeight: 200px; " + params);
    } else {
        return false;
    }

    setComboValue(combo, ret);
    return true;
}

/// set chosen value from a dialog in a combobox
function setComboValue(combo, val) {
	var i;
	var found = false;
	
    if (val != "") {
		for (i = 0; i < combo.options.length; i++) {
			if (combo.options[i].innerText == val) {
				combo.selectedIndex = i;
				found = true;
				break;
			}
		} 
		if (!found) {
		    var opt = document.createElement("option");
			combo.options.add(opt, 1);
			opt.innerText = val;
			opt.value = val;
			combo.selectedIndex = 1;
		}
    } else {
        combo.selectedIndex = 0;
    }
    changeStyle(combo);
}

/// change the preview element when change was made in a combobox
function changeStyle(combo) {
    var demo = document.getElementById("demo");
    var key = combo.id;
    var index = key.indexOf("-");
    var val = combo.value;
        
    while (index != -1) {
        var pre = key.substring(0, index);
        var mid = key.substring(index + 1, index + 2);
        var suf = key.substring(index + 2);
        var temp = pre + mid.toUpperCase() + suf;
        key = temp;
        index = key.indexOf("-");
    }

	demo.style[key] = val;
    
    alertListeners(combo);
}







