<?php
include '../../../includes/config.php';
global $_SETTINGS;
global $_SESSION;
$FileManager = new FileManager();

/** WIDGET ACTIONS
 *********************************************************************/
// ADD SLIDE
if(urlRequest('newSlide') == '1')
{
	$slideImage = urlRequest('filePath');
	$slideText 	= urlRequest('slideText'); 
	$parentThingId = urlRequest('parentThingId');
	$thingId 	= nextId('things');
	$insert =	"INSERT INTO things SET ".
				"parent_thing_id='".$parentThingId."',".
				"name='slide',".
				"text='".$slideText."',".
				"file='".$slideImage."',".
				"website_id='".$_SETTINGS['website_id']."',".
				"active='1'";
	doQuery($insert);
	
	echo $thingId; // return the thing id as an ajax response
    
    die();
	exit();
} 

// REPLACE SLIDE
if(urlRequest('replaceSlide') != '')
{
	$slideImage = urlRequest('filePath');
	$thingId 	= urlRequest('replaceSlide');
	$update = 	"UPDATE things SET ".
				"file='".$slideImage."' ".
				"WHERE thing_id='".$thingId."'";
	doQuery($update);
	die();
	exit();
}

/** BEGIN PANEL
 *********************************************************************/
 
// DECLARE VARS
$content 									= "";
$id 										= urlRequest('widget_id');

// GET ALL THINGS THAT BELONG TO THIS WIDGET THEY ARE SLIDES
$selectSlides = "SELECT * FROM things ".
				"WHERE 1=1 ".
                "AND name='slide' ".
				"AND parent_thing_id='".$id."' ".
				"AND website_id='".$_SETTINGS['website_id']."' ".
				"AND active='1' ".
				"ORDER BY sort_order ASC, date DESC";
$resultSlides = doQuery($selectSlides);

// GET ALL OTHER OPTIONS (THINGS)
$thingsArray = array();

// DEFAULTS
$thingsArray['effect']['text'] 				    = "fade";
$thingsArray['animation_speed']['text'] 	    = "500";
$thingsArray['pause_speed']['text'] 		    = "3000";

$selectThings = "SELECT * FROM things ".
				"WHERE 1=1 ".
				"AND parent_thing_id='".urlRequest('widget_id')."' ".
				"AND website_id='".$_SETTINGS['website_id']."' ".
                "AND name!='slide' ".
				"AND active='1'";
$resultThings = doQuery($selectThings);
while($rowThings = mysql_fetch_array($resultThings))
{
	$thingsArray[$rowThings['name']] = $rowThings;
}

// TABS
$content .= "	<div class='wesley-cmsnav-contenteditareatitle'><span>Slideshow</span>
                    <ul class='wesley-cmsnav-tabs'>
                        <li><a href='' id='tab-settings' class='wesley-cms-active-tab'>Options</a></li>
						<li><a href='' id='tab-slides'>Slides</a></li>
                        <li><a href='' id='tab-files'>Files</a></li>
                    </ul>
				</div>";
				
// SETTINGS PANEL
$content .= "   <div id='pane-settings' class='wesley-cmsnav-contenteditarea-edit overflowy'>";
$content .= "   	<table class='form' cellpadding='0' cellspacing='0'>						
						<tr>
                            <th>Effect</th>
                            <td>
                                <select class='ajax-widget-select' column_name='text' parent_thing_id='".urlRequest('widget_id')."' thing_name='effect'>
                                    <option value='none' ".selected('none',$thingsArray['effect']['text']).">None</option>
									<option value='fade' ".selected('fade',$thingsArray['effect']['text']).">Fade</option>
									<option value='scrollHorz' ".selected('scrollHorz',$thingsArray['effect']['text']).">Scroll Horizontal</option>
									<option value='scrollVert' ".selected('scrollVert',$thingsArray['effect']['text']).">Scroll Vertical</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
							<th>Animation Speed</th>
							<td><input type='text' value='".$thingsArray['animation_speed']['text']."' class='ajax-widget-input' parent_thing_id='".urlRequest('widget_id')."' thing_name='animation_speed' column_name='text' /></td>
						</tr>
						<tr>
							<th>Pause Speed</th>
							<td><input type='text' value='".$thingsArray['pause_speed']['text']."' class='ajax-widget-input' parent_thing_id='".urlRequest('widget_id')."' thing_name='pause_speed' column_name='text' /></td>
						</tr>
					</table>";
$content .= "	</div>"; // END SETTINGS

// SLIDES PANEL
$content .= "	<div id='pane-slides' class='wesley-cmsnav-contenteditarea-edit overflowy' style='display:none;'>
					<ul id='accordion".$id."' class='slide-sort'>";
$i = 1;
$tableContent = "";
while($slide = mysql_fetch_array($resultSlides))
{
	$content .= "		<li thing_id='".$slide['thing_id']."' id='".$slide['thing_id']."' slide='".($i-1)."' >
							<span>".$i."</span>
							<img src='".$slide['file']."' />
						</li>";
						
	$tableContent .= "		<table class='form subform' cellpadding='0' cellspacing='0' rel='".$slide['thing_id']."' style='clear:both; display:none;'>
								<tr>
									<th>Text</th>
									<td><input id='text-".$slide['thing_id']."' type='text' value='".$slide['text']."' class='ajax-input slide-text' table='things' column='text' columnid='thing_id' xid='".$slide['thing_id']."' /></td>
								</tr>
								<tr>
									<th>Link</th>
									<td><input id='link-".$slide['thing_id']."' type='text' value='".$slide['url']."' class='ajax-input' table='things' column='url' columnid='thing_id' xid='".$slide['thing_id']."' /></td>
								</tr>
							</table>";					
						
	$i++;
}					
$content .= "		<div style='clear:both;'></div></ul>";

// APPEND TABLES
$content .= $tableContent;

					
$content .= "	</div>"; // END SLIDES
				
// SCRIPTS FOR SLIDES PANEL
$content .= "	<script type='text/javascript'>
					
                    // UPDATE THE SLIDE TEXT WITH PANEL INPUT TEXT ON THE FLY
                    $('.slide-text').keyup(function(){
                        
                        // get new slide text
						var slidetext = $(this).val();
                        
                        // get slide id
                        var slideId = $(this).attr('xid');
                                      
                        $('.slideshow".$id." a[thing_id=\"'+slideId+'\"] span').html(slidetext);
						//$('#wesley-logo-a-".$id."').html('title',logoname);
					});
                    
					// SORTABLE SLIDES
					$('.slide-sort').sortable({
                        //placeholder: 'ui-state-highlight',
                        stop: function(event, ui) {
                            // GET THE NEW SORT ORDER
                            var result = $('.slide-sort').sortable('toArray');
                            var resultstring = result.toString();
							alert(resultstring);
                            // AJAX THE NEW SORT ORDER
                        }
                    });
					
                    $('.slide-sort li').click(function(){
                        //alert('clicked');
						$('.slideshow".$id."').cycle(parseInt($(this).attr('slide')));	// GO TO THE SLIDE
						$(this).addClass('active-panel-slide');
						$(this).siblings('li').removeClass('remove-panel-slide');
                        var thing = $(this).attr('id');
                        $('table.subform').hide();
                        $('table[rel=\"'+thing+'\"]').show();
                        
                        $.cookie('widget-".urlRequest('widget_id')."-slide', ''+thing+'',{ expires: 10 });
                        
                    });
                    
					// INITIAL SLIDE AND TALBE DISPLAY
                    var activeSlideTable = $.cookie('widget-".urlRequest('widget_id')."-slide'); 
					if(activeSlideTable){
                        $('table.subform').each(function(){
                            if($(this).attr('rel') == activeSlideTable)
                            {
                                $(this).show();
                            }
                        });
                    }
					
					
				</script>";				
				

// FILES PANEL
$dir = "";
$content .= "	<div id='pane-files' class='wesley-cmsnav-contenteditarea-edit' style='display:none;'>";
$content .=			$FileManager->uploadsManager($dir,urlRequest('widget_id'));
$content .= "	</div>"; // END FILES

// SCRIPTS FOR TABS/PANELS
$content .= "	<script>     
					// TABS CLICKED
					$('a#tab-settings').click(function(e){
						e.preventDefault();
						$(this).addClass('wesley-cms-active-tab');
						$(this).closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');
						
						$('#pane-files').hide();
						$('#pane-slides').hide();
						$('#pane-settings').show();
						
						$.cookie('widget-".urlRequest('widget_id')."', 'settings',{ expires: 10 });
					});
					
					$('a#tab-slides').click(function(e){
						e.preventDefault();
						$(this).addClass('wesley-cms-active-tab');
						$(this).closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');
						
						$('#pane-files').hide();
						$('#pane-slides').show();
						$('#pane-settings').hide();
						
						$.cookie('widget-".urlRequest('widget_id')."', 'slides',{ expires: 10 });
					});
					
					$('a#tab-files').click(function(e){
						e.preventDefault();
						$(this).addClass('wesley-cms-active-tab');
						$(this).closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');
						
						$('#pane-settings').hide();
						$('#pane-slides').hide();
						$('#pane-files').show();
						
						$.cookie('widget-".urlRequest('widget_id')."', 'files',{ expires: 10 });
					});
					
                    // TABS SET
					var activeTab = $.cookie('widget-".urlRequest('widget_id')."'); 
					
					if(activeTab == 'settings')
					{
						$('a#tab-settings').addClass('wesley-cms-active-tab');
						$('a#tab-settings').closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');
						$('#pane-settings').show();
						$('#pane-files').hide();
						$('#pane-slides').hide();
					}
					
					if(activeTab == 'slides')
					{
						$('a#tab-slides').addClass('wesley-cms-active-tab');
						$('a#tab-slides').closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');
						$('#pane-settings').hide();
						$('#pane-files').hide();
						$('#pane-slides').show();
					}
					
					if(activeTab == 'files')
					{
						$('a#tab-files').addClass('wesley-cms-active-tab');
						$('a#tab-files').closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');
						$('#pane-settings').hide();
						$('#pane-files').show();
						$('#pane-slides').hide();
					}
					
				</script>";
echo $content;
?>