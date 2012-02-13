<?php
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

// Session Class

class Session
{		
	var $auth;						
	var $admin;						
	
	function Session() {
		$this->auth 		= false;
		$this->admin 		= new Admin();
	}		
}


// Default Classes & Functions 
include $_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/breadcrumbs.class.php";
include $_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/admin.class.php";
include $_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/pagination.class.php";

// FPDF GENERATION 
require_once($_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/fpdf/fpdf.php");
//require_once($_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/scripts/fpdf/Table/class.fpdf_table.php");
//require_once($_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/scripts/fpdf/Table/header_footer.inc");    
//require_once($_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/scripts/fpdf/Table/table_def.inc");

// WYSIWYG Classes & Functions 
//include $_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/scripts/wysiwygPro/wysiwygPro.class.php";

// Rmail Classes & Functions
include $_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/Rmail/Rmail.php";

// Captcha
require_once($_SERVER['DOCUMENT_ROOT'].$_SETTINGS['website_path']."admin/includes/recaptcha-php-1.11/recaptchalib.php");
$recaptchapublickey = "6LdqAMISAAAAACC52nJDsVTSGlrjmonZbesSQ7ha"; // you got this from the signup page
$recaptchaprivatekey = "6LdqAMISAAAAAPu8CcsYr48rhEJeMMw2iG_wJlbX"; // you got this from the signup page

