<?php
/*************************************************************************************************************************************
 *
 *	Wesley (TM), A Karl Steltenpohl Development LLC Product and Service
 *  Copyright (c) 2011 Karl Steltenpohl Development LLC. All Rights Reserved.
 *	
 *	This file is part of Wesley (TM) Karl Steltenpohl Development LLC's Proprietary Software.
 *	Created and Developed By: Karl Steltenpohl
 *	
 *	Commercial License
 *	http://wesley.wescms.com/license
 *
 *************************************************************************************************************************************/
(include'includes/config.php') or die("<Br>No Config File!");


// Take them to the admin or login...
if($_SETTINGS['website_id'] == "")
{
	//header('Location: http://wesley.wescms.com');
	header('Location: /admin/index.php');
	exit();
} 
// Build the requested page
else {
	$Pages = new Pages();	
	$Pages->constructPage();
}
//echo "<br>SESSION:<br>";     
//debugArray($_SESSION);
?>

