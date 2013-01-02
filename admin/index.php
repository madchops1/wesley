<?php
/*************************************************************************************************************************************
 *
 *	Wesley (TM)
 *	A Karl Steltenpohl Development LLC Product
 *	Copyright 2012, All Rights Reserved
 *
 *************************************************************************************************************************************/
	 
//var_dump($_SERVER);

// Include Main Configuration File
if(is_file('../includes/config.php'))
{
	require_once('../includes/config.php');	
} else {	
	die("No config.php file found...");
}

// LOGIN CHECK
if($_SESSION["session"]->admin->auth == 0)
{
	header("Location: login.php?messageType=4&message=Please log in to continue.");
	exit();
}

// LOGOUT
if(isset($_GET["logout"]))
{
	$website = lookupDbValue('websites','name',$_SESSION['website_id'],'website_id');
	session_unset();
	session_destroy();
	header("location: login.php?messageType=1&message=You have been logged out successfully.");
	exit();
}

// WEBSITE DROPDOWN ACTION
if(isset($_POST['changewebsite']))
{
	
	// NEW WEBSITE
	if($_POST['changewebsite'] == 'new')
	{
	
	}
	// MAIN DASH
	elseif($_POST['changewebsite'] == 'main_dashboard')
	{	
		$webiste_id = '';
		header("Location: http://wescms.com/admin/index.php?session=".session_id()."");
	}
	// CHANGING WEBSITES
	else
	{
		$website_id = $_POST['changewebsite'];
		$select = "SELECT * FROM websites WHERE website_id='".$website_id."'";
		$result = doQuery($select);
		$row = mysql_fetch_array($result);
		if($row['domain'] != "")
		{
			header("Location: http://".$row['domain']."/admin/index.php?session=".session_id()."");
		} else {
			header("Location: http://".$row['subdomain'].".wescms.com/admin/index.php?session=".session_id()."");
		}
		exit();
	}	
}

// FILE MANAGER FORCE DOWNLOADS
//$FileManager = new FileManager();
//$FileManager->downloadFile();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cs" lang="cs">
    <head>
	
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-style-type" content="text/css" />
        <meta http-equiv="content-script-type" content="text/javascript" />
        
        <title><?=adminTitle()?> | <?=lookupDbValue('websites','name',settingRequest('website_id'),'website_id')?> Admin</title>
        
		<link rel="stylesheet" type="text/css" href="/admin/scripts/jquery-ui-1.8.16.custom/css/custom-theme/jquery-ui-1.8.16.custom.css" media="screen, projection, tv" /><!-- Change name of the stylesheet to change colors (blue/red/black/green/brown/orange/purple) -->
		<link rel="stylesheet" type="text/css" href="/admin/styles/blue.css" media="screen, projection, tv" />
		
		
        <!--[if lte IE 7.0]>
			<link rel="stylesheet" type="text/css" href="styles/ie.css" media="screen, projection, tv" />
		<![endif]-->

		<!--[if IE 8.0]>
			<style type="text/css">
				form.fields fieldset {margin-top: -10px;}
			</style>
		<![endif]-->
	
		<script type="text/javascript" src="scripts/jquery/jquery-1.6.4.min.js"></script>
		<script type="text/javascript" src="scripts/jquery-ui-1.8.16.custom/js/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript" src="scripts/jquery-ui-1.8.16.custom/development-bundle/external/jquery.cookie.js"></script>
		<script type="text/javascript" src="scripts/jquery.multiselect.js"></script>
		<script type="text/javascript" src="scripts/ui.selectmenu.js"></script>
		
		<!-- Adding support for transparent PNGs in IE6: -->
		<!--[if lte IE 6]>
			<script type="text/javascript" src="scripts/ddpng.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('#nav #h-wrap .h-ico');
				DD_belatedPNG.fix('.ico img');
				DD_belatedPNG.fix('.msg p');
				DD_belatedPNG.fix('table.calendar thead th.month a img');
				DD_belatedPNG.fix('table.calendar tbody img');
			</script>
		<![endif]-->
	
	<?
	// JAVASCRIPT FUNCTIONS
	include('../admin/scripts/functions.js.php');
	
	// GET MODULE SPECIFIC BACKEND JAVASCRIPTS
	$modules = arrayDirectories($_SETTINGS['DOC_ROOT']."admin/modules/");
	//debugArray($modules);
	if(urlRequest('module') != "")
	{
		if(is_file('modules/'.urlRequest('module').'/scripts/backend_script.js'))
		{
			echo " 
					<script type='text/javascript' src='modules/".urlRequest('module')."/scripts/backend_script.js'></script>";
		}
		else
		{
			wesleySystemError("The file modules/".urlRequest('module')."/scripts/backend_script.js doesn't exist.");
		}
	}
	?>
		
	</head>
	<body>
		<div id="header">
			<div class="inner-container clearfix">
				<h1 id="logo">
					<a class="home" href="index.php" title="Home">
						<?=adminTitle()?>
						<span class="ir"></span>
					</a>
				</h1>
				<form id='changewebsite' class='changewebsite' action='' method='post'>
					<select name='changewebsite' class='website' id='website' onchange='this.form.submit();'>
						<option value='main_dashboard'>Main Dashboard</option>
						<?
						$webSelect = 	"SELECT * FROM websites w ".
										"LEFT JOIN website_permissions wp ON w.website_id=wp.website_id ".
										"WHERE 1=1 AND ".
										"wp.user_id='".$_SESSION["session"]->admin->user_id."'";
						$webResult = doQuery($webSelect);
						$i = 0;
						while($webRow = mysql_fetch_array($webResult))
						{
							echo "	<option value='".$webRow['website_id']."' ".selected($webRow['website_id'],settingRequest('website_id'))." >";
							if($webRow['domain'] == ""){ echo $webRow['subdomain'].".wescms.com"; } else { echo $webRow['domain']; }
							echo "	</option>";
							$i++;
						}
						echo "		<option value='new'>Create New Website</option>";
						?>
					</select>
				</form>
				
				<?
					$websiteName 			= lookupDbValue('websites','name',settingRequest('website_id'),'website_id'); 
					$websiteSubdomain		= lookupDbValue('websites','subdomain',settingRequest('website_id'),'website_id');
					$websiteDomain			= lookupDbValue('websites','domain',settingRequest('website_id'),'website_id');
					$homepageSelect			= "SELECT * FROM pages WHERE home='1' AND website_id='".settingRequest('website_id')."' LIMIT 1";
					$homepageResult			= doQuery($homepageSelect);
					$homepageRow			= mysql_fetch_array($homepageResult);
					$Pages = new Pages();
					$homepageName			= $Pages->formatCleanPageUrl($homepageRow['name']);					
				?>
				<?php /* 
				<a class="button visitsite" href="<?=($websiteDomain != "" ? "http://".$websiteDomain."/".$homepageName."/session/".session_id()."/cms/1" : "http://".$websiteName.".wescms.com/".$homePageName."/session/".session_id()."/cms/1")?>" target="_blank">visit site</a>
				*/ 
				//echo "WEBSITE DOMAIN: ".$websiteDomain;
				//echo "WEBSITE SUBDOMAIN: ".$websiteSubdomain;
				if($websiteDomain != ""){
					echo '<a class="button visitsite" href="http://'.$websiteDomain.'/'.$homepageName.'/session/'.session_id().'/cms/1" target="_blank">Edit Website</a>';
				} else {
					echo '<a class="button visitsite" href="http://'.$websiteSubdomain.'.wescms.com/'.$homepageName.'/session/'.session_id().'/cms/1" target="_blank">Edit Website</a>';
				}
				?>
				
				
				<div id="userbox">
					<div class="inner">
						<strong><?=$_SESSION['session']->admin->name?></strong>
						<ul class="clearfix">
							<li><a href="index.php?module=profile">profile</a></li>
							<li><a href="index.php?module=settings">settings</a></li>
							<li><a href="?logout=1">logout</a></li>
						</ul>
					</div>
				</div><!-- #userbox -->
			</div><!-- .inner-container -->
		</div><!-- #header -->
      	<div id="nav">
			<div class="inner-container clearfix">
				<div id="h-wrap">
					<div class="inner">
						<?php if($websiteName != ""){ ?>
						<h2>
							<span class="h-ico ico-dashboard"><span><?=$websiteName ?> Main Dashboard</span></span>
							<span class="h-arrow"></span>
						</h2>
						<?php } ?>
						<ul class="clearfix">
							
							<?
							// MODULE MENU
							foreach($modules as $module)
							{
								if($module != "dashboard")
								{
									echo "<li><a class='h-ico ico-".$module."' href='?module=".$module."'><span>".ucwords(str_replace("_"," ",$module))."</span></a></li>";
								}
							}
							?>
							<!-- Admin sections - feel free to add/modify your own icons are located in "images/h-ico/*"
							<li><a class="h-ico ico-edit" href="#"><span>Content</span></a></li>
							<li><a class="h-ico ico-comments" href="#"><span>Comments</span></a></li>
							<li><a class="h-ico ico-media" href="#"><span>Media</span></a></li>
							<li><a class="h-ico ico-syndication" href="#"><span>Syndication</span></a></li>
							<li><a class="h-ico ico-send" href="#"><span>Newsletter</span></a></li>
							<li><a class="h-ico ico-cash" href="#"><span>Affiliate</span></a></li>
							<li><a class="h-ico ico-color" href="#"><span>Appearance</span></a></li>
							<li><a class="h-ico ico-users" href="#"><span>Users</span></a></li>
							<li><a class="h-ico ico-advanced" href="#"><span>Settings</span></a></li>
							-->
						</ul>
					</div>
				</div><!-- #h-wrap -->
				
				<form action="?module=<?=urlRequest('module')?>" method="get">
					<fieldset>
						<label class="a-hidden" for="keywords">Search query:</label>
						<input id="keywords" class="text fl" type="text" name="keywords" size="20" value="search&hellip;" />
						<input class="hand fr" type="image" src="images/search-button.png" alt="Search" />
					</fieldset>
				</form>
				
			</div>
      	</div>

		<div id="container">
			<div class="inner-container">
				
				<?
					// Get The Main My Website's Panel
					if(urlRequest('module') == ''){
						include 'modules/main_dashboard/panel.php';
					} 
					// Get A Specific Website's Dashboard Panel
					elseif(urlRequest('module') == 'dashboard')
					{
						include 'modules/dashboard/panel.php';
					}
					// GET THE PANEL FOR THE REQUESTED MODULE
					else 
					{
						if(is_file('modules/'.$_REQUEST['module'].'/panel.php'))
						{
							include 'modules/'.$_REQUEST['module'].'/panel.php';
						}
						else
						{
							wesleySystemError("Module doesn't exist.");
						}
					}
				?>
				
				
				<?
				ob_flush();
				?>
				
				<div id="footer"><!-- footer, maybe you don't need it -->
					<p>Wesley&trade; is a <a href="http://www.webksd.com">Karl Steltenpohl Development LLC</a> service.<Br>&copy; 2010-<?=date("Y")?></p>
				</div>
			</div>
		</div>		
		<script type="text/javascript">
		  var uvOptions = {};
		  (function() {
			var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
			uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/9IatzGx4FMCoTl0g6FaQpw.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
		  })();
		</script>
	</body>
</html>

<?
/*
//
//
//
//
//
//
//



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>		
		
		
			
	</head>
	
	<body style="background-color:#ddd;">
		<div id="contentwrap">
			<div id="content">
				<div id="header">
					<div id="header-logo">
						<h1>
							<a href="index.php">
								<?
								// LOGO
								if($_SETTINGS['logoImage'] != ""){
									// SETTINGS LOGO
									?> <img src="<?=$_SETTINGS['logoImage']?>" /> <?						
								} else {
									// WES LOGO
									?>
									<img src="images/weslogo.png" />
									<?
								}						
								?>
							</a>
						</h1>
					</div>
					<div style="margin-right:20px;">
						<p id="welcomebar">
							<?
							// ADMIN WELCOME BAR
							if ($_SESSION["session"]->admin->auth==1){
								// WELECOME								
								echo "Welcome, ".$_SESSION["session"]->admin->name;
								
								// HELP
								echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href='".$_SERVER["PHP_SELF"]."?'>Help</a> ";
								
								// LOGOUT
								echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href='".$_SERVER["PHP_SELF"]."?LOGOUT=1&'>Logout</a>	";														
							
								// DEMO MODE
								if($_SETTINGS['demo'] == '1'){								
									echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; You are in Demo Mode";		
								}
								
								// IF CMS VIEW WEBSITE
								if(checkActiveModule('0000000')){
									// VIEW SITE
									echo "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href='../' target='_blank'>View Web Site</a>";
								}
							}
							?>
						</p>				
						
						<?
						if($_SETTINGS['debug'] == '1'){
							?>
							<p id="debugbar">
								[IP : <?=$_SERVER['REMOTE_ADDR'] ?>] 
								[ADMIN ID : <?=$_SESSION["session"]->admin->userid ?>] 
								<? if($_SESSION["session"]->admin->accesslevel == '0'){$superadmin = 'Yes'; } else { $superadmin = 'No'; } ?>
								[SUPER ADMIN : <?=$superadmin?>]
							</p>					
							<?
						}
						?>
						
					</div>
					<br clear="all" />
						
						<?
						$z = 0;
						$zz = 1;
						$z1 = 1;
						$z2 = 25;
						$z3 = 50;
						$rowloop = 5;
						$loop = count($_ADMIN)/$rowloop;
						
						//echo "rowloop: $rowloop <Br>";
						//echo "loop: $loop <Br><Br><br><br>";
						
						?>
						
				</div>
				
				<div class='navtop'>
					<div id="tabsB" style="clear:both;">
					  <ul>				
						<li><a href="<?=$_SERVER["PHP_SELF"]?>" title="Home" class="Home <? if($_GET['VIEW']==""){ ?> active <? }?>"><span>Home</span></a></li>
						<?		
						//
						// TOP NAVIGATION TABS
						//
						foreach ($_ADMIN as $adminer)
						{
							// IF ACTIVE AND ACCESSIBLE
							if($adminer["4"] == 1 and $_SESSION["session"]->admin->CheckAccessLevelNavigation($adminer))
							{
								?>
								<li>
								<?
								if($adminer["7"] != ""){
									$href = "href=\"".$adminer['7']."\" target=\"_blank\"";
								}else{
									$href = "href=\"".$_SERVER["PHP_SELF"]."?VIEW=".$adminer["2"]."&\" target=\"\"";
								}
								?>
								<a class="<?=str_replace(" ","",$adminer["0"])?> <? if($_GET['VIEW']==$adminer["2"]){ ?> active <? }?>" id="" rel="<?=$adminer["8"]?>" <?=$href?> title="<?=$adminer["0"]?>">
								<span><?=$adminer["0"]?></span>
								</a>
								</li>
								<?
								//if($z == 4){ echo "<br clear='all'>"; } 
								

								
								$zz++;
								$z++;
								
							}
						}					
						?>
						</span>
					  </ul>
					</div>	
				</div>			
				
			<div style='width:95%; margin:0px auto;'>	
				
				<div class="col2 toolbarOut">
					<?							
					if( $section != '' ){
						//
						// INCLUDE MODULE LEFT NAVIGATION IF ACCESS LEVEL PERMITS
						//
						?><ul><?
						foreach ($_ADMIN as $adminer)
						{
							if ($_GET['VIEW']==$adminer["2"]) {
								$_SESSION["session"]->admin->CheckAccessLevel($adminer);
								include($adminer["5"]);
								$inc = 1;
							}
						}		
						?></ul><?
					} else {
						//
						// ELSE INCLUDE HOME PAGE LEFT NAVIGATION
						//
						?><ul><?
						foreach ($_ADMIN as $adminer)
						{
							if($adminer["4"] == 1 and $_SESSION["session"]->admin->CheckAccessLevelNavigation($adminer) == true)
							{
								?>
								<li>
								<?
								if($adminer["7"] != ""){
									$href = "href=\"".$adminer['7']."\" target=\"_blank\"";
								}else{
									$href = "href=\"".$_SERVER["PHP_SELF"]."?VIEW=".$adminer["2"]."&\" target=\"\"";
								}
								?>
								<a class="<?=str_replace(" ","",$adminer["0"])?> <? if($_GET['VIEW']==$adminer["2"]){ ?> active <? }?>" rel="<?=$adminer["8"]?>" <?=$href?> title="<?=$adminer["0"]?>">
							
								<?=$adminer["0"]?>
								</a>
								</li>
								<?
							}
						}	
						?></ul><?	
					}
					?>					
				</div>
								
				<!-- <div class="colmask leftmenu">	-->					
					
					<div class="col1">
						<?
						// CURRENT MODULE IF ACCESS LEVEL PERMITS 
						if( $section != '' )
						{
							foreach ($_ADMIN as $adminer)
							{
								if($_GET['VIEW'] == $adminer["2"])
								{
									$_SESSION["session"]->admin->CheckAccessLevel($adminer);
									echo "<div class='maincontent'>";
									include($adminer["3"]);
									echo "</div>";
									$inc = 1;
								}
							}
						}
						//ELSE HOME PAGE BUTTONS 
						else
						{
						?>
							
							<?
							if($_SETTINGS['wes_graphic'] == '1'){
							?>
							<style>
							.col1{ background-image:url(images/wesbg.png); }
							</style>
							<?
							} else {
							?>
							<style>
							.col1{ background-image:; }
							</style>
							<?
							}
							?>
							
							<div class="maincontent">	
								
								<? report($_REQUEST['REPORT'],$_REQUEST['SUCCESS']); ?>
								
								
								
								<ul id='mainul'>
								
									<?
									// HOME PAGE BUTTONS 
									$i=1;
									foreach ($_ADMIN as $adminer)
									{
										if($adminer["4"] == 1 and $_SESSION["session"]->admin->CheckAccessLevelNavigation($adminer) == true)
										{
										?>
											<li>
											
											<?
											if($adminer["7"] != ""){
												$href = "href=\"".$adminer['7']."\" target=\"_blank\"";
											}else{
												$href = "href=\"".$_SERVER["PHP_SELF"]."?VIEW=".$adminer["2"]."&\" target=\"\"";
											}
											?>
											<a class="<?=str_replace(" ","",$adminer["0"])?> <? if($_GET['VIEW']==$adminer["2"]){ ?> active <? }?>" <?=$href?> title="<?=$adminer["0"]?>" rel="<?=$adminer["8"]?>">
											<img src="<?=$adminer["6"]?>" border="0" />
											<span><?=$adminer["0"]?></span>
											</a>
											</li>
											<?													
											$i++;
										}
									}					
									?>
									<br clear='all'>
								</ul>
							
								
							</div>
						<?
						}
						?>
						<!-- <br clear="all" /> -->
					</div>			
			</div>
			
					<!-- </div> -->
				<!-- </div> -->
				<div id="footer">
					<table border=0 cellpadding=0 cellspacing=0 style="margin:0px auto;">
					<tr>
						<td class="copyimg">
							<a href="http://www.karlsdevelopment.com" style="border:0px;"><img src="images/weslogo.png" align="left" width="50" border="0"></a>
						</td>
						<td class="copytext">
							ver. <small><?=$_SETTINGS['version']?></small> &copy; 2009 - <?=date("Y")?>
							<Br>
						</td>
					</tr>
					</table>
				</div>
			</div>
		</div>	
	</body>
</html>
*/
//debugArray($_SESSION);
//echo "<br>THE SESSION ID IS: ".session_id()."<Br>";
?>