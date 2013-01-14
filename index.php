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


/**
 * When someone hits wescms.com,
 * Take them to the admin/login...
 */ 
if($_SETTINGS['website_id'] == "")
{
	header('Location: /admin/index.php');
	exit();
} 
/**
 * Else Build page
 */
else {
  $Pages = new Pages();	
  $Pages->constructPage();
}

/**
 * Debugging
 */
if($_SETTINGS['debug'] == TRUE){
  echo "<br>SESSION:<br>";     
  debugArray($_SESSION);
}
?>

