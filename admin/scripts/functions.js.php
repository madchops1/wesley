<?php
/*************************************************************************************************************************************
*
*	Wesley (TM), A Karl Steltenpohl Development LLC Product and Service
*  	Copyright (c) 2011 Karl Steltenpohl Development LLC. All Rights Reserved.
*	
*	This file is part of Karl Steltenpohl Development LLC's WES (Website Enterprise Software).
*	Written By: Karl Steltenpohl
*	
*	Commercial License
*	http://wesley.wescms.com/license
*
*************************************************************************************************************************************/
?>
<script type="text/javascript">
    $(document).ready(function() {
        
        // PENCIL IMAGE FOR AJAX INPUT AND TEXTAREA
        $('.ajax-input, .ajax-textarea').live('focusin', function(){
			$(this).next('img').remove(); // remove anything if its there
            $(this).after('<img src="images/pencil.png" class="edit-pencil" />'); 
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
		
		// GENERIC AJAX INPUT SAVE // WORKS WITH CURRENT MODULE 
        function ajaxSaveInput(that)
        {
            var item_id         =   $(that).attr('xid');
            var sqltable        =   $(that).attr('table');
            var sqlcolumn       =   $(that).attr('column');
            var fieldtype       =   $(that).attr('settingtype');
			var tableid	        =   $(that).attr('columnid');
            var fieldvalue      =   $(that).val();
       
				$(that).next('img').remove();  // remove pencil
				$(that).after('<img src="images/loader.gif" class="loader"/>');
		
            // AJAX POSTS TO THE CURRENT MODULE'S PANEL
            $.ajax({
                type: 'POST',
                url: 'modules/<?php echo urlRequest('module'); ?>/panel.php',
                data: { ajax: "1", save: "1", id: item_id, table: sqltable, column: sqlcolumn, type: fieldtype, value: fieldvalue, columnid: tableid },
                success: function(data){
                    
				
						$(that).next('img').remove(); // remove loader
						$(that).after('<img src="images/accept.png" class="edit-pencil" />');
						$(that).next('img').fadeOut(1000,function(){ $(this).remove(); });
				
					
					// TEXT SAVED!
                    //$(that).after('<span class="setting-saved"> Saved!</span>');
                    //$(that).next('span').fadeOut(1000,function(){ $(this).remove(); });
                }
            });
        }
		
		// GENERIC AJAX DELETE // WORKS WITH CURRENT MODULE
		function ajaxDeleteItem(that)
		{
			var ask = confirm('Are you sure?');
                        if(ask == true){
                            var item_id			=	$(that).attr('xid');
                            var sqltable		= 	$(that).attr('table');
                            var tableid		    =   $(that).attr('columnid');
                            $.ajax({
                                    type:	'POST',
                                    url:	'modules/<?php echo urlRequest('module'); ?>/panel.php',
                                    data:	{ajax: "1", del: "1", id: item_id, table: sqltable, columnid: tableid },
                                    success: function(data){
                                            $(that).closest('tr').fadeOut(500,function(){
                                                    $(that).closest('tr').remove();
                                            });
                                    }
                            });
                        }
		}
        
		// TEXTAREA, INPUT TYPING TIMER
		var inputDelayer=delayTimer(800);
		$('.ajax-input, .ajax-textarea').keyup(function(){
			var that = this
			inputDelayer(function(){
				ajaxSaveInput(that);
			});
		});
        
		// TEXTAREA, INPUT FOCUS OUT
		$('.ajax-input, .ajax-textarea').live('focusout', function(){
            ajaxSaveInput(this);
        }).live('keypress', function(e){
            if(e.keypress == 9 || e.keypress == 13)
            {
                ajaxSaveInput(this);
            }        
        });
		
        // RADIO BUTTON INPUT JAVASCRIPT
        $('.ajax-radio').live('focusin',function(){
            ajaxSaveInput(this);
        });
        
        // SELECT BOX
        $('.ajax-select').live('change',function(){
            ajaxSaveInput(this);
        });
		
        // SINGLE SELECT
        $(".singleselect").selectmenu({
            style:'dropdown',
            width: 150
        });
        
        // MULTISELECT
        $(".multiselect").multiselect({
			selectedText: "# of # selected"
        });
		
        // TABS - VIA JQUERY UI        
        $(".tabs").tabs({
            cookie: {
                // store cookie for a day, without, it would be a session cookie
                expires: 1
            }
        });
        
		// CHECKING ALL
		$("#check-all").live('click',function(e){
			
			//alert($(this).attr('checked'));
			
			// IF CHECKED
			if($(this).attr('checked') == 'checked')
			{
				alert('all checked');
				$(".rowcheck").attr('checked',true);
			} 
			// IF UNCHECKED
			else 
			{
				alert('all unchecked');
				$(".rowcheck").attr('checked',false);
			}
			
		});
		
		// DELETING
		$(".ajax-delete").live('click',function(e){
			e.preventDefault();
			ajaxDeleteItem(this);
		});
		
		$(".ajax-delete-many").live('click',function(e){
			e.preventDefault();
			$(".rowcheck").each(function(){
				if($(this).attr('checked') == 'checked')
				{
					var xid = $(this).attr('xid');
					ajaxDeleteItem(this);
				}
			});
		});
		
        /** ORIGINAL JS BELOW **/        
        // Search input text handling on focus
        var $searchq = $("#keywords").attr("value");
        
        $('#keywords.text').css('color', '#999');
        
        $('#keywords').focus(function(){
            if ( $(this).attr('value') == $searchq) {
                $(this).css('color', '#555');
                $(this).attr('value', '');
            }
        });
        
        $('#keywords').blur(function(){
            if ( $(this).attr('value') == '' ) {
                $(this).attr('value', $searchq);
                $(this).css('color', '#999');
            }
        });
        
        // Switch categories
        $('#h-wrap').hover(function(){
            $(this).toggleClass('active');
            $("#h-wrap ul").css('display', 'block');
        }, function(){
            $(this).toggleClass('active');
            $("#h-wrap ul").css('display', 'none');
        });
        
        // Handling with tables (adding first and last classes for borders and adding alternate bgs)
        $('tbody tr:even').addClass('even');
        $('table.grid tbody tr:last-child').addClass('last');
        $('tr th:first-child, tr td:first-child').addClass('first');
        $('tr th:last-child, tr td:last-child').addClass('last');
        $('form.fields fieldset:last-child').addClass('last');
        
        // Handling with lists (alternate bgs)
        $('ul.simple li:even').addClass('even');
        
        // Handling with grid views (adding first and last classes for borders and adding alternate bgs)
        $('.grid .line:even').addClass('even');
        $('.grid .line:first-child').addClass('firstline');
        $('.grid .line:last-child').addClass('lastline');
        
        /*
        // Tabs switching
        $('#box1 .content#box1-grid').hide(); // hide content related to inactive tab by default
        $('#box1 .header ul a').click(function(){
            $('#box1 .header ul a').removeClass('active');
            $(this).addClass('active'); // make clicked tab active
            $('#box1 .content').hide(); // hide all content
            $('#box1').find('#' + $(this).attr('rel')).show(); // and show content related to clicked tab
            return false;
        });
        */      
        
    });
</script>