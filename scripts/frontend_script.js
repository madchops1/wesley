$(document).ready(function() {


	
	
	function resizeEditor()
	{
		var windowHeight = $(window).height();   // returns height of browser viewport
		var windowWidth = $(window).width();
		//$(document).height(); // returns height of HTML document
		
		
		// contentFrame and Wrapper HEIGHT
		// GET THE FIRST CHILD OF BODY
		//var firstChildBody = $('body :nth-child(0)');
		var firstChildBody = $('#contentFrame');
		// WRAP THE THEMES INDEX CONTENT FRAME
		firstChildBody.wrap('<div id="contentFrameWrapper" />');
		
		var contentFrameWidth = $("#contentFrame").width();
		//var contentFrameHeight = (windowHeight * 0.75);
		var contentFrameHeight = (windowHeight - 250);
		
		$("#contentFrameWrapper").css("height",contentFrameHeight+"px");
		$("#contentFrameWrapper").css("width","100%");
		
		
		
		// CMS NAV HEIGHT
		//var cmsnavHeight = ((windowHeight * 0.25) - 2);
		var cmsnavHeight = (250 - 2);
		$("#wesley-cmsnav").css("height",cmsnavHeight+"px");
		
		// WIDGET MENU WIDTH
		var widgetMenuWidth = ((windowWidth * 0.25) - 1);
		$('#wesley-cmsnav-widgetmenu').css("width",widgetMenuWidth+"px");
		
		//
		var contenteditareaWidth = ((windowWidth * 0.75) - 1);
		$('#wesley-cmsnav-contenteditarea').css("width",contenteditareaWidth+"px");


		
	}
		
resizeEditor();	
	
$(window).resize(resizeEditor);	

// ADD 30 PX TO CONTENT FRAME WIDTH ONCE
//contentFrameWidth = ($("#contentframe").width() + 30);
//$("#contentframe").css("width",contentFrameWidth+"px");

/*
$("#wesley-cmsnav").resizable({ 
	handles: 'n',
	maxHeight: 400,
	minHeight: 200,
	ghost: true,
	containment: 'body'
});
*/



$('.wesley-spot').hover(
	function()
	{
		//$(this).css('border','5px solid #d4ebff');
		/*$(this).css('opacity','1.0');*/
		//$(this).addClass("wesley-spot-active");
	},
	function()
	{
		//$(this).css('border','5px solid #A9D6FC');
		//$(this).removeClass("wesley-spot-active");
	}
);

$('.wesley-cmsnav').hover(
	function(){
		$('.wesley-spot').removeClass('wesley-spot-active');
	},
	function(){
		
	}
);


$('.wesley-spot').height(function(){
	return (($(this).parent().height()) - 22);
});

$('.wesley-spot').width(function(){
	return (($(this).parent().width()) - 22);
});

/** WIDGET DRAGGABLE / DROPPABLE **/
$( "#wesley-cmsnav-widgetmenu a" ).draggable(
	{
	revert: "invalid",
	containment: $( "body" ).length ? "body" : "document",
	helper: "clone",
	//cancel: "#wesley-cmsnav",
	cursor: "move"
	}
);

$( "#wesley-cmsnav" ).droppable(
	{
		accept: "#wesley-cmsnav-widgetmenu a",
		greedy: true,
		over: function(event,ui){ $(".wesley-spot").removeClass('wesley-spot-active'); }
	}
);

$( ".wesley-spot" ).droppable(
	{
	accept: "#wesley-cmsnav-widgetmenu a",
	greedy: true,
	over: function( event, ui ) {
		$(this).addClass("wesley-spot-active");
	},
	drop: function( event, ui ) {
		that = this;
		var draggable = ui.draggable;
		
		$.ajax(
			{
				
				type:	'POST',
				url:	'/admin/modules/pages/panel.php',
				data:	{ajax: "1", dropwidget: "1", widget:draggable.attr('widget'), page:draggable.attr('page'), spot:$(that).attr('id')},
				success: function(data){
					//$(that).closest('tr').fadeOut(500,function(){
					//	$(that).closest('tr').remove();
					//});
					alert('dropped ' + draggable.attr('widget') + '');
					$(that).removeClass("wesley-spot-active");
					$(that).append(data);
					
				}
                           
			}       
		);
		/*$( this )
		.addClass( "ui-state-highlight" )
		.find( "p" )
		.html( "Dropped!" );
		*/
		
		//alert('Dropped');
	},
	out: function(event, ui)
	{
		$(this).removeClass("wesley-spot-active");
	}
});


/*		
parentHeight = $('.wesley-spot').parent().height(); 			// get parent height
parentWidth = $('.wesley-spot"').parent().width();			// get parent width

$('#wesley-spot-".$name."').css('width',parentWidth+'px');
$('#wesley-spot-".$name."').css('height',parentHeight+'px');

*/

// CMS NAV HIDE DESIGN TAB		
$('.wesley-cmsnav-design').hide();


var outlineElements = false;
// IF POINTER CLICK
$('wesley-cmsnav-pointer').click(function(e){
	e.preventDefault();
	alert('clicked');
	outlineElements = true;
});


/** MAIN OUTLINE STUFF **
	$.fn.cssPath = function() {
		var currentObject = $(this).get(0);        
		cssResult = "";
		
		while (currentObject.parentNode) {
		
			if(currentObject.id) {
				cssResult = currentObject.nodeName + '#' + currentObject.id + " " + cssResult;
				break;
			} else if(currentObject.className) {
				cssResult = currentObject.nodeName + '.' + currentObject.className + " " + cssResult;            
			} else {
				cssResult = currentObject.nodeName + " " + cssResult;            
			}
			
			currentObject = currentObject.parentNode;
		}
		return cssResult.toLowerCase();
	}

	$("*").mouseenter(function(e) {
		var value = $(this).cssPath();
		if($(this).parents().is("#wesley-cmsnav")){
			return false;
		} else {
			//$("#homo-herectus").parents().is("#australopithecus");
			$(".highlight").removeClass("highlight");
			$(this).addClass("highlight");    
		}
	});

	$("*").bind('click',function(e){
		var value = $(this).cssPath();
		if($(this).parents().is("#wesley-cmsnav")){
			return false;
		} else {
			alert(value);
			//$('#web_page_filter',top.document).val(value);
			
			
			
		}
		return false;
	});
** END MAIN OUTLINE STUFF **/

	/*
	$(document).ready(function() {
		$(document).hover(function(e) {
			alert(e.target);
			$(".highlight").removeClass("highlight");
			$(e.target).addClass("highlight");
			var id = e.target.id; // or $(e.target).attr('id');
		});
	});
	*/


/*

	var box = new Overlay();

	$("body").mouseover(function(e){
		var el = $(e.target);
		var offset = el.offset();
		box.render(el.outerWidth(), el.outerHeight(), offset.left, offset.top);
	});

	**
	 * This object encapsulates the elements and actions of the overlay.
	 *
	function Overlay(width, height, left, top) {

		this.width = this.height = this.left = this.top = 0;

		// outer parent
		var outer = $("<div class='outer' />").appendTo("body");

		// red lines (boxes)
		var topbox    = $("<div />").css("height", 1).css("border","1px solid red").appendTo(outer);
		var bottombox = $("<div />").css("height", 1).css("border","1px solid red").appendTo(outer);  
		var leftbox   = $("<div />").css("width",  1).css("border","1px solid red").appendTo(outer);
		var rightbox  = $("<div />").css("width",  1).css("border","1px solid red").appendTo(outer);

		// don't count it as a real element
		outer.mouseover(function(){ 
			outer.hide(); 
		});    

		**
		 * Public interface
		 *

		this.resize = function resize(width, height, left, top) {
		  if (width != null)
			this.width = width;
		  if (height != null)
			this.height = height;
		  if (left != null)
			this.left = left;
		  if (top != null)
			this.top = top;      
		};

		this.show = function show() {
		   outer.show();
		};

		this.hide = function hide() {
		   outer.hide();
		};     

		this.render = function render(width, height, left, top) {

			this.resize(width, height, left, top);

			topbox.css({
			  top:   this.top,
			  left:  this.left,
			  width: this.width
			});
			bottombox.css({
			  top:   this.top + this.height - 1,
			  left:  this.left,
			  width: this.width
			});
			leftbox.css({
			  top:    this.top, 
			  left:   this.left, 
			  height: this.height
			});
			rightbox.css({
			  top:    this.top, 
			  left:   this.left + this.width - 1, 
			  height: this.height  
			});

			this.show();
		};      

		// initial rendering [optional]
		// this.render(width, height, left, top);
	}
*/
	
});