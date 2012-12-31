<?
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

//echo "Made It 4535356...";

// IF AJAX GET CONFIG AND SHIT
if(isset($_POST['ajax']))
{
    if(is_file('../../../includes/config.php'))
    {
        require_once '../../../includes/config.php';
    } else {
        wesleySystemError("THERE IS NO CONFIG!");
    }
    global $_SETTINGS;
    global $_SESSION;   
}


$Pages = new Pages();								// Page Class	
report(urlRequest('msg'),urlRequest('msgType'));	// Report

//echo "Made It 76896789...";


/**
 * Actions Section
 * 
 * 
 */


// NEW PAGE AJAX
if(isset($_POST['newpage']) == '1')
{
	$nextId = 	nextId('pages');
	$insert = 	"INSERT INTO pages SET ".
				"name='New Page', ".
				"status='draft', ".
				"website_id='".$_SETTINGS['website_id']."', ".
				"active='1'";
	doQuery($insert);
	echo $nextId;
	die();
	exit();
}


// UPDATE PAGE PARENTS
if(isset($_POST['updateparents']) == '1')
{
	//echo urlRequest('value');
	$valueString = implode(",",urlRequest('value')).",";
	
	foreach(urlRequest('value') as $value)
	{
		
		// GET ALL PAGES THAT ARE NOT CHECKED
		//$select =	"SELECT * FROM pages WHERE active='1' AND website_id='".$_SETTINGS['website_id']."' AND page_id!='".$value."'";
		$select =	"SELECT * FROM pages WHERE active='1' AND website_id='".$_SETTINGS['website_id']."'";
		$result =	doQuery($select);
		$deleteSql = "";
		// BUILD DELETE SQL // LOOP THROUGH ALL PAGES
		while($row = mysql_fetch_array($result))
		{
			if(!strstr($valueString,"".$row['page_id'].","))
			{
				$deleteSql .= " parent_page_id='".$row['page_id']."' OR";
			}
		}
		$deleteSql = rtrim($deleteSql,"OR"); // TRIM THE OR
		// DELETE ALL PAGE PARENT RELATIONS THAT ARE NOT CHECKED
		$delete = 	"DELETE FROM pages_parents WHERE page_id='".urlRequest('id')."' AND (".$deleteSql.")";
		//echo $delete." /n";
		doQuery($delete);
		
		// CHECK IF THE PAGE EXISTS
		$select = 	"SELECT * FROM pages_parents WHERE page_id='".urlRequest('id')."' AND parent_page_id='".$value."'";
		$result = 	doQuery($select);
		// IF NOT THEN ADD THE PAGE
		if(!mysql_num_rows($result))
		{
			$insert = 	"INSERT INTO pages_parents SET page_id='".urlRequest('id')."', parent_page_id='".$value."'";
			//echo $insert." /n";
			doQuery($insert);
		}
	}
	
	//echo "success dddd";
	die();
	exit();
}

// SAVE GENERIC FIELD AJAX
if(isset($_POST['save']) == '1')
{
	// UPDATE FIELD		// TABLE, 		// COLUMN			// XID				// TABLEID			// FIELD VALUE
    ajaxUpdate(urlRequest('table'),urlRequest('column'),urlRequest('id'),urlRequest('columnid'),urlRequest('value'));
    //echo "success bbbb";
    die();
    exit();
}

if(isset($_POST['saveWidgetThingy']) == '1')
{
	// UPDATE WIDGET FIELD		// WIDGET ID / PARENT		// THING NAME		// COLUMN NAME		// FIELD VALUE
	ajaxUpdateWidgetThingy(urlRequest('parentId'),urlRequest('thingName'),urlRequest('columnName'),urlRequest('value'));
	//echo "success zzzzz";
	die();
	exit();
}

// DELETE GENERIC ROW AJAX
if(isset($_POST['del']) == '1')
{
	// DELETE ROW		// TABLE		// XID			// TABLE ID
	ajaxDelete(urlRequest('table'),urlRequest('id'),urlRequest('columnid'));
	//echo "success cccc";
	die();
	exit();
}

// UPDATE WIDGET POSITION
if(isset($_POST['updatewidgetposition']) == '1')
{
	$update = 	"UPDATE `things` SET ".
				"`left`='".$_POST['left']."',".
				"`top`='".$_POST['top']."',".
				"`width`='".$_POST['width']."',".
				"`height`='".$_POST['height']."' ".
				"WHERE `thing_id`='".$_POST['thing_id']."'";
	doQuery($update);
	die();
	exit();
}

// GET SPOT WIDGETS
if(isset($_POST['getwidgets']) == '1')
{
	$select = 	"SELECT * FROM things ".
				"WHERE 1=1 ".
				"AND parent_thing_id = '0' ".
				"AND spot = '".urlRequest('spot')."' ".
				"AND page = '".(urlRequest('everypage') == 'true' ? 'everypage' : urlRequest('page') )."' ".
				"AND widget != '' ".
				"AND active = '1' ".
				"AND website_id = '".$_SETTINGS['website_id']."' ".
				"ORDER BY thing_id DESC";
	$widgetresult = doQuery($select);
	$num = mysql_num_rows($widgetresult);
	//echo "NUM: ".$num."";
	$i = 0;	
	$root = rtrim($_SETTINGS['DOC_ROOT'],"/");
	while($widgetrow = mysql_fetch_array($widgetresult))
	{
		
		//var_dump($row);
		//echo "<br><br>";
		//echo "i: ".$i." ";
		// get and return the widget
		//echo "<div>".$widgetId." - ".$_SETTINGS['DOC_ROOT']."</div>";
		
		
		if(file_exists($root.$widgetrow['widgetpath']."index.php"))
		{
			$widgetId = $widgetrow['thing_id'];
			include($root.$widgetrow['widgetpath']."index.php");
			
		}
		else
		{
			wesleySystemError(''.$root.$widgetrow['widgetpath'].'index.php');	
		}
		
		$i++;
	}
	
	//echo "success wwwww";
	die();
	exit();
}



// IF DROPPED NEW WIDGET
if(isset($_POST['dropwidget']) == '1')
{
	
	$spot	= $_POST['spot'];
	$page	= $_POST['page'];
	$widget = $_POST['widget'];
	
	// INSERT THE SPOT - PAGE - WIDGET RELATIONSHIP AS A THING
	$nextId = 	nextId('things');
	$insert = 	"INSERT INTO things SET ".
				"thing_id='',".
				"parent_thing_id='0',".
				"spot='".$_POST['spot']."',".
				"page='".$_POST['page']."',".
				"widget='".$_POST['widget']."',".
				"widgetpath='".$_POST['widgetpath']."',".
				"active='1',".
				"website_id='".$_SETTINGS['website_id']."'";
	doQuery($insert);
	
	// get and return the widget
	if(file_exists($_SETTINGS['DOC_ROOT']."admin/widgets/".$widget."/index.php"))
	{
		$widgetId = $nextId;
		include($_SETTINGS['DOC_ROOT']."admin/widgets/".$widget."/index.php");
		
	}
	else
	{
		wesleySystemError('Widget does not exist.');	
	}
	
	die();
	exit();
}

// IF DELETE WIDGET FROM SPOT
if(isset($_POST['deletewidget']) == '1')
{
	$thing_id = $_POST['thing_id'];
	$update = "UPDATE things SET active='0' WHERE thing_id='".$thing_id."'";
	doQuery($update);
	die();
	exit();
}

// IF GET DESIGN PANEL HTML EDITOR
if(isset($_POST['htmleditor']) == '1')
{
    die();
    exit();
}


// IF GET DESIGN PANEL CSS EDITOR
if(isset($_POST['csseditor']) == '1')
{
    die();
    exit();
}



/**
 * GUI DISPATCHER BELOW
 *
 *
 */
//echo "Made It... 35345345";
echo powerPagesTable(); 

?>
