<?php
// THIS IS THE TEXT WIDGET

// SETTINGS AND SESSION
global $_SETTINGS;
global $_SESSION;
$Pages = new Pages();

// DEFINE WIDGET'S VARS
$widgetName     = 'Text Area';
$defaultWidth   = "100px";
$defaultHeight  = "100px";
$panelPath		= "/admin/widgets/text/panel.php";
$id             = $widgetId;
$defaultText	= "<p>Lorem Ipsum...</p>";
$htmlContent	= "";

// GET THE CURRENT MAIN WIDGET WIDGET DATA
$select =   "SELECT * FROM things ".
            "WHERE 1=1 ".
            "AND thing_id='".$id."' ".
            "AND active='1' ".
            "LIMIT 1";
$result = 	doQuery($select);

// IF THERE IS A WIDGET 
if($row = mysql_fetch_array($result))
{
    
    $htmlContent = $row['text'];
	if($htmlContent == "")
	{
		// INSERT DEFAULT TEXT
		$insert = 	"UPDATE things SET ".
					"text='".$defaultText."' ".
					"WHERE thing_id='".$id."'";
		doQuery($insert);
		$htmlContent = $defaultText;
	}
	
    // GET THE POSITION OF THE WIDGET TO PASS TO THE WIDGET HEADER (!IMPORTANT REQUIRED FOR EVERY WIDGET)
	if($row['width'] != "" AND $row['width'] != '0'){	$defaultWidth	= $row['width']."px"; }
	if($row['height'] != "" AND $row['height'] != '0'){	$defaultHeight	= $row['height']."px"; }
	$left			= $row['left'];
	$top			= $row['top'];
}

// (!IMPORTANT REQUIRED FOR EVERY WIDGET)
// IF SESSION CMS WIDGETS ARE ADDED TO PAGE VIA AJAX SO $content MUST BE CONCANTENATED WITH OTHER WIDGET DATA
// BUT IF USER IS VIEWING LIVE WEBSITE THEN $content IS INCLUDED VIA PHP EACH WIDGET IS INDIVIDUALLY INCLUDED SO CONTENT VAR MUST BE REFRESHED
if(sessionRequest('cms') == 1){ $content = ""; } 

// WIDGET HEADER
$buttons = "edit";
@$content .= widgetHeader($widgetName,$defaultWidth,$defaultHeight,$panelPath,$id,$top,$left,$buttons);

//$content .= "<textarea id='text-".$id."' class='wesley-widget-text-textarea'>";
//$content .= $htmlContent;
//$content .= "</textarea>";

$content .= "<div id='text-".$id."' class='wesley-widget-text-div'>";
$content .= $htmlContent;
$content .= "</div>";

if(sessionRequest('cms') == 1)
{

	$content .= "	<script type='text/javascript'>
						
							var widgetWidth 	= $('#text-".$id."').parent().width();
							var widgetHeight 	= $('#text-".$id."').parent().height();
							//$('#text-".$id."').width(widgetWidth);
							//$('#text-".$id."').height(widgetHeight);
							
							
							
							// EACH WIDGET CLICK CHECK IF IT IS THIS WIDGET
							$('.wesley-cmsnav-widget').live('mousedown',function(e)
							{
								var widgetId = $(this).attr('id');
								// IF IT ISNT THEN DESTROY THE EDITOR
								//alert(e.target);
								if(widgetId != '".$id."' || $(e.target).hasClass('wesley-cmsnav-widget-resize') || $(e.target).hasClass('wesley-cmsnav-widget-move'))
								{
									//if($(e.target).hasClass('wesley-cmsnav-widget-resize') || $(e.target).hasClass('wesley-cmsnav-widget-move'))
									//{
										if($('#cke_text-".$id."').length){
											var editor = $('#text-".$id."').ckeditorGet();
											editor.destroy();
										}
									//}
								} 
								// IF IT IS THEN CREATE THE EDITOR
								if(widgetId == '".$id."') {
									if($(e.target).hasClass('wesley-cmsnav-widget-edit'))
									{
									
										e.preventDefault();
										var cssString = $.cookie('cssString');
										//alert(cssString);
										var cssArray = cssString.split(',');
										cssString = cssArray[0];
										//var cssArray = $.cookie('cssArray');
										//cssString = cssArray[0];
										
										var config = {
											toolbar:
											[
												/*
												['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
												['UIColor'],
												[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ]
												*/
												/*
												[ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ],
												[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],
												[ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ],
												[ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ],
												[ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ],
												[ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ],
												[ 'Link','Unlink','Anchor' ],
												[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ],
												[ 'Styles','Format','Font','FontSize' ],
												[ 'TextColor','BGColor' ],
												[ 'Maximize', 'ShowBlocks','-','About' ]
												*/
												[ 'Source','-','Print','-','Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],
												[ 'Find','Replace','-','SelectAll','-','SpellChecker' ],
												[ 'Bold','Italic','Underline','Strike' ],
												[ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ],
												[ 'Link','Unlink','Anchor' ],
												[ 'Image','Flash','Table','HorizontalRule','SpecialChar' ],
												[ 'RemoveFormat','Format','Font','FontSize' ],
												[ 'TextColor','BGColor' ]
											],
											sharedSpaces:
											{
												top:'text-toolbar-".$id."'
											},
											resize_enabled: false,
											removePlugins : 'elementspath',
											contentsCss : cssString,
											toolbarCanCollapse : false
											/*
											contentsCss : '/themes/".$Pages->theme."/css/mysitestyles.css',
											contentsCss : ['/css/mysitestyles.css', '/css/anotherfile.css'],
											width: widgetWidth,
											height: widgetHeight,
											extraPlugins : 'autogrow',
											autoGrow_maxHeight : 400,
											removePlugins : 'resize'
											*/
										};
									
										// Initialize the editor.
										setTimeout(function(){
											// INITIALIZE CKeditor
											var editor = $('#text-".$id."').ckeditor(function(){
												var editorValue = CKEDITOR.instances['text-".$id."'].getData();
												//alert(editorValue);
												CKEDITOR.instances['text-".$id."'].document.on('keydown', function() {
													setTimeout(function(){
														editorValue = CKEDITOR.instances['text-".$id."'].getData();
														$.ajax({
															type:'POST',
															data:{ updatetext: editorValue,thing_id: '".$id."' },
															url:'/admin/widgets/text/panel.php'
														});
													}, 200);
												});
											},config);							
											//
											//$('#text-".$id."').ckeditor().on('blur', function() {
											//	alert('onblur 123');
											//});
											$('#".$id.".wesley-cmsnav-widget').resizable('disable');  		// DESTROY RESIZE
											$('#".$id.".wesley-cmsnav-widget').draggable('disable'); 		// DESTROY DRAGGABLE
											$('#".$id.".wesley-cmsnav-widget a.wesley-cmsnav-widget-resize').css('background-image','url(/admin/modules/pages/images/icons/gray_18/dimensions.png)');
											$('#".$id.".wesley-cmsnav-widget a.wesley-cmsnav-widget-move').css('background-image','url(/admin/modules/pages/images/icons/gray_18/arrow_bidirectional.png)');
											$.cookie('move-widget-".$id."',0, { expires:10 } );
											$.cookie('resize-widget-".$id."',0, { expires:10 } );
											$('#".$id.".wesley-cmsnav-widget').removeClass('movemode');
											$('#".$id.".wesley-cmsnav-widget').removeClass('resizemode');
										}, 800);
										
									}

									
								}
							});
							
						
					</script>";
		
} 


$content .= widgetFooter();
echo $content;
?>