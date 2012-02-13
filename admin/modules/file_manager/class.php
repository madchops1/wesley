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

// FILE MANAGER
class FileManager {
	
	// CONSTRUCTOR
	function FileManager()
	{
		global $_SETTINGS;
	}
	
	function uploadsManagerFiles($panelId='')
	{
		
		global $_SESSION;
		global $_SETTINGS;
		$content = "";
		
		// GET CUSTOMER DIRECTORY
		$contents = scandir($_SERVER['DOCUMENT_ROOT'].$_SESSION['current_directory']);
		
		$goodfiles = array();
		$gooddirs = array();
		foreach($contents as $f){
			if(strpos($f,'.')!==0){
				if(is_dir($_SERVER['DOCUMENT_ROOT'].$_SESSION['current_directory']."/".$f."/")){
					array_push($gooddirs,$f);
				} else {
					array_push($goodfiles,$f);
				}
			}
		}
		sort($gooddirs);
		sort($goodfiles);
		
		$view = 'list';
		if($view=='list'){
			$i = 0;
			$j = 1;
			
			// BREAK DOWN THE DIR
			$theDir = rtrim($_SESSION['current_directory'],"/");
			
			// TESTING
			//echo "	DIR: ".$theDir."<br>";
			
			//echo $theDir;
			$theDirBackArray = explode("/",$theDir);
			array_pop($theDirBackArray);
			$theDirBack = implode("/",$theDirBackArray);
			
			// TESTING
			//echo "	BACK 1: ".$theDirBack." ".strlen($theDirBack)."<Br>";
			
			if(strlen($theDirBack) < strlen($_SESSION['default_directory'])){
				$theDirBack = $_SESSION['default_directory'];
			}
			
			// TESTING
			//echo "
			//	CURRENT: ".$theDir."<br>
			//	BACK 2: ".$theDirBack." <Br>
			//	DEFAULT: ".$_SESSION['default_customer_directory'.$xid]." ".strlen($_SESSION['default_customer_directory'.$xid])."";
				
			//var_dump($theDirBackArray);
			$inputId = randomNumber();
			$content .= "			<div class='fileupload-modal' style='display:none;'>
										<input id='file_upload_".$inputId."' name='file_upload_".$inputId." type='file' />
									</div>
									<div class='wesley-cmsnav-toolbar'>
										<ul class='wesley-cmsnav-buttons wesley-cmsnav-leftbuttons'>
											<li><a class='root' title='Root'>Root</a></li>
											<li><a class='backdirectory' title='Back'>.. Back</a></li>
											<li><a class='newdirectory' title='New Directory'>Create New Directory</a></li>
											<li><a class='uploadfiles' title='Upload Files'>Upload Files</a></li>
										</ul>
										<div>
											<input value='".$_SESSION['current_directory']."' class='filemanager-panel-path' />
										</div>
										<div class='filemanager-panel-details'>
											".count($goodfiles)." Files, ".count($gooddirs)." Folders
										</div>
									</div>
									<div class='overflowy drop-file-upload' style='border:1px solid #1px solid #BBB9BA; height:171px; '>
										<table class='panel-filemanager files' cellpadding='0' cellspacing='0'>
											<tbody id=''>
												<tr id='tablehead'>
													<th style='width:21px; text-align:left;'> </th>
													<th style='width:21px; text-align:left;'> </th>
													<th style='text-align:left; width:300px;'>Filename</th>
													<th style='text-align:left;'>Download</th><th style='text-align:left;'>Type</th>
													<th style='text-align:left;'>Size</th><th style='text-align:left;'>Dimensions</th>
													<th style='text-align:left;'>Date</th>
													<th style='text-align:left;'> </th>
												</tr>";
			
			// MERGE ARRAYS
			$goodarray = array_merge($gooddirs,$goodfiles);
			foreach($goodarray as $key => $file){
				//if ($i == 0){ echo "<table class='customer-filemanager'>"; }
				$ext = pathinfo($file, PATHINFO_EXTENSION);	// GET EXTENTION
				
				$content .= "		<tr id='filerow-".$i."' class='filerow'>";
				
				// CHECKBOX
				if(is_dir($_SERVER['DOCUMENT_ROOT'].$_SESSION['current_directory']."/".$file."/")){
					$content .= "		<td><input type='checkbox' class='foldercheckbox' value='' ></td>";
				} else {
					$content .= "		<td><input type='checkbox' class='filecheckbox' value='' ></td>";
				}
				
				// IMAGE
				$content .= "			<td>";
				if(is_dir($_SERVER['DOCUMENT_ROOT'].$_SESSION['current_directory']."/".$file."/")){
					$content .= "			<img src='".$_SETTINGS['website']."admin/modules/pages/images/icons/color_18/folder.png' style=''>";
					$ext = 'Folder';
				}
				$image = 0;
				if($ext == 'gif' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png'){
					$image = 1;
					$content .= "			<img src='".$_SESSION['current_directory']."/".$file."' class='draggable-image' path='".str_replace("//","/",$_SESSION['current_directory']."/".$file)."' style='width:18px; height:18px;'>";
				}
				$content .= "			</td>";
				
				// FILENAME
				$content .= "			<td>";
				if(is_dir($_SERVER['DOCUMENT_ROOT'].$_SESSION['current_directory']."/".$file."/")){
					$reverse = strrev($_SESSION['current_directory']);
					if($reverse[0] != "/"){ $_SESSION['current_directory'] .= "/"; }
					$content .= "		<span id='folderwrap-".$i."'><a class='dir' href='javascript:void(0);' rel='".$_SESSION['current_directory'].$file."' id='dir=".$i."'>".$file."</a></span>";	
				} else {
					$reverse = strrev($_SESSION['current_directory']);
					if($reverse[0] != "/"){ $_SESSION['current_directory'] .= "/"; }
					$content .= "		<span id='filewrap-".$i."'><a class='file' href='".$_SESSION['current_directory'].$file."' target='_blank' id='file-".$i."'>".truncateHtml($file,35)."</a></span>";
				}
				$content .= "			</td>";
				
				// DOWNLOAD
				$content .= "			<td>";
				if(!is_dir($_SERVER['DOCUMENT_ROOT'].$_SESSION['current_directory']."/".$file."/")){
					$content .= "			<a target='_blank' href='".$_SETTINGS['website'].$_SESSION['current_directory']."/".$file."'>Download</a>";
				}
				$content .= "			</td>";
				
				// EXTENSION
				$content .= "			<td>".$ext."</td>";
				
				// SIZE
				$size = formatBytes(filesize($_SERVER['DOCUMENT_ROOT'].$_SESSION['current_directory']."/".$file.""));
				$content .= "			<td>".$size."</td>";
				
				// DIMENSIONS
				$dimensions = "";
				if($image == 1){
					list($width, $height, $type, $attr) = getimagesize($_SERVER['DOCUMENT_ROOT'].$_SESSION['current_directory']."/".$file."");
					$dimensions = "".$width." x ".$height."";
				}
				$content .= "			<td>".$dimensions."</td>";
				
				// DATE
				$content .= "			<td>".date("m/d/Y", filemtime($_SERVER['DOCUMENT_ROOT'].$_SESSION['current_directory']."/".$file.""))."</td>";
				
				// DELETE
				$content .= "			<td><a href='' id='delete-".$i."' class='file-delete-button'>Delete</a></td>";
				
				$content .= "		</tr>";	// END ROW
				
				// DELETE SCRIPT
				$content .= "		<script>
										$('#delete-".$i."').click(function(){ 
											var confirma = confirm('Are you sure you want to delete this file?');
											if(confirma){
												$.post('/admin/modules/customer_filemanager/customer_filemanager.php',{ DELETEFILE: '".$_SESSION['current_directory'].$file."' }, function(data) { 
													$('#filerow-".$i."').fadeOut(500); 
												});
											}
										});
										$('#deletefolder-".$i."').click(function(){
											var confirmb = confirm('Are you sure you want to delete this folder?');
											if(confirmb){
												$.post('/admin/modules/customer_filemanager/customer_filemanager.php',{ DELETEFOLDER: '.".$_SESSION['current_directory'].$file."' }, function() {
													$('#filerow-".$i."').fadeOut(500);
												});
											}
										});
							</script>";
		//if ($j == 25){ $j = 0; echo "</table>"; }
		$j++;
		$i++;
	}
	$content .= "	<tr><td colspan='6'><a href='javascript:void(0);' id='delete-all'>Delete Checked</a></td></tr>";
	$content .= "	</tbody>
				</table>
			</div>";
			
	
	$content .= "	<script type='text/javascript'>
	
	
	
						$('.draggable-image').draggable({
							revert: 'invalid',
							containment: $( 'body' ).length ? 'body' : 'document',
							helper: 'clone',
							cursor: 'move'
						});
						
						// PREVENT DROPPING WIDGET ONTO CMS EDITOR
						$( '#wesley-cmsnav' ).droppable(
							{
								accept: '.draggable-image',
								greedy: true,
								over: function(event,ui)
								{
									// nada
								},
								drop: function(event, ui)
								{
									alert('Can\'t drop on toolbar!');
								}
							}
						);
					</script>";
	
	
	if($i == 0){
		$content .= "<p>0 Files</p>";
	}
	
	$content .= "	<script type='text/javascript'>
	
						// NEW DIRECTORY AJAX
						$('.newdirectory').click(function(e){
							e.preventDefault();
							var dirname = prompt('Directory Name','');
							if(dirname != ''){ 
								$.post('/admin/modules/file_manager/panel.php',{ MAKEDIRECTORY: ''+dirname+'', ajax:1 }, function(data) { 
									$.post('/admin/modules/file_manager/panel.php',{ DISPLAYCUSTOMERFILEMANAGER: ''+dirname+'',ajax:1,panelId:".$panelId." }, function(data) { 
										$('#pane-files').html(data); 
									}); 
								}); 
							}
						});
						
						// OPEN DIRECTORY AJAX
						$('.dir').click(function(e){ 
							e.preventDefault();
							var folder = $(this).attr('rel');
							$.post('/admin/modules/file_manager/panel.php',{ DISPLAYCUSTOMERFILEMANAGERDIR: ''+folder+'',ajax:1,panelId:".$panelId." }, function(data) { 
								$('#pane-files').html(data); 
							}); 
						});
						
						// BACKUP DIRECTORY AJAX
						$('.backdirectory').click(function(e){
							e.preventDefault();
							$.post('/admin/modules/file_manager/panel.php',{ DISPLAYCUSTOMERFILEMANAGERDIR: '".$theDirBack."',ajax:1,panelId:".$panelId." }, function(data) { 
								$('#pane-files').html(data);
							});
						});
						
						// ROOT DIRECTORY AJAX
						$('.root').click(function(e){
							e.preventDefault();
							$.post('/admin/modules/file_manager/panel.php',{ DISPLAYCUSTOMERFILEMANAGERDIR: '".$_SESSION['default_directory']."',ajax:1,panelId:".$panelId." }, function(data) { 
								$('#pane-files').html(data);
							});
						});
						
						// TOGGLE UPLOAD PANE
						$('.uploadfiles').click(function(e){
							e.preventDefault();
							$('.fileupload-modal').toggle();
						});
												
						$('#file_upload_".$inputId."').uploadify({
							'uploader'  : '/admin/scripts/uploadify-v2.1.4-1/uploadify.swf',
							'script'    : '/admin/scripts/uploadify-v2.1.4-1/uploadify.php',
							'cancelImg' : '/admin/scripts/uploadify-v2.1.4-1/cancel.png',
							'folder'    : '/uploads/".$_SETTINGS['website_id']."',
							'auto'      : true
						});
					</script>";
		}
		return $content;
	}
	
	function uploadsManager($dir,$panelId='')
	{
		
		//die('made it');
		//exit();
		
		$content = "";
		
		
		global $_SESSION;
		global $_SETTINGS;
		// CHECK IF THERE IS A WEBSITE UPLAODS DIRECTORY
		if(!is_dir($_SETTINGS['DOC_ROOT']."uploads/".$_SETTINGS['website_id']."/")){
			// CREATE A CUSTOMER DIRECTORY
			@mkdir($_SETTINGS['DOC_ROOT']."/uploads/".$_SETTINGS['website_id']."", 0777);
		}
		
		// SET DEFAULT DIRECTORY
		if(sessionRequest('current_directory') == ''){
			$_SESSION['current_directory'] = "/uploads/".$_SETTINGS['website_id']."/";
		}
		
		$_SESSION['default_directory'] = "/uploads/".$_SETTINGS['website_id']."/";
		
		if($dir != ''){
			$_SESSION['current_directory'] = $dir;
		}
		$content = "";
		//$content .= "<div id='customer-file-manager-menu'>";
		//$content .= "<input id='file_upload' name='file_upload' type='file' />";
		//$content .= "</div>";
		
		
		// CALL THE FILE MANAGER
		$content .= "<div class='' style=''>";
		//$this->Files($xid);
		$content .= $this->uploadsManagerFiles($panelId);
		$content .= "</div>";
		
		// JS FOR UPLOADIFY 
		$content .= "	<script type='text/javascript'>
						
						</script>";
		
		
		return $content;
	}
	
}
?>