<?php
include '../../../includes/config.php';
global $_SETTINGS;
global $_SESSION;

// updatetext
if(urlRequest('updatetext') != '')
{
	// INSERT TEXT
    $insert = 	"UPDATE things SET ".
                "text='".urlRequest('updatetext')."' ".
                "WHERE thing_id='".urlRequest('thing_id')."'";
    doQuery($insert);
    echo "saved!";
	//echo $thingId; // return the thing id as an ajax response
    
    die();
	exit();
} 

/** BEGIN PANEL
 *********************************************************************/
 
// DECLARE VARS
$content 									= "";
$id 										= urlRequest('widget_id');

$content .= "	<div class='wesley-cmsnav-contenteditareatitle'>Text Area</div>
				<div id='wesley-cmsnav-contenteditarea-edit'>
                    <div id='text-toolbar-".$id."' style='width: 100%;'>
                    
                    </div>
				</div>";

$content .= "   <script>
                    
                   
                   
                   
                </script>";

echo $content;

?>