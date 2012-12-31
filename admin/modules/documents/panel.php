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

// DISPLAY THE WHOLE FILE MANAGER
if(isset($_POST['DISPLAYCUSTOMERFILEMANAGER']))
{
	//echo "made it | ";
	
	$FileManager = new FileManager();
	$content = $FileManager->uploadsManager("",urlRequest('panelId'));
	echo $content;	
	
	//echo "made it here to ";
	die();
	exit();
}

if(isset($_POST['DISPLAYCUSTOMERFILEMANAGERDIR']))
{
	$FileManager = new FileManager();
	$content = $FileManager->uploadsManager($_POST['DISPLAYCUSTOMERFILEMANAGERDIR'],urlRequest('panelId'));
	echo $content;
	die();
	exit();
}

if(isset($_POST['DELETEFILE']))
{
	//$CustomerFileManager = new CustomerFileManager();
	//$CustomerFileManager->Files($_POST['DISPLAYCUSTOMERFILEMANAGER']);
	@unlink("../../..".$_POST['DELETEFILE']."");
	//	echo $_POST['DELETEFILE'];
	die();
	exit();
}

if(isset($_POST['DELETEFOLDER']))
{
	//$CustomerFileManager = new CustomerFileManager();
	//$CustomerFileManager->Files($_POST['DISPLAYCUSTOMERFILEMANAGER']);
	@rmdir("../../.".$_POST['DELETEFOLDER']."");
	//	echo $_POST['DELETEFILE'];
	die();
	exit();
}

if(isset($_POST['MAKEDIRECTORY']))
{
	//$CustomerFileManager = new CustomerFileManager();
	//$CustomerFileManager->Files($_POST['DISPLAYCUSTOMERFILEMANAGER']);
	
	//var_dump($_SESSION);
	//die();
	//exit();
	
	if(!file_exists($_SERVER['DOCUMENT_ROOT']."".$_SESSION['current_directory']."".$_POST['MAKEDIRECTORY']."/")){
		// CREATE A CUSTOMER DIRECTORY
		@mkdir($_SERVER['DOCUMENT_ROOT']."".$_SESSION['current_directory']."".$_POST['MAKEDIRECTORY']."/", 0777);
		@chmod($_SERVER['DOCUMENT_ROOT']."".$_SESSION['current_directory']."".$_POST['MAKEDIRECTORY']."/", 0777);
		echo "SUCCESS | CREATED: ".$_SERVER['DOCUMENT_ROOT']."".$_SESSION['current_directory']."".$_POST['MAKEDIRECTORY']."/";
	} else {
		echo "FAILED | Directory exists!";
	}
	
	die();
	exit();
}


//$FileManager = new FileManager();	
report(urlRequest('msg'),urlRequest('msgType'));
?>