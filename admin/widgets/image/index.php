<?php
global $_SETTINGS;
global $_SESSION;

// DEFINE VARS
$widgetName     = 'Image';
//$defaultWidth   = "200px";
//$defaultHeight  = "100px";
$panelPath		= "/admin/widgets/image/panel.php";
$id             = $widgetId;
//$companyName 	= "Company Name";
//$logoImagePath	= "";
//$logoLink	    = "";

if(sessionRequest('cms') = 1){ $content = ""; }
$content .= "	<div id='wesley-image-div-".$id."' class='wesley-cmsnav-widget' title='".$title."' layer='".$zindex."' style='position:".$position."; top:".$top."px; left:".$left."px; width: ".$width."px; height:".$height."px; background:#".$color."; z-index:".$zindex."'>
                  <img src=''/>
                </div>";


/*
// GET THE CURRENT WIDGET DATA
$select =   "SELECT * FROM things ".
            "WHERE 1=1 ".
            "AND thing_id='".$id."' ".
            "AND active='1' ".
            "LIMIT 1";
$result = 	doQuery($select);

if($row = mysql_fetch_array($result))
{
	$thingsSelect =     "SELECT * FROM things ".
						"WHERE 1=1 ".
						"AND parent_thing_id='".$row['thing_id']."' ".
						"AND active='1'";
	$thingsResult =     doQuery($thingsSelect);
	$thingsArray = array();
	while($thingsRow = mysql_fetch_array($thingsResult))
	{
		if($thingsRow['name'] == 'image'){ $logoImagePath = $thingsRow['file']; }
		if($thingsRow['name'] == 'text'){ $companyName = $thingsRow['text']; }
		if($thingsRow['name'] == 'link'){ $logoLink = $thingsRow['url']; }			
	}                   
    
	if($row['width'] != "" AND $row['width'] != '0'){	$defaultWidth	= $row['width']."px"; }
	if($row['height'] != "" AND $row['height'] != '0'){	$defaultHeight	= $row['height']."px"; }
	$left			= $row['left'];
	$top			= $row['top'];
}

// IF EDITING THEN CLEAR CONTENT
if(sessionRequest('cms') == 1){ $content = ""; }
@$content .= widgetHeader($widgetName,$defaultWidth,$defaultHeight,$panelPath,$id,$top,$left);

// CMS EDITOR
if(sessionRequest('cms') == 1)
{
	$content .= "	<div id='file-drop-".$id."' class='wesley-file-drop'>";
}

// WIDGET
$content .= "	<a href='".$logoLink."' id='wesley-logo-a-".$id."' class='' title='".$companyName."' >";
$content .= "		<img id='wesley-logo-id-".$id."' class='' src='".$logoImagePath."' />";
$content .= "	</a>";
$content .= "	<style>
					#wesley-logo-a-".$id."
					{
						display:block;
						text-align:center;
					}
					
					#wesley-logo-id-".$id." { 
						display:block;
						margin:0 auto;
						width:100%;
					}
				</style>";

// CMS EDITOR SCRIPT				
if(sessionRequest('cms') == 1)
{
	$content .=     "</div>";
	$content .= "	<script type='text/javascript'>
						// DROPPABLE LOGO
						$('#file-drop-".$id."').droppable({
							accept: '.draggable-image',
							drop: function( event, ui ){
								var draggable = ui.draggable;
								var widgetThingId = $(this).closest('.wesley-cmsnav-widget').attr('id');
								var filePath = draggable.attr('path');
								alert('Dropped! | WIDGET THING ID: ' + widgetThingId + ' | FILE PATH: ' + filePath + '');
								
								//WHEN DROPPING A FILE INTO A WIDGET
								//THE FILE BECOMES A CHILD THING OF THE WIDGET (PARENT THING)
								$.ajax({
									type:'POST',
									data:{ thing_id: widgetThingId,thing_name: 'image', file_path: filePath},
									url:'/admin/widgets/logo/panel.php',
									success:function(data){
										var jsonObj = $.parseJSON(data);
										$('#wesley-logo-id-".$id."').attr('src','' + filePath + '');
									}
								});
							}
						});
						// PREVENT LOGO CLICK IN CMS
						$('#wesley-logo-id-".$id."').click(function(e){
							e.preventDefault();
						});
					</script>";
}

// THE WIDGET FOOTER
$content .= widgetFooter();
*/

// IF EDITING THEN ECHO THE CONTENT
if(sessionRequest('cms') == 1)
{
    echo $content;
}
?>