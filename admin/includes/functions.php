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

// SQL QUERY FUNCTION
function doQuery ($query,$error=1)
{		
	global $_SETTINGS;
	$queried = mysql_query($query);		
	if(mysql_error() AND $error == 1){
		echo "Uh-oh, our website is currently experiencing a hic-up. We aploigize and will have it corrected asap!";
		//$message = "<br><br>".mysql_error()." <br><br> IN QUERY: <br>".$query."";
		//sendEmail('karl@webksd.com',$_SETTINGS['website'],"SQL QUERY ERROR - ".$_SETTINGS['website']."",$message);
		
		echo "	<br><br>
				<strong>Error:</strong><br>
				".mysql_error()."
				<br><br>
				<strong>Query:</strong><br>
				".$query."
				<Br><br>";
				
		exit();
	}
	return $queried;				
}

// RETURN A CELL VALUE
function lookupDbValue($table, $column, $lookup, $id='x')
{
	global $_SETTINGS;		
	$sel = 	"SELECT `$column` FROM `$table` WHERE `$id`='$lookup'";
	$res = doQuery($sel);
	$row = mysql_fetch_array($res);
	return $row[$column];
}

// SELECTED
function selected($i, $x)
{
	if ($i!=NULL)
	{
		if ($i==$x)
			return " SELECTED ";
	}
}

// CHECKED
function checked($i, $x)
{
	if ($i==$x)
		return " CHECKED ";
}

// RANDOM NUMBER
function randomNumber($low = 1,$high = 1000000){
	return rand($low, $high);
}

// GET NEXT ID
function nextId($table)
{	
	$r = mysql_query("SHOW TABLE STATUS LIKE '$table' ");
	$row = mysql_fetch_array($r);
	$Auto_increment = $row['Auto_increment'];
	//mysql_free_result($r);
	return $Auto_increment;		
}

// ESCAPE A VARIABLE
function escape_smart($value)
{
   // Stripslashes
   if (get_magic_quotes_gpc()) {
	   $value = stripslashes($value);
   }
   // Quote if not integer
   if (!is_numeric($value)) {
	   $value = mysql_escape_string($value);
   }
   return $value;
}

// ESCAPE AN ARRAY
function escape_smart_array($array)
{
	foreach ($array as $key => $value)
	{
		if (get_magic_quotes_gpc())
		{
			$array[$key] = stripslashes($array[$key]);
		}
		$array[$key] = mysql_escape_string($array[$key]);
	}
	return $array;		
}

function recurse_copy($src,$dst)
{
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
} 

// SET INPUT - config mod_rewrite function = !IMPORTANT!
function setInput()
{
	
	//var_dump($_GET['request']);
	
	// EXPLODE THE REQUEST
	if(isset($_GET['request']))
	{	
		// TESTINGS
		//echo "<br>UNSET INPUT GET: <pre>";
		//var_dump($_GET['request']);
		//echo "</pre><br>";
		
		// EXPLODE $_SERVER['THE_REQUEST']
		$input = explode( ' ', $_GET['request']);
		
		// COUNT THE NEW ARRAY
		$count = count($input);
		$lastkey = $count - 1;
		
		// REMOVE THE FIRST AND LAST ELEMENTS OF THE ARRAY FROM $_SERVER['THE_REQUEST']
		// DOESN'T WORK		
		//unset($input[0]);
		//unset($input[$lastkey]);
		// PUT THE OTHER SECTIONS BACK TOGETHER
		//$inputString = implode(" ", $input);
		
		
		// JUST GET THE SECOND ELEMENT
		$inputString = $input[1];				
		
		// EXPLODE THE NEW REQUEST WITHOUTH THE EXTRA DETAILS FROM MOD_REWRITE
		$input = explode( '/', $inputString );
		
		// TESTING
		//echo "<br>GET: <pre>";
		//var_dump($input);
		//echo "</pre><br>";
		
		// PASS THE PAGE PARAMATER FOR PAGES/CMS
		$input_array['page'] = $input[1];
		
		for( $i = 0; $i < count( $input ); $i++ )
		{
			if( $i != 0 AND $i != 1 )
			{
				//echo "<br>INPUT ".$i.": ".$input[$i]."=".$input[$i + 1]."<br>";
				@$input_array[$input[$i]] = $input[$i + 1];
				$i++;
			}
			//else {
			//	echo "<br>INPUT ".$i.": ".$input[$i]."=".$input[$i + 1]."<br>";
			//}			
		}

		$input_array['website'] = $_GET['website'];
		unset( $_GET );
		$_GET = $input_array;

	}
}

// SET THE WEBSITE ID - config function - !IMPORTANT!
function setWebsiteId($websiteName)
{
	global $_SETTINGS;
	//global $_SESSION;	
	
	// FRONTEND - USE WEBSITENAME FROM MOD REWRITE
	if($websiteName != "")
	{
		// IF DOMAIN
		if(strstr($websiteName,"."))
		{
			$websiteName = str_ireplace("www.","",$websiteName);
			$websiteName = strtolower($websiteName);
			$_SETTINGS['website_id'] = lookupDbValue('websites','website_id',$websiteName,'domain');
		}
		// IF TMP SUBDOMAIN
		else 
		{
			$_SETTINGS['website_id'] = lookupDbValue('websites','website_id',$websiteName,'subdomain');
		}
	}
	// BACKEND ADMIN - USE HOST NO MOD REWRITE
	else
	{
		$host = $_SERVER['HTTP_HOST'];
		// DOMAIN
		if(!strstr($host,"wescms.com"))
		{
			$websiteName = str_ireplace("www.","",$host);
			$websiteName = strtolower($websiteName);
			$_SETTINGS['website_id'] = lookupDbValue('websites','website_id',$websiteName,'domain');
		}
		// IF TMP SUBDOMAIN
		else
		{
			$hostArray = explode(".",$host);
			$websiteName = $hostArray[0];
			$_SETTINGS['website_id'] = lookupDbValue('websites','website_id',$websiteName,'subdomain');
		}
	}
	
	/*
	// IF ! SETTINGS['website_id']
	if($_SETTINGS['website_id'] == "")
	{
		//header('Location: http://wesley.wescms.com');
		header('Location: wescms.com/admin/login.php');
		//exit();
	}
	*/
	
	/*
	else {
		$host = str_ireplace("www.","",$_SERVER['HTTP_HOST']);
		$host = strtolower($host);
		// CHECK HOST AGAINST DB
		$select = "SELECT * FROM websites WHERE domain='".$host."' LIMIT 1";
		$result = doQuery($select);
		if($row	= mysql_fetch_array($result)){
			//$_SESSION['website_id'] = $row['website_id'];
			$_SETTINGS['website_id'] = $row['website_id'];
		}
	}
	*/
}

// MSG FUNCTION
function report($message,$messageType)
{
	$content = "";
	if($message != "")
	{
		// SUCCESS
		if($messageType == "1")
		{
			$content .= "<div class='msg msg-ok'><p>".$message."</p></div>";
		}
		// INFO
		elseif($messageType == "2")
		{
			$content .= "<div class='msg msg-info'><p>".$message."</p></div>";
		}
		// WARN
		elseif($messageType == "3")
		{
			$content .= "<div class='msg msg-warn'><p>".$message."</p></div>";
		}
		// ERROR
		elseif($messageType == "4")
		{
			$content .= "<div class='msg msg-error'><p>".$message."</p></div>";
		}
	}
	return $content;
}

// THE ADMIN TITLE - white labeling
function adminTitle()
{
	return "Wesley&trade; ";
}

// EMAIL FUNCTION Send Email
function sendEmail($to,$from,$subject,$message,$attachment="")
{
	global $_SETTINGS;
	
	$mail = new Rmail();
	$mail->setFrom("$from");
	$mail->setHeader('Reply-To', $to);
	$mail->setSubject("$subject");
	if($attachment != ""){
		$mail->addAttachment(new fileAttachment($attachement));
	}
	//$mail->setPriority('high');
	//$mail->setText('Sample text');	
	/**
	* Set the HTML of the email. Any embedded images will be automatically found as long as you have added them
	* using addEmbeddedImage() as below.
	**/
	$mail->setHTML($message);
	$address = $to;
	$result  = $mail->send(array($address));
	return true;
}

// Make a safe password
function makePass()
{
	$cons = "bcdfghjklmnpqrstvwxyz";
	$vocs = "aeiou";
	for ($x=0; $x < 6; $x++) {
		mt_srand ((double) microtime() * 1000000);
		$con[$x] = substr($cons, mt_rand(0, strlen($cons)-1), 1);
		$voc[$x] = substr($vocs, mt_rand(0, strlen($vocs)-1), 1);
	}
	$makepass = $con[0] . $voc[0] .$con[2] . $con[1] . $voc[1] . $con[3] .$voc[3] . $con[4];
	
	return($makepass);
}

// FORMAT COLUMN NAME
function formatColumnName($columnName)
{
	$columnName = str_replace("_"," ",$columnName);
	$columnName = ucWords($columnName);
	return $columnName;
}

// POWER TABLE
function powerTable($table,$columns)
{
	global $_SETTINGS;
	// ACTION POWER TOOLS
	$tableFormated			= ucwords($table);
	$tableTormatedSingular	= rtrim($table,"s");
	
	// DISPLAY POWER TOOLS
	$content = '	<div id="box1" class="box box-100">
						<div class="boxin">
							<div class="header">
								<h3>'.$tableFormated.'</h3>
								<a class="button" href="#">New '.$tableTormatedSingular.'</a>
								<!--
								<ul>
										<li><a rel="box1-tabular" href="#" class="active">list view</a></li>
										<li><a rel="box1-grid" href="#">grid view</a></li>
								</ul>
								-->
							</div>
							<div id="box1-tabular" class="content"><!-- content box 1 for tab switching -->
								<form class="plain" action="" method="post" enctype="multipart/form-data">
									<fieldset>
										<table cellspacing="0">
											<thead><!-- universal table heading -->
												<tr>';
	
	// CHECK ALL BOX
	$content .= '									<td class="tc"><input type="checkbox" id="data-1-check-all" name="data-1-check-all" value="true" /></td>';
	
	// COLUMN TITLES
	foreach($columns as $column)
	{
		$columnName = "";
		if($column[0] != "")
		{
			$columnName = formatColumnName($column[0]);
		}
		
		elseif($column[0] == "" AND $column[1] == 'relational')
		{
			$columnName = $column[2][4];
		}
		
		elseif($column[0] == "" AND $column[1] == 'directory')
		{
			$columnName = $column[2][1];	
		}
		
		else
		{
			$columnName = "No Type or Method!";
		}
		$content .= '								<td class="tc">'.$columnName.'</td>';
	}
	
	/*
													<td class="tc"><input type="checkbox" id="data-1-check-all" name="data-1-check-all" value="true" /></td>
													<td class="tc">Type</td>
													<th>File</th>
													<td>Description</td>
													<td class="tc">Pub. date</td>
													<td class="tc">Actions</td>
	*/
	
	$content .= '									<td class="tc">Action</td>';
	
	$content .= '								</tr>
											</thead>
											<tfoot><!-- table foot - what to do with selected items -->
												<tr>
													<td colspan="'.(count($columns) + 2).'"><!-- do not forget to set appropriate colspan if you will edit this table -->
														<label>
															with selected do:
															<select name="data-1-groupaction">
																<option value="delete">delete</option>
																<option value="edit">edit</option>
															</select>
														</label>
														<input class="button altbutton" type="submit" value="OK" />
													</td>
												</tr>
											</tfoot>
											<tbody>';
	
	
	$select = "SELECT * FROM ".$table." WHERE website_id='".$_SETTINGS['website_id']."' AND active='1'";
	$result = doQuery($select);
	$num = mysql_num_rows($result);
	$i = 0;
	while($row = mysql_fetch_array($result))
	{
		$content .= '							<tr>';
		$content .= '								<td class="tc"><input type="checkbox" id="data-1-check-1" name="data-1-check-1" value="true" /></td>';
		
		foreach($columns as $column)
		{
			$columnCount 	= count($column);
			$fieldName	= "";
			//echo "COUNT: ".$columnCount."<br>";
			
			// RELATIONAL METHOD CHECK
			if($column[0] == '' AND $column[1] == 'relational')
			{
				$relSelect = 	"SELECT * FROM ".$column[2][0]." b ".
						"LEFT JOIN ".$column[2][2]." c ON b.".$column[2][1]."=c.".$column[2][3]." ".
						"WHERE 1=1 ".
						"AND b.".$column[2][1]."='".$columns[0][0]."'";
				$relResult 	= doQuery($relSelect);
				$relNum  	= mysql_num_rows($relResult);
				$reli 		= 0;
				$content .= '						<td class="tc">';
				$content .= '						<select>';
				$content .= '							<option> -- Select '.$column[2][4].' -- </option>';
				while($relRow = mysql_fetch_array($relResult))
				{
					$content .= '						<option>'.$reli.'</option>';
					$reli++;	
				}
				$content .= '						</select>';
				$content .= '						</td>';
			}
			
			// DIRECTORY METHOD CHECK
			elseif($column[0] == '' AND $column[1] == 'directory')
			{
				
				$content .= '						<td class="tc">';
				$content .= '						<select>';
				$content .= '							<option> -- SELTECT '.$column[2][1].' -- </option>';
				if(is_dir($column[2][0]))
				{
					$dirs = arrayDirectories($column[2][0]);
					foreach($dirs AS $dir)
					{
						$dirName = $dir;
						$dirValue = '';
						$content .= '					<option>'.$dirName.'</option>';
					}
				} else {
					wesleySystemError($column[2][0]." IS NOT A DIRECTORY!");
				}
				$content .= ' 						</select>';
				$content .= '						</td>';
			}
			
			// DISPLAY DATA
			elseif($column[1] == '' AND $column[0] != '')
			{
				$content .= '						<td class="tc">'.$row[$column[0]].'</td>';
			}
			
			// TEXTBOX
			elseif($column[1] == 'textbox' AND $column[0] != '')
			{
				$content .= '						<td class="tc"><input value="'.$row[$column[0]].'"/></td>';
			}
			
			else
			{
				$content .= '						<td class="tc">No Type or Method!</td>';
			}
		}
		
		$content .= '								<td class="tc"><a class="button" href="#">Open</a> <a class="button" href="#">Delete</a></td>';
		$content .= '							</tr>';
		$i++;
	}
	/*
												<tr class="first"><!-- .first for first row of the table (only if there is thead) -->
													<td class="tc"></td>
													<td class="tc"><span class="tag tag-gray">jpeg</span></td>
													<th><a href="#">On vacation with my 13.3Ó honey</a></th>
													<td>Lovely picture of me and my MacBook during sunset É</td>
													<td class="tc">715&nbsp;KB</td>

													<td class="tc"><!-- action icons - feel free to add/modify your own - icons are located in "images/led-ico/*" -->
														<ul class="actions">
															<li><a class="ico" href="#" title="edit"><img src="images/led-ico/pencil.png" alt="edit" /></a></li>
															<li><a class="ico" href="#" title="delete"><img src="images/led-ico/delete.png" alt="delete" /></a></li>
														</ul>
													</td>
												</tr>
												<tr>
													<td class="tc"><input type="checkbox" id="data-1-check-2" name="data-1-check-2" value="true" /></td>

													<td class="tc"><span class="tag tag-gray">jpeg</span></td>
													<th><a href="#">On vacation with my 13.3Ó honey</a></th>
													<td>Lovely picture of me and my MacBook during sunset É</td>
													<td class="tc">715&nbsp;KB</td>
													<td class="tc">
														<ul class="actions">

															<li><a class="ico" href="#" title="edit"><img src="images/led-ico/pencil.png" alt="edit" /></a></li>
															<li><a class="ico" href="#" title="delete"><img src="images/led-ico/delete.png" alt="delete" /></a></li>
														</ul>
													</td>
												</tr>
												<tr>
													<td class="tc"><input type="checkbox" id="data-1-check-3" name="data-1-check-3" value="true" /></td>
													<td class="tc"><span class="tag tag-gray">jpeg</span></td>

													<th><a href="#">On vacation with my 13.3Ó honey</a></th>
													<td>Lovely picture of me and my MacBook during sunset É</td>
													<td class="tc">715&nbsp;KB</td>
													<td class="tc">
														<ul class="actions">
															<li><a class="ico" href="#" title="edit"><img src="images/led-ico/pencil.png" alt="edit" /></a></li>
															<li><a class="ico" href="#" title="delete"><img src="images/led-ico/delete.png" alt="delete" /></a></li>

														</ul>
													</td>
												</tr>
												<tr>
													<td class="tc"><input type="checkbox" id="data-1-check-4" name="data-1-check-4" value="true" /></td>
													<td class="tc"><span class="tag tag-gray">jpeg</span></td>
													<th><a href="#">On vacation with my 13.3Ó honey</a></th>
													<td>Lovely picture of me and my MacBook during sunset É</td>

													<td class="tc">715&nbsp;KB</td>
													<td class="tc">
														<ul class="actions">
															<li><a class="ico" href="#" title="edit"><img src="images/led-ico/pencil.png" alt="edit" /></a></li>
															<li><a class="ico" href="#" title="delete"><img src="images/led-ico/delete.png" alt="delete" /></a></li>
														</ul>
													</td>
												</tr>
	*/
	
	$content .= '							</tbody>
										</table>
									</fieldset>
								</form>
								<div class="pagination"><!-- pagination underneath the box\'s content -->
									<ul>
										<li><a href="#">previous</a></li>
										<li><a href="#">1</a></li>

										<li><a href="#">2</a></li>
										<li><strong>3</strong></li>
										<li><a href="#">4</a></li>
										<li><a href="#">5</a></li>
										<li><a href="#">next</a></li>
									</ul>

								</div>
							</div><!-- .content#box-1-holder -->';
		
	/*		
							<!-- code bellow is only example for switching between tabs, not regular content -->
							<div id="box1-grid" class="content"><!-- content box 2 for tabs switching (hidden by default) -->
								<form class="plain" action="" method="post" enctype="multipart/form-data">
									<fieldset>
										<div class="grid"><!-- grid view -->
											<div class="line">
												<div class="item">

													<div class="inner">
														<a class="thumb" href="#"><img src="_tmp/grid-img.jpg" alt="" /></a>
														<div class="data">
															<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3Ó honey</a></h4>
															<p>Lovely picture of me and my MacBook during sunset É Lovely picture of me and my MacBook during sunset É Lovely picture of me and my MacBook during sunset É</p>
															<p>Lovely picture of me and my MacBook during sunset É</p>

															<div class="meta">715&nbsp;KB, 1920&times;1200</div>
															<ul class="actions">
																<li><a class="ico" href="#" title="edit"><img src="images/led-ico/pencil.png" alt="edit" /></a></li>
																<li><a class="ico" href="#" title="delete"><img src="images/led-ico/delete.png" alt="delete" /></a></li>
															</ul>
														</div>
													</div>

												</div>
												<div class="item">
													<div class="inner">
														<a class="thumb" href="#"><img src="_tmp/grid-img.jpg" alt="" /></a>
														<div class="data">
															<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3Ó honey</a></h4>
															<p>Lovely picture of me and my MacBook during sunset É Lovely picture of me and my MacBook during sunset</p>

															<p>Lovely picture of me and my MacBook during sunset É</p>
															<div class="meta">715&nbsp;KB, 1920&times;1200</div>
															<ul class="actions">
																<li><a class="ico" href="#" title="edit"><img src="images/led-ico/pencil.png" alt="edit" /></a></li>
																<li><a class="ico" href="#" title="delete"><img src="images/led-ico/delete.png" alt="delete" /></a></li>
															</ul>
														</div>

													</div>
												</div>
											</div>
											<div class="line">
												<div class="item">
													<div class="inner">
														<a class="thumb" href="#"><img src="_tmp/grid-img.jpg" alt="" /></a>
														<div class="data">
															<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3Ó honey</a></h4>

															<p>Lovely picture of me and my MacBook during sunset É Lovely picture of me and my MacBook during sunset É Lovely picture of me and my MacBook during sunset É</p>
															<p>Lovely picture of me and my MacBook during sunset É</p>
															<div class="meta">715&nbsp;KB, 1920&times;1200</div>
															<ul class="actions">
																<li><a class="ico" href="#" title="edit"><img src="images/led-ico/pencil.png" alt="edit" /></a></li>
																<li><a class="ico" href="#" title="delete"><img src="images/led-ico/delete.png" alt="delete" /></a></li>

															</ul>
														</div>
													</div>
												</div>
												<div class="item">
													<div class="inner">
														<a class="thumb" href="#"><img src="_tmp/grid-img.jpg" alt="" /></a>
														<div class="data">
															<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3Ó honey</a></h4>

															<p>Lovely picture of me and my MacBook during sunset É Lovely picture of me and my MacBook during sunset É Lovely picture of me and my MacBook during sunset É</p>
															<p>Lovely picture of me and my MacBook during sunset É</p>
															<div class="meta">715&nbsp;KB, 1920&times;1200</div>
															<ul class="actions">
																<li><a class="ico" href="#" title="edit"><img src="images/led-ico/pencil.png" alt="edit" /></a></li>
																<li><a class="ico" href="#" title="delete"><img src="images/led-ico/delete.png" alt="delete" /></a></li>

															</ul>
														</div>
													</div>
												</div>
											</div>		
										</div>
									</fieldset>
								</form>
								<div class="pagination">

									<ul>
										<li><a href="#">previous</a></li>
										<li><a href="#">1</a></li>
										<li><a href="#">2</a></li>
										<li><strong>3</strong></li>
										<li><a href="#">4</a></li>

										<li><a href="#">5</a></li>
										<li><a href="#">next</a></li>
									</ul>
								</div>
							</div><!-- .content#box-1-grid -->
	*/						
	
	$content .= '		</div>
					</div>';
	echo $content;
}

/**
 * 
 * 
 * 
 */
function getWebsiteLayerIndex(){
  global $_SETTINGS;
  $layerIndex = 10001;
  $select = "  SELECT zindex 
               FROM things 
               WHERE 
               parent_thing_id='' AND 
               website_id = '".$_SETTINGS['website_id']."' AND 
               active = '1' 
               ORDER BY zindex DESC 
               LIMIT 1";
  $result = doQuery($select);
  $row = mysql_fetch_array($result);
  return $row['zindex'];
}

function getWidgetLayerIndex($thing_id){
  return 1;
}

// TRUNCATE STRING
/**
 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
 *
 * @param string $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 *
 * @return string Trimmed string.
 */
function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
	if ($considerHtml) {
		// if the plain text is shorter than the maximum length, return the whole text
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		// splits all html-tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($ending);
		$open_tags = array();
		$truncate = '';
		foreach ($lines as $line_matchings) {
			// if there is any html-tag in this line, handle it and add it (uncounted) to the output
			if (!empty($line_matchings[1])) {
				// if it's an "empty element" with or without xhtml-conform closing slash
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
				// if tag is a closing tag
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
					unset($open_tags[$pos]);
					}
				// if tag is an opening tag
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				// add html-tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if ($total_length+$content_length> $length) {
				// the number of characters which are left
				$left = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach ($entities[0] as $entity) {
						if ($entity[1]+1-$entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
				// maximum lenght is reached, so get off the loop
				break;
			} else {
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			// if the maximum length is reached, get off the loop
			if($total_length>= $length) {
				break;
			}
		}
	} else {
		if (strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = substr($text, 0, $length - strlen($ending));
		}
	}
	// if the words shouldn't be cut in the middle...
	if (!$exact) {
		// ...search the last occurance of a space...
		$spacepos = strrpos($truncate, ' ');
		if (isset($spacepos)) {
			// ...and cut the text in this position
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	// add the defined ending to the text
	$truncate .= $ending;
	if($considerHtml) {
		// close all unclosed html-tags
		foreach ($open_tags as $tag) {
			$truncate .= '</' . $tag . '>';
		}
	}
	return $truncate;
}
 
function formatBytes($bytes, $precision = 2) {
	$units = array('B', 'KB', 'MB', 'GB', 'TB');
	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);
	$bytes /= pow(1024, $pow);
	return round($bytes, $precision) . ' ' . $units[$pow];
}  
 
// URL REQUEST (GET, POST, REQUEST)
function urlRequest($var)
{
	global $_GET;
	global $_REQUEST;
	global $_POST;
	
	if(isset($_GET[$var]))
	{
		return $_GET[$var];
	}
	elseif(isset($_REQUEST[$var]))
	{
		return $_REQUEST[$var];
	} 
	elseif(isset($_POST[$var]))
	{
		return $_POST[$var];
	}
	else
	{
		return "";
	}
}

// URL SESSION REQUEST
function sessionRequest($var)
{
	global $_SESSION;
	if(isset($_SESSION[$var]))
	{
		return $_SESSION[$var];
	}
	else
	{
		return "";
	}
}

// URL SETTING REQUEST
function settingRequest($var)
{
	global $_SETTINGS;
	if(isset($_SETTINGS[$var]))
	{
		return $_SETTINGS[$var];
	}
	else
	{
		return "";
	}
}

// RETURN ARRAY OF DIRECTORY CONTENTS
function arrayDirectories($dir)
{
	$dirContents = Array();
	if(is_dir($dir)){
		if($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle)))
			{
				if ($file != "." && $file != "..")
				{
					array_push($dirContents,$file);	
				}
			}
			closedir($handle);
		}
	}
	else
	{
		wesleySystemError('Directory doesn\'t exist.');
	}
	return $dirContents;	
}

function checkWesleySiteStatus($websiteId){
  $url = lookupDbValue('websites','url','website_id',$websiteId);
  if($url == ''){
    return true;
  }
  return false;
}

// SYSTEM ERROR
function wesleySystemError($message)
{
	echo $message;
	echo "<Br>";
}

// DEBUG ARRAY
function debugArray($array)
{
	echo "<br><pre>";
	var_dump($array);
	echo "</pre><br>";
}

function statusField($table,$column,$xid,$column_id,$value)
{
	$content = "";
	$content .= "	<select class='ajax-select singleselect' table='".$table."' column='".$column."' columnid='page_id' id='".randomNumber()."' xid='".$xid."'>";
	$content .= "		<option value='Published' ".selected('Published',$value).">Published</option>";
	$content .= "		<option value='Pending' ".selected('Pending',$value).">Pending</option>";
	$content .= "		<option value='Draft' ".selected('Draft',$value).">Draft</option>";
	$content .= "	</select>";
	$content .= "	<input type='text' value='' style='display:none;' />";
	$content .= "	<script type='text/javascript'>";
	
	$content .= "	</script>";
	return $content;
}

function ajaxUpdate($table,$column,$xid,$column_id,$value)
{
	$update = 	"UPDATE ".$table." SET ".$column."='".$value."' WHERE ".$column_id."='".$xid."'";
	doQuery($update);
	return true;
}

function ajaxInsert()
{
	$insert =	"";
	return true;
}

function ajaxUpdateWidgetThingy($parentId,$thingName,$columnName,$value)
{
	global $_SETTINGS;
	// CHECK IF THE WIDGET THINGY ALREADY EXISTS
	$checkSelect = 	"SELECT * FROM things ".
					"WHERE 1=1 ".
					"AND parent_thing_id='".$parentId."' ".
					"AND name='".$thingName."' ".
					"AND active='1' ".
					"AND website_id='".$_SETTINGS['website_id']."'";
	$checkResult = 	doQuery($checkSelect);
	if($checkRow = mysql_fetch_array($checkResult))
	{
		// UPDATE
		$update = 	"UPDATE things SET ".
					"".$columnName."='".$value."' ".
					"WHERE ".
					"parent_thing_id='".$parentId."' ".
					"AND name='".$thingName."' ".
					"AND active='1'";
		doQuery($update);
	} else {	
		// INSERT
		$insert = 	"INSERT INTO things SET ".
					"".$columnName."='".$value."',".
					"parent_thing_id='".$parentId."',".
					"name='".$thingName."',".
					"active='1',".
					"website_id='".$_SETTINGS['website_id']."'";
		doQuery($insert);
	}
	return true;
}

function ajaxDelete($table,$xid,$column_id)
{
	$delete = 	"DELETE FROM ".$table." WHERE ".$column_id."='".$xid."'";
	doQuery($delete);
	return true;
}

function widgetNav($buttons=""){
  $content = "";
  $content .= "	<div class='wesley-cmsnav-widget-toolbar'>";
  //$content .= "		<span>".($title != "" ? $title : "Widget")."</span>";
  $content .= "		<ul class='wesley-cmsnav-buttons wesley-cmsnav-rightbuttons' style='float:right;'>";
  
  // ADD BUTTONS
  if($buttons != "")
  {
    $buttons = rtrim($buttons,",");
    $buttonsArray = explode(",",$buttons);
    foreach($buttonsArray as $toolbarButton)
    {
      $content .= "		<li><a href='javascript:void(0);' class='wesley-cmsnav-widget-".$toolbarButton."'>".ucwords($toolbarButton)."</a></li>";
    }
  }
  
  $content .= "			<li><a href='' class='wesley-cmsnav-widget-move'>Move</a></li>
						<!-- <li><a href='' class='wesley-cmsnav-widget-resize'>Resize</a></li>-->
						<li><a href='' class='wesley-cmsnav-widget-close'>Delete</a></li>";
  
  $content .= "		</ul>";
  $content .= "	</div>";
  
  return $content;
}

function widgetHeader($title,$width,$height,$panelpath,$id,$top,$left,$buttons="",$overflowHidden=true,$zIndex=10001)
{
	// TITLE 		= The widget's name in the title bar
	// WIDTH 		= The widget's default width
	// HEIGHT 		= The widget's default height
	// PANELPATH	= The path to the widget's panel 
	
	if($overflowHidden == true){
		$overflowClass = "wesley-widget-overflow-hidden";
	} else {
		$overflowClass = "wesley-widget-overflow-visible";
	}
	
	
	global $_SESSION;
	$content = "";
	//echo "SESSION: ".$_SESSION['cms'];
	if(sessionRequest('cms') == 1){
		$content .= "<div class='wesley-cmsnav-widget ".$overflowClass." cms-widget' id='".$id."' top='".$top."' left='".$left."' panelpath='".$panelpath."' style='width:".$width."; height:".$height."; top:".$top."; left:".$left.";'>";
		
		
		$content .= "	<div class='wesley-cmsnav-widget-toolbar'>";
		//$content .= "		<span>".($title != "" ? $title : "Widget")."</span>";		
		$content .= "		<ul class='wesley-cmsnav-buttons wesley-cmsnav-rightbuttons' style='float:right;'>";
		
		// ADD BUTTONS
		if($buttons != "")
		{
				$buttons = rtrim($buttons,",");
				$buttonsArray = explode(",",$buttons);
				foreach($buttonsArray as $toolbarButton)
				{
						$content .= "		<li><a href='javascript:void(0);' class='wesley-cmsnav-widget-".$toolbarButton."'>".ucwords($toolbarButton)."</a></li>";
				}
		}						
		
		$content .= "			<li><a href='' class='wesley-cmsnav-widget-move'>Move</a></li>
								<!-- <li><a href='' class='wesley-cmsnav-widget-resize'>Resize</a></li> -->
								<li><a href='' class='wesley-cmsnav-widget-close'>Close</a></li>";
		
		$content .= "		</ul>";
		$content .= "	</div>";
		
		
		$content .= "	<div class='wesley-cmsnav-widget-content'>";
	} else {
		$content .= "<div class='wesley-cms-widget' style='width:".$width."; height:".$height."; top:".$top."; left:".$left.";'>";
	}
	return $content;
}

function widgetFooter()
{
	global $_SESSION;
	$content = "";
	if(sessionRequest('cms') == 1){
		$content .= "	</div>";
		$content .= "</div>";
	} else {
		$content .= "</div>";
	}
	return $content;
}


?>