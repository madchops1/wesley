var imageActive = false;
$(document).ready(function() {

	
	console.log('image script loaded');
	$("#wesley-toolbox ul li#image").click(function(e){
		e.preventDefault;
		if($(e.target).hasClass('active')){
			imageActive = false;
			$("#wesley-toolbox ul li#image").removeClass('active');
		} else {
			imageActive = true;
			$("#wesley-toolbox ul li").removeClass('active');
			$(e.target).addClass('active'); // add the active class
		}
	});
	
	$("#wesley-toolbox ul li[id!='image']").click(function(e){
		e.preventDefault;
		console.log('clicked not image');
		imageActive = false;
	});
	
	
	
	
	
	
});