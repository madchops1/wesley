<?php
/*************************************************************************************************************************************
 *
 *	Wesley (TM)
 *	A Karl Steltenpohl Development LLC Product
 *	Copyright 2012, All Rights Reserved
 *
 *************************************************************************************************************************************/

//ini_set('display_errors', 1); 
//error_reporting(E_ERROR);
//error_reporting(E_ALL);

// Include Main Configuration File
if(is_file('../includes/config.php'))
{
	require_once('../includes/config.php');	
} else {	
	echo "<Br>NO CONFIG!";
}


// Get Admin Authorization
//$adminAuth = $_SESSION["session"]->admin->auth;

// Get Admin Access Level
//$adminLevel = $_SESSION["session"]->admin->accessLevel;	

// LOGIN
if(isset($_POST['login']))
{	
	// IF FROM WESLEY.WESCMS.COM THEN LOG IN AUTOMATICALLY
	if(isset($_POST['fromwesley']) AND $_SESSION["session"]->admin->login($_POST['username'], "","1"))
	{
		header("Location: index.php");
		exit();
	}
	// If username password validate forward to index.php with session id
	elseif($_SESSION["session"]->admin->login($_POST['username'], $_POST['password']))
	{
		header("Location: index.php");
		exit();
	}
	// ELSE IF USER NOT FOUND
	else {
		header("Location: ?messageType=4&message=Your credentials are not in our system.");
		exit();
	}
}

// RETRIEVE PASSWORD
if(isset($_POST['retrieve']))
{
	// SELECT USER
	$select = 	"SELECT * FROM users u ".
				"LEFT JOIN website_permissions wp ON u.user_id=wp.user_id ".
				"WHERE 1=1 AND ".
				"wp.website_id='".$_SESSION['website_id']."' AND ".
				"u.email='".$_POST['email']."' AND ".
				"u.active='1' ";
				
	$result = doQuery($select);
	if(mysql_num_rows($result)){
		$row = mysql_fetch_array($result);
		$newpass = makePass();
		
		$select = 	"UPDATE users SET ".
				"password='".md5($newpass)."' ".
				"WHERE user_id='".$row['user_id']."' ";
		doQuery($select);
		
		$to = $row['email'];
		$from = $_SETTINGS['email'];
		
		$subject = 	"New Password Request from ".$_SETTINGS['site_name']."";
		
		$message = 	"	<br>Your new password is:<br><br><strong>".$newpass."</strong>
					<br>
					<br>
					Login to your admin by following the link below.
					<br>
					<a href=\"".$siteName.".wescms.com/admin\">".$siteName.".wescms.com/admin\</a>
					<br>";
		
		@sendEmail($to,$from,$subject,$message);
	
		header("Location: ?msgType=1&msg=Email has been sent to ".$row['email']." with your new password.");
		exit();
		
	} else {
		header("Location: ?msgType=4&lost_pass=1&msg=That email is not in our system.");
		exit();	
	}
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
	<head>
	
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-style-type" content="text/css" />
        <meta http-equiv="content-script-type" content="text/javascript" />
        
        <title>Log In</title>
        
        <link rel="stylesheet" type="text/css" href="styles/blue.css" media="screen, projection, tv" />  
        <!--[if lte IE 7.0]><link rel="stylesheet" type="text/css" href="styles/ie.css" media="screen, projection, tv" /><![endif]-->
		<!--[if IE 8.0]>
			<style type="text/css">
				form.fields fieldset {margin-top: -10px;}
			</style>
		<![endif]-->
		
		<script type="text/javascript" src="scripts/jquery/jquery-1.6.4.min.js"></script>
		<!-- Adding support for transparent PNGs in IE6: -->
		<!--[if lte IE 6]>
			<script type="text/javascript" src="scripts/ddpng.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('h3 img');
			</script>
		<![endif]-->
		
    </head>
    <body id="login">

		<div class="box box-50 altbox">
			<div class="boxin">
				<div class="header">
					<h3>
					<?=adminTitle() ?>
					</h3>
					<ul>
						<li><a href="?login=1" class="<?=(!isset($_REQUEST['lost_pass']) ? "active" : "") ?>">login</a></li><!-- .active for active tab -->
						<li><a href="?lost_pass=1" class="<?=(isset($_REQUEST['lost_pass']) ? "active" : "") ?>">lost password</a></li>
					</ul>
				</div>
				<?
				if(isset($_REQUEST['login']) || !isset($_REQUEST['lost_pass'])){
					?>
					<form class="table" action="" method="post"><!-- Default forms (table layout) -->
						<div class="inner-form">
							<?=report(urlRequest('message'),urlRequest('messageType')) ?>
							<table cellspacing="0">
								<tr>
									<th><label for="username">Username: </label></th>
									<td><input class="txt" type="text" id="username" name="username" /></td>
								</tr>
								<tr>
									<th><label for="password">Password: </label></th>
									<td><input class="txt pwd" type="password" id="password" name="password" /></td><!-- class error for wrong filled inputs -->
								</tr>
								<tr>
									<th></th>
									<td class="tr proceed">
										<input class="button" type="submit" name='login' value="Log In" />
									</td>
								</tr>
							</table>
						</div>
					</form>
					<?
				}
				else
				{
					?>
					<form class="table" action="" method="post"><!-- Default forms (table layout) -->
						<div class="inner-form">
							<?=report(urlRequest('message'),urlRequest('messageType')) ?>
							<table cellspacing="0">
								<tr>
									<th><label for="some1">Email: </label></th>
									<td><input class="txt" type="text" id="some1" name="some1" /></td>
								</tr>
								<tr>
									<th></th>
									<td class="tr proceed">
										<input class="button" type="submit" name='retrieve' value="Retrieve Password" />
									</td>
								</tr>
							</table>
						</div>
					</form>
					<?
				}
				?>
			</div>
		</div>
    </body>
</html>
<?
 

?>