/**
 * Default Vars
 */
var currentForgroundColor 	= 'FFFFFF';
var lastHeight 				= 0;
var lastWidth 				= 0;
var hexDigits 				= new Array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 


function makeWidgetDraggable(that){
	$(that).children('.wesley-cmsnav-widget').draggable(
		{
			containment: $(that),
			stop:function(event,ui)
			{
				var widget = this;		
				updateWidget(widget);
			},
			//grid: [ 1, 10 ],
			handle: ".wesley-cmsnav-widget-move",
			snap: "#grid .overlay div",
			snapMode: "both",
			snapTolerance: 5
			//scroll: true
		}
	);
}

function makeWidgetResizable(that){
	$(that).children('.wesley-cmsnav-widget').resizable({
		containment: 'parent',
		grid: [ 1, 5 ],
		alsoResize: '.also-resizable-' + $(this).attr('id') + ''
	});
}

// Update guide thing
function updateGuide(guide){
	var guidePos = $(guide).offset();

	
	// Save the guide
	$.ajax(
	{
		type:'POST',
		url:'/admin/modules/pages/panel.php',
		data:{
				ajax: "1",
				updateguide: "1",
				thing_id:$(guide).attr('thing_id'),
				top:guidePos.top,
				left:guidePos.left,
				color:'',
				resolution:$("#wesley-spot-workspace").width(),
				widget: 'guide',
				name:$(guide).attr('axis'),
				page:$("#wesley-field-page").val()
			},
		success: function(data){
			//alert();
		}
	});
}

// Update widget thing
function updateWidget(widget){
	
	// Vars
	var widgetPos = $(widget).offset();
	var wOffset = $("#wesley-spot-workspace").offset();
	var Color;
	
	// Handle hex 2 rgb
	if($(widget).css('background-color') != 'transparent'){ Color = rgb2hex($(widget).css('background-color')); }
	
	
	// Save the widget
	$.ajax(
	{
		type:'POST',
		url:'/admin/modules/pages/panel.php',
		data:{
				ajax: "1",
				updatewidget: "1",
				thing_id:$(widget).attr('thing_id'),
				top:widgetPos.top - 42,
				left:widgetPos.left - wOffset.left,
				width:$(widget).width(),
				height:$(widget).height(),
				zindex:$(widget).attr('layer'),
				color:Color,
				resolution:$("#wesley-spot-workspace").width()
			},
		success: function(data){
			//alert();
		}
	});
}

function rgb2hex(rgb) {
  rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  return "" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hex(x) {
  return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
}

//RESIZE THE EDITOR
function resizeEditor()
{
	var windowHeight = $(window).height();   // returns height of browser viewport
	var windowWidth = $(window).width();
	//$(document).height(); // returns height of HTML document
	
	var minimized = $.cookie('minimized'); 
	
	// contentFrame and Wrapper HEIGHT
	// GET THE FIRST CHILD OF BODY
	//var firstChildBody = $('body :nth-child(0)');
	var firstChildBody = $('#contentFrame');
			
	// WRAP THE THEMES INDEX CONTENT FRAME	
	if(!$('#contentFrameWrapper').length){
		firstChildBody.wrap('<div id="contentFrameWrapper" />');
	}
	var contentFrameWidth = $("#contentFrame").width();
	//var contentFrameHeight = (windowHeight * 0.75);
	
	var navHeight = 250;
	
	if(minimized == '1'){
		navHeight = 27;
	}
		
	var contentFrameHeight = 0;
	contentFrameHeight = (windowHeight - navHeight);
	
	$("#contentFrameWrapper").css("height",contentFrameHeight+"px");
	$("#contentFrameWrapper").css("width","100%");
	
	// CMS NAV HEIGHT
	//var cmsnavHeight = ((windowHeight * 0.25) - 2);
	var cmsnavHeight = 0;
	cmsnavHeight = (navHeight - 2);
	$("#wesley-cmsnav").css("height",cmsnavHeight+"px");
	
	// WIDGET MENU WIDTH
	var widgetMenuWidth = ((windowWidth * 0.25) - 1);
	$('#wesley-cmsnav-widgetmenu').css("width",widgetMenuWidth+"px");
	$('#wesley-cmsnav-css-editor').css("width",widgetMenuWidth+"px");    
	
    
	//
	var contenteditareaWidth = ((windowWidth * 0.75) - 1);
	$('#wesley-cmsnav-contenteditarea').css("width",contenteditareaWidth+"px");
    $('#wesley-cmsnav-designeditarea').css("width",contenteditareaWidth+"px");
    
}


function resizeWorkspace(){
	// SETUP SPOTS | OFFSET WESLEY SPOTS BORDER, MARGIN, PADDING
	// HEIGHT
	$('#wesley-toolbox').css('right','50px');
	$('#wesley-toolbox').css('top','50px');
	
	$('.wesley-spot').height(function(){
		return (($(this).parent().parent().height()) - 42);
	});
	
	var windowHeight = $(window).height();   // returns height of browser viewport
	var windowWidth = $(window).width();
	
	//re-put widgets
	if(windowWidth < 960 && windowWidth > 720){
		//$("")
	}
	
	
	//console.log(windowWidth+" x "+windowHeight);
	
	$('#grid').height(function(){
		return (($(this).parent().height()) - 0);
	});
}

/**
 * Setup the rulers
 */
function resizeRulers(){
	var windowHeight = $(window).height();   // returns height of browser viewport
	var windowWidth = $(window).width();
	
	
	if(windowWidth != lastWidth){
		// x ruler
		xrulerOffset = $("#wesley-spot-workspace").offset();
		$("#x-ruler").css('width',windowWidth+'px');
		$("#x-ruler").html("<div id='wesley-ruler-website-width'></div>");
		$("#wesley-ruler-website-width").css('width',($('#wesley-spot-workspace').width() - 2)+'px');
		$("#wesley-ruler-website-width").css('left', xrulerOffset.left+'px');
		
		for (var i=0; i<($('#wesley-spot-workspace').width()-5)/5; i++){
			//document.write(cars[i] + "<br>");
			$("#wesley-ruler-website-width").prepend('<div class="ruler-px"></div>');
		}
	}
	lastWidth = windowWidth;
	
	var body = document.body,
    html = document.documentElement;

	var height = Math.max( body.scrollHeight, body.offsetHeight, 
                       html.clientHeight, html.scrollHeight, html.offsetHeight );
	
	if(height != lastHeight){
		// y ruler
		$("#y-ruler").css('height',height+'px');
		$("#y-ruler-guide").css('height',height+'px');
		
		for (var i=0; i<(height-5)/5; i++){
			//document.write(cars[i] + "<br>");
			$("#y-ruler").prepend('<div class="yruler-px"></div>');
		}
	}
	lastHeight = windowHeight;
}

$(document).ready(function() {
	$("#wesley-toolbox-panel").hide();
	// COOKIE FOR THE EDITOR
	//$.cookie('minimized', '0'); 
	
	//resizeEditor();							// INITIALLY RESIZE THE EDITOR		
	//$(window).resize(resizeEditor);			// RESIZE THE EDITOR ON WINDOW RESIZE
	
	resizeRulers();
	resizeWorkspace();
	$(window).resize(resizeWorkspace);
	$(window).resize(resizeRulers);
	
	$("#x-ruler-guide").draggable({
        axis: "y",
        //cursorAt: { top: -12, left: -20 },
        helper: function( event ) {
        	return $( "<div class='wesley-x-guide' thing_id='' axis='x'></div>" );
        },
        drag: function(event, ui) {

        },
        stop: function(event, ui) {
        	
        	//yPos = $(ui.helper).css('top') + 21;
        	var newGuide = $(ui.helper).clone();
        	$("body").prepend(newGuide);
        	$(newGuide).draggable({
        		axis: "y"
        	});
        	
        	
        	// TODO...
        	// Save guide as a thing for this page
        	updateGuide(newGuide);
        }
    });

	
	$("#y-ruler-guide").draggable({
        axis: "x",
        //cursorAt: { top: -12, left: -20 },
        helper: function( event ) {
        	return $( "<div class='wesley-y-guide' thing_id='' axis='y'></div>" );
        },
        drag: function(event, ui) {
        	
        },
        stop: function(event, ui) {
        	
        	var newGuide = $(ui.helper).clone();
        	$("#wesley-spot-workspace").prepend(newGuide);
        	
        	newGuideOffset = $(newGuide).offset();
        	workSpaceOffset = $("#wesley-spot-workspace").offset();
        	newOffset = newGuideOffset.left - (workSpaceOffset.left*2);
        	
        	console.log('newGuideOffset: '+newGuideOffset.left);
        	console.log('workSpaceOffset: '+workSpaceOffset.left);
        	console.log('newOffset: '+newOffset);
        	
        	$(newGuide).css("left", newOffset);
        	
        	$(newGuide).draggable({
        		axis: "x"
        			
        			// TODO...
        			// Save the guide on every movement....
        			
        	});
        	
        	
        	// TODO...
        	// Save guide as a thing for this page
        	updateGuide(newGuide);
        }
    });
	
	
	//$(window).scroll(resizeWorkspace);
	
	/*
	// MINIMIZE EDITOR BUTTONS
	$('.wesley-cmsnav-minimize').click(function(e){
		e.preventDefault();
		$('.wesley-cmsnav').addClass('wesley-cmsnav-minimized');
		$.cookie('minimized', '1',{ expires: 10 });
        $(this).css('background-image','url(/admin/modules/pages/images/icons/color_18/directional_down.png)');
        $('.wesley-cmsnav-maximize').css('background-image','url(/admin/modules/pages/images/icons/gray_18/directional_up.png)');
        resizeEditor();
	});
	
	// MAXIMIZE EDITOR BUTTONS
	$('.wesley-cmsnav-maximize').click(function(e){
		e.preventDefault();
		$('.wesley-cmsnav').removeClass('wesley-cmsnav-minimized');
		$.cookie('minimized', '0',{ expires: 10 });
        $(this).css('background-image','url(/admin/modules/pages/images/icons/color_18/directional_up.png)');
        $('.wesley-cmsnav-minimize').css('background-image','url(/admin/modules/pages/images/icons/gray_18/directional_down.png)');
		resizeEditor();
	});
	*/
    
	
	// HOVER OVER EDITOR - CANCEL SPOT ACTIVE CLASS
	//$('.wesley-cmsnav').hover(
	$('.wesley-toolbox').hover(
			function(){
			$('.wesley-spot').removeClass('wesley-spot-active');
		},
		function(){
			
		}
	);
	
	
	
	
	
	
	// WIDTH
	//$('.wesley-spot').width(function(){
	//	return (($(this).parent().width()) - 0);
	//});
	
	// PARENT OVERFLOW
	//$('.wesley-spot').parent().css('overflow','visible');
	
	/* */
	// toolbox color picker
	$('#colorSelector').ColorPicker({
		color: '#ffffff',
		onShow: function (colpkr) {
			$(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			$(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			$('#colorSelector div').css('backgroundColor', '#' + hex);
			currentForgroundColor = hex;
		}
	});
	
	// make the toolbox draggable
	$( "#wesley-toolbox" ).draggable({
		handle: "#wesley-toolbox-widgetstitle"
	}); //... nope, not yet
	
	// make the toolbox panel draggable
	$( "#wesley-toolbox-panel" ).draggable({
		handle: "#wesley-toolbox-panel-title"
	}); //... nope, not yet
	
	
	
	
	
	// prevent clicking on a toolbox item
	$( "#wesley-toolbox-toolmenu-tools a" ).live('click',function(e){
		e.preventDefault();
	});
	
	$( "#wesley-filemenu-handle-open" ).click(function(){
		$( "#wesley-filemenu" ).css('left','0px');
		$( "#wesley-filemenu-handle-open" ).hide();
		$( "#wesley-filemenu-handle-close" ).show();
	});
	
	$( "#wesley-filemenu-handle-close" ).click(function(){
		$( "#wesley-filemenu" ).css('left','-99%');
		$( "#wesley-filemenu-handle-close" ).hide();
		$( "#wesley-filemenu-handle-open" ).show();
	});
	
	// DRAGGABLE WIDGET IN CMS EDITOR
	//$( "#wesley-cmsnav-widgetmenu a" ).live('click',function(){
	//	e.preventDefault();
	//});
	
	
	//$( "#wesley-cmsnav-widgetmenu a" ).draggable({
	$( "#wesley-toolbox-toolmenu-tools li[type='draggable']" ).draggable({
		revert: "invalid",
		containment: $( "body" ).length ? "body" : "document",
		helper: "clone",
		cursor: "move"
	});

	// PREVENT DROPPING WIDGET ONTO CMS EDITOR
	$( "#wesley-cmsnav" ).droppable({
		accept: "#wesley-cmsnav-widgetmenu a",
		greedy: true,
		over: function(event,ui)
		{
			$(".wesley-spot").removeClass('wesley-spot-active');
		}
	});

	
	
	/**
     * Droppable workspace / spot
     */
	$( ".wesley-spot" ).droppable({
		/*accept: "#wesley-cmsnav-widgetmenu a",*/
		accept: "#wesley-toolbox-toolmenu-tools li[type='draggable']",
		greedy: true,
		over: function( event, ui ){
			//formatWidgetsOver(this);
			//var currentHeight = $(this).height();
			//$(this).height(currentHeight+100);
			$(this).addClass("wesley-spot-active");					// ADD HOVER CLASS TO SPOT
			//$(this).append('<div class="ui-sortable-placeholder wesley-cmsnav-widget active-widget" > </div>');
		},
		drop: function( event, ui ){
			that = this;
			var draggable = ui.draggable;
			var thisWidgetSpotPage = '';
			
			
			// IF THIS SPOT HAS EVERYPAGE CLASS THEN WIDGET RESIDES IN THIS SPOT ON EVERY PAGE
			//if($(that).hasClass('everypage')){
			//	thisWidgetSpotPage = 'everypage';
			//}
			// ELSE WIDGET RESIDES IN THIS SPOT ON JUST THIS PAGE
			//else {
				thisWidgetSpotPage = draggable.attr('page');
			//}
			/**
			 * Save the drop 
			 */
			$.ajax(
				{
					type:	'POST',
					url:	'/admin/modules/pages/panel.php',
					data:	{
								ajax: "1",
								dropwidget: "1",
								widget:draggable.attr('widget'),
								widgetpath:draggable.attr('widgetpath'),
								page:thisWidgetSpotPage,
								spot:$(that).attr('id'),
								width: 0,
								height: 0,
								top: 0,
								left: 0,
								color: currentForgroundColor
							},
					success: function(data){
						$(that).removeClass("wesley-spot-active");	// Remove hover class from active
						$(that).prepend(data);						// Add the widget via drag/drop
						$(that).height($(that).parent().height());	// resize workspace to parent height
						
						// Make the widget drop draggable
						$(that).children('.wesley-cmsnav-widget').draggable(
							{
								/*containment: $(that),*/
								stop:function(event,ui)
								{
									//var top = $(this).css('top');
									//var left = $(this).css('left');
									//$(this).attr('top',top);
									//$(this).attr('left',left);
									//$(this).attr('style','position:absolute;');
									//$(this).css('position','absolute');
									var widget = this;		
									//updateWidgetSizeAndPosition(widget);
								},
								handle: ".wesley-cmsnav-widget-move",
								snap: "#grid .overlay div",
								snapMode: "both",
								snapTolerance: 5
							}
						);
						
						/*
						$(that).children('.wesley-cmsnav-widget').resizable(
							{
								containment: 'parent',
								//containment: $(that),
								handles: 'e,se,s',
								grid: [ 2, 10 ],
								alsoResize: '.also-resizable-' + $(this).attr('id') + '',
								create:function(event,ui){
									$(this).css('position','relative');							// SET NEW WIDGET ELEMENT TO RELATIVE POSITION
									newWidgetWidth = $(this).width();							// NEW WIDGET ELEMENT WIDTH
									
									// LOOP THROUGH ALL CHILDREN
									$(that).children('.wesley-cmsnav-widget').each(function()
									{	
										var adjustLeft = $(this).css('left').replace('px','');	// GET THE LEFT AMOUNT FOR EACH WIDGET
										adjustLeft = (adjustLeft - newWidgetWidth);				// SUBTRACT THE NEW ELEMENT WIDTH
										$(this).css('left',adjustLeft+"px");					// SET THE NEW LEFT POSITION
										$(this).attr('left',adjustLeft+"px");					// SET THE NEW LEFT ATTR
									});
									
									// SET THE NEW WIDGET ELEMENT TO 0
									$(this).css('top','0px');
									$(this).css('left','0px');
									$(this).attr('left','0px');
									$(this).attr('top','0px');
									$('.wesley-cmsnav-widget').removeClass('active-widget');
									$(this).addClass('active-widget');
								},
								start:function(event,ui){								
									var flag = 0;
									var addleft = 0;
									var thisId = $(this).attr('id');
									
									// PRESERVE ASPECT RATION ON SE - TODO
									if ($(event.originalTarget).hasClass("ui-resizable-se")) {
										// Keep aspect ratio function
									}
									
									// LOOP THROUGH THE CHILDREN WIDGETS
									$(that).children('.wesley-cmsnav-widget').each(function(){										
										if(flag == 1){										
											var adjustLeft = $(this).css('left').replace('px','');		// GET ALL WIDGETS AFTER CURRENT WIDGET LEFT VALUE
											newWidth = parseInt(adjustLeft) + addleft;					// ADD LEFT TO THE PREVIOUS WIDGET SIZE
											$(this).css('left',newWidth+'px');							// SET NEW LEFT
											$(this).attr('left',newWidth+'px');							// SET NEW LEFT ATTR
										}									
										// IF IT IS THIS WIDGET THEN FLAG
										if($(this).attr('id') == thisId){
											flag = 1;
											addleft = $(this).width();
										}									
									});
								},
								resize:function(){
									//$(this).children('.wesley-cmsnav-widget-content').text('Left: '+$(this).attr('left')+', Top: '+$(this).attr('top')+'');
								},
								stop:function(){
									var flag = 0;
									var addleft = 0;
									var thisId = $(this).attr('id');
									
									$(that).children('.wesley-cmsnav-widget').each(function(){
										if(flag == 1){
											var adjustLeft = $(this).css('left').replace('px','');
											newWidth = parseInt(adjustLeft) - addleft;
											$(this).css('left',newWidth+"px");
											$(this).attr('left',newWidth+"px");
										}
										// IF IT IS THIS WIDGET THEN FLAG
										if($(this).attr('id') == thisId){
											flag = 1;
											addleft = $(this).width();
										}
										
										var widget = this;	
										updateWidgetSizeAndPosition(widget);				// UPDATE WIDGET SIZE AND POSITION									
										
									});
									
									// ON STOP RESET TO PREVIOUS POSITION
									$(this).css('position','relative');
									$(this).css('top',$(this).attr('top'));
									$(this).css('left',$(this).attr('left'));
									
									
								}
							}						    
						);
                        */
						
                        //$('.wesley-cmsnav-widget').draggable('disable');        // DISABLE DRAGGABLES
                        //$('.wesley-cmsnav-widget').resizable('disable');        // DISABLE RESIZABLES
                        
					}   
				}       
			);
		},
		out: function(event, ui)
		{
			$(this).removeClass("wesley-spot-active");
			//$(this).children('.ui-sortable-placeholder').remove();
		}
	});

	// WIDGET CLICK - z-index - OPEN WIDGET PANEL
    var load = 0;
	$('.wesley-cmsnav-widget').live('mousedown',function(e){
		
		var panelpath = $(this).attr('panelpath');
		var widgetId = $(this).attr('id');
        
        // check if the cookie exists
        //if(!$)
        //load = 0;
		// OPEN WIDGET PANEL
        //$.cookie('wesley-cmsnav-tab', 'content',{ expires: 10 });
        // IF MAIN CMSNAV TAB IS "CONTENT"
        //if($.cookie('wesley-cmsnav-tab') == 'content')
        //{
            $('.wesley-cmsnav-widget').removeClass('active-widget');
            $(this).addClass('active-widget');
            
            // IF NOT CLICKING THE SAME WIDGET
            if($.cookie('wesley-widget-panel') != widgetId || load == 0)
            {
                $.ajax(
                {
                    type:	'POST',
                    url:	panelpath,
                    data:	{ widget_id: widgetId },
                    success: function(data){
                        //$('#wesley-cmsnav-contenteditarea').html(data);
                    	//$("body").prepend("<div id='wesley-toolbox-panel-"+widgetId+"' class='toolbox-panel'></div>")
                        //$("#wesley-toolbox-panel-content").html(data);
                        //$("#wesley-toolbox-panel").show();
                    }
                });
                load = 1;
            }
        //}
        
        // SET THE COOKIE AFTER THE CHECK
        $.cookie('wesley-widget-panel', widgetId , { expires: 10 });
	});
	
	// WIDGET REMOVE 
	$('a.wesley-cmsnav-widget-close').live('click',function(e){
		e.preventDefault();
		var widget = $(this).parents('.wesley-cmsnav-widget');
		var that = this;
		var spot = widget.parents('.wesley-spot');
		var id = widget.attr('id');
		//alert(id);
		// DELETE WIDGET
		$.ajax(
				{
					type:	'POST',
					url:	'/admin/modules/pages/panel.php',
					data:	{
								ajax: "1",
								deletewidget: "1",
								thing_id:id
							},
					success: function(data){
						
						// ADUST ALL WIDGETS POSITION 
						
						var flag = 0;
						var addleft = 0;
						// LOOP THROUGH THE CHILDREN WIDGETS
						spot.children('.wesley-cmsnav-widget').each(function(){										
							//if(flag == 1){										
							//	var adjustLeft = $(this).css('left').replace('px','');		// GET ALL WIDGETS AFTER CURRENT WIDGET LEFT VALUE
							//	newWidth = parseInt(adjustLeft) + addleft;					// ADD LEFT TO THE PREVIOUS WIDGET SIZE
							//	$(this).css('left',newWidth+'px');							// SET NEW LEFT
							//	$(this).attr('left',newWidth+'px');							// SET NEW LEFT ATTR
							//}									
							// IF IT IS THIS WIDGET THEN FLAG
							if($(this).attr('id') == id){
								flag = 1;
							//	addleft = $(this).width();
								//$(this).fadeOut(500,function(){$(this).remove();});
								$(this).remove();
								//alert(addleft);
							}									
						});
						
						
						// REMOVE THE WIDGET
						//$(that).parents('.wesley-cmsnav-widget').fadeOut(500,function(){$(this).remove();});
					}
				}
		);
	});
	
	// WIDGET MOVE 
	$('a.wesley-cmsnav-widget-move').live('click',function(e){
		e.preventDefault();
		/*
		var widget = $(this).parents('.wesley-cmsnav-widget');
		var that = this;
		var spot = widget.parents('.wesley-spot');
		var id = widget.attr('id');
       
		//alert($.cookie('resize-widget-' + id + ''));
		if($.cookie('move-widget-' + id + '') == 1){
            $('#' + id + '.wesley-cmsnav-widget a.wesley-cmsnav-widget-move').css('background-image','url(/admin/modules/pages/images/icons/gray_18/arrow_bidirectional.png)');
            $('#' + id + '.wesley-cmsnav-widget').draggable('disable');              // DISABLE DRAGGABLES
            //$('.wesley-cmsnav-widget').resizable('disable');                // DISABLE RESIZABLES
            $.cookie('move-widget-' + id + '',0, { expires:10 } );
            widget.removeClass('movemode');
        } else {
            $('#' + id + '.wesley-cmsnav-widget a.wesley-cmsnav-widget-move').css('background-image','url(/admin/modules/pages/images/icons/color_18/arrow_bidirectional.png)');
            $('#' + id + '.wesley-cmsnav-widget').draggable('enable');            // ENABLE DRAGGABLES
            //$('#' + id + '.wesley-cmsnav-widget').resizable('enable');              // ENABLE RESIZABLES
            $.cookie('move-widget-' + id + '',1, { expires:10 } );
            widget.addClass('movemode');
        }*/
	});
	
    // WIDGET RESIZE 
	$('a.wesley-cmsnav-widget-resize').live('click',function(e){
		e.preventDefault();
		/*
		var widget = $(this).parents('.wesley-cmsnav-widget');
		var that = this;
		var spot = widget.parents('.wesley-spot');
		var id = widget.attr('id');
       
		//alert($.cookie('resize-widget-' + id + ''));
		if($.cookie('resize-widget-' + id + '') == 1){
            $('#' + id + '.wesley-cmsnav-widget a.wesley-cmsnav-widget-resize').css('background-image','url(/admin/modules/pages/images/icons/gray_18/dimensions.png)');
            //$('.wesley-cmsnav-widget').draggable('disable');              // DISABLE DRAGGABLES
            $('#' + id + '.wesley-cmsnav-widget').resizable('disable');                // DISABLE RESIZABLES
            $.cookie('resize-widget-' + id + '',0, { expires:10 } );
            widget.removeClass('resizemode');
        } else {
            $('#' + id + '.wesley-cmsnav-widget a.wesley-cmsnav-widget-resize').css('background-image','url(/admin/modules/pages/images/icons/color_18/dimensions.png)');
            //$('#' + id + '.wesley-cmsnav-widget').draggable('enable');            // ENABLE DRAGGABLES
            $('#' + id + '.wesley-cmsnav-widget').resizable('enable');              // ENABLE RESIZABLES
            $.cookie('resize-widget-' + id + '',1, { expires:10 } );
            widget.addClass('resizemode');
        }
        */
	});
    
    // GET CURRENT PAGE WIDGETS FOR EACH SPOT
	$('.wesley-spot').each(function(){
		var that = this;
		getIndividualSpotsWidgets(that);
		
		/* THIS HAS BEEN MOVED INTO THE getIndividualSpotsWidgets() FUNCTION
		var everypage = '';
		if($(this).hasClass('everypage')){
			everypage = 'true';
		}
		// AJAX CURRENT WIDGETS
		$.ajax(
			{
				type:	'POST',
				url:	'/admin/modules/pages/panel.php',
				data:	{
							ajax: "1",
							getwidgets: "1",
							page:$("#wesley-field-page").val(),
							spot:$(this).attr('id'),
							everypage: everypage
						},
				success: function(data){
					$(that).prepend(data);
					
					
					$(that).children('.wesley-cmsnav-widget').draggable(
					{
						containment: $(that),
						stop:function(event,ui)
						{
							var top = $(this).css('top');
							var left = $(this).css('left');
							$(this).attr('top',top);
							$(this).attr('left',left);
							widget = this;
							updateWidgetSizeAndPosition(widget);
						}
					});
					
					
					
					$(that).children('.wesley-cmsnav-widget').resizable(
					{
						containment: 'parent',
						//containment: $(that),
						handles: 'e,se,s',
						alsoResize: '.also-resizable-' + $(this).attr('id') + '',
						start:function(event,ui){								
							var flag = 0;
							var addleft = 0;
							var thisId = $(this).attr('id');
							
							// LOOP THROUGH THE CHILDREN WIDGETS
							$(that).children('.wesley-cmsnav-widget').each(function(){										
								if(flag == 1){										
									var adjustLeft = $(this).css('left').replace('px','');		// GET ALL WIDGETS AFTER CURRENT WIDGET LEFT VALUE
									newWidth = parseInt(adjustLeft) + addleft;					// ADD LEFT TO THE PREVIOUS WIDGET SIZE
									$(this).css('left',newWidth+'px');							// SET NEW LEFT
									$(this).attr('left',newWidth+'px');							// SET NEW LEFT ATTR
								}									
								// IF IT IS THIS WIDGET THEN FLAG
								if($(this).attr('id') == thisId){
									flag = 1;
									addleft = $(this).width();
								}									
							});
						},
						resize:function(){
							//$(this).children('.wesley-cmsnav-widget-content').text('Left: '+$(this).attr('left')+', Top: '+$(this).attr('top')+'');
						},
						stop:function(){
							var flag = 0;
							var addleft = 0;
							var thisId = $(this).attr('id');
							
							$(that).children('.wesley-cmsnav-widget').each(function(){
								if(flag == 1){
									var adjustLeft = $(this).css('left').replace('px','');
									newWidth = parseInt(adjustLeft) - addleft;
									$(this).css('left',newWidth+"px");
									$(this).attr('left',newWidth+"px");
								}
								// IF IT IS THIS WIDGET THEN FLAG
								if($(this).attr('id') == thisId){
									flag = 1;
									addleft = $(this).width();
								}
								
								var widget = this;
								updateWidgetSizeAndPosition(widget);
							});
							
							// ON STOP RESET TO PREVIOUS POSITION
							$(this).css('position','relative');
							$(this).css('top',$(this).attr('top'));
							$(this).css('left',$(this).attr('left'));
							
						}
					});
				}		
			}
		);
		*/
	});
	
	// GET AN INDIVIDUAL SPOTS WIDGETS
	function getIndividualSpotsWidgets(that){
		// AJAX CURRENT WIDGETS
		$.ajax({
			type:	'POST',
			url:	'/admin/modules/pages/panel.php',
			data:	{
						ajax: "1",
						getwidgets: "1",
						page:$("#wesley-field-page").val(),
						spot:$(that).attr('id')
					},
			success: function(data){
				$(that).prepend(data); // ADD THE DATA TO THE SPOT
                
                // MAKE IT DRAGGABLE
				makeWidgetDraggable(that);
				makeWidgetResizable(that);
				
				/*
                // MAKE IT RESIZABLE
				$(that).children('.wesley-cmsnav-widget').resizable(
				{
					containment: 'parent',
					//containment: $(that),
					handles: 'e,se,s',
					alsoResize: '.also-resizable-' + $(this).attr('id') + '',
					start:function(event,ui){								
						var flag = 0;
						var addleft = 0;
						var thisId = $(this).attr('id');
						
						// PRESERVE ASPECT RATION ON SE - TODO
						if ($(event.originalTarget).hasClass("ui-resizable-se")) {
							// Keep aspect ratio function
						}
						
						// LOOP THROUGH THE CHILDREN WIDGETS
						$(that).children('.wesley-cmsnav-widget').each(function(){										
							if(flag == 1){										
								var adjustLeft = $(this).css('left').replace('px','');		// GET ALL WIDGETS AFTER CURRENT WIDGET LEFT VALUE
								newWidth = parseInt(adjustLeft) + addleft;					// ADD LEFT TO THE PREVIOUS WIDGET SIZE
								$(this).css('left',newWidth+'px');							// SET NEW LEFT
								$(this).attr('left',newWidth+'px');							// SET NEW LEFT ATTR
							}									
							// IF IT IS THIS WIDGET THEN FLAG
							if($(this).attr('id') == thisId){
								flag = 1;
								addleft = $(this).width();
							}									
						});
					},
					resize:function(){
						//$(this).children('.wesley-cmsnav-widget-content').text('Left: '+$(this).attr('left')+', Top: '+$(this).attr('top')+'');
					},
					stop:function(){
						var flag = 0;
						var addleft = 0;
						var thisId = $(this).attr('id');
						
						$(that).children('.wesley-cmsnav-widget').each(function(){
							if(flag == 1){
								var adjustLeft = $(this).css('left').replace('px','');
								newWidth = parseInt(adjustLeft) - addleft;
								$(this).css('left',newWidth+"px");
								$(this).attr('left',newWidth+"px");
							}
							// IF IT IS THIS WIDGET THEN FLAG
							if($(this).attr('id') == thisId){
								flag = 1;
								addleft = $(this).width();
							}
							
							var widget = this;
							updateWidget(widget);
						});
						
						// ON STOP RESET TO PREVIOUS POSITION
						$(this).css('position','relative');
						$(this).css('top',$(this).attr('top'));
						$(this).css('left',$(this).attr('left'));
						
					}
				});
                */
                
                
                // INITIALLY DISSABLE THE DRAGGABLE & RESIZABLE
                //$('.wesley-cmsnav-widget').draggable('disable');        // DISABLE DRAGGABLES
                //$('.wesley-cmsnav-widget').resizable('disable');        // DISABLE RESIZABLES
                
                //$.cookie('resize-widget-' + $(this).attr('id') + '',0, { expires:10 } );
                //$.cookie('move-widget-' + $(this).attr('id') + '',0, { expires:10 } );
			}		
		});
	}
	
	
	
	// PENCIL IMAGE FOR AJAX INPUT AND TEXTAREA
	$('.ajax-widget-input').live('focusin', function(){
		$(this).next('img').remove(); // remove anything if its there
		$(this).after('<img src="/admin/images/pencil.png" class="edit-pencil" />'); 
	});
        
	// TEXTAREA, INPUT FOCUS OUT
	$('.ajax-widget-input').live('focusout', function(){
		ajaxSaveWidgetThingyInput(this);
	}).live('keypress', function(e){
		if(e.keypress == 9 || e.keypress == 13)
		{
			ajaxSaveWidgetThingyInput(this);
		}        
	});
    
    // TEXTAREA, INPUT FOCUS OUT
	$('.ajax-input').live('focusout', function(){
		ajaxSaveInput(this);
	}).live('keypress', function(e){
		if(e.keypress == 9 || e.keypress == 13)
		{
			ajaxSaveInput(this);
		}        
	});
    
	// It basically lets you define a block of code that will 
	// execute in 'x' milliseconds, unless the same block is 
	// called again- in which case the timer resets. 
	function delayTimer(delay){
		var timer;
		return function(fn){
			timer=clearTimeout(timer);
			if(fn)
				timer=setTimeout(function(){
					fn();
				},delay);
			return timer;
		}
	}

	// AJAX FOR WIDGET PANELS
	// GENERIC AJAX INPUT SAVE THINGS AND THINGYS // WORKS WITH CURRENT MODULE 
	function ajaxSaveWidgetThingyInput(that)
	{
		var parent_id       =   $(that).attr('parent_thing_id');
		var name	        =   $(that).attr('thing_name');
		var column_name		= 	$(that).attr('column_name');
		var fieldvalue      =   $(that).val();
   
			$(that).next('img').remove();  // remove pencil
			$(that).after('<img src="/admin/images/loader.gif" class="loader"/>');
	
		// AJAX POSTS TO THE CURRENT MODULE'S PANEL
		$.ajax({
			type: 'POST',
			url: '/admin/modules/pages/panel.php',
			data: { ajax: "1", saveWidgetThingy: "1", parentId: parent_id, thingName: name, value: fieldvalue, columnName: column_name },
			success: function(data){
				$(that).next('img').remove(); // remove loader
				$(that).after('<img src="/admin/images/accept.png" class="edit-pencil" />');
				$(that).next('img').fadeOut(1000,function(){ $(this).remove(); });
			}
		});
	}
	
    // GENERIC AJAX INPUT SAVE // WORKS WITH CURRENT MODULE // COPIED && ALTERED FROM /admin/scripts/functions.js.php FOR THE WEBSITE EDITOR
    function ajaxSaveInput(that)
    {
        var item_id         =   $(that).attr('xid');
        var sqltable        =   $(that).attr('table');
        var sqlcolumn       =   $(that).attr('column');
        var fieldtype       =   $(that).attr('settingtype');
        var tableid	        =   $(that).attr('columnid');
        var fieldvalue      =   $(that).val();
   
        $(that).next('img').remove();  // remove pencil
        $(that).after('<img src="/admin/images/loader.gif" class="loader"/>');
        
        // AJAX POSTS TO THE CURRENT MODULE'S PANEL
        $.ajax({
            type: 'POST',
            url: '/admin/modules/pages/panel.php',
            data: { ajax: "1", save: "1", id: item_id, table: sqltable, column: sqlcolumn, type: fieldtype, value: fieldvalue, columnid: tableid },
            success: function(data){
                
                $(that).next('img').remove(); // remove loader
                $(that).after('<img src="/admin/images/accept.png" class="edit-pencil" />');
                $(that).next('img').fadeOut(1000,function(){ $(this).remove(); });
            
            }
        });
    }
    
    // TEXTAREA, INPUT TYPING TIMER
	var inputDelayer=delayTimer(800);
    
    // MAIN WIDGET OPTIONS USES PARENT ID AND OPTION NAME
	//$('.ajax-widget-input').keyup(function(){
	$('.ajax-widget-input').live('keyup',function()
    {
		var that = this
		inputDelayer(function(){
			ajaxSaveWidgetThingyInput(that);
		});
	});
    
    // ALL OTHER THINGS AND THINGYS (LIKE THINGY OPTIONS (SLIDE TEXT))
    $('.ajax-input').live('keyup',function()
    {
		var that = this
		inputDelayer(function(){
			ajaxSaveInput(that);
		});
	});
    
	

	// LAST CMS NAV TAB
	// IF DESIGN THEN SETUP DESIGN MODE
	if($.cookie('wesley-cmsnav-tab') == 'design')
	{
		$('a#wesley-cmsnav-design-tab').ajaxStop(function(){
			if($.cookie('wesley-cmsnav-tab') == 'design')
			{
				designMode("","a#wesley-cmsnav-design-tab");
				//alert('yo');
			}
		});
	} else {
        // INITIAL CMS NAV HIDE DESIGN TABS
        $('#wesley-cmsnav-content').show();
        $('a#wesley-cmsnav-content-tab').addClass('wesley-cms-active-tab');
        $('#wesley-cmsnav-design').hide();
    }
	
    
    
	// CLICK CONTENT TAB
	$('a#wesley-cmsnav-content-tab').click(function(e)
    {
		e.preventDefault();                                                                     // PREVENT LINK
		$(this).addClass('wesley-cms-active-tab');                                              // ADD ACTIVE STATE TO THIS TAB
		$(this).closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');    // REMOVE ACTIVE STATE ON OTHER TABS
		$('#wesley-cmsnav-content').show();                     // SHOW CONTENT TAB
		$('#wesley-cmsnav-design').hide();                      // HIDE DESIGN TAB
		$('.wesley-spot').removeClass("wesley-spot-inactive");  // REMOVE WESLEY SPOT INACTIVE CLASS 
        //$('.wesley-cmsnav-widget').draggable('enable');         // ENABLE DRAGGABLES
        //$('.wesley-cmsnav-widget').resizable('enable');         // ENABLE RESIZABLES
        $('.wesley-cmsnav-widget').addClass('cms-widget');      // ADD CMS-WIDGET CLASS (BORDER AND DROP SHADOW)
        $.cookie('wesley-cmsnav-tab', 'content',{ expires: 10 });	// SET COOKIE
		$(".hoverhighlight").removeClass("hoverhighlight");			// REMOVE ALL HOVERHIGHLIGHTS
        $('.wesley-cmsnav-widget-toolbar').show();                  // SHOW TOOLBAR
        $('a.wesley-cmsnav-pointer').css('background-image','url(/admin/modules/pages/images/icons/gray_18/target.png)');   // SHUT OFF POINTER
        $.cookie('pointer', 0,{ expires: 10 });	// SET COOKIE                                                               // SHUT OFF POINTER
	});

	// CLICK DESIGN TAB
	$('a#wesley-cmsnav-design-tab').click(function(e)
    {
		designMode(e,this);
	});
	
	// DESIGN MODE FUNCTION
    function designMode(e,that){
		if(e != ""){
			e.preventDefault();                                                                     // PREVENT LINK
		}
		$(that).addClass('wesley-cms-active-tab');                                              // ADD ACTIVE STATE TO THIS TAB
		$(that).closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');    // REMOVE ACTIVE STATE ON OTHER TABS
		$('#wesley-cmsnav-content').hide();                     // HIDE CONTENT TAB
		$('#wesley-cmsnav-design').show();                      // SHOW DESIGN TAB
		$('.wesley-spot').addClass("wesley-spot-inactive");     // DISABLE SPOT HIGHLIGHTING
        $('.wesley-cmsnav-widget').draggable('disable');        // DISABLE DRAGGABLES
        $('.wesley-cmsnav-widget').resizable('disable');        // DISABLE RESIZABLES
        $('.wesley-cmsnav-widget').removeClass('cms-widget');   // REMOVE CMS-WIDGET CLASS (BORDER AND DROP SHADOW)
		$.cookie('wesley-cmsnav-tab', 'design',{ expires: 10 });// SET COOKIE
        $('.wesley-cmsnav-widget-toolbar').hide();              // HIDE TOOLBAR
        $('.wesley-cmsnav-widget').removeClass('active-widget');// REMOVE ACTIVE WIDGETS
        
	}
	
	
    // IF POINTER / TARGET ICON CLICK GO TO DESIGN MODE
	$('a.wesley-cmsnav-pointer').click(function(e){
        e.preventDefault();
		var pointerStatus = $.cookie('pointer');
        if(pointerStatus == 1){
            $.cookie('pointer', 0,{ expires: 10 });	// SET COOKIE
            $(this).css('background-image','url(/admin/modules/pages/images/icons/gray_18/target.png)');
        } else {
            $.cookie('pointer', 1,{ expires: 10 });	// SET COOKIE
            $(this).css('background-image','url(/admin/modules/pages/images/icons/color_18/target.png)');
            designMode("","a#wesley-cmsnav-design-tab");
        }
	});
    
    // INITIAL POINTER SETTING
    var pointerStatus = $.cookie('pointer');
    if(pointerStatus == 1){
        $('a.wesley-cmsnav-pointer').css('background-image','url(/admin/modules/pages/images/icons/color_18/target.png)');
        designMode("","a#wesley-cmsnav-design-tab");
    }

	
    
    

    
    //$("#wesley-html-editor").val(theDom);
    // THIS COOKIE IS TO MAKE SURE THE FUNCTION THAT
    // CREATES THE CODE MIRROR RUNS ONLY ONCE AFTER THE WIDGETS ARE ALL CREATED
    //$.cookie('widgetsloaded',0,{ expires: 10 });    
    
    // SET UP THE HTML AND CSS EDITOR
    //    if($.cookie('widgetsloaded') == 0)
    //    {
    //       $.cookie('widgetsloaded',1,{ expires: 10 });
            var theDom = $('#contentFrameWrapper').html();
            
            //theDom = js_beautify(theDom);
            // STYLE WITH JSBUEATIFY!
            theDom =    style_html(theDom, {
                            'indent_size': 2,
                            'indent_char': ' ',
                            'max_char': 78,
                            'brace_style': 'expand',
                            /*'unformatted': ['a', 'sub', 'sup', 'b', 'i', 'u']*/
                            'unformatted': ['sub', 'sup', 'b', 'i', 'u']
                        });
            $("#wesley-html-editor-html").val(theDom);
            $("#wesley-html-editor-html").focus();
            
            /*var htmlEditor =    CodeMirror.fromTextArea(document.getElementById("wesley-html-editor-html"), {
                                    mode: "text/html",
                                    height: "197px",
                                    lineNumbers: true,
                                    readOnly: true
                                });*/
            
            // <PRE> ELEMENTS IN THE HTML EDITOR WILL NOT WORK WITH THE TARGETING TOOL IF IT HASN"T BEEN VISIBLE YET (scrolled over)
            //setTimeout(function(){
            //    //var offset = $('.CodeMirror-scroll').
            //    $('.CodeMirror-scroll').scrollTo($('.CodeMirror-scroll'), 5000, {axis:'y',offset:10000 });
            //},1000);

    
    //$.cookie('fireId',1000,{expires:1});
    
    function addFireId(that)
    {
        var fireId = Math.random();
        //fireId++;
        //$.cookie('fireId',fireId,{expires:1})
        $(that).children().each(function(){
            fireId = Math.random();
            $(this).attr('wesley-id',fireId);
            if($(this).children().size() > 0)
            {
                fireId = Math.random();
                addFireId(this);
                //i++;
            }
            //i++;
        });
    }
    
    $('#contentFrameWrapper').ajaxStop(function()
    {
        
        if(htmlEditor)
        {
            //alert('yup');
            
            
        
            
            addFireId('#contentFrameWrapper');
            
            
            theDom = $('#contentFrameWrapper').html();
            //theDom = js_beautify(theDom);
            // STYLE WITH JSBUEATIFY!
            theDom =    style_html(theDom, {
                            'indent_size': 2,
                            'indent_char': ' ',
                            'max_char': 78,
                            'brace_style': 'expand',
                            /*'unformatted': ['a', 'sub', 'sup', 'b', 'i', 'u']*/
                            'unformatted': ['sub', 'sup', 'b', 'i', 'u']
                        });
            htmlEditor.setValue(theDom);
        }
    
    });
    
    
    
    
    
    // GET CSS PATH (ID / CLASS)
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

    // HOVER / SELECTOR / ELEMENT TARGETING
	$("body").on("mouseover", function(event){
		//alert( $(this).text() );
        // prevent targeting in the editor
		if($(event.target).parents().is("#wesley-cmsnav") || $(event.target).is("#wesley-cmsnav")){
			return false;
		} else {
			var tab = $.cookie('wesley-cmsnav-tab');
			if(tab == 'design'){
                var pointerStatus = $.cookie('pointer');
                if(pointerStatus == 1){
                    //$("#wesley-cmsnav-design").html("#" + event.target.id + " <br> ." + event.target.class + "");
                    $(".hoverhighlight").removeClass("hoverhighlight");
                    
                    // CHECK FOR A PARENT WIDGET
                    //var widgetParent = $(event.target).parents('.wesley-cmsnav-widget');
                    //if(widgetParent.length){
                    //    $(event.target).closest('.wesley-cmsnav-widget').addClass("hoverhighlight");
                    //} else {
                        $(event.target).addClass("hoverhighlight");
                    //}
                    
                    
                    // CHECK FOR A MATCH IN THE CODE MIRROR
                    var match = 0;
                    var id = "";
                    $('.CodeMirror-lines pre').css('background-color','transparent'); // RESET THE PRE BG
                    $('.CodeMirror-lines pre').css('outline','none'); // RESET THE PRE BG
                    $('.CodeMirror-lines pre').children('span.*').css('color','auto'); // RESET THE PRE BG
                    
                    // get FIRE ID
                    var fireId = $(event.target).attr('wesley-id');
                    
                    if(fireId)
                    {
                        match = $('.CodeMirror-lines pre:contains("'+fireId+'")').length;  // GET MATCH FROM ID
                        if(match == 1){
                            $('.CodeMirror-lines pre:contains("'+fireId+'")').css('background-color','#c4f4ff');
                            $('.CodeMirror-lines pre:contains("'+fireId+'")').css('outline','2px solid #3875d7');
                            $('.CodeMirror-lines pre:contains("'+fireId+'")').children('span.*').css('color','#000');
                            $('.CodeMirror-scroll').scrollTo($('.CodeMirror-lines pre:contains("'+fireId+'")'), 000, {axis:'y',offset:-20 });
                        } else {
                            // SCAN
                            $('.CodeMirror-scroll').scrollTo({top:'10000px', left:'0px'}, 500 );    // BOTTOM 
                            $('.CodeMirror-scroll').scrollTo({top:'0px', left:'0px'}, 500 );        // TOP
                            // STOP IF MATCH
                        }
                    }
                    
                    //TESTING
                    $('#wesley-css-element').html($(event.target).cssPath() + " : " + match + " : " + fireId + " : " + $('.CodeMirror pre').length + "");
                    //$('#wesley-css-element').html($(event.target).cssPath());
                    
                    
                }
            }
			return true;
		}
	}).on("click", function(event){
		if($(event.target).parents().is("#wesley-cmsnav") || $(event.target).is("#wesley-cmsnav"))
        {
			return false;
		} else {
			var tab = $.cookie('wesley-cmsnav-tab');
			if(tab == 'design')
            {
                var pointerStatus = $.cookie('pointer');
                if(pointerStatus == 1)
                {
                    event.preventDefault();
                    var fireId = $(event.target).attr('wesley-id');
                    $('.CodeMirror-lines pre').css('background-color','transparent'); // RESET THE PRE BG
                    $('.CodeMirror-lines pre').css('outline','none'); // RESET THE PRE BG
                    $('.CodeMirror-lines pre').children('span.*').css('color','auto'); // RESET THE PRE BG
                    //$("#wesley-cmsnav-design").html("clicked: " + $(event.target).cssPath() + "<br> #" + event.target.id + " <br> ." + event.target.class + "");
                    if(fireId)
                    {
                        //match = $('.CodeMirror-lines pre:contains("'+fireId+'")').length;  // GET MATCH FROM ID
                        
                        $('.CodeMirror-lines pre:contains("'+fireId+'")').css('background-color','#3875d7');
                        $('.CodeMirror-lines pre:contains("'+fireId+'")').css('outline','2px solid #3875d7');
                        $('.CodeMirror-lines pre:contains("'+fireId+'")').children('span.*').css('color','#fff');
                        //match = $('.CodeMirror-lines').length;
                        //id = event.target.fireid;
                        $('.CodeMirror-scroll').scrollTo($('.CodeMirror-lines pre:contains("'+fireId+'")'), 300, {axis:'y',offset:-20 });
                        //$('.CodeMirror-lines').find('#'+event.target.id+'').closest('.CodeMirror-lines').css('background-color','green');
                        $.cookie('pointer',0,{ expires: 10 } );
                        $('a.wesley-cmsnav-pointer').css('background-image','url(/admin/modules/pages/images/icons/gray_18/target.png)');   // SHUT OFF POINTER
                    }
                }
            }
			return true;
		}
	});
	
    
    
    
    
    
    // HOVER / HTML AREA ELEMENT TARGETING
    /*
    $("body").on("mouseover", function(event){
        
        // WIPE STYLES
        
        //$('.CodeMirror-lines pre').css('background-color','transparent'); // RESET THE PRE BG
        //$('.CodeMirror-lines pre').css('outline','none'); // RESET THE PRE BG
        //$('.CodeMirror-lines pre').children('span.*').css('color','auto'); // RESET THE PRE BG
        
        $('.hoverbox').remove();
        // SET STYLE
        if($(event.target).parents(".CodeMirror").length){
            
            $(event.target).css('cursor','pointer');
            
            var fireId = '';
            
            if($(event.target).is('pre') || $(event.target).parents('pre').length){
                fireId          = $(event.target).html();
                if($(event.target).parents('pre').length){ fireId = $(event.target).parents('pre').html(); }
                if(fireId.indexOf("wesley-id") != -1 ){
                    fireIdArray     = fireId.split('wesley-id');
                    fireIdArray2    = fireIdArray[1].split('</');
                    fireIdArray3    = fireIdArray[1].split('"');
                    count           = fireIdArray3.length - 2;
                    fireId          = fireIdArray3[3];
                    ewidth          = $('*[wesley-id="'+fireId+'"]').width();
                    eheight         = $('*[wesley-id="'+fireId+'"]').height();
                    $('*[wesley-id="'+fireId+'"]').prepend('<div class="hoverbox" style="opacity:0.5;z-index:50;width:'+ewidth+'px; position:absolute; background-color:yellow; height:'+eheight+'px;"></div>');
                    $('#contentFrameWrapper').scrollTo($('*[wesley-id="'+fireId+'"]'), 000, {axis:'y',offset:-20 });
                    
                    //$(event.target).css('background-color','#c4f4ff');
                    //$(event.target).css('outline','2px solid #3875d7');
                    //$(event.target).children('span.*').css('color','#000');
                }
            }
            
            //if($(event.target).parents('pre').length){
            //    fireIdArray         = $(event.target).parents('pre').html
            //    //$(event.target).parents('pre').css('background-color','#c4f4ff');
            //    //$(event.target).parents('pre').css('outline','2px solid #3875d7');
            //    //$(event.target).parents('pre').children('span.*').css('color','#000');
            //}
            
            // find the contents of fireid
            
        }
           
        
        
        
        
        //var fireId = $(event.target).attr('wesley-id');
                    
        //if(fireId)
        //{
        //    match = $('.CodeMirror-lines pre:contains("'+fireId+'")').length;  // GET MATCH FROM ID
        //    $('.CodeMirror-lines pre:contains("'+fireId+'")').css('background-color','#c4f4ff');
        //    $('.CodeMirror-lines pre:contains("'+fireId+'")').css('outline','2px solid #3875d7');
        //    $('.CodeMirror-lines pre:contains("'+fireId+'")').children('span.*').css('color','#000');
        //    $('.CodeMirror-scroll').scrollTo($('.CodeMirror-lines pre:contains("'+fireId+'")'), 000, {axis:'y',offset:-20 });
        //}
        
        
    }).on("click", function(event){
        //alert('');
        return true;
    });
    */
    
    // TABS FOR DESIGN PANEL
    //$.cookie('wesley-design-tab', 'content',{ expires: 10 });	// SET COOKIE
    // HTML TAB
    $('#tab-html').click(function(e)
    {
        $.cookie('wesley-design-tab', 'html',{ expires: 10 });	// SET COOKIE
        $(this).addClass('wesley-cms-active-tab');                                              // ADD ACTIVE STATE TO THIS TAB
		$(this).closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');    // REMOVE ACTIVE STATE ON OTHER TABS
        htmlEditor.setValue(theDom);
        $('.CodeMirror').show();
    });
    
    
    
    
   
    // INITIAL TAB SETTINGS
    if($.cookie('wesley-design-tab') == 'html' || $.cookie('wesley-design-tab') == '')
    {
        $('#tab-html').addClass('wesley-cms-active-tab');                                               // ADD ACTIVE STATE TO THIS TAB
		$(this).closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');            // REMOVE ACTIVE STATE ON OTHER TABS 
    }
    
    
    //// GET ALL OF THE CSS INTO AN ARRAY
    //$().
    var cssArray = new Array();
    var cssString = "";
    $("link").each(function(){
        if($(this).attr("type") == "text/css"){
            //cssArray.push($(this).attr('href'));
            cssString = cssString + "" + $(this).attr('href') + ",";
        } 
    });
    //$cssString =
    cssString = cssString.slice(0, -1);
    //cssString = cssString + " ]";
    //$.cookie('cssArray',cssArray,{expires:10});
    $.cookie('cssString',cssString,{expires:10});
    //alert(cssString);
});