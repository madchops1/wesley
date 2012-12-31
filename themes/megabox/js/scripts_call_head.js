Cufon.replace('h1', { fontFamily: 'Qlassik Bold', hover: true }); 
Cufon.replace('h2, h3, h4, h5, h6', { fontFamily: 'Qlassik Medium', hover: true }); 

$(document).ready(function(){
	//Examples of how to assign the ColorBox event to elements
	$("a[class='crbox']").colorbox();
	$(".video-crbox").colorbox({iframe:true, innerWidth:640, innerHeight:510});
	$(".iframe-crbox").colorbox({width:"80%", height:"80%", iframe:true});
	
});

document.write("<link href=\"switcher\/colorpicker\/css\/colorpicker.css\" rel=\"stylesheet\" type=\"text\/css\" \/> ");
document.write("<link href=\"switcher\/switcher.css\" rel=\"stylesheet\" type=\"text\/css\" \/>");
document.write("<script type=\"text\/javascript\" src=\"switcher\/jquery.cookie.js\"><\/script> ");
document.write("<script type=\"text\/javascript\" src=\"switcher\/colorpicker\/js\/colorpicker.js\"><\/script> ");
document.write("<script type=\"text\/javascript\" src=\"switcher\/switcher.js\"><\/script> ");