var boxActive = false;
$(document).ready(function() {

	
	console.log('box script loaded');
	
	/**
	 *  Toolbox code
	 */
	$("#wesley-toolbox ul li#box").click(function(e){
		e.preventDefault;
		if($(e.target).hasClass('active')){
			boxActive = false;
			$("#wesley-toolbox ul li#box").removeClass('active');
			$("#wesley-toolbox-panel").hide();
			$("#wesley-spot-workspace").css('cursor','auto');
		} else {
			boxActive = true;
			$("#wesley-toolbox ul li").removeClass('active');
			$(e.target).addClass('active'); // add the active class
			$("#wesley-spot-workspace").css('cursor','crosshair');
			// Open the panel
			$.ajax(
            {
                type:	'POST',
                url:	'/admin/widgets/box/panel.php',
                data:	{ widget_id: '' },
                success: function(data){
                    //$('#wesley-cmsnav-contenteditarea').html(data);
                	//$("body").prepend("<div id='wesley-toolbox-panel-"+widgetId+"' class='toolbox-panel'></div>")
                    //$("#wesley-toolbox-panel-content").html(data);
                    $("#wesley-toolbox-panel").show();
                }
            });
		}
	});
	
	/**
	 * Toolbox code cont...
	 * If user clicks any other tool than this one
	 * make sure this tool is deactivated
	 * hide panel
	 */
	$("#wesley-toolbox ul li[id!='box']").click(function(e){
		e.preventDefault;
		console.log('clicked not box');
		boxActive = false;
		$("#wesley-spot-workspace").css('cursor','auto');
		$("#wesley-toolbox-panel").hide();
	});
	
	/**
	 * Box Drawer
	 */
	//if($("#wesley-toolbox a#box").hasClass('active')){
		// Boxer plugin
		//$.widget("ui.boxer", $.extend({}, $.ui.mouse, {
		$.widget("ui.boxer", $.ui.mouse, {
			options:{
				appendTo: '#wesley-spot-workspace',
				distance: 0
			},
			_init: function() {
				
				console.log(boxActive);
				
				this.element.addClass("ui-boxer");
	
				this.dragged = false;
	
				this._mouseInit();
	
				this.helper = $(document.createElement('div'))
					.css({border:'1px dotted black'})
					.addClass("ui-boxer-helper");
			},
	
			destroy: function() {
				this.element
					.removeClass("ui-boxer ui-boxer-disabled")
					.removeData("boxer")
					.unbind(".boxer");
				this._mouseDestroy();
	
				return this;
			},
	
			_mouseStart: function(event) {
				
				console.log(event);
				
				if(		!boxActive || 
						$(event.target).hasClass("wesley-cmsnav-widget-close") ||
						$(event.target).hasClass("wesley-cmsnav-widget")
						){ return false; }
				var self = this;
	
				this.opos = [event.pageX, event.pageY];
	
				
				
				if (this.options.disabled)
					return;
	
				var options = this.options;
	
				console.log(options)
				
				this._trigger("start", event);
	
				$(options.appendTo).append(this.helper);
	
				console.log($(options.appendTo).offset());
				
				mOffset = $(options.appendTo).offset();
				
				this.helper.css({
					"z-index": 100000,
					"position": "absolute",
					"left": event.clientX - mOffset.left,
					"top": event.clientY - 42, 
					"width": 0,
					"height": 0
				});
			},
	
			_mouseDrag: function(event) {
				if(!boxActive){ return false; }
				var self = this;
				this.dragged = true;
	
				if (this.options.disabled)
					return;
	
				var options = this.options;
				mOffset = $(options.appendTo).offset();
				
				var x1 = this.opos[0], y1 = this.opos[1], x2 = event.pageX, y2 = event.pageY;
				if (x1 > x2) { var tmp = x2; x2 = x1; x1 = tmp; }
				if (y1 > y2) { var tmp = y2; y2 = y1; y1 = tmp; }
				this.helper.css({left: (x1 - mOffset.left ), top: (y1 - 42), width: x2-x1, height: y2-y1});
				
				this._trigger("drag", event);
	
				return false;
			},
	
			_mouseStop: function(event) {
				if(!boxActive){ return false; }
				var self = this;
	
				this.dragged = false;
	
				var options = this.options;
	
				var clone = this.helper.clone()
					.removeClass('ui-boxer-helper')
					.appendTo(this.element);
	
				var cloneOffset = clone.offset();
				////
				////
				////
				mOffset = $(options.appendTo).offset();
				
				var clickable = $("#wesley-toolbox li#box");
				var that = $(options.appendTo);
				var thisWidgetSpotPage = '';
				thisWidgetSpotPage = clickable.attr('page');
				
				/**
				 * Save the box drag click into a box 
				 */
				$.ajax(
					{
						type:	'POST',
						url:	'/admin/modules/pages/panel.php',
						data:	{
									ajax: "1",
									dropwidget: "1",
									widget:clickable.attr('widget'),
									widgetpath:clickable.attr('widgetpath'),
									page:thisWidgetSpotPage,
									spot:$(that).attr('id'),
									width: clone.width(),
									height: clone.height(),
									top: cloneOffset.top - 42,
									left: cloneOffset.left - mOffset.left,
									color: currentForgroundColor
								},
						success: function(data){
							$(that).removeClass("wesley-spot-active");	// Remove hover class from active
							clone.remove();
							$(that).prepend(data);						// Add the widget via drag/drop
							$(that).height($(that).parent().height());	// resize workspace to parent height
							
							
							
							makeWidgetDraggable(that);
							makeWidgetResizable(that);
							
							// Make the widget drop draggable
							
							
							
							
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
							);*/
	                        
							
							
							
	                        //$('.wesley-cmsnav-widget').draggable('disable');        // DISABLE DRAGGABLES
	                        //$('.wesley-cmsnav-widget').resizable('disable');        // DISABLE RESIZABLES
	                        
						}   
					}       
				);
				
				
				
				
				
				
				
				
				
				
				
				
				////
				////
				////
				
				
				this._trigger("stop", event, { box: clone });
	
				this.helper.remove();
				//clone.remove();
				return false;
			
			}
	
		});
		
		/*
		$.extend($.ui.boxer, {
			defaults: $.extend({}, $.ui.mouse.defaults, {
				appendTo: 'body',
				distance: 0
			})
		});*/
	
		// Using the boxer plugin
		$('#wesley-spot-workspace').boxer({
			stop: function(event, ui) {
				var offset = ui.box.offset();
				ui.box.css({ background: currentForgroundColor, border: '0px' });
				
				//ui.box.
				
					/*.append('x:' + offset.left + ', y:' + offset.top)
					.append('<br>')
					.append('w:' + ui.box.width() + ', h:' + ui.box.height());*/
				
				
				
				
			}
		});
	//}	
	
});