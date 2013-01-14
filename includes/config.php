<?php
/*************************************************************************************************************************************
 *
 *	Wesley (TM)
 *	A Karl Steltenpohl Development LLC Product
 *	Copyright 2012, All Rights Reserved
 *
 *************************************************************************************************************************************/

ob_start();

// Set Error Reporting Level
ini_set('display_errors', 1); 
//error_reporting(E_ERROR);
error_reporting(E_ALL);

// Local Settings
/*
$_SETTINGS['dbHost'] 		= 'localhost';								// Database Host
$_SETTINGS['dbName']		= 'wesley';									// Database Name
$_SETTINGS['dbUser'] 		= 'root';									// Database User
$_SETTINGS['dbPass'] 		= 'karlkarl1';								// Database Password
*/

// Production Settins
$_SETTINGS['dbHost'] 		= 'localhost';								// Database Host
$_SETTINGS['dbName']		= 'wesley';									// Database Name
$_SETTINGS['dbUser'] 		= 'root';									// Database User
$_SETTINGS['dbPass'] 		= 'Karlkarl1';								// Database Password


$_SETTINGS['website_path']	= "/";										// Website Relative Path "/" is the root...
$_SETTINGS['host_name']		= "wescms.com"; 							// 

/**
 * IMPORTANT! :
 * PLEASE DO NOT CHANGE ANYTHING BELOW THIS LINE...
 *-----------------------------------------------------------------|
 *-----------------------------------------------------------------|
 */

//var_dump($_SERVER['SERVER_ADDR']);

$_SETTINGS['$softwareName'] = 'Wesley&trade;';
$_SETTINGS['debug']	 		= FALSE;

// Connect to the Database
mysql_connect($_SETTINGS['dbHost'], $_SETTINGS['dbUser'], $_SETTINGS['dbPass']);
mysql_select_db($_SETTINGS['dbName']);
unset($_SETTINGS['dbPass']);	

// Include the Main Functions
if(is_file($_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/functions.php")){
	include_once $_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/functions.php";
} else {
	die("<br>MAIN FUNCTIONS FILE NOT FOUND!");
	exit();
}

// Website Path Setting
$_SETTINGS['website'] = "http://".$_SERVER["SERVER_NAME"].$_SETTINGS['website_path'];
if(isset($_SERVER['HTTPS'])){
	if ($_SERVER['HTTPS'] != "on") {
		true;
	}  else {
		$_SETTINGS['website'] 		= "https://".$_SERVER["SERVER_NAME"].$_SETTINGS['website_path'];
	}
}

// DOC ROOT SETTING
$_SETTINGS["DOC_ROOT"] = $_DOC_ROOT = $_SERVER["DOCUMENT_ROOT"].$_SETTINGS['website_path'];

// Main Classes
if(is_file($_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/classes.php"))
{
	include_once $_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/classes.php";	
} else {
	wesleySystemError("MAIN CLASSES FILE NOT FOUND!");
}
	
// Include Functions and Classes for Installed Modules
$moduleDir = $_SETTINGS['DOC_ROOT']."admin/modules/";
if(is_dir($moduleDir)){
	//echo "<br>The module directory does exist.<br>";
	if($handle = opendir($moduleDir)) {
		while (false !== ($file = readdir($handle)))
		{
			if ($file != "." && $file != "..")
			{
				// INCLUDE MODULE class.php
				if(file_exists($_SETTINGS['DOC_ROOT']."admin/modules/".$file."/class.php"))
				{
					include_once $_SETTINGS['DOC_ROOT']."admin/modules/".$file."/class.php";
					//echo "Included ".$_SETTINGS['DOC_ROOT']."admin/modules/".$file."/class.php<Br>";
					//echo file_get_contents($_SETTINGS['DOC_ROOT']."admin/modules/".$file."/class.php");
				}				
				// INCLUDE MODULE functions.php
				if(file_exists($_SETTINGS['DOC_ROOT']."admin/modules/".$file."/functions.php"))
				{
					include_once $_SETTINGS['DOC_ROOT']."admin/modules/".$file."/functions.php";
					//echo "Included ".$_SETTINGS['DOC_ROOT']."admin/modules/".$file."/functions.php<Br>";
				}
			}
		}
		closedir($handle);
	}
} else {
	wesleySystemError("<br>The module directory does not exist.<br>");
}


// IMPORTANT CODE TO HANLE PASSING OF SESSION ID TO WEBSITE FROM ADMIN
$serverArray = explode("/",$_SERVER['REQUEST_URI']);
if(isset($serverArray[2]) || isset($_GET['session']))
{
	if($serverArray[2] == "session")
	{
		session_id($serverArray[3]);
		//echo "<br>PASSED SESSION ID: ".$_GET['session']."<br>";
		//echo "<br>SERVER REQUEST URI: ".$_SERVER['REQUEST_URI']."<br>";
	}
	if(isset($_GET['session']))
	{
		session_id($_GET['session']);
	}
}


session_start();						// BEGIN SESSIONS
setInput(); 							// HANDLE MOD REWRITE
setWebsiteId(urlRequest('website'));	// SET WEBSITE ID



if(urlRequest('clearsessions'))
{
	session_destroy();					// DESTROY ALL SESSIONS
}

/**
 * Debugging...
 * 
 * 
 */
//echo "<strong>DEBUG:</strong><br>THE WEBSITE ID IS: ".$_SETTINGS['website_id']."";
//echo "<br>THE SESSION ID IS: ".session_id()."<Br>";
//debugArray($_SERVER);
//debugArray($_SETTINGS);
//die();
//exit();

/*
// CHECK IF THERE IS NO SUBDOMAIN, AND IF NOT THEN GO TO wesley.wescms.com
if($_SETTINGS['website_id'] == '')
{
	// IF THEY ARE NOT REQUESTING ADMIN THEN
	if(!strstr($_SERVER['REQUEST_URI'],"admin"))
	{
		header("Location: http://wesley.wescms.com/");
		exit();
	}
	else
	{
		header("Location: http://wescms.com/admin/index.php");
		exit();
	}
}

// THIS WILL REDIRECT LOGGED IN SESSIONS THAT TRY TO GO TO WESLEYCMS.COM WITHOUT A SUBDOMAIN TO THE SALE SITE
if(!isset($_SETTINGS['website_id']) AND !strstr($_SERVER['REQUEST_URI'],"admin"))
{
	header("Location: http://wesley.wescms.com/");
	exit();
}
*/

if(!isset($_SESSION["session"]))
{
	$_SESSION["session"] = new Session();
}


//$_SESSION['website'] = $_SETTINGS['website'];

// Site Global and Universal Settings
$setselect 	= "SELECT * FROM settings";
$setresult 	= doQuery($setselect);
$setnum		= mysql_num_rows($setresult);
$seti		= 0;
while($seti<$setnum){
	$setrow = mysql_fetch_array($setresult);
	$setrowname = strtolower(str_replace(" ","_",$setrow['name']));
	
	if(isset($_SESSION["session"]))
	{
		// WEBSITE SPECIFIC SETTINGS
		if($setrow['user_setting'] != '1')
		{
			$setselect1 = 	"SELECT * FROM settings_website ".
							"WHERE 1=1 AND ".
							"active = '1' AND ".
							"setting_id = '".$setrow['id']."' AND ".
							"website_id = '".$_SETTINGS['website_id']."' ";
			$setresult1 = doQuery($setselect1);
			$setnum1 = mysql_num_rows($setresult1);
			
			// THE WESITE SETTING DOESN'T EXIST AND IT NEEDS TO BE ADDED TO THE DB
			if($setnum1 == 0)
			{
				$default = $setrow['value']; 	// THE VALUE IN THE SETTINGS TABLE WILL BE THE DEFAULT FOR THE WEBSITE SETTINGS VALUES
				$setinsert1 = 	"INSERT INTO settings_website SET ".
								"website_id = '".$_SETTINGS['website_id']."',".
								"setting_id = '".$setrow['id']."',".
								"active= '1',".
								"value = '".escape_smart($default)."'";
				doQuery($setinsert1);
				$setrow['value'] = $default;
			}
			// THE WEBSITE SETTING DOES EXIST
			else
			{
				$setrow1 = mysql_fetch_array($setresult1);			
				$setrow['value'] = $setrow1['value'];
			}			
		}
		// USER SPECIFIC SETTINGS
		elseif($setrow['user_setting'] == '1')
		{
			$setselect1 = 	"SELECT * FROM settings_user ".
							"WHERE 1=1 AND ".
							"active = '1' AND ".
							"user_id = '".$_SESSION['session']->admin->user_id."' AND ".
							"setting_id = '".$setrow['id']."' ".
							"LIMIT 1";
			$setresult1 = doQuery($setselect1);
			$setrow1 = mysql_fetch_array($setresult1);
			$setrow['value'] = $setrow1['value'];
		}
	}
	
	$_SETTINGS[$setrowname] = $setrow['value'];
	$seti++;	
}

// MAKE SURE THERE IS A HOMEPAGE
$select = "SELECT * FROM pages WHERE website_id='".$_SETTINGS['website_id']."' AND home='1' LIMIT 1";
$result = doQuery($select);
if(!mysql_num_rows($result))
{
	
	// Determine if there is a default home page thempate in this thme
	// todo...
	
	// ADD THE HOMEPAGE
	$insert = 	"INSERT INTO pages SET ".
				"name='Home',".
				"website_id='".$_SETTINGS['website_id']."',".
				"home='1',".
				"template=''";
	doQuery($insert);
}

// MAKE SURE THERE IS AN UPLOADS DIRECTORY
if(!is_dir($_SETTINGS['DOC_ROOT']."uploads/".$_SETTINGS['website_id']."/")){
	// CREATE THE UPLOADS DIR
	@mkdir($_SETTINGS['DOC_ROOT']."uploads/".$_SETTINGS['website_id'], 0777);
	@chmod($_SETTINGS['DOC_ROOT']."uploads/".$_SETTINGS['website_id'], 0777);
}

// include DOM PARSER FOR CMS
//if(sessionRequest('cms') == 1)
//{
//		include_once($_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/simplehtmldom_1_5/simple_html_dom.php");
//}

//die('DEAD AS A DOORNAIL');
//exit();
//debugArray($_SETTINGS);

/*
// DEBUG INFO
echo "<div class='debug-panel'>";

echo "	<br>
		WEBSITE: ".$_GET['website']."<Br>
		WEBSITE ID ".$_SESSION['website_id']."<Br>
	<br>";

// GET
echo "	<br>";
echo "	<strong>GET:</strong><br>";
		var_dump($_GET);
echo "	<br>";

// POST
echo "	<br>";
echo "	<strong>POST:</strong><br>";
		var_dump($_POST);
echo "	<br>";

// SETTINGS
echo "	<br>";
echo "	<strong>SETTINGS:</strong><br>";
		var_dump($_SETTINGS);
echo "	<br>";

// SETTINGS
echo "	<br>";
echo "	<strong>SERVER:</strong><br>";
		var_dump($_SERVER);
echo "	<br>";


echo "</div>";

*/

/**
 *
 * ADMIN NAVIGATION BAR ARRAY
 * array(
 *	'Display Name',
 *	accessLevel (not used),
 *	'url Name',
 *	'file name',
 *	active,
 *	'sub nav file name',
 *	'iconpath',
 *	'external link',
 *	'description',
 *	'unique identifier'
 *	);
 *
 
 
$_ADMIN = array();
		
$selectmodule = "SELECT * FROM wes_modules WHERE active='1' AND status='Installed'";
$resultmodule = doQuery($selectmodule);
$imodule = 0;
$nummodule = mysql_num_rows($resultmodule);
while($imodule<$nummodule){
	$rowmodule = mysql_fetch_array($resultmodule);
	$newarray = array(
					''.$rowmodule['name'].'',
					1,
					''.$rowmodule['url_name'].'',
					'modules/'.$rowmodule['filename'].'/'.$rowmodule['filename'].'.php',
					1,
					'modules/'.$rowmodule['filename'].'/'.$rowmodule['filename'].'_navigation.php',
					''.$rowmodule['icon_path'].'',
					'',
					''.$rowmodule['description'].'',
					''.$rowmodule['unique_identifier'].''
				);
	array_push($_ADMIN, $newarray);
	$imodule++;
}
			

 *
 * PUT THE ADMIN ARRAY IN A SESSION FOR ACCESS ANYWHERE
 *
 
$_SESSION['AdminArray'] = $_ADMIN;	
*/


// DEBUGGING
/*
echo "---------------------------------<br><br> SESSION: ";
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
echo "---------------------------------<br><br> SETTINGS: ";
echo "<pre>";
var_dump($_SETTINGS);
echo "</pre>";
echo "---------------------------------<Br>";
echo "<br>THE WEBSITE SETTING ID IS: ".$_SETTINGS['website_id']."";
echo "<br>THE SESSION ID IS: ".session_id()."<Br>";
*/
?>