<?php
/*************************************************************************************************************************************
*
*   Copyright (c) 2011 Karl Steltenpohl Development LLC. All Rights Reserved.
*	
*	This file is part of Karl Steltenpohl Development LLC's WES (Website Enterprise Software).
*	Authored By Karl Steltenpohl
*	Commercial License
*	http://www.wescms.com/license
*
*	http://www.wescms.com
*	http://www.webksd.com/wes
* 	http://www.karlsteltenpohl.com/wes
*
*************************************************************************************************************************************/

	/**
	 * 
	 * SQL QUERY FUNCTION
	 * 
	 **/
	function doQuery ($query,$error=1)
	{		
		global $_SETTINGS;
		$queried = mysql_query($query);		
		if(mysql_error() AND $error == 1){
			echo "Uh-oh, our website is currently experiencing a hic-up. We aploigize and will have it corrected asap!";
			$message = "<br><br>".mysql_error()." <br><br> IN QUERY: <br>".$query."";
			sendEmail('karl@webksd.com',$_SETTINGS['website'],"SQL QUERY ERROR - ".$_SETTINGS['website']."",$message);
			echo "	<br><br>
				".mysql_error()."
				<br><br>
				".$query."
				<!-- <script type='text/javascript'>alert(\"".mysql_error()."\");</script> ->
				<Br><br>";
			exit();
		}
		return $queried;				
	}
	
	/**
	 *
	 * GET SQL ARRAY
	 *
	 */
	function getSqlSelectArray($table,$sort=""){
		$select = "SELECT * FROM ".$table." WHERE active='1' ".$sort."";
		$result = doQuery($select);
		$i = 0;
		$num = mysql_num_rows($result);
		return $result;
	}
	
	/**
	 *
	 * RETURN A CELL VALUE
	 *
	 */
	function lookupDbValue($table, $column, $lookup, $id='x')
	{
		global $_SETTINGS;		
		$sel = 	"SELECT `$column` FROM `$table` WHERE `$id`='$lookup'";
		$res = doQuery($sel);
		$row = mysql_fetch_array($res);
		return $row[$column];
	}
	
	/**
	 *
	 * GET NEXT ID
	 *
	 */
	function nextId($table)
	{	
		$r = mysql_query("SHOW TABLE STATUS LIKE '$table' ");
		$row = mysql_fetch_array($r);
		$Auto_increment = $row['Auto_increment'];
		//mysql_free_result($r);
		return $Auto_increment;		
	}
	
	/**
	 *
	 * ESCAPE A VARIABLE
	 *
	 */
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
	
	/**
	 *
	 * ESCAPE AN ARRAY
	 *
	 */
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
	
	/*** REMOVE ALL NON ALPHANUMERIC BUT NOT SPACES ***/
	function stripVariable($keyword)
	{
		$var = $keyword;
		$str = str_replace(" ", "+", $var);
		$stripped = ereg_replace("[^A-Za-z0-9\+]", "", $str);

		$str1 = str_replace("+", " ", $stripped); 
		return $str1;
	}
		
	/**
	 *
	 * REMOVE ALL LINE BREAKS
	 *
	 */
	function removeLineBreaks($string){
		$string = str_replace(array("\r", "\r\n", "\n"), '', $string);
		return $string;
	}
	
	/**
	 *
	 * REPORT FUNCTION
	 *
	 */
	function report($report,$success)
	{
		if($report != "")
		{
			$report = str_replace("_",".",$report);
			if($success == "1")
			{
				ReportSuccess($report);
			}
			else
			{
				ReportError($report);
			}
		}
	}
	
	
	
	/**
	 *
	 * CHMOD THROUGH FTP
	 * 
	 *
	 */
	function chmod_11oo10($path, $mod, $ftp_details)
	{
		// extract ftp details (array keys as variable names)
		extract ($ftp_details);
	   
		// set up basic connection
		$conn_id = ftp_connect($ftp_server);
	   
		// login with username and password
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	   
		// try to chmod $path directory
		if (ftp_site($conn_id, 'CHMOD '.$mod.' '.$ftp_root.$path) !== false) {
			$success=TRUE;
		}
		else {
			$success=FALSE;
		}

		// close the connection
		ftp_close($conn_id);
		return $success;
	}
	
	
	/**
	 *
	 * CHMOD DIRECTORY 777
	 * ONLY WORK DOWNWARDS I BELIEVE??
	 *
	 */
	function chmodDirectory( $path = '.', $level = 0 ){  
		$ignore = array( 'cgi-bin', '.', '..' );
		$dh = @opendir( $path );
		while( false !== ( $file = readdir( $dh ) ) ){ // Loop through the directory
			if( !in_array( $file, $ignore ) ){
				if( is_dir( "$path/$file" ) ){
					chmod("$path/$file",0777);
					chmodDirectory( "$path/$file", ($level+1));
				} else {
					chmod("$path/$file",0777); // desired permission settings
				}//elseif
			}//if in array
		}//while
		closedir( $dh );
	}//function
	
	/**
	 *
	 * CHECK IF SELECTD
	 *
	 */
	function selected($i, $x)
	{
		if ($i!=NULL)
		{
			if ($i==$x)
				return ' selected="selected"';
		}
	}
	
	/**
	 *
	 * CHECK IF CHECKED
	 *
	 */
	function isChecked($i, $x)
	{
		if ($i==$x)
			return " CHECKED";
	}
		
	function isSelected($i,$x)
	{
		if ($i==$x)
			return " SELECTED ";
	}
		
	//---//
	
	/*** FORM Functions - Find a value in an array, and return checked, $array = array of values, $val = value to find in above array ***/
	function isCheckedArray($array, $val)
	{
		if (is_array($array) && in_array($val, $array))
		{
			return " CHECKED";
		}
	}
	
	/*** FORM Functions - WriteSelect ***/
	function WriteYesNoSelect($name, $selected, $js="")
	{		
		echo '<select name="' . $name . '" '.$js.'>';
		
		echo '<option value="1"' . selected($selected, '1') . '>Yes</option>';
		echo '<option value="0"' . selected($selected, '0') . '>No</option>';
	
		echo '</select>';
		
	}
	
	/*** FORM Functions - Verify that all data posted has a value ***/
	function VerifyPost($_POST)
	{		
		$i = 0;
		$keys = array_keys($_POST);
		
		foreach ($_POST as $d) {

			if (strstr($keys[$i],"_NOTREQ")!=FALSE) {
			
				$keys[$i] = str_replace("_NOTREQ", "", $keys[$i]);
				$_POST[$keys[$i]] = $d;
			
			} else {
				if ($d=="" || $d=="NULL") {
					return 0;
				}
			}

			$i++;
		}

		return 1;
	}
	
	/*** FORM Functions - Error Reporting Function ***/
	function ReportError($s)
	{
		echo "<span class=\"errorbox\" id=\"errorbox\">$s</span>";		
		$javascript .= 	"<script>".
						"jQuery.fn.delay = function(time,func){".
						"	return this.each(function(){".
						"		setTimeout(func,time);".
						"	});".
						"};".
						'$(".errorbox").delay(2500, function(){$(".errorbox").fadeOut("slow")});'.
						"</script>";
		echo $javascript;		
	}
	
	/*** FORM Functions - Success Reporting function ***/
	function ReportSuccess($s)
	{
		echo "<span class=\"successbox\" id=\"successbox\">$s</span>";
		$javascript .= 	"<script>".
						"jQuery.fn.delay = function(time,func){".
						"	return this.each(function(){".
						"		setTimeout(func,time);".
						"	});".
						"};".
						'$(".successbox").delay(2500, function(){$(".successbox").fadeOut("slow")});'.
						"</script>";
		echo $javascript;
	}
	
	/**
	 *
	 * FORM Functions - Form String Encode
	 *
	 *
	 */
	function form_encode($string)
	{
	/*make sure you remove the spaces in the first variable of the str_replace function, Word press doesn't seem to like to print out the htmlentity of the ampersand which is understandable*/
	return str_replace("&amp;", "&", (htmlentities(stripslashes($string), ENT_QUOTES)));
	}
	
	/**
	 *
	 * CONVERT RELATIVE TO ABSOLUTE PATHS FOR WES
	 *
	 */
	function relative2absolute($absolute, $relative) {
		$p = @parse_url($relative);
		if(!$p) {
			//$relative is a seriously malformed URL
			return false;
		}
		
		// THIS WILL RETURN IF THE PATH HAS HTTP
		if(isset($p["scheme"])) return $relative;
 
		$parts=(parse_url($absolute));
 
		if(substr($relative,0,1)=='/') {
			$relative = substr($relative, 1);
		}
		
		if(isset($parts['path'])){
			 $aparts=explode('/',$parts['path']);
			 array_pop($aparts);
			 $aparts=array_filter($aparts);
		} else {
			 $aparts=array();
		}
	   $rparts = (explode("/", $relative));
	   $cparts = array_merge($aparts, $rparts);
	   foreach($cparts as $i => $part) {
			if($part == '.') {
				unset($cparts[$i]);
			} else if($part == '..') {
				unset($cparts[$i]);
				unset($cparts[$i-1]);
			}
		}
		
		$path = implode("/", $cparts);
 
		$url = '';
		if($parts['scheme']) {
			$url = "$parts[scheme]://";
		}
		if(isset($parts['user'])) {
			$url .= $parts['user'];
			if(isset($parts['pass'])) {
				$url .= ":".$parts['pass'];
			}
			$url .= "@";
		}
		if(isset($parts['host'])) {
			$url .= $parts['host']."/";
		}
		$url .= $path;
 
		return $url;
	}
	
	/*
	*
	* SELECT BOX FROM TABLE
	* This function turns an sql table into a select box input
	*
	*/
	function selectTable($table,$name,$value,$option,$order,$direction="DESC",$other="",$selectedValue = "")
	{
		
		//echo "TABLE: $table<br>";
		//echo "SELECT NAME: $name<br>";
		//echo "SELECT VALUE: $value<br>";
		//echo "SELECT OPTION: $option<br>";
		//echo "ORDER BY: $order<br>";
		//echo "DIRECTION: $direction<br>";
		//echo "OTHER: $other<br>";
		
		$select1 = "SELECT * FROM ".$table." WHERE active='1'";
		if($order != ""){ $select1 .= " ORDER BY ".$order." ".$direction.""; }
		
		//echo "SEL: ".$select1."<Br>";
		//exit();
		
		$result1 = doQuery($select1);
		
		$num1 = mysql_num_rows($result1);
		$i1 = 0;
		echo '<select class="select1" name="'.$name.'" id="'.$name.'">';
		
		//
		// IF OTHER THEN ADD OTHER AS AN OPTION ID VALUE OF 0
		//
		if($other != ""){			
			if($other == 1){ $other = "Other"; }
			echo '<option value="0" ';
			if($_POST[$name] == '0'){ echo 'SELECTED'; }
			echo ' >'.$other.'</option>';
		}
		
		while($i1<$num1){
			$row1 = mysql_fetch_array($result1);
			
			//
			// SET THE OPTION VALUE
			//
			$option_array = explode(",",$option);
			$visibleoption = "";
			foreach($option_array as $noption){
				if($noption != ""){
					$visibleoption .= $row1[$noption].",";
				}
			}
			
			echo '<option value="'.$row1[$value].'" ';
			if($_POST[$name] == $row1[$value] OR $selectedValue == $row1[$value]){ echo 'SELECTED'; }
			echo ' >'.trim($visibleoption,",").'</option>';				
			$i1++;
		}
		
		echo '</select>';
	}
	
	/**
	 * SELECT BOX FROM TABLE WITH HIERARCHY 
	 * This function turns an sql table into a select box input
	 * Used for tables with hierarchy like categories / parent ids
	 **/
	function hierarchyselectTable($table,$name,$value,$option,$order,$direction="DESC",$other=0,$none=0)
	{
		//
		// SELECT THE TOP LEVEL
		//
		$select1 = "SELECT * FROM ".$table." WHERE active='1' AND (parent_id='' OR parent_id=NULL OR parent_id='0' OR parent_id=0)";
		if($order != ""){ $select1 .= " ORDER BY ".$order." ".$direction.""; }
		$result1 = doQuery($select1);
		$num1 = mysql_num_rows($result1);
		$i1 = 0;
		echo '<select name="'.$name.'">';
		while($i1<$num1){
			$row1 = mysql_fetch_array($result1);
			
			if($none == 1){
			
				echo "<option value=''> -- Select -- </option>";
			
			}
			
			//
			// SET THE OPTION VALUE
			//
			$option_array = explode(",",$option);
			$visibleoption = "";
			foreach($option_array as $noption){
				$visibleoption .= $row1[$noption];
			}			
			
			echo '<option value="'.$row1[$value].'" ';
			if($_POST[$name] == $row1[$value]){ echo 'SELECTED'; }
			echo ' >'.$visibleoption.'</option>';		

			//
			// SELECT THE 2nd Level
			//
			$select2 = "SELECT * FROM ".$table." WHERE active='1' AND parent_id='".$row1['category_id']."'";
			if($order != ""){ $select2 .= " ORDER BY ".$order." ".$direction.""; }
			$result2 = doQuery($select2);
			$num2 = mysql_num_rows($result2);
			$i2 = 0;
			while($i2<$num2){
				$row2 = mysql_fetch_array($result2);
				
				//
				// SET THE OPTION VALUE
				//
				$option_array = explode(",",$option);
				$visibleoption = "";
				foreach($option_array as $noption){
					$visibleoption .= $row2[$noption];
				}			
				
				echo '<option value="'.$row2[$value].'" ';
				if($_POST[$name] == $row2[$value]){ echo 'SELECTED'; }
				echo ' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$visibleoption.'</option>';		

				//
				// SELECT THE 3rd Level
				//
				$select3 = "SELECT * FROM ".$table." WHERE active='1' AND parent_id='".$row2['category_id']."'";
				if($order != ""){ $select3 .= " ORDER BY ".$order." ".$direction.""; }
				$result3 = doQuery($select3);
				$num3 = mysql_num_rows($result3);
				$i3 = 0;
				while($i3<$num3){
					$row3 = mysql_fetch_array($result3);
					
					//
					// SET THE OPTION VALUE
					//
					$option_array = explode(",",$option);
					$visibleoption = "";
					foreach($option_array as $noption){
						$visibleoption .= $row3[$noption];
					}			
					
					echo '<option value="'.$row3[$value].'" ';
					if($_POST[$name] == $row3[$value]){ echo 'SELECTED'; }
					echo ' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$visibleoption.'</option>';		

					$i3++;
				}				
				$i2++;
			}
			$i1++;
		}
		//
		// IF OTHER THEN ADD OTHER AS AN OPTION ID VALUE OF 0
		//
		if($other == 1){
			echo '<option value="0" ';
			if($_POST[$name] == '0'){ echo 'SELECTED'; }
			echo ' >Other</option>';
		}
		
	
		
		echo '</select>';
		//echo "$num1 : $num2 : $num3";
	}
	
	function multiselectTable($table,$name,$value,$option,$order,$direction="DESC",$other=0, $relationaltable='', $columnname='', $itemid='0',$columnname2='category_id')
	{
		//
		// SELECT THE TOP LEVEL
		//
		$select1 = "SELECT * FROM ".$table." WHERE active='1'";
		if($order != ""){ $select1 .= " ORDER BY ".$order." ".$direction.""; }
		$result1 = doQuery($select1);
		$num1 = mysql_num_rows($result1);
		$i1 = 0;
		echo '<select class="multiselect" name="'.$name.'" multiple="multiple" size="5">';
		while($i1<$num1){
			$row1 = mysql_fetch_array($result1);
			
			//
			// SET THE OPTION VALUE
			//
			$option_array = explode(",",$option);
			$visibleoption = "";
			foreach($option_array as $noption){
				$visibleoption .= $row1[$noption];
			}			
			
			echo '<option value="'.$row1[$value].'" ';
			//DETERMINE SELECTED LEVEL 1
			if($relationaltable != ''){
				$sela = "SELECT * FROM `".$relationaltable."` WHERE ".$columnname."='".$itemid."'";
				$resa = doQuery($sela);
				$numa = mysql_num_rows($resa);
				$ia = 0;
				while($ia<$numa){
					$rowa = mysql_fetch_array($resa);
					//$reporter .= "--". $rowa['category_id'] ."";
					if($rowa[$columnname2] == $row1[$value]){
						echo 'SELECTED';
					}
					$ia++;
				}
				//$reporter .= "-". $sela ."<Br>";
			}
			//if($_POST[$name] == $row1[$value]){ echo 'SELECTED'; }
			echo ' >'.$visibleoption.'</option>';		

			
			$i1++;
		}
		//
		// IF OTHER THEN ADD OTHER AS AN OPTION ID VALUE OF 0
		//
		//if($other == 1){
			echo '<option value="0" ';
			if($_POST[$name] == '0'){ echo 'SELECTED'; }
			echo ' >None</option>';
		//}
		echo '</select>';
		//echo "$num1 : $num2 : $num3";
		//echo "$reporter";
		//exit;
	}
	
	function hierarchymultiselectTable($table,$name,$value,$option,$order,$direction="DESC",$other=0, $relationaltable='ecommerce_product_category_relational', $columnname='product_id', $itemid='0' )
	{
		//
		// SELECT THE TOP LEVEL
		//
		$select1 = "SELECT * FROM ".$table." WHERE active='1' AND (parent_id='' OR parent_id=NULL OR parent_id='0' OR parent_id=0)";
		if($order != ""){ $select1 .= " ORDER BY ".$order." ".$direction.""; }
		$result1 = doQuery($select1);
		$num1 = mysql_num_rows($result1);
		$i1 = 0;
		
		$sel = "SELECT * FROM ".$table." WHERE active='1'";
		$res = doQuery($sel);
		$nu = mysql_num_rows($res);
		
		$height = $nu * 10;
		$height = "height:".$height."px;";
		
		echo '<select class="multiselect" name="'.$name.'" id="'.$name.'" multiple="multiple" size="5" style="font-size:11px; '.$height.'">';
		while($i1<$num1){
			$row1 = mysql_fetch_array($result1);
			
			//
			// SET THE OPTION VALUE
			//
			$option_array = explode(",",$option);
			$visibleoption = "";
			foreach($option_array as $noption){
				$visibleoption .= $row1[$noption];
			}			
			
			echo '<option value="'.$row1[$value].'" ';
			//DETERMINE SELECTED LEVEL 1
			if($relationaltable != ''){
				$sela = "SELECT * FROM `".$relationaltable."` WHERE ".$columnname."='".$itemid."'";
				$resa = doQuery($sela);
				$numa = mysql_num_rows($resa);
				$ia = 0;
				while($ia<$numa){
					$rowa = mysql_fetch_array($resa);
					//$reporter .= "--". $rowa['category_id'] ."";
					if($rowa['category_id'] == $row1[$value]){
						echo 'SELECTED';
					}
					$ia++;
				}
				//$reporter .= "-". $sela ."<Br>";
			}
			//if($_POST[$name] == $row1[$value]){ echo 'SELECTED'; }
			echo ' >'.$visibleoption.'</option>';		

			//
			// SELECT THE 2nd Level
			//
			$select2 = "SELECT * FROM ".$table." WHERE active='1' AND parent_id='".$row1['category_id']."'";
			if($order != ""){ $select2 .= " ORDER BY ".$order." ".$direction.""; }
			$result2 = doQuery($select2);
			$num2 = mysql_num_rows($result2);
			$i2 = 0;
			while($i2<$num2){
				$row2 = mysql_fetch_array($result2);
				
				//
				// SET THE OPTION VALUE
				//
				$option_array = explode(",",$option);
				$visibleoption = "";
				foreach($option_array as $noption){
					$visibleoption .= $row2[$noption];
				}			
				
				echo '<option value="'.$row2[$value].'" ';
				//DETERMINE SELECTED LEVEL 2
				if($relationaltable != ''){
					$selb = "SELECT * FROM `".$relationaltable."` WHERE ".$columnname."='".$itemid."'";
					$resb = doQuery($selb);
					$numb = mysql_num_rows($resb);
					$ib = 0;
					while($ib<$numb){
						$rowb = mysql_fetch_array($resb);
						//$reporter .= "--". $rowa['category_id'] ."";
						if($rowb['category_id'] == $row2[$value]){
							echo 'SELECTED';
						}
						$ib++;
					}
					//$reporter .= "-". $sela ."<Br>";
				}			
				//if($_POST[$name] == $row2[$value]){ echo 'SELECTED'; }
				echo ' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$visibleoption.'</option>';		

				//
				// SELECT THE 3rd Level
				//
				$select3 = "SELECT * FROM ".$table." WHERE active='1' AND parent_id='".$row2['category_id']."'";
				if($order != ""){ $select3 .= " ORDER BY ".$order." ".$direction.""; }
				$result3 = doQuery($select3);
				$num3 = mysql_num_rows($result3);
				$i3 = 0;
				while($i3<$num3){
					$row3 = mysql_fetch_array($result3);
					
					//
					// SET THE OPTION VALUE
					//
					$option_array = explode(",",$option);
					$visibleoption = "";
					foreach($option_array as $noption){
						$visibleoption .= $row3[$noption];
					}			
					
					echo '<option value="'.$row3[$value].'" ';
					if($relationaltable != ''){
						$selc = "SELECT * FROM `".$relationaltable."` WHERE ".$columnname."='".$itemid."'";
						$resc = doQuery($selc);
						$numc = mysql_num_rows($resc);
						$ic = 0;
						while($ic<$numc){
							$rowc = mysql_fetch_array($resc);
							//$reporter .= "--". $rowa['category_id'] ."";
							if($rowc['category_id'] == $row3[$value]){
								echo 'SELECTED';
							}
							$ic++;
						}
						//$reporter .= "-". $sela ."<Br>";
					}
					//if($_POST[$name] == $row3[$value]){ echo 'SELECTED'; }
					echo ' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$visibleoption.'</option>';		

					$i3++;
				}				
				$i2++;
			}
			$i1++;
		}
		//
		// IF OTHER THEN ADD OTHER AS AN OPTION ID VALUE OF 0
		//
		if($other == 1){
			echo '<option value="0" ';
			if($_POST[$name] == '0'){ echo 'SELECTED'; }
			echo ' >Other</option>';
		}
		echo '</select>';
		//echo "$num1 : $num2 : $num3";
		echo "$reporter";
		//exit;
	}
	
	/*** TABLE Functions - Admin Table Header ***/
	function tableHeader($t,$cols=1,$width=500)
	{

		GLOBAL $_SETTINGS;

		$str = "<TABLE BGCOLOR=\"{$_SETTINGS["headerColor"][2]}\" class=\"results\" WIDTH=$width ALIGN=CENTER BORDER=0 CELLSPACING=0 CELLPADDING=3 id=\"\">";
		$str .= "<TR><Th COLSPAN=\"$cols\" class=\"heading\" >$t</Th></TR>";
		return $str;
	}
	
	/*** !!DEPRECATED!! DO NOT USE!! TABLE Functions - Toggler Table Header ***/
	function togglertableHeader($t,$width=500,$identifier)
	{

		GLOBAL $_SETTINGS;
		
		$str .= "<TABLE BGCOLOR=\"{$_SETTINGS["headerColor"][2]}\" class=\"results\" WIDTH=$width ALIGN=CENTER BORDER=0 CELLSPACING=0 CELLPADDING=3 id=\"\">";
		$str .= "<TR><Td STYLE=\"height:30px; padding:0 3px; COLOR:#000; font-family:sans-serif; FONT-SIZE:15px;\"><span class=\"toggler".$identifier." tog\" style=\"display:block;\"><b class=\"\" style=\"float:left;\">$t</b> <img src=\"images/toggle-arrow-down.png\" style=\"margin:0px; float:right;\"></span></Td></TR>";
		$str .= "</table>";
		return $str;
	}
	
	/*** !!DEPRECATED!! DO NOT USE!! TABLE Functions - Toggle Table Header ***/
	function toggletableHeader($cols=1,$width=500,$identifier){

		GLOBAL $_SETTINGS;
		
		$str = "<TABLE class=\"results toggle".$identifier."\" WIDTH=$width ALIGN=CENTER BORDER=0 CELLSPACING=0 CELLPADDING=3 style=\"background-color:#f2f2f2;\" id=\"\">";
		return $str;
	}
	
	/*** !!LEGACY!! !!DEPRECATED!! TABLE Functions - Admin Table Header ID ***/
	function tableHeaderid($t,$cols=1,$width=500,$id){
		GLOBAL $_SETTINGS;
		$str = "<TABLE BGCOLOR=\"{$_SETTINGS["headerColor"][2]}\" class=\"results\" WIDTH=$width ALIGN=CENTER BORDER=0 CELLSPACING=0 CELLPADDING=3 id=\"".$id."\">";
		return $str;
	}
	
	/*** NEW ADMIN TABLE HEADER AS OF 4/19/2010 ***/
	function tableHeaderid_1($width=500,$id){
		GLOBAL $_SETTINGS;
		$table = "<table class=\"results\" width=\"".$width."\" align=\"center\" border=0 cellspacing=\"0\" cellpadding=\"3\" id=\"".$id."\">";
		return $table;
	}
	
	/*** ADMIN SORTABLE LIST 4/19/2010 ***/
	function sortableList($idammend = ""){
		GLOBAL $_SETTINGS;
		$list = "<ul class=\"resultslist\" id=\"sortable".$idammend."\">";
		return $list;
	}
		
	/*** !!DEPRECATED!! DO NOT USE!! TABLE Functions - Admin Table Footer ***/
	function tableFooter(){
		return "</TABLE>";
	}	
	
	/*** !!DEPRECATED!! DO NOT USE!! TABLE Functions - Simple Table Header ***/
	function simpleTableHeader($width="500", $align="center",$td_align="left"){
		return "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 ALIGN=\"$align\" WIDTH=\"$width\"><tR><TD ALIGN=\"$td_align\">";
	}
	
	/*** !!DEPRECATED!! DO NOT USE!! TABLE Functions - Simple Table Footer ***/
	function simpleTableFooter(){
		return "</TD></TR></TABLE>";
	}
	
	/*** ARRAY FUNCTIONS ***/
	function msort($array, $key, $sort_flags = SORT_REGULAR) {
	 /**
	 * Sort a 2 dimensional array based on 1 or more indexes.
	 * 
	 * msort() can be used to sort a rowset like array on one or more
	 * 'headers' (keys in the 2th array).
	 * 
	 * @param array        $array      The array to sort.
	 * @param string|array $key        The index(es) to sort the array on.
	 * @param int          $sort_flags The optional parameter to modify the sorting 
	 *                                 behavior. This parameter does not work when 
	 *                                 supplying an array in the $key parameter. 
	 * 
	 * @return array The sorted array.
	 */
		if (is_array($array) && count($array) > 0) {
			if (!empty($key)) {
				$mapping = array();
				foreach ($array as $k => $v) {
					$sort_key = '';
					if (!is_array($key)) {
						$sort_key = $v[$key];
					} else {
						// @TODO This should be fixed, now it will be sorted as string
						foreach ($key as $key_key) {
							$sort_key .= $v[$key_key];
						}
						$sort_flags = SORT_STRING;
					}
					$mapping[$k] = $sort_key;
				}
				asort($mapping, $sort_flags);
				$sorted = array();
				foreach ($mapping as $k => $v) {
					$sorted[] = $array[$k];
				}
				return $sorted;
			}
		}
		return $array;
	}
		
	/*** FILE Functions - Find size of file, and return propertly,	formatted size, KB, MB, TB, etc., $file = full dir path of file ***/
	function getFileSize($file){
		$size = filesize($file);

		$bytes = array('B','KB','MB','GB','TB');
		foreach($bytes as $val) {
			if ($size > 1024)
			{
				$size = $size / 1024;
			}
			else
			{
				break;
			}
		}
		return round($size, 2)." ".$val;
	}
	
	/*** FILE Functions - Return Unique file name ***/
	function uniqueFileName($dir, $file_name, $nameChange="0"){
	
		if ($nameChange=="1")
		{
			$ext = explode(".", $file_name);
			
			// Make sure it doesnt exist
			$file_name = substr(md5(mktime),0,10).".".$ext[1];
			$f = $dir.$file_name;
			while (file_exists($f)) {
				$file_name = substr(md5(mktime()),0,10).".".$ext[1];
				$f = $dir.$file_name;
			}
		}

		else
		{
			$file_name = str_replace(" " , "_", $file_name);
			$ext = explode(".", $file_name);
			$base = ereg_replace("[^[:alnum:]+_]","",$ext[0]);

			// Make sure it doesnt exist
			$f = $dir.$file_name;
			$i = 1;
			while (file_exists($f)) {
				$file_name = $base."-$i.".$ext[1];
				$f = $dir.$file_name;
				$i++;
			}
		}

		return $file_name;
	}
	
	/**
	 * 
	 * thumbName
	 * Return a thumbnail version of a file in an uploads folder
	 *
	 **/
	function thumbName($filename,$width='150') {
		// Explode the filename to get the name and the extension
		$ext = explode(".", $filename);		
		// Ooops... The original filename is a thumbnail, lets get a smaller version
		if(findinString($ext[0],"_w")){
		
			//die('IN STRING');
			//exit();
			
			$file = explode("_w",$ext[0]);
			return $file[0]."_w".$width.".".$ext[count($ext)-1];
		} else {
			return $ext[0]."_w".$width.".".$ext[count($ext)-1];
		}
	}
	
	/*** FILE Functions - Return largenail name ***/
	function largeName($name) {
			
		$ext = explode(".", $name);
		return $ext[0]."_large.".$ext[count($ext)-1];
	}
	
	/*** IMAGE Functions - Get Images Avg. Color ***/
	function average($img) {
		$w = imagesx($img);
		$h = imagesy($img);
		$r = $g = $b = 0;
		for($y = 0; $y < $h; $y++) {
			for($x = 0; $x < $w; $x++) {
				$rgb = imagecolorat($img, $x, $y);
				$r += $rgb >> 16;
				$g += $rgb >> 8 & 255;
				$b += $rgb & 255;
			}
		}
		$pxls = $w * $h;
		$r = dechex(round($r / $pxls));
		$g = dechex(round($g / $pxls));
		$b = dechex(round($b / $pxls));
		if(strlen($r) < 2) {
			$r = 0 . $r;
		}
		if(strlen($g) < 2) {
			$g = 0 . $g;
		}
		if(strlen($b) < 2) {
			$b = 0 . $b;
		}
		return "#" . $r . $g . $b;
	}
		
	/*** IMAGE Functions - Set Memory For Image ***/
	function setMemoryForImage( $filename ){
	   $imageInfo = getimagesize($filename);
	   $MB = 1048576;  // number of bytes in 1M
	   $K64 = 65536;    // number of bytes in 64K
	   $TWEAKFACTOR = 1.5;  // Or whatever works for you
	   $memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]
											   * $imageInfo['bits']
											   * $imageInfo['channels'] / 8
								 + $K64
							   ) * $TWEAKFACTOR
							 );
	   //ini_get('memory_limit') only works if compiled with "--enable-memory-limit" also
	   //Default memory limit is 8MB so well stick with that.
	   //To find out what yours is, view your php.ini file.
	 $memoryLimitMB = 8;
	 $memoryLimit = $memoryLimitMB * $MB;
	   if (function_exists('memory_get_usage') &&
		   memory_get_usage() + $memoryNeeded > $memoryLimit)
	   {
		   $newLimit = $memoryLimitMB + ceil( ( memory_get_usage()
											   + $memoryNeeded
											   - $memoryLimit
											   ) / $MB
										   );
			
			$newLimit = $newLimit+3000000;
		   ini_set( 'memory_limit', $newLimit . 'M' );
		
		   return true;
	   }else {
		   return false;
	   }
	}

	/*** IMAGE Functions - Scale Image ***/
	function sslib_ScaleCopy($src,$dest,$width,$height=0){

		setMemoryForImage($src);

		if(strtolower(substr($dest,-3))=="png") {
			$src_img = imagecreatefrompng($src); 
		} elseif(strtolower(substr($dest,-3))=="jpg" || strtolower(substr($dest,-4))=="jpeg") {
			$src_img = imagecreatefromjpeg($src); 
		} elseif(strtolower(substr($dest, -3))=="gif") {
			$src_img = imagecreatefromgif($src);
		} else {
			return false;
		}

        if ($height==0) {
			$height = imagesy($src_img);
		}    


        $rwidth  = imagesx($src_img);
        $rheight = imagesy($src_img);
		$ratio = $rwidth/$rheight;
		$spotratio = ($width/$height);
		$ratiox = $width/imagesx($src_img); 
		$ratioy = $height/imagesy($src_img); 

		if($ratio>$spotratio) {
			$new_w = round($width);
			$new_h = round(imagesy($src_img)*$ratiox);
			$resize=true;
		} else {
			$new_w = round(imagesx($src_img)*$ratioy);
			$new_h = round($height);
			$resize=true;
		} 

		if ($resize) {
			$dst_img = imagecreatetruecolor($new_w,$new_h); 
			imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img)); 
		} else {
			$dst_img = imagecreate(imagesx($src_img), imagesy($src_img));
			imagecopy($dst_img,$src_img, 0, 0, 0, 0, $width, $height);
		}
		if(strtolower(substr($dest,-3))=="png") {
			imagepng($dst_img, $dest); 
		} else {
			imagejpeg($dst_img, $dest); 
		}
		return true;
	}
	
	function constrainProportions($max_width,$max_height,$img_path){
		//$max_width = $maxwidth; 
		//$max_height = $m; 
		list($width, $height) = getimagesize($img_path); 
		$ratioh = $max_height/$height; 
		$ratiow = $max_width/$width; 
		$ratio = min($ratioh, $ratiow); 
		// New dimensions 
		$width = intval($ratio*$width); 
		$height = intval($ratio*$height);
		$proportions = array($width,$height);
		return $proportions;
	}
	
	/*** STRING Functions - Remove all chars from string Execpet for ".", $wild = Money ***/
 	function cleanMoneyString($wild)
	{
		//return preg_replace("/^[1-9]{1}([0-9]{0,5})?\.[0-9]{1,2}$/","",$wild);	
		//return preg_replace("/[^0-9(\.0-9{1,2})?]/","",$wild);	

		return str_replace("$","", str_replace(",","",$wild) );
	}
	
	/*** STRING Functions - Generate Random Number ***/
	function random_number($range1,$range2)
	{
		$num = rand($range1,$range2);
		return $num;
	}
	
	/*** STRING Functions - display secure email js ***/
	function secureEmail($name,$address)
	{
		if ($address!="")
		{
			$address	= explode("@",$address);
			$to			= $address[0];
			$host		= $address[1];
			
			$str = "<script language=javascript>";
			$str .= "<!--\n\n";
			$str .= "var contact = \"$name\"\n";
			$str .= "var email = \"$to\"\n";
			$str .= "var emailHost = \"$host\"\n";
			if ($contact=="") {
				$str .= "contact = email + \"&#64;\" + emailHost;\n";
			}
			$str .= "document.write(\"<a href=\" + \"mail\" + \"to:\" + email + \"&#64;\" + emailHost+ \">\" + contact + \"</a>\");";
			$str .= "\n\n//-->";
			$str .= "</script>";

			return $str;
		}
	}
	
	/*** STRING Functions - Return 1 if a valid Email address ***/
	function VerifyEmail($email)
	{
		if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
		{
			return 0;
		}
		return 1;
	}
	
	/*** STRING Functions - Make a safe password ***/
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
	
	/*** STRING Functions - Clean a string of all special chars ***/
 	function cleanString($wild)
	{
	   return ereg_replace("[^[:alnum:]+]","",$wild);
	}
	
	/*** STRING Functions - Return array of three values. area code, and number ***/
	function SplitPhone($phone)
	{
		if ($phone!=0) {
			eregi("^([0-9]{3})([0-9]{3})([0-9]{4})([0-9]*)", $phone, $res);

			return array($res[1], $res[2], $res[3], $res[4]);
		}
		return 0;
	}
	
	/*** STRING Functions - Return formatted phone ***/
	function FormatPhone($phone)
	{
		if ($phone!="") {
			$phone = preg_replace("/[^0-9]/", "", $phone);
			$phone = str_replace("(","",$phone);
			$phone = str_replace(")","",$phone);
			
			if(strlen($phone) == 7)
			return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
			elseif(strlen($phone) == 10)
			return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
			elseif(strlen($phone) == 11)
			return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "($2) $3-$4", $phone);
			else
			return $phone;
		}
	}
	
	function FormatBirthdayForDatabase($birthday)
	{	
		$birthday = str_replace("-","/",$birthday); // REPLACE -, with /
		$birthdayArray = explode("/",$birthday);
		$month 	= $birthdayArray[0];
		$day 	= $birthdayArray[1];
		$year	= $birthdayArray[2];		
		$birthday = $year."-".$month."-".$day;		
		return $birthday;	
	}
	
	function FormatBirthdayForGUI($birthday)
	{	
		$birthdayArray = explode("-",$birthday);
		$month 	= $birthdayArray[1];
		$day 	= $birthdayArray[2];
		$year	= $birthdayArray[0];		
		$birthday = $month."/".$day."/".$year;		
		return $birthday;	
	}
	
	/*** STRING Functions - time stamp formating func ***/
	function FormatTimeStamp($mysql_timestamp, $format="m/d/Y")
	{
	
		if ($mysql_timestamp!="") {
            // Ability to parse 2006-06-05 12:00:00
            if ($mysql_timestamp[4]=="-") {
                eregi("^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})",$mysql_timestamp,$res);

            // Ability to parse 20060605120000
            } else {
                eregi("^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})",$mysql_timestamp,$res);
            }
			
			$year=$res[1];
			$month=$res[2];
			$day=$res[3];
			$hour=$res[4];
			$min=$res[5];
			$sec=$res[6];

			if ($mysql_timestamp>0)
			{
				return date($format, mktime(date($hour), date($min), date($sec), date($month), date($day), date($year)));
			}
			else
			{
				return 0;
			}
		}
	}

	/*** STRING Functions - HTML Entities to an array ***/
	function HtmlEntitiesArray($_POST)
	{
		$i = 0;
		$keys = array_keys($_POST);
		
		foreach ($_POST as $d) {

			$_POST[$keys[$i]] = htmlEntities($d);

			$i++;
		}
		return $_POST;
	}
	
	/**
	 *
	 * LEGACY 
	 * STRING FUNCTION 
	 * Truncate String
	 *
	 */
	function truncate($string, $length, $stopanywhere=false){
		$string = truncateString($string, $length, $stopanywhere);
		return $string;
	}
	
	/**
	 *
	 * STRING FUNCTION
	 * Truncate String
	 *
	 */
	function truncateString($string, $length, $stopanywhere=false) {
		//truncates a string to a certain char length, stopping on a word if not specified otherwise.
		if (strlen($string) > $length) {
			//limit hit!
			$string = substr($string,0,($length -3));
			if ($stopanywhere) {
				//stop anywhere
				$string .= '...';
			} else{
				//stop on a word.
				$string = substr($string,0,strrpos($string,' ')).'...';
			}
		}
		return $string;
	}
	
	/**
	 *
	 * STRING FUNCTION
	 * Find In String
	 *
	 */
	function findinString($string,$find)
	{
		$pos = strpos($string, $find);
		if ($pos !== false) {
			return true;
		} else {
			return false;
		}	
	}
	
	/**
	 *
	 * EMAIL FUNCTION
	 * Send Email
	 *
	 */
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
	
	/**
	 *
	 * SEND EMAIL TO DEVELOPER
	 *
	 */
	function sendDeveloperEmail($message="")
	{
		global $_SETTINGS;
		
		// SEND ADMIN EMAIL // SEND DEVELOPER EMAIL
		$to = $_SETTINGS['developer_email'];
		$from = "orders@".$_SETTINGS['website_domain']."";
		$subject = "Error From ".$_SETTINGS['site_name']." -- URGENT!!!";
		$message = "<br>".$message."<br>";
		@sendEmail($to,$from,$subject,$message);
		return true;
	}
	
	/**
	 *
	 * UTILITY FUNCTION
	 * Check Active Module
	 *
	 */
	function checkActiveModule($module)
	{
		global $_SESSION;	
		foreach($_SESSION['AdminArray'] AS $adminer){
			if(($adminer[0] == $module || $adminer[9] == $module ) AND $adminer[4] == 1){
				return true;
			}
		}
	}
	
	/**
	 *
	 * Return the viewer's ip address
	 *
	 */
	function getUserIP()
	{
		if ($_SERVER['HTTP_X_FORWARD_FOR']) {
			$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		} 
		return $ip;
	}
	
	/**
	 *
	 * Build Form
	 * Builds and displays a form from the form database
	 * 
	 */
	function buildForm($form_id)
	{			
		global $_SETTINGS;
		global $_REQUEST;
		global $_POST;
		global $_COOKIE;
		global $_SESSION;
		
		echo "<form action='' method='post' class='contact moduleform' >";
				
		$select1 = 	"SELECT * FROM form_fields ".
				"LEFT JOIN form_field_relational ON form_fields.field_id=form_field_relational.field_id ".
				"LEFT JOIN forms ON form_field_relational.form_id=forms.form_id ".
				"WHERE forms.form_id='".$form_id."' AND forms.active='1' AND form_fields.active='1' ".
				"ORDER BY form_field_relational.sort_level ASC";
		$result1 =  doQuery($select1);
		$num1 = mysql_num_rows($result1);
		$i1 = 0;
		while($i1<$num1){
			$row1 = mysql_fetch_array($result1);
			
			$name = strtolower(str_replace(" ","_",$row1['label']));
			$value = $_POST[$name];
			
			// Textbox
			if($row1['type'] == 'text'){
				$req_text = "";
				if($row1['required'] == '1'){
					$req_text = "*";
				}
				echo "<p>";						
				echo "<label>".$req_text."".$row1['label']."</label>";
				echo "<input type='textbox' name='".$name."' value='".$value."' />";						
				echo "</p>";					
			}
			
			// Textarea
			if($row1['type'] == 'textarea'){
				$req_textarea = "";
				if($row1['required'] == '1'){
					$req_textarea = "*";
				}
				echo "<p>";	
				echo "	<label>".$req_textarea."".$row1['label']."</label>";	
				echo "	<textarea name='".$name."'>".$value."</textarea>";	
				echo "</p>";	
			}
			
			// Captcha
			if($row1['type'] == 'captcha'){
				echo "<p>";
				echo "	<label>Validation</label>";
				echo "	<img src='".$_SETTINGS['website']."admin/scripts/captcha/captcha_image.php'/>";
				echo "</p>";
				echo "<p>";
				echo "	<label>*Enter Validation Code</label>";
				echo "	<input name='".$name."' size='10' style='width:50px' type='text' id='captcha-code' />";
				echo "</p>";
			}
			
			// SELECT
			if($row1['type'] == 'select' || $row1['type'] == 'multiselect'){
				echo "<p>";
				echo "<label>".$row1['label']."</label>";
				if($type='select'){
					$mode = $row1['mode_select'];
					if($mode='select'){ echo "<select name='".$name."'>"; }
				} else {
					$mode = $row1['mode_multiselect'];
					if($mode='select'){ echo "<select multiselect=multiselect name='".$name."'>"; }
				}
				
				// GET OPTIONS
				$seloptions = "SELECT * FROM form_field_options WHERE field_id='".$row1['field_id']."' ORDER BY name ASC";
				$selresult = doQuery($seloptions);
				$otheroption = false;
				$num = mysql_num_rows($selresult);
				$i=0;
				while($i<$num){
					$option = mysql_fetch_array($selresult);
					if(strtolower($option['name']) == 'other' AND $otheroption == false){
						$otheroption = $option;
						$i = $i-1;
					} else {
						if($i == $num-1){ $option = $otheroption; }
						if($mode == 'select' || $mode == 'multiselect'){
							echo "<option value='".$option['option_id']."' ".isSelected($value,$option['option_id']).">".$option['name']."</option>";
						}
						if($mode == 'radiogroup'){
							echo "<input type='radio' ".isChecked($value,$option['option_id'])." name='".$name."' id='option-".$option['option_id']."'><label for='option-".$option['option_id']."'>".$option['name']."</label>";
						}
						if($mode == 'checkboxgroup'){
							echo "<input type='checkbox' ".isChecked($value,$option['option_id'])." name='".$name."[]' id='option-".$option['option_id']."'><label for='option-".$option['option_id']."'>".$option['name']."</label>";
						}
					}
					$i++;
				}
				
				if($mode == 'select' || $mode == 'multiselect'){
					echo "</select>";
				}
				echo "</p>";
			}
			
			$i1++;
		}
		
		// Submit Button
		echo "<p class='submit-button'>";
		echo "	<label>&nbsp;</label>";
		echo "	<input type='submit' class='submit button ".$row1['name']."-form-submit' name='".strtoupper(str_replace(" ", "_", $row1['button']))."' value='".$row1['button']."' />";
		echo "</p>";
		
		echo "<br clear='all' />";
		echo "</form>";	
	}

	//---//
	
	/**
	 *
	 * Display Wysiwyg
	 * Displays the degault usage of the wysiwyg
	 *
	 */
	function displayWysiwyg($fieldName="Textarea",$initialContent="",$width="900",$height="400")
	{
		global $_SETTINGS;	
		global $_SERVER;
		
		$CMS = new CMS();
								
		$editor = new wysiwygPro();

		// configure the editor:

		// give the editor a name (equivalent to the name attribute on a regular textarea):
		$editor->name = $fieldName;

		$currenttheme = $CMS->activeTheme();									
		//$editor->addStylesheet($_SETTINGS['website']."themes/".$currenttheme."scripts/style.css");
		//$editor->addStylesheet("scripts/adminStylesEditor.css");									
		$editor->editImages = 1;
		$editor->upload = 1;
		$editor->deleteFiles = 1;
		$editor->maxImageSize = '10000 MB';
		$editor->maxImageWidth = 100000;
		$editor->maxImageHeight = 100000;
		$editor->maxDocSize = '10000 MB';									
		$editor->disableFeatures(array('previewtab'));
		$editor->escapeCharacters = true;
		
		$editor->imageDir = $_SERVER["DOCUMENT_ROOT"].$_SETTINGS['website_path']."uploads/";
		$editor->imageURL = $_SETTINGS['website']."uploads";																	
		$editor->documentDir = $_SERVER["DOCUMENT_ROOT"].$_SETTINGS['website_path']."uploads/";
		$editor->documentURL = $_SETTINGS['website']."uploads";
		$editor->mediaDir = $_SERVER["DOCUMENT_ROOT"].$_SETTINGS['website_path']."uploads/";
		$editor->mediaURL = $_SETTINGS['website']."uploads";

		$editor->value = $initialContent;
		
		// display the editor, the two paramaters set the width and height:
		$editor->display($width, $height);
	}
	
	//---//
	
	/**
	 *
	 * Info/Help bubble
	 *
	 */
	function info($info)
	{
		$ui = random_number(0,5000);
		echo "<a class='info left ".$ui."'> </a>";
		echo "<script type='text/javascript' language='javascript'>";
		echo "$('.".$ui."').simpletip({ ";
		echo "	fixed: true,";
		echo "	position: ['left', '-70'],";
		echo "	content: '".$info."'";
		echo "}); ";
		echo "</script>";
	}
	
	//---//
	
	/**
	 *
	 * DATE + TIME Into Timestamp
	 * Turn a datepicker field and a timepicker field into a timestamp for database insertion
	 * 
	 * date input format
	 * 5/10/1985
	 *
	 * time input format
	 * 09:30 PM or 12:45 AM
	 *
	 */
	function DateTimeIntoTimestamp($date,$time)
	{
			
		if($date == '//' || $date == ''){ return "";}
		
		// FORMAT DATE;
		$date_array = explode("/",$date);
		
		
		// IF CONVERTING TO TIMESTAMP
		$date_time = mktime(0,0,0,$date_array[0],$date_array[1],$date_array[2]);
		$date_time = date("Y-m-d",$date_time);
		
		
		// FORMAT TIME
		if($time != ""){
			// IF ADDING TIME INTO THE TIMESTAMP
			$time_array1 = explode(" ",$time);		
			$time_pp = $time_array1[1];
			$time_array2 = explode(":",$time_array1[0]);
			$time_hours	= $time_array2[0];
			$time_mins = $time_array2[1];
			// HANDLE AM/PM
			if(($time_pp == 'PM' AND $time_hours != '12') || ($time_pp == 'AM' AND $time_hours == '12')){
				$time_hours = $time_hours + 12;
			}
			$time = "".$time_hours.":".$time_mins.":01";
		} else {
			// ELSE TIME IS 01
			$time = "00:00:01";
		}
		
		// THE DATE
		$date_timestamp = "".$date_time."";				
		$date_timestamp .= " ".$time."";
		
		
		return $date_timestamp;
	}
	
	//---//
	
	/**
	 *
	 * TIMESTAMP Into Date
	 *
	 */
	function TimestampIntoDate($timestamp)
	{
		$date_array1 = explode(" ",$timestamp);
		$date_array2 = explode("-",$date_array1[0]);
		$date = "".$date_array2[1]."/".$date_array2[2]."/".$date_array2[0]."";
		
		if($date_array1[0] == '0000-00-00'){ $date = ""; }
		
		return $date;
	}
	
	
	
	//---//
	
	/**
	 *
	 * TIMESTAMP Into Time
	 *
	 */
	function TimestampIntoTime($timestamp)
	{
		$time_array1 = explode(" ",$timestamp);
		$time_array2 = explode(":",$time_array1[1]);
		$time_hours = $time_array2[0];
		$time_mins = $time_array2[1];
		if($time_hours >= 13 AND $time_hours <= 24){
			$time_hours = $time_hours - 12;
			$time_pp = "PM";
		} else {
			$time_pp = "AM";
		}
		$time = "".$time_hours.":".$time_mins." ".$time_pp."";
		
		if($time_hours == '00' AND $time_mins == '00'){
			$time = "";
		}
		
		return $time;
	}
	
	//---//
	
	/**
	 *
	 * FUNCTION
	 * Process Form
	 *
	 */
	function processForm($form_id)
	{
		global $_SETTINGS;
		global $_REQUEST;
		global $_POST;
		global $_COOKIE;
		global $_SESSION;
	
		$select1 = "SELECT * FROM forms WHERE form_id='".$form_id."' AND active='1' LIMIT 1";
		$result1 = doQuery($select1);
		$row1 = mysql_fetch_array($result1);
	
		
		// SUBMIT FLAG
		$flag = strtoupper(str_replace(" ","_",$row1['button']));		
		$trigger = $_POST[''.$flag.''];	
	
		
		if($trigger != "")
		{
			$error = 0;
			
			/**
			 *
			 * Processing Validation
			 *
			 */
			$fieldselect 	= 	"SELECT * FROM form_fields ".
						"LEFT JOIN form_field_relational ON form_fields.field_id=form_field_relational.field_id ".
						"LEFT JOIN forms ON form_field_relational.form_id=forms.form_id ".
						"WHERE forms.form_id='".$form_id."' AND forms.active='1' AND form_fields.active='1' ".
						"ORDER BY form_field_relational.sort_level ASC";
			$fieldresult 	=	doQuery($fieldselect);
			$fieldnum		= 	mysql_num_rows($fieldresult);
			$fieldi			= 	0;
			while($fieldi<$fieldnum){
				$fieldrow = mysql_fetch_array($fieldresult);
				$name = strtolower(str_replace(" ","_",$fieldrow['label']));
				$value = $_POST[$name];
				
				//echo "".$name.": ".$value."<Br>";
				
				// CHECK IF REQUIRED
				if($fieldrow['required'] == '1'){
					if($value == ''){ $error = 1; $report = "Please fill in the required field: ".$fieldrow['label'].""; }
				}
								
				// CAPTCHA VALIDATION
				if($fieldrow['type'] == 'captcha'){
					$key=substr($_SESSION['key'],0,5);
					//$number = $_REQUEST['number'];
					if($value!=$key){ $error = 1; $report = "".$fieldrow['label']." is Incorrect"; }
				}
				
				// EMAIL VALIDATIOIN
				if($fieldrow['type'] == 'text' AND findinString($fieldrow['label'],"Email")){
					if(VerifyEmail($value) != 1){ $error = 1; $report = "The Email Address Is Not Valid"; }
					$process_email_from = $value;
				}
								
				$fieldi++;
			}
			
			/**
			 *
			 * If successful validation
			 *
			 */
			if($error == 0)
			{			
				
				// PROCESS FORMS
				// GET PROCESSING TYPES
				$selecttype = 	"SELECT *, form_processing_types.name AS type_name ".
						"FROM form_processing_types ".
						"LEFT JOIN form_processing_type_relational ON form_processing_type_relational.type_id=form_processing_types.type_id ".
						"LEFT JOIN forms ON forms.form_id=form_processing_type_relational.form_id ".
						"WHERE forms.form_id='".$form_id."'";
								
				$resulttype = 	doQuery($selecttype);
				$resultnum	= 	mysql_num_rows($resulttype);
				$resulti	=	0;
			
				while($resulti<$resultnum){				
					$resultrow = mysql_fetch_array($resulttype);
					
					// EMAIL PROCESSING
					if($resultrow['type_name'] == 'Email'){
					
						$to = $_SETTINGS['contact_email_address'];
						$from = $process_email_from;
						$subject = "".$_SETTINGS['siteName']." ".$resultrow['form_name']." Submission";
					
						// START MESSAGE
						$message = 	"<br>".$resultrow['form_name']." Submission Details: <Br><br>";
									
						// LOOP THROUGH FIELDS
						$fieldresult 	=	doQuery($fieldselect);
						$fieldnum		= 	mysql_num_rows($fieldresult);
						$fieldi			= 	0;
						while($fieldi<$fieldnum){
							$fieldrow = mysql_fetch_array($fieldresult);
							$name = strtolower(str_replace(" ","_",$fieldrow['label']));
							$value = $_POST[$name];
							
							// TEXT
							if($fieldrow['type'] == 'text'){
								$message .= "";
								$message .= "<strong>".$fieldrow['label'].":</strong><br>";
								$message .= "".$value."";
								$message .= "<br>";
							}
							
							// TEXTAREA
							if($fieldrow['type'] == 'textarea'){
								$message .= "";
								$message .= "<strong>".$fieldrow['label'].":</strong><br>";
								$message .= "".$value."";
								$message .= "<br>";
							}
							
							// SELECT OR MULTISELECT
							if($fieldrow['type'] == 'select' || $fieldrow['type'] == 'multiselect'){
								if($fieldrow='select'){
									$mode = $row1['mode_select'];
									$value = lookupDbValue('form_field_options','name',$value,'option_id');
								} else {
									$mode = $row1['mode_multiselect'];
									foreach($value as $option){
										$string .= "".$option['name'].",";
									}
									$value = trim($string,",");
								}
								
								$message .= "";
								$message .= "<strong>".$fieldrow['label']."</strong><br>";
								$message .= "".$value."";
								$message .= "<br>";
							}
							
							
							$fieldi++;
						}
						
						// END MESSAGE
						$message .= "<br>";		

						// SEND EMAIL
						@sendEmail($to,$from,$subject,$message);						
					}				
					$resulti++;
				}
				
				$report = "Form Submitted Successfully";
				$success = "1";
				
				header("Location: ".$_SETTINGS['website']."".$_REQUEST['page']."/0/".$report."/".$success."/0");
				exit();
				
			} else {
				$success = '0';
				$array[0] = $success;
				$array[1] = $report;
				return $array;
			}
		}		
	}
	
	//---//
	
	/**
	 *
	 * FUNCTION
	 * loop uploads folders Folders in the root directory 
	 *
	 */
	function loopUploadsFolder($dir="../")
	{
		//$dir = "../"; //You could add a $_GET to change the directory
		$files = scandir($dir);
		$i = 0;
		// LOOP UPLOAD FOLDERS
		foreach($files as $key => $file){
			if ($file != "." && $file != ".."){							
				// MAKE SURE ITS REAL DIRECTORY
				if(is_dir("../".$file)){									
					$filearray = explode("-",$file);
					if($filearray[0] == "uploads"){						
						echo "<option value='".$file."/'>".$file."</option>";
					}									
				}
			}							
		}
	}
	
	//---// 
	
	/*
	$name 		= "Sort Categories and Products";
	$nameColumn	= "Categories / Products";
	$catTable	= "ecommerce_product_categories";
	$itemTable 	= "ecommerce_products";
	$relTable	= "ecommerce_product_category_relational";
	$itemId		= "product_id";
	$itemIdentifier = "PRODUCT"; // used to delete and such
	$filePath	= "ecommerce/ecommerce.php";
	*/
	function sortableThreeLevelParentItemTable($name,$nameColumn,$catTable,$itemTable,$relTable,$itemId,$itemIdentifier,$filePath)
	{
	
		// START 
		echo "<div class='textcontent1'>";
		echo "	<h1>".$name."</h1>";
		echo "</div>";
		echo "<br />";
		echo "<br />";
		
		// TABLE HEADER
		echo tableHeaderid($name,6,"100%","list");
		echo "<thead><TR><th width='600'>".$nameColumn."</th><th>Action</th></TR></thead><tbody>";
		echo "</tbody></table>";
				
		// SELECT
		$select = 	"SELECT * FROM ".$catTable." ".
					"WHERE ".
					"active='1' AND (parent_id='' OR parent_id=0) ORDER BY sort_level ASC".
					"".$_SETTINGS['demosqland']."";
		
		// START UL LIST
		echo sortableList();
		
		$res = doQuery($select);
		$num = mysql_num_rows($res);
		$i=0;
		
		// START TOP LEVEL CATEGORY LOOP
		while ($row = mysql_fetch_array($res)){
			$default = "";
			if($i % 2) { $class = "odd"; } else { $class = "even"; }
			echo "<li class=\"".$class." selector\" id=\"".$row['category_id']."\"> <span class=\"cat1\"></span> <span>{$row["name"]} {$default}</span>";
				// TOP LEVEL FORM
				echo "<FORM class=\"listform\" METHOD=GET ACTION=\"$_SERVER[PHP_SELF]\">";
				echo "<INPUT TYPE=HIDDEN NAME=cid VALUE=\"{$row["category_id"]}\">";
				echo "<INPUT TYPE=HIDDEN NAME=VIEW VALUE=\"{$_GET["VIEW"]}\">";
				echo "<INPUT TYPE=HIDDEN NAME=SUB VALUE=\"{$_GET["SUB"]}\">";
				echo "<INPUT TYPE=SUBMIT NAME=DELETECATEGORY VALUE=\"Delete\" onClick=\"return confirm('Are You Sure?');\">";
				echo "<INPUT TYPE=SUBMIT NAME=view VALUE=\"View\">";
				echo "</FORM>";
				
				
				//
				// TOP LEVEL PRODUCTS
				//
				echo "<script>";
				echo '	$("#sortableproducts1'.$i.'").sortable();';
				echo "</script>";
				echo sortableList("products1".$i."");
				$selectp1 = "SELECT * FROM ".$itemTable." ". 
							"LEFT JOIN ".$relTable." ON ".$itemTable.".".$itemId."=".$relTable.".".$itemId." ".
							"WHERE ".
							"".$relTable.".category_id='".$row['category_id']."' ".
							"AND ".$itemTable.".active='1' ".
							"ORDER BY sort_level ASC";
				$resultp1 = doQuery($selectp1);
				$nump1 = mysql_num_rows($resultp1);
				$ip1 = 0;
				while($rowp1 = mysql_fetch_array($resultp1)){
					echo "<li class=\"".$class." selector\" id=\"".$rowp1['product_id']."\"> <span class=\"prod1\"></span> <span>{$rowp1["name"]} {$default}</span>";
						//
						// TOP LEVEL PRODUCT FORM
						//
						echo "<FORM class=\"listform\" METHOD=GET ACTION=\"$_SERVER[PHP_SELF]\">";
						echo "<INPUT TYPE=HIDDEN NAME=pid VALUE=\"".$rowp1["".$itemId.""]."\">";
						echo "<INPUT TYPE=HIDDEN NAME=VIEW VALUE=\"".$_GET["VIEW"]."\">";
						echo "<INPUT TYPE=HIDDEN NAME=SUB VALUE=\"".$_GET["SUB"]."\">";
						echo "<INPUT TYPE=SUBMIT NAME=DELETE".$itemIdentifier." VALUE=\"Delete\" onClick=\"return confirm('Are You Sure?');\">";
						echo " <INPUT TYPE=SUBMIT NAME=view VALUE=\"View\">";
						echo "</FORM>";
					
					echo "</li>";
					$ip2++;
				}
				echo "</ul>";
				//
				// SCRIPT
				//
				echo "<script>";
				echo "	// AJAX REQUEST SORT TOP LEVEL";
				echo "	$( '#sortableproducts1".$i."' ).bind( 'sortstop', function(event, ui) {";
				echo "  var result = $('#sortableproducts1".$i."').sortable('toArray');";
				echo "  var resultstring = result.toString();";
				echo "  $.ajax({";
				echo "	  type: 'POST',";
				echo "	  url: 'modules/".$filePath."',";
				echo "	  data: { sortarray: resultstring, SORT_PRODUCTS: '1', CATEGORY: '".$row['category_id']."' }";
				echo "	});";
				echo "});";
				echo "</script>";
				
				
				
				//
				// SECOND LEVEL
				//
				echo "<script>";
				echo "	$('#sortable1".$i."').sortable();";
				echo "</script>";
				echo sortableList("1".$i."");
					$select1 = "SELECT * FROM ".$catTable." WHERE active='1' AND parent_id='".$row['category_id']."' ORDER BY sort_level ASC";
					$result1 = doQuery($select1);
					$num1 = mysql_num_rows($result1);
					$i1 = 0;
					while($row1 = mysql_fetch_array($result1)){
						echo "<li class=\"".$class." selector\" id=\"".$row1['category_id']."\"> <span class=\"cat2\"></span> <span>{$row1["name"]} {$default}</span>";
							// SECOND LEVEL FORM
							echo "<FORM class=\"listform\" METHOD=GET ACTION=\"$_SERVER[PHP_SELF]\">";
							echo "<INPUT TYPE=HIDDEN NAME=cid VALUE=\"{$row1["category_id"]}\">";
							echo "<INPUT TYPE=HIDDEN NAME=VIEW VALUE=\"{$_GET["VIEW"]}\">";
							echo "<INPUT TYPE=HIDDEN NAME=SUB VALUE=\"{$_GET["SUB"]}\">";
							echo "<INPUT TYPE=SUBMIT NAME=DELETECATEGORY VALUE=\"Delete\" onClick=\"return confirm('Are You Sure?');\">";
							echo "<INPUT TYPE=SUBMIT NAME=view VALUE=\"View\">";
							echo "</FORM>";
							
							//
							// SECOND LEVEL PRODUCTS
							//
							echo "<script>";
							echo "	$('#sortableproducts2".$i1."').sortable();";
							echo "</script>";
							echo sortableList("products2".$i1."");
							$selectp2 = "SELECT * FROM ".$itemTable." ". 
										"LEFT JOIN ".$relTable." ON ".$itemTable.".".$itemId."=".$relTable.".".$itemId." ".
										"WHERE ".
										"".$relTable.".category_id='".$row1['category_id']."' ".
										"AND ".$itemTable.".active='1' ".
										"ORDER BY sort_level ASC";
							$resultp2 = doQuery($selectp2);
							$nump2 = mysql_num_rows($resultp2);
							$ip2 = 0;
							while($rowp2 = mysql_fetch_array($resultp2)){
								echo "<li class=\"".$class." selector\" id=\"".$rowp2['product_id']."\"> <span class=\"prod2\"></span> <span>{$rowp2["name"]} {$default}</span>";
									//
									// SECOND LEVEL PRODUCT FORM
									//
									echo "<FORM class=\"listform\" METHOD=GET ACTION=\"$_SERVER[PHP_SELF]\">";
									echo "<INPUT TYPE=HIDDEN NAME=pid VALUE=\"".$rowp2["".$itemId.""]."\">";
									echo "<INPUT TYPE=HIDDEN NAME=VIEW VALUE=\"".$_GET["VIEW"]."\">";
									echo "<INPUT TYPE=HIDDEN NAME=SUB VALUE=\"".$_GET["SUB"]."\">";
									echo "<INPUT TYPE=SUBMIT NAME=DELETE".$itemIdentifier." VALUE=\"Delete\" onClick=\"return confirm('Are You Sure?');\">";
									echo " <INPUT TYPE=SUBMIT NAME=view VALUE=\"View\">";
									echo "</FORM>";
								
								echo "</li>";
								$ip2++;
							}
							echo "</ul>";
							// SCRIPT
							echo "<script>";
							echo "	// AJAX REQUEST SORT THIRD LEVEL";
							echo "	$( '#sortableproducts2".$i1."' ).bind( 'sortstop', function(event, ui) {";
							echo "  var result = $('#sortableproducts2".$i1."').sortable('toArray');";
							echo "  var resultstring = result.toString();";
							echo "  $.ajax({";
							echo "	  type: 'POST',";
							echo "	  url: 'modules/".$filePath."',";
							echo "	  data: { sortarray: resultstring, SORT_PRODUCTS: '1', CATEGORY: '".$row1['category_id']."' }";
							echo "	});";
							echo "});";
							echo "</script>";
							
							
							//
							// THIRD LEVEL
							//
							echo "<script>";
							echo '$("#sortable2'.$i1.'").sortable();';
							echo "</script>";
							echo sortableList("2".$i1."");
							$select2 = "SELECT * FROM ".$catTable." WHERE active='1' AND parent_id='".$row1['category_id']."' ORDER BY sort_level ASC";
							$result2 = doQuery($select2);
							$num1 = mysql_num_rows($result2);
							$i2 = 0;
							while($row2 = mysql_fetch_array($result2)){
								echo "<li class=\"".$class." selector\" id=\"".$row2['category_id']."\"> <span class=\"cat3\"></span> <span>{$row2["name"]} {$default}</span>";
									// THIRD LEVEL FORM
									echo "<FORM class=\"listform\" METHOD=GET ACTION=\"$_SERVER[PHP_SELF]\">";
									echo "<INPUT TYPE=HIDDEN NAME=cid VALUE=\"{$row2["category_id"]}\">";
									echo "<INPUT TYPE=HIDDEN NAME=VIEW VALUE=\"{$_GET["VIEW"]}\">";
									echo "<INPUT TYPE=HIDDEN NAME=SUB VALUE=\"{$_GET["SUB"]}\">";
									echo "<INPUT TYPE=SUBMIT NAME=DELETECATEGORY VALUE=\"Delete\" onClick=\"return confirm('Are You Sure?');\">";
									echo " <INPUT TYPE=SUBMIT NAME=view VALUE=\"View\">";
									echo "</FORM>";
																
									//
									// THIRD LEVEL PRODUCTS
									//
									echo "<script>";
									echo "$('#sortableproducts3".$i2."').sortable();";
									echo "</script>";
									
									echo sortableList("products3".$i2."");
									$selectp3 = "SELECT * FROM ".$itemTable." ". 
												"LEFT JOIN ".$relTable." ON ".$itemTable.".".$itemId."=".$relTable.".".$itemId." ".
												"WHERE ".
												"".$relTable.".category_id='".$row2['category_id']."' ".
												"AND ".$itemTable.".active='1' ".
												"ORDER BY sort_level ASC";
									$resultp3 = doQuery($selectp3);
									$nump3 = mysql_num_rows($resultp3);
									$ip3 = 0;
									while($rowp3 = mysql_fetch_array($resultp3)){
										echo "<li class=\"".$class." selector\" id=\"".$rowp3['product_id']."\"> <span class=\"prod3\"></span> <span>{$rowp3["name"]} {$default}</span>";
											//
											// THIRD LEVEL PRODUCT FORM
											//
											echo "<FORM class=\"listform\" METHOD=GET ACTION=\"$_SERVER[PHP_SELF]\">";
											echo "<INPUT TYPE=HIDDEN NAME=pid VALUE=\"".$rowp3["".$itemId.""]."\">";
											echo "<INPUT TYPE=HIDDEN NAME=VIEW VALUE=\"".$_GET["VIEW"]."\">";
											echo "<INPUT TYPE=HIDDEN NAME=SUB VALUE=\"".$_GET["SUB"]."\">";
											echo "<INPUT TYPE=SUBMIT NAME=DELETE".$itemIdentifier." VALUE=\"Delete\" onClick=\"return confirm('Are You Sure?');\">";
											echo "<INPUT TYPE=SUBMIT NAME=view VALUE=\"View\">";
											echo "</FORM>";
										
										echo "</li>";
										$ip3++;
									}
									echo "</ul>";
									echo "<script>";
									echo "	// AJAX REQUEST SORT THIRD LEVEL";
									echo "	$( '#sortableproducts3".$i2."' ).bind( 'sortstop', function(event, ui) {";
									echo "  var result = $('#sortableproducts3".$i2."').sortable('toArray');";
									echo "  var resultstring = result.toString();";
									echo "  $.ajax({";
									echo "	  type: 'POST',";
									echo "	  url: 'modules/".$filePath."',";
									echo "	  data: { sortarray: resultstring, SORT_PRODUCTS: '1', CATEGORY: '".$row2['category_id']."' }";
									echo "	});";
									echo "});";
									echo "</script>";								
								// END THIRD LEVEL CAT	
								echo "</li>";
								$i2++;
							}
							// END THIRD LEVEL CAT LIST
							echo "</ul>";
							// SCRIPT
							echo "<script>";
							echo "// AJAX REQUEST SORT THIRD LEVEL";
							echo "$( '#sortable2".$i1."' ).bind( 'sortstop', function(event, ui) {";
							echo "  var result = $('#sortable2".$i1."').sortable('toArray');";
							echo "  var resultstring = result.toString();";
							  
							echo "  $.ajax({";
							echo "	  type: 'POST',";
							echo "	  url: 'modules/".$filePath."',";
							echo "	  data: { sortarray: resultstring, SORT_CATEGORIES: '1', LEVEL: 'cat3' }";
							echo "	});";
							echo "});";
							echo "</script>";
						// END SECOND LEVEL CAT
						echo "</li>";						
						$i1++;
					}			
				// END SECOND LEVEL CAT LIST
				echo "</ul>";
				// SCRIPT
				echo "<script>";
				echo "	// AJAX REQUEST SORT SECOND LEVEL";				
				echo "	$( '#sortable1".$i."' ).bind( 'sortstart', function(event, ui) {";
				echo "		//$('ul.resultslist li ul li').css('background-color','blue');";				  
				echo "	});";				
				echo "	$( '#sortable1".$i."' ).bind( 'sortstop', function(event, ui) {";
				echo "  	var result = $('#sortable1".$i."').sortable('toArray');";
				echo "  	var resultstring = result.toString();";				  
				echo "  	$.ajax({";
				echo "	  		type: 'POST',";
				echo "	  		url: 'modules/".$filePath."',";
				echo "	  		data: { sortarray: resultstring, SORT_CATEGORIES: '1', LEVEL: 'cat2' }";
				echo "		});";
				echo "		//$('ul.resultslist li ul li').css('background-color','#f5f5f5');";
				echo "	});";
				echo "</script>";
			// END FIRST LEVEL CAT
			echo "</li>";
			$i++;
		}
		echo "</ul>";
		
		echo "<script>";
		echo "	// AJAX REQUEST SORT TOP LEVEL";			
		echo "	$( '#sortable' ).bind( 'sortstart', function(event, ui) {";
		echo "		$(ui.item).css('background-color','#f3f8ff');";
		echo "		$(ui.item).css('border','2px solid #89a8d8');";
		echo "		$(ui.item).css('cursor','-moz-grabbing');";
		echo "	});";
			
		echo "	$( '#sortable' ).bind( 'sortstop', function(event, ui) {";
		echo "	  var result = $('#sortable').sortable('toArray');";
		echo "	  var resultstring = result.toString();";
			  
		echo "	  $.ajax({";
		echo "		  type: 'POST',";
		echo "		  url: 'modules/".$filePath."',";
		echo "		  data: { sortarray: resultstring, SORT_CATEGORIES: '1', LEVEL: 'cat1' }";
		echo "		});";
		echo "		//$(ui.item).css('background-color','transparent');";
		echo "		$(ui.item).css('background-color','#f5f5f5');";
		echo "		$(ui.item).css('border-top','1px solid #eeeeee');";
		echo "		$(ui.item).css('border-right','1px solid #eeeeee');";
		echo "		$(ui.item).css('border-bottom','0px solid #eeeeee');";
		echo "		$(ui.item).css('border-left','0px solid #eeeeee');";
		echo "		$(ui.item).css('cursor','-moz-grab');";
		echo "	});	";
		echo "</script>";
		echo "<div class='pagination'>&nbsp;</div>";
	}
	
	
	//---//
	
	function sortableTable($name,$table,$titleColumnArray,$valueColumnArray,$xid,$parentItemId="",$parentItemId2="")
	{
		global $_REQUEST;
		global $_SESSION;
		global $_SETTINGS;
		
		//
		// SORTABLE 
		//	
		echo "<div class='textcontent1'>";
		echo "	<h1>".$name."</h1>";
		echo "  <a class='admin-new-button' href='index.php?VIEW=".$_REQUEST['VIEW']."&SUB=NEW".strtoupper(rtrim($name,"s"))."&parentItemId=".$_REQUEST[$parentItemId]."' >New ".rtrim($name,"s")."</a>";
		echo "</div>";
		echo "<br />";
		echo "<br />";
		
		// HEADER
		echo tableHeaderid($name,6,"100%","list");
		echo "<thead><TR>";
		
		foreach($titleColumnArray as $column){
			echo "<th class='head-sortable-cell'>".$column."</th>";
		}	
		
		if($parentItemId != ""){
			$parentsql = "AND ".$parentItemId2."='".$_REQUEST[$parentItemId]."' ";
		}
		
		echo "<th width='400px'>Action</th>";
		echo "</TR></thead><tbody>";
		echo "</tbody></table>";
		
		// List
		$select = 	"SELECT * FROM ".$table." ".
					"WHERE ".
					"active='1' ".
					$parentsql.
					"ORDER BY sort_level ASC".
					"".$_SETTINGS['demosqland']."";
		
		echo sortableList();
		
		$res = doQuery($select);
		$num = mysql_num_rows($res);
		$i=0;
		while ($row = mysql_fetch_array($res)){
			$default = "";
			if($i % 2){ $class = "odd"; } else { $class = "even"; }
			
			
			
			echo "<li class=\"".$class." selector\" id=\"".$name."\"> <span class=\"cat1\"></span>";
			
			$cellcount = count($valueColumnArray) + 1;
			$cellpercentage = (100 / $cellcount) - 3;
			
			foreach($valueColumnArray as $cell){
			
				if($cell == "name"){
					// FORMAT NAME
					$name = "";
					$name = $row['title'];
					if($name == ""){ $name = $row['name']; }
					echo "<span class='sortable-cell' style='width:".$cellpercentage."%;'>".$name."</span>";
				} elseif($cell == "image"){
					// FORMAT IMAGE
					echo "<span class='sortable-cell' style='width:".$cellpercentage."%;'><img src='".$_SETTINGS['website']."uploads/".$row[$cell]."'></span>";
				} else {
					// ELSE NORMAL VALUE
					echo "<span class='sortable-cell' style='width:".$cellpercentage."%;'>".$row[$cell]."</span>";
				}
			
				
			}
			
			
				// TOP LEVEL FORM
				echo "<FORM class='listform' METHOD='GET' ACTION='".$_SERVER[PHP_SELF]."'>";
				echo "<INPUT TYPE='HIDDEN' NAME='".$xid."' VALUE='".$row[$valueColumnArray[0]]."'>";
				echo "<INPUT TYPE='HIDDEN' NAME='VIEW' VALUE='".$_GET["VIEW"]."'>";
				echo "<INPUT TYPE='HIDDEN' NAME='SUB' VALUE='".$_GET["SUB"]."'>";
				echo "<INPUT TYPE='SUBMIT' NAME='DELETE_CATEGORY' VALUE='Delete' onClick=\"return confirm('Are You Sure?');\">";
				echo "<INPUT TYPE='SUBMIT' NAME='view' VALUE='Open'>";
				echo "</FORM>";
			echo "</li>";	
			$i++;
		}
		echo "</ul>";
		
		echo "<script>";
		echo "	// AJAX REQUEST SORT LEVEL";
			
		echo "	$( '#sortable' ).bind( 'sortstart', function(event, ui) { ";
		echo "		//$(ui.item).css('background-color','#ffffff'); ";
		echo "		$(ui.item).css('background-color','#f3f8ff'); ";
		echo "		$(ui.item).css('border','2px solid #89a8d8'); ";
		echo "		$(ui.item).css('cursor','-moz-grabbing'); ";
		echo "	}); ";
			
		echo "	$( '#sortable' ).bind( 'sortstop', function(event, ui) { ";
		echo "	  var result = $('#sortable').sortable('toArray'); ";
		echo "	  var resultstring = result.toString(); ";
			  
		echo "	  $.ajax({ ";
		echo "		  type: 'POST', ";
		echo "		  url: 'modules/scrollnavigation/scrollnavigation.php', ";
		echo "		  data: { sortarray: resultstring, SORT_CATEGORIES: '1' } ";
		echo "		}); ";
		echo "		//$(ui.item).css('background-color','transparent'); ";
		echo "		$(ui.item).css('background-color','#f5f5f5'); ";
		echo "		$(ui.item).css('border-top','1px solid #eeeeee'); ";
		echo "		$(ui.item).css('border-right','1px solid #eeeeee'); ";
		echo "		$(ui.item).css('border-bottom','0px solid #eeeeee'); ";
		echo "		$(ui.item).css('border-left','0px solid #eeeeee'); ";
		echo "		$(ui.item).css('cursor','-moz-grab'); ";
		echo "	});	";
		echo "</script> ";
		echo "<div class='pagination'> &nbsp;";
		//echo "<a href='index.php?VIEW=".$_REQUEST['VIEW']."&SUB=NEW".strtoupper(rtrim($name,"s"))."' style='background:#fff; float:right; border:1px solid white; padding:5px 10px'>New ".rtrim($name,"s")."</a>";
		echo "</div>";
		
	}
	
	//---//
	
	/**
	 *
	 * FUNCTION
	 * admin search and paginated table
	 *
	 */
	function basicSearchListingTable($name,$table,$orderByString,$searchColumnArray,$titleColumnArray,$valueColumnArray,$xid,$ajaxURL="",$Join="",$On="",$readonly=False,$addnew=True)
	{

		global $_GET;
		global $_REQUEST;
		global $_SETTINGS;

		$num = mysql_num_rows(doQuery("SELECT * FROM ".$table." WHERE active='1'"));
		
		// BEGIN SEARCH BOX
		echo "<div class='textcontent'>";
		echo "	<h1>".$name." (".$num.")</h1>";
		
		// ADD NEW BUTTON
		if($addnew == True){
			echo "  <a class='admin-new-button' href='index.php?VIEW=".$_REQUEST['VIEW']."&SUB=NEW".strtoupper(rtrim($name,"s"))."' >New ".rtrim($name,"s")."</a>";
		}
		
			echo "<FORM METHOD=GET>";
			echo "<INPUT TYPE=HIDDEN NAME=\"VIEW\" VALUE=\"".$_GET["VIEW"]."\">";
			echo "<INPUT TYPE=HIDDEN NAME=\"SUB\" VALUE=\"".$_GET["SUB"]."\">";
			echo "<INPUT TYPE=TEXT NAME=\"KEYWORDS\" VALUE=\"".$_GET["KEYWORDS"]."\"> &nbsp; ";
			echo "<SELECT NAME=\"COLUMN\">";				
				foreach($searchColumnArray as $column){
					echo "<OPTION VALUE=\"t1.".$column."\" ".selected($_REQUEST['COLUMN'],"t1.".$column."").">".str_replace("_"," ",ucfirst($column))."</OPTION>";			
				}
			echo "</select>";
			echo "<INPUT TYPE=SUBMIT NAME=search VALUE=\"Search\"> <INPUT TYPE=BUTTON NAME=NONE VALUE=\"Clear\" ONCLICK=\"document.location = '".$_SERVER["PHP_SELF"]."?VIEW=".$_GET["VIEW"]."&SUB=".$_GET['SUB']."';\">";
			echo "<input type='checkbox' ";
			
			if($_GET['hidden'] == '1'){ echo " CHECKED "; }
			echo " name='hidden' value='1'> <small>Show Hidden</small>";
			echo "</FORM>";
			
		echo "</div>";
		echo "<br /><br />";
			
			
		if ($_GET['KEYWORDS']!="") {
			$q = "AND ".$_GET['COLUMN']." like '%".$_GET['KEYWORDS']."%'";
		}

		// SHOW HIDDEN
		if ($_GET['hidden']==''){
			$h = "AND hidden='0' "; 
		} else {
			$h = "";
		}
		
		if($_REQUEST['COLUMN'] != ""){
			$orderByString = " ORDER BY ".$_GET['COLUMN']." ASC";
		}
		
		$page = 1;
		$size = 50;	 

		if($Join != ""){ $Join = " LEFT JOIN ".$Join." t2 ON t1.".$On."=t2.".$On." "; }
		
		$select = 	"SELECT * FROM ".$table." t1 ".$Join." WHERE ".
					"t1.active='1' ".
					"$join".
					"$q ".
					"$h ".
					"".$_SETTINGS['demosqland']." ".
					"".$orderByString." ";
					
		$total_records = mysql_num_rows(doQuery($select)); 
		
		//echo "<Br>SELECT - $select<Br>";
		//echo "<Br>TOTAL RECORDS - $total_records<Br>";
		 
		if (isset($_GET['page'])){
			$page = (int) $_GET['page'];
		}

		$pagination = new Pagination();
		$pagination->setLink("index.php?VIEW=".$_REQUEST['VIEW']."&SUB=".$_REQUEST['SUB']."&KEYWORDS=".$_REQUEST['KEYWORDS']."&COLUMN=".$_REQUEST['COLUMN']."&page=%s");
		$pagination->setPage($page);
		$pagination->setSize($size);
		$pagination->setTotalRecords($total_records);
		 
		// now use this SQL statement to get records from your table

		$SQL = 	$select.$pagination->getLimitSql()."";	
		//echo "<Br> $SQL <Br>";
		
		// 
		// TABLE HEADER COLUMNS
		//
		echo tableHeaderid("".$name."",6,"100%","list");	
		echo "<thead><TR>";
		// CHECBOX
		echo "<th style='width:25px' width='25'>Delete</th>";
		$i = 0;
		$imagekey = 1000;
		$categorykey = 1000;
		$datekey = 1000;
		foreach($titleColumnArray as $column){
			$width = "";
			if($i == 0){ $width = "width:20px;"; }
			echo "<th style='".$width." ".$textalign."'>".$column."</th>";
			// SET TYPE FLAGS
			if($column == "Image"){ $imagekey = $i; }
			if($column == "Category"){ $categorykey = $i; }
			if(strstr($column,"Date")){ $datekey = $i; }
			$i++;
		}
		echo "<th>Action</th>";
		echo "</TR></thead><tbody>";	
		
		$res = doQuery($SQL);

		//echo "$SQL";
		
		$i=0;	
		while ($row = mysql_fetch_array($res)) {
			if($i % 2) { $class = "odd"; } else { $class = "even"; }
			if($row['hidden'] == '1'){ $class .= " hiddenrow"; }
			//document.location = '".$_SERVER["PHP_SELF"]."?VIEW=".$_GET["VIEW"]."&SUB=".$_GET['SUB']."&".$xid."=".$row[$valueColumnArray[0]]."';
			echo "<TR class=\"$class\" ondblclick=\" document.location = '".$_SERVER["PHP_SELF"]."?VIEW=".$_GET["VIEW"]."&SUB=".$_GET['SUB']."&".$xid."=".$row[$valueColumnArray[0]]."';\">";
			// CHECKBOX
			echo "<td>";
			if($row['locked'] != '1'){
				echo "<input type='checkbox' class='deletebox' name='delete_array[]' value='".$row[$valueColumnArray[0]]."'>";
			}
			echo "</td>";
			// VALUE COLUMNS
			$imagenum = 0;
			$numkey = 0;
			$dkey = 0;
			foreach($valueColumnArray as $column){
				$value = $row[$column];
				$textalign = "";
				
				/*** DESCRIPTION TYPE ***/
				if($column == "description"){					
					$value = truncate($value,100);
				}
				
				/*** IMAGE TYPE ***/
				elseif($imagenum == $imagekey){
					$img = "";
					$dir = "uploads";
					//$value = "";
					
					// CHECK FOR AN ALTERNATE UPLAODS PATH
					if(strstr($value,"::")){
						$valuearray = explode("::",$value);
						$column = $valuearray[0];
						$dir = $valuearray[1];
					}
					
					// SET THE IMAGE FILE NAME
					$img = $row[$column];
					
					// BUILD THE IMAGE
					if($img != ""){
						$value = "<img src='".$_SETTINGS['website']."".$dir."/".$row[$column]."'>";
						$textalign = "text-align:center; ";
					}
					
					/** FOR ECOMMERCE / TITLE MATCH
					if($value == ""){
						// SECOND LOOK FOR A NAME MATCH
						$size = lookupDbValue('ecommerce_thumbnail_sizes', 'name', $_SETTINGS['product_page_thumbnail_size'], 'size_id');
						$image1 = strtolower(str_replace(" ","_",$row['name']).".jpg");
						$image1Array = explode(".",$image1);
						$image1formated = $image1Array[0]."_w".$size.".".$image1Array[1];
						//$path = $_SETTINGS['website']."uploads-products/wpThumbnails/".$image1formated."";
						if(file_exists($_SETTINGS["DOC_ROOT"]."uploads-products/wpThumbnails/".$image1formated."")){
							//$path = $_SETTINGS['website']."themes/".$_SETTINGS['theme']."images/".$_SETTINGS['image_not_available_thumbnail_file']."";
							$value = "<img src='".$_SETTINGS['website']."uploads-products/wpThumbnails/".$image1formated."' style='display:block; margin:0px auto;'>";
						}
					}
					**/
					
					/** FOR ECOMMERCE RELAIONAL IMAGES 
					if($value == ""){
						// SECOND LOOK FOR IMAGES THAT ARE STORED IN A RELATIONAL TABLE THAT IS THE COLUMN VALUE
						// THE RELATIONAL TABLE MUST BE THE COLUMN VALUE
						$select = "SELECT * FROM `".$column."` WHERE `".$valueColumnArray[0]."`='".$row[$valueColumnArray[0]]."' LIMIT 1";
						// IMPLY NO ERROR
						$result = doQuery($select,0);
						$num = mysql_num_rows($result);					
						if($num){
							$row1 = mysql_fetch_array($result);
							$img = $row1['image'];
							// LOOK IN PRODUCTS
							$value = "<img src='".$_SETTINGS['website']."uploads-products/".$img."'>";
							$textalign = "text-align:center; ";
						}
						
						// THIRD CHECK FOR AN IMAGE IN OTHER PLACES IF THERE IS NONE HERE
						// THE IMAGE COLUMN MUST BE THE COLUMN VALUE
						if($img == ""){
							$img = $row[$column];
							if($img != ""){
								$value = "<img src='".$_SETTINGS['website']."uploads/".$row[$column]."'>";
								$textalign = "text-align:center; ";
							}
						}	
					} */
				}// END IMAGE FORMAT				
				
				// IF A CATEGORY VALUE
				elseif($numkey == $categorykey){
					/* FOR ECOMMERCE 
					
					//echo "<br>NUMKEY:".$numkey."<br>";
					//echo "<br>CATKEY:".$categorykey."<br>";
										
					$cat = "";
					// LOOK FOR CATGORIES IN A RELATIONAL TABLE
					// THE RELATIONAL TABLE MUST BE THE COLUMN VALUE
					$select = "SELECT * FROM `".$column."` WHERE `".$valueColumnArray[0]."`='".$row[$valueColumnArray[0]]."'";
					$result = doQuery($select,0);
					$num = mysql_num_rows($result);	
					$category = "";
					if($num){
						while($row1 = mysql_fetch_array($result)){							
							$category_id = $row1['category_id'];
							$category .= lookupDbValue('ecommerce_product_categories','name',$category_id,'category_id').",";
						}
					} else {
						$category_id = 0;
						$category = "Uncategorized";
					}
					
					//$value = "";
					$value = "<a id='cat-".$row[$valueColumnArray[0]]."'>".trim($category,",")."</a>";
					$value .= "<div id='catselect-".$row[$valueColumnArray[0]]."'></div>";
					$value .= "<script>";
					$value .= "
					$('#cat-".$row[$valueColumnArray[0]]."').click(function() {
						
						$.ajax({
						  type: 'POST',
						  url: '".$ajaxURL."',
						  data: 'GET_CATEGORY_SELECT=".$row[$valueColumnArray[0]]."',
						  success: function(data) {
							$('#catselect-".$row[$valueColumnArray[0]]."').html(data);
							$('#cat-".$row[$valueColumnArray[0]]."').html('');
						  }
						});
						
						//zoomloader.gif
						
						$('#cat-".$row[$valueColumnArray[0]]."').css('visibility','hidden');
						$('#catselect-".$row[$valueColumnArray[0]]."').html('<img src=\"images/zoomloader.gif\">');
						
						return false;
						
					});";
					$value .= "</script>";
					//$category = $row1['category_id'];						
					//$value = "<select></select>";
					//$value = "<script></script>"							
					//$value= "".hierarchymultiselectTable('ecommerce_product_categories',''.$row[$valueColumnArray[0]].'categories[]','category_id','name','sort_level','ASC',0, 'ecommerce_product_category_relational','product_id',''.$row[$valueColumnArray[0]].'')."";
					*/
				}
				
				// IF A DATE FIEld
				elseif($datekey == $dkey){
					$value = TimestampIntoDate($value);
				}
				
				// IF A COLUMN FROM ANTHER TABlE
				elseif(strstr($column,"::")){
					//die "<Br>VALUE $value;</br>";
					//exit;
					$valArray  = explode("::",$column);
					$table1 = $valArray[0];
					$column1 = $valArray[1];
					$link1 = $valArray[2];
					$select = "SELECT `".$column1."` FROM `".$table1."` WHERE ".$link1."='".$row[$link1]."' LIMIT 1";
					//echo "$select <br>";
					$result = doQuery($select);
					$val = mysql_fetch_array($result);
					$value = $val[$column1];
				}
				
				// IF A STATUS COLUMN
				elseif($column == "status"){
					$value = $row[$column];
				}
				
				// iF A TYPE COLUMN
				elseif($column == "type"){
					$value = $row[$column];
				}
				
				// IF ANY OTHER TYPE O FIELD SETUP FOR LIVE EDIT
				elseif($dkey != 0){
					if($readonly == false){
						$inputsize="15";
						if($column == 'price'){ $inputsize="5"; }
						if($column == 'name'){ $inputsize="75"; }
						$value = 	"<input id='".$column.$i."' name='' style='font-size:11px;' size='".$inputsize."' value='".$value."'>";
						$value .= 	"<script>";
						$value .= "
										$('input#".$column.$i."').change(function() {
											
											$.ajax({
											  type: 'POST',
											  url: '".$ajaxURL."',
											  data: 'UPDATE_TABLE=".$table."&UPDATE_FIELD=".$column."&UPDATE_FIELD_VALUE=' + $('#".$column.$i."').val() + '&UPDATE_ROW=".$valueColumnArray[0]."&UPDATE_ROW_ID=".$row[$valueColumnArray[0]]."',
											  success: function(data) {
												true;
											  }
											});
											
											
										});";
						
						$value .= 	"</script>";
					} else {
						$value = $row[$column];
					}
				}
				
				//DISPLAY CELL VALUE
				echo "<td style='".$textalign."'>";
				echo $value;
				echo "</td>";
				
				
				
				$imagenum++;
				$numkey++;
				$dkey++;
			}
			
			//echo "<TD>{$row["name"]}</TD>";
			//echo "<TD>/".$row['clean_url_name']."</TD>";
			//echo "<TD>{$row["title"]}</TD>";
			
			//
			
			// ACTION
			echo "<TD width=\"150\" nowrap ALIGN=\"LEFT\">";
				echo "<FORM METHOD=GET ACTION=\"".$_SERVER[PHP_SELF]."\">";				
				echo "<INPUT TYPE=HIDDEN NAME='".$xid."' VALUE=\"".$row[$valueColumnArray[0]]."\">";
				echo "<INPUT TYPE=HIDDEN NAME=VIEW VALUE=\"".$_GET["VIEW"]."\">";				
				echo "<INPUT TYPE=HIDDEN NAME=SUB VALUE=\"".$_GET["SUB"]."\">";
				echo "<INPUT TYPE=HIDDEN NAME=page VALUE=\"".$_GET["page"]."\">";
				if($row['locked'] != '1'){
					echo "<INPUT TYPE=SUBMIT NAME='DELETE_".rtrim(strtoupper($name),"S")."' VALUE=\"Delete\" onClick=\"return confirm('Are You Sure?');\">";
				}
				echo "<INPUT TYPE=SUBMIT NAME=view VALUE=\"Open\">";
				echo "</FORM>";
			echo "</TD>";
			echo "</TR>";
			$i++;
		}
		if($total_records = 0){
			echo "<tr><td colsapan='".count($titleColumnArray)."'>There are 0 records.</td></tr>";
		}
		echo "</tbody></TABLE>";
		echo "<form class='delete-form'>";
		echo "<input type='hidden' name='items' id='items' value=''>";
		echo "<input type='submit' value='Delete' name='DELETE_".strtoupper($name)."'>";
		echo "<INPUT TYPE=HIDDEN NAME=VIEW VALUE=\"".$_GET["VIEW"]."\">";				
		echo "<INPUT TYPE=HIDDEN NAME=SUB VALUE=\"".$_GET["SUB"]."\">";
		echo "<INPUT TYPE=HIDDEN NAME=page VALUE=\"".$_GET["page"]."\">";
		echo "</form>";
		echo "
			<script>
				// EACH INPUT CLICK GET THE CHECKED ITEMS
				$('.deletebox').click(function(){
					// GET ALL CHECKED INPUTS
					var items = '';
					$(\"input[name='delete_array[]']:checked\").each(function () {
						items += $(this).val() + ',';
					  });
					$('#items').val(items);
				});
			

			</script>
		";
		
		$navigation = $pagination->create_links();
		echo $navigation; // will draw our page navigation
		//exit();
	}
	
	//---//
	
	function startAdminForm()
	{
		echo "<form name='wesform' id='wesform' METHOD='POST' ACTION='' enctype='multipart/form-data'>";
	}
	
	//---//
	
	/**
	 *
	 * FUNCTION
	 * admin form
	 *
	 */
	function administrativeForm($name,$xid,$table,$id_column,$fieldLabelArray,$fieldTypeArray,$fieldRowArray,$fieldOptionsArray = "",$back="")
	{
		
		global $_REQUEST;
		global $_GET;
		global $_POST;
		
		if (isset($_REQUEST[$xid])) {
			$select = 	"SELECT * FROM ".$table." ".
						"WHERE ".
						"".$id_column."='".$_REQUEST[$xid]."'";
			$res = doQuery($select);
			
			$_POST = mysql_fetch_array($res);
			$button = "Update ".$name."";
			//$header = "<a href='?VIEW=".$_REQUEST['VIEW']."&SUB=".$_REQUEST['SUB']."'>".ucwords(strtolower($_REQUEST['SUB']))."</a> / ".$_POST['name']." <small style='font-size:12px; font-style:italic;'>Id: ".$_POST[$id_column]."</small>";
			$header = $_POST['name']." <small style='font-size:12px; font-style:italic;'>Id: ".$_POST[$id_column]."</small>";
		} else {
			$button = "Add ".$name."";
			$header = "<a href=''>".ucwords(strtolower($_REQUEST['SUB']))."</a> / New ";
		}
		
		echo "<FORM name='wesform' id='wesform' METHOD='POST' ACTION=''>";
			echo tableHeader($header,2,'100%');
		
				$i = 0;
				foreach($fieldLabelArray as $field){
					
					echo "<tr>";					
					echo "<th>".$field."</th>";
					echo "<td>";
					
					// TEXTBOX
					if(strtoupper($fieldTypeArray[$i]) == 'TEXTBOX'){
						echo "<input type='textbox' name='".$fieldRowArray[$i]."' value=\"".$_POST[$fieldRowArray[$i]]."\">";
					}
					
					// CURRENCY
					if(strtoupper($fieldTypeArray[$i]) == "CURRENCY"){
						echo "".$Ecommerce->currency." <INPUT TYPE='TEXT' NAME='".$fieldRowArray[$i]."' size='10' VALUE='".$_POST[$fieldRowArray[$i]]."' />"; 
					}
					
					// WEIGHT
					if(strtoupper($fieldTypeArray[$i]) == "WEIGHT"){
						echo "<INPUT TYPE='TEXT' NAME='".$fieldRowArray[$i]."' size='10' VALUE='".$_POST[$fieldRowArray[$i]]."' /> &nbsp; lbs.";
					}
					
					// TEXTAREA 
					if(strtoupper($fieldTypeArray[$i]) == "TEXTAREA"){
						echo "<TEXTAREA NAME='".$fieldRowArray[$i]."' style='width:200px;' >".$_POST[$fieldRowArray[$i]]."</TEXTAREA >";
					}		
					
					// CHECBOX
					if(strtoupper($fieldTypeArray[$i]) == "CHECKBOX"){
						$selected = "";
						if($value == '1'){ $selected = " SELECTED "; }
						echo "<INPUT TYPE='CHECKBOX' NAME='".$fieldRowArray[$i]."' ".$selected." value='1'  />";
					}
					
					// SELECT
					if(strtoupper($fieldTypeArray[$i]) == "SELECT"){
						
						// GET THE TYPE OF THE SELECT
						if(strstr($fieldOptionsArray[$i],"::")){
							$typeArray = explode("::",$fieldOptionsArray[$i]);
							$selecttype = $typeArray[0];
							$selecttable = $typeArray[1];
							@$selectrelational = $typeArray[2];
							@$selectrelationalcolumnname = $typeArray[3];
						}
						
						// IF PASSING AN ARRAY OF OPTIONS
						if(is_array($fieldOptionsArray[$i])){
							echo "		<select name='".$fieldRowArray[$i]."'>";
							foreach($fieldOptionsArray[$i] AS $option){
								$selected = "";
								if($_POST[$fieldRowArray[$i]] == $option){ $selected = " SELECTED "; }
								echo "			<option value='".$option."' ".$selected.">".$option."</option>";
							}
							echo "		</select>";
						}											
						// ELSE IF USING A TABLE
						elseif($selecttype == "table")
						{
							echo "		<select name='".$fieldRowArray[$i]."'>";
							$sel = "SELECT * FROM ".$selecttable." WHERE active='1'";
							$res = doQuery($sel);
							while($ro = mysql_fetch_array($res)){
								$selected = "";
								if($_POST[$fieldRowArray[$i]] == $ro[0] ){ $selected = " SELECTED "; }
								echo "			<option value='".$ro[0]."' ".$selected.">".$ro['name']."</option>";
							}
							echo "		</select>";
						}
						// ELSE IF A HIERARCHY TABLE
						elseif($selecttype == "hierarchy")
						{
							hierarchyselectTable(''.$selecttable.'',''.$fieldRowArray[$i].'',''.$fieldRowArray[$i].'','name','sort_level','ASC',0,1);
						}
						// ELSE IF A HIERARCHY MULTI SELECT TABLE
						//elseif($selecttype == "hierarchy-multi"){
						//	hierarchymultiselectTable(''.$selecttable.'',''.$fieldRowArray[$i].'',''.$fieldRowArray[$i].'','name','sort_level','ASC',0,''.$selectrelational.'',''.$selectrelationalcolumnname.'',''.$_POST[$fieldRowArray[$i]].'');
						//}
						
						echo "	</TD>";
						echo "</TR>";
					}
					
					
					echo "</td>";				
					echo "</tr>";
					$i++;
				}
		
			echo "</table>";		
		
			if(isset($_REQUEST[$xid])){		
				echo "<INPUT TYPE=HIDDEN NAME='".$xid."' VALUE='".$_POST[$id_column]."'>";		
			}
		
			echo "<div id='submit'>";
			echo "<a href='?VIEW=".$_GET['VIEW']."&SUB=".$_GET['SUB']."'>Back</a> &nbsp;&nbsp;&nbsp;"; 
			echo "<INPUT TYPE='HIDDEN' NAME='SUB' VALUE='".$_REQUEST['SUB']."'>";
			echo "<INPUT TYPE='HIDDEN' NAME='VIEW' VALUE='".$_REQUEST['VIEW']."'>";
			
			echo "<INPUT TYPE='SUBMIT' NAME='".strtoupper(str_replace(" ", "_", $button))."' VALUE='".$button."'>";
			if (isset($_REQUEST[$xid])){
				echo "<INPUT TYPE=SUBMIT NAME=DELETE_".strtoupper(str_replace(" ", "_", $name))." value=\"Delete\" onclick=\"return confirm('Are You Sure?');\">";
			}		
			
			echo "</div>";
		echo "</form>";		
	}

	//---//
	
	/**
	 *
	 * ADMIN SETTINGS FORM FUNCTION
	 * This is a framework function that all my modules can use.
	 * This function gets all the configurable settings to do with a module 
	 * 
	 *
	 */
	function administrativeSettingsForm($name,$group_id)
	{
		
		global $_REQUEST;
		global $_GET;
		global $_POST;
		$Settings = new Settings();
		
		$button = "Update ".$name;
		$header = $name;

		echo "<FORM name='wesform' id='wesform' METHOD='POST' ACTION='' enctype='multipart/form-data'>";
		
			echo tableHeader($header.$_POST[$id_column]."",2,'100%');
		
				$sela = "SELECT * FROM settings WHERE active=1 AND group_id='".$group_id."' ORDER BY type ASC";

				$resa = doQuery($sela);
				$numa = mysql_num_rows($resa);
				$ja = 0;
				
				//echo $sela;
				
				while($ja<$numa){
					$rowa = mysql_fetch_array($resa);					
					$Settings->displaySettingField($rowa,0,$ja);					
					$ja++;
				}
				
			echo "</table>";		
		
			//
			// Submit FORM
			//
			echo "<div id='submit'>";
			echo "	<a href='?VIEW=".$_GET['VIEW']."&SUB=".$GET['SUB']."'>Back</a> &nbsp;&nbsp;&nbsp;";
			echo "	<INPUT TYPE=SUBMIT NAME=\"".strtoupper(str_replace(" ", "_", $button))."\" VALUE=\"$button\">";	
			echo "</div>";
			
		echo "</form>";		
	}

	//---//
	
	function adminFormField($label,$name,$value,$type,$options=false,$note=false)
	{
		//$Ecommerce = new Ecommerce();
		echo "<tr id='".$name."_row'>";
		echo "	<th>";
		// NOTE 
		if($note != false){
			echo info($note);	
		}
		echo "".$label."";
		echo "</th>";
		echo "<td>";
		
			if(strstr($type,":")){
				$typearray = explode(":",$type);
				$type = $typearray[0];
				$table = $typearray[1];
			}
		
			// TEXTBOX
			if($type == "textbox"){
				echo "		<input type='text' name='".$name."' id='".$name."' style='width:200px;' value='".$value."' />";
			}
			
			// CURRENCY
			if($type == "currency"){
				echo "$ <INPUT TYPE='TEXT' NAME='".$name."' id='".$name."' size='10' VALUE='".$value."' />"; 
			}
			
			// Percentage as decimal
			if($type == "decimal"){
				echo "<INPUT TYPE='TEXT' NAME='".$name."' id='".$name."' size='10' VALUE='".$value."' /> <small>Ex. 0.5 would be equal to 50%, as 0.23 would be equal to 23%.</small> "; 
			}
			
			// WEIGHT
			if($type == "weight"){
				echo "	<INPUT TYPE='TEXT' NAME='".$name."' id='".$name."' size='10' VALUE='".$value."' /> &nbsp; lbs.";
			}
			
			// TEXTAREA 
			if($type == "textarea"){
				echo "		<TEXTAREA NAME='".$name."' id='".$name."' style='width:200px;' >".$value."</TEXTAREA >";
			}		
			
			// CHECKBOX
			if($type == "checkbox"){
				$selected = "";
				if($value == '1'){ $selected = " CHECKED "; }
				echo "		<INPUT TYPE='CHECKBOX' NAME='".$name."' id='".$name."' ".$selected." value='1'  />";
			}
			
			// SELECT
			if($type == "select"){
				if(is_array($options)){
					echo "		<select name='".$name."' id='".$name."'>";
					foreach($options AS $option){
						$selected = "";
						if($value == $option){ $selected = " SELECTED "; }
						echo "			<option value='".$option."' ".$selected.">".$option."</option>";
					}
					echo "		</select>";
				}			
				if($table != ""){
					//echo "";
					echo "		<select name='".$name."' id='".$name."'>";
					$select = "SELECT * FROM `".$table."` WHERE active='1'";
					$result = doQuery($select);
					$num = mysql_num_rows($result);
					$i = 0;
					while($i<$num){
						$row = mysql_fetch_array($result);
						$selected = "";
						if($value == $row[0]){ $selected = " SELECTED "; }
						echo "		<option value='".$row[0]."' ".$selected.">".$row['name']."</option>";
						$i++;
					}
					echo "		</select>";
				}
			}
			
			// DATE
			if($type == "date"){
				$value = TimestampIntoDate($value);
				echo "<script>$(function() { $('.datepicker".$name."').datepicker(); });</script>";
				echo "<input type='textbox' class='datepicker".$name."' id='".$name."' size='7' name='".$name."' value='".$value."'>";
			}
			
			// IMAGE
			if($type == "image"){
				$path = $options;
				//$path = '../uploads/';
				echo "<input style='float:none;' type='text' name='".$name."' id='".$name."' value='".$value."' /><button type='button' onClick=\"SmallFileBrowser('".$path."','".$name."')\">Choose Image...</button>";
			}
			
			
			
		
		echo "</td>";
		echo "</tr>";
	}
	
	//---//
	
	function endAdminForm($button,$xid,$identifier,$row="")
	{
		global $_GET;
		global $_REQUEST;
		
		echo "</table>";
		echo "<div id='submit'>";
		//echo "	<a href='?VIEW=".$_GET['VIEW']."&SUB=".$_GET['SUB']."'>Back</a> &nbsp;&nbsp;&nbsp;";
		echo " <a href='javascript:history.go(-1)'>Back</a> &nbsp;&nbsp;&nbsp;";
		echo "	<INPUT TYPE='SUBMIT' NAME='".strtoupper(str_replace(" ", "_", $button))."' VALUE='".$button."'>";
				if(isset($_REQUEST['parentItemId'])){
					echo "<INPUT TYPE=HIDDEN NAME='parentItemId' VALUE='".$_REQUEST['parentItemId']."'>";
				}
				if(isset($_REQUEST[$xid])){
					echo "<INPUT TYPE=HIDDEN NAME='".$xid."' VALUE='".$_REQUEST[$xid]."'>";
					if($row['locked'] != '1'){
						echo '<INPUT TYPE="SUBMIT" NAME="DELETE_'.$identifier.'" value="Delete" onclick="return confirm(\'Are You Sure?\');">';
					}
				}
		echo "</div>";
		// JAVASCRIPT FOR TOGGLES
		echo '<script> ';
		echo '	  function callback($ident){ ';
		echo '		$(".toggleidentifier"+$ident+"").css({"display" : ""}); ';
		echo '		$(".toggleridentifier"+$ident+"").css({"display" : "none"}); ';
		echo '		$(".toggleropenidentifier"+$ident+"").css({"display" : "none"}); ';
		echo '		$(".togglercloseidentifier"+$ident+"").css({"display" : ""}); ';			
		echo '		return true; ';
		echo '	  } ';		  
		echo '	  function callback1($ident){ ';
		echo '		$(".toggleidentifier"+$ident+"").css({"display" : "none"}); ';
		echo '		$(".toggleridentifier"+$ident+"").css({"display" : "inline"}); ';
		echo '		$(".toggleropenidentifier"+$ident+"").css({"display" : ""}); ';
		echo '		$(".togglercloseidentifier"+$ident+"").css({"display" : "none"}); ';
		echo '		return true; ';
		echo '	  } ';
		echo '	</script> ';
		echo '</FORM> ';
	}
	
	//---//
	
	function selectPermissions()
	{	
		global $_REQUEST;
		global $_POST;
		
		echo "<TR class='toggleidentifier'>";
		echo "<Th>Permission</TH>";
		echo "<TD>";								
		$sel1 = "SELECT * FROM user_permission WHERE active='1' ORDER BY permission_level ASC";
		$res1 = doQuery($sel1);
		$num1 = mysql_num_rows($res1);
		$i1 = 0;
		echo "<select name='user_permission'>";
		$selected = "";
		if($_POST['user_permission'] == "0"){ $selected = " SELECTED "; }
		echo "	<option value='0' ".$selected.">None</option>";
		while($i1<$num1){
			$row1 = mysql_fetch_array($res1);
			$selected = "";
			if($_POST['user_permission'] == $row1['permission_id']){ $selected = " SELECTED "; }
			echo "<option value='".$row1['permission_id']."'>";
			echo $row1['name'];
			echo "</option>";
			$i1++;
		}
		echo "</select>";
		// INFO 'Set the permission level users must have to access this page.'
		echo "</TD>";
		echo "</TR>";		
	}
	
	//---//
	
	/**
	 *
	 * FUNCTION
	 * administrative crud table
	 *
	 */
	function crudTable($table,$name,$idColumn,$id,$xid,$emptyValidatedFieldArray,$fieldArray,$parentItemId="")
	{	
		global $_POST;
		global $_GET;
		global $_REQUEST;
		
		// ADD UNDERSCORES TO NAME
		$name = str_replace(" ","_",$name);
		
		//
		// CREATE / ADD
		//
		if (isset($_POST['ADD_'.strtoupper($name).''])){
			$error = 0;
			
			// EMPTY VALIDATION
			foreach($emptyValidatedFieldArray as $field){
				if($_POST[$field] == ""){ $error = 1; ReportError("Complete all required fields"); }	
			}	
			
			// OTHER VALIDATION LIKE EMAIL WILL GO HERE 
			// ...
			
			if($error==0){						
				// ESCAPE DATA
				$_POST = escape_smart_array($_POST);				
				// FORMATTING DATA WILL GO HERE
				// ...				
				// NEXT 
				$next = nextId($table);				
				// UPDATE STRING
				$select = 		"INSERT INTO ".$table." SET ";					
				// PARENT ID
				//if($parentItemId != ""){
				//	$select .= "`".$parentItemId."`='".$_REQUEST[$parentItemId]."',";
				//}			
				// LOOP FIELDS
				foreach($fieldArray as $field){
					$select .=	"`".$field."`='".$_POST[$field]."',";					
				}							
				// TRIM THE LAST COMMA
				$select = rtrim($select,",");				
				// DO INSERT
				$result = doQuery($select);	

				// TESTING
				//echo "<br>SELECT: $select <br>";
				//die();
				//exit();
				
				// SUCCESSFUL REDIRECT 
				$name = str_replace("_"," ",$name);
				header("Location: ".$_SERVER["PHP_SELF"]."?VIEW=".$_REQUEST["VIEW"]."&REPORT=".$name." created successfully&SUCCESS=1&".$xid."=".$next."");
				exit();
			}
		}
		
		//
		// UPDATE
		//
		if (isset($_REQUEST['UPDATE_'.strtoupper($name).''])){
			$error = 0;
			
			// EMPTY VALIDATION
			foreach($emptyValidatedFieldArray as $field){
				if($_POST[$field] == ""){ $error = 1; ReportError("Complete all required fields"); }	
			}	
			
			// OTHER VALIDATION LIKE EMAIL WILL GO HERE 
			// ...
			
			if($error==0){
						
				// ESCAPE DATA
				$_POST = escape_smart_array($_POST);
				
				// FORMATTING DATA WILL GO HERE
				// ...
				
				// UPDATE STRING
				$select = 		"UPDATE ".$table." SET ";				
				foreach($fieldArray as $field){
					$select .=	"`".$field."`='".$_POST[$field]."',";					
				}			
				$select = rtrim($select,",");
				$select	.=		" WHERE `".$idColumn."`='".$id."'";
				
				// DO UPDATE
				$result = doQuery($select);
				
				header("Location: ".$_SERVER["PHP_SELF"]."?VIEW=".$_REQUEST["VIEW"]."&SUB=".$_REQUEST['SUB']."&REPORT=".$name." updated successfully&SUCCESS=1&".$xid."=".$id."");
				exit();
			}
		}
		
		//
		// DELETE
		//
		if (isset($_REQUEST['DELETE_'.strtoupper($name).''])){
			$error = 0;		
			doQuery("DELETE FROM ".$table." WHERE ".$idColumn."='".$id."'");		
			header("Location: ".$_SERVER["PHP_SELF"]."?VIEW=".$_REQUEST["VIEW"]."&SUB=".$_REQUEST['SUB']."&REPORT=".str_replace("_"," ",$name)." Deleted Successfully&SUCCESS=1");
			exit();
		}	
	}

	



	
	function universalResults($table){
		// GET ALL THE COLUMNS FROM THE TABLE
		
		// SEARCH FORM
		
		// SEARCH RESULTS
		
		//
		
		return true;
	}
	
	function universalForm(){
		
		return true;
	}
	
	function universalPowerTools(){
		
		return true;
	}
	
	function formatBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);
		return round($bytes, $precision) . ' ' . $units[$pow];
	    } 
?>