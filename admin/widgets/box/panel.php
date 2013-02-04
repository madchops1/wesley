<?php
include '../../../includes/config.php';
global $_SETTINGS;
global $_SESSION;
$FileManager = new FileManager();

/** WIDGET ACTIONS
 *
 *
 *
 */

// AJAX UPDATE IMAGE | DROP IMAGE ON WIDGET
if(urlRequest('file_path'))
{
	// CHECK IF THERE IS A CHILD THING (IMAGE) FOR THIS LOGO WIDGET
	$select = 	"SELECT * FROM things ".
				"WHERE parent_thing_id='".urlRequest('thing_id')."' ".
				"AND name='image' ".
				"AND active='1' ".
				"AND website_id='".$_SETTINGS['website_id']."' ".
				"LIMIT 1";
	$result = doQuery($select);
	
	// FORMAT FILE PATH | REMOVE THE USERS DIRECTORY
	$filePath = str_replace("//","/",urlRequest('file_path'));
	list($width, $height, $type, $attr) = getimagesize($_SETTINGS['DOC_ROOT'].$filePath);
	//$filePath = str_replace("/uploads/".$_SETTINGS['website_id']."/","",$filePath);
	
	// UPDATE
	if($row = mysql_fetch_array($result))
	{
		$update = 	"UPDATE things ".
					"SET file='".$filePath."' ".
					"WHERE 1=1 ".
					"AND parent_thing_id='".urlRequest('thing_id')."' ".
					"AND name='image' ".
					"AND website_id='".$_SETTINGS['website_id']."' ".
					"AND active='1'";
		doQuery($update);
		
	}
	// NEW
	else
	{
		$insert = 	"INSERT INTO things SET ".
					"file='".$filePath."',".
					"parent_thing_id='".urlRequest('thing_id')."',".
					"name='image',".
					"website_id='".$_SETTINGS['website_id']."',".
					"active='1'";
		doQuery($insert);
		//echo "INSERTED SUCCESSFULLY ".$width." x ".$height."";
	}
	
	//Json
	echo '	{ 
				"width":"'.$width.'",
				"height":"'.$height.'"
			}';
	
	die();
	exit();
}

/** BEGIN PANEL
 *
 *
 *
 */
 
$id = urlRequest('widget_id'); 
 
// GET THINGS
$thingsArray = array();

// DEFAULTS
$thingsArray['text']['text'] = "";
$thingsArray['image']['file'] = "";
$thingsArray['link']['url'] = "";

$selectThings = "SELECT * FROM things ".
				"WHERE 1=1 ".
				"AND parent_thing_id='".$id."' ".
				"AND website_id='".$_SETTINGS['website_id']."' ".
				"AND active='1'";
$resultThings = doQuery($selectThings);
while($rowThings = mysql_fetch_array($resultThings))
{
	$thingsArray[$rowThings['name']] = $rowThings;
}

$content = "";
//$content .= "LOGO PANEL";
$content .= "	<div class='wesley-cmsnav-contenteditareatitle'><span>Logo</span>
                    <ul class='wesley-cmsnav-tabs'>
                        <li><a href='' id='tab-settings' class='wesley-cms-active-tab'>Options</a></li>
                        <li><a href='' id='tab-files'>Files</a></li>
                    </ul>
				</div>";
// SETTINGS
$content .= "   <div id='pane-settings' class='wesley-cmsnav-contenteditarea-edit overflowy'>";
$content .= "   	<table class='form' cellpadding='0' cellspacing='0'>
						<tr>
							<th>Company Name</th>
							<td><input id='logo-name-".$id."' type='text' value='".$thingsArray['text']['text']."' class='ajax-widget-input' parent_thing_id='".$id."' thing_name='text' column_name='text' /></td>
						</tr>
						<tr>
							<th>Link</th>
							<td><input id='logo-link-".$id."' type='text' value='".$thingsArray['link']['url']."' class='ajax-widget-input' parent_thing_id='".$id."' thing_name='link' column_name='url' /></td>
						</tr>
					</table>";
$content .= "	</div>";

// FILES
$dir = "";
$content .= "	<div id='pane-files' class='wesley-cmsnav-contenteditarea-edit' style='display:none;'>";
$content .=			$FileManager->uploadsManager($dir,$id);
$content .= "	</div>";

// REALTIME SCRIPTS
$content .= "	<script>
					// update logo anchor html/text with the company name
					$('#logo-name-".$id."').keyup(function(){
						var logoname = $(this).val();
						$('#wesley-logo-a-".$id."').attr('title',logoname);
					});
					
					// update logo anchor link with the url
					$('#logo-link-".$id."').keyup(function(){
						var logolink = $(this).val();
						$('#wesley-logo-a-".$id."').attr('href',logolink);
					});
				</script>";

// TABS SCRIPTS
$content .= "	<script>           
					// TABS
					$('a#tab-settings').click(function(e){
						e.preventDefault();
						$(this).addClass('wesley-cms-active-tab');
						$(this).closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');
						
						$('#pane-files').hide();
						$('#pane-settings').show();
						
						$.cookie('widget-".urlRequest('widget_id')."', 'settings',{ expires: 10 });
					});
					
					$('a#tab-files').click(function(e){
						e.preventDefault();
						$(this).addClass('wesley-cms-active-tab');
						$(this).closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');
						
						$('#pane-settings').hide();
						$('#pane-files').show();
						
						$.cookie('widget-".urlRequest('widget_id')."', 'files',{ expires: 10 });
					});
					
					var activeTab = $.cookie('widget-".urlRequest('widget_id')."'); 
					
					if(activeTab == 'settings')
					{
						$('a#tab-settings').addClass('wesley-cms-active-tab');
						$('a#tab-settings').closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');
						$('#pane-settings').show();
						$('#pane-files').hide();
					}
					
					if(activeTab == 'files')
					{
						$('a#tab-files').addClass('wesley-cms-active-tab');
						$('a#tab-files').closest('li').siblings().children('a').removeClass('wesley-cms-active-tab');
						$('#pane-settings').hide();
						$('#pane-files').show();
					}
					
				</script>";
echo $content;
?>