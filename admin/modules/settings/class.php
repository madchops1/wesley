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
class Settings {
    
    // CLASS CONSTRUCTOR
    function Settings()
    {
    
    
    }
    
    
    // DISPLAY SETTING FIELD
    function displaySettingField($rowa)
    {
        global $_SESSION;
        global $_SETTINGS;

        // GET VALUE IF USER SETTING
        if($rowa['user_setting'] == "1")
        {
            $sel2 = "SELECT * FROM settings_user WHERE ".
                    "user_id='".$_SESSION["session"]->admin->user_id."' AND ".
                    "setting_id='".$rowa['id']."' LIMIT 1";
            $res2 = doQuery($sel2);
            $row2 = mysql_fetch_array($res2);
            $rowa['value'] = $row2['value'];
            $rowa['item_id'] = $row2['item_id'];
            $table = "settings_user";
        }
        // GET VALUE OF WEBSITE SETTING
        else
        {
            $sel2 = "SELECT * FROM settings_website WHERE ".
                    "website_id='".$_SETTINGS['website_id']."' AND ".
                    "setting_id='".$rowa['id']."' LIMIT 1";
            $res2 = doQuery($sel2);
            $row2 = mysql_fetch_array($res2);
            $rowa['value'] = $row2['value'];
            $rowa['item_id'] = $row2['item_id'];
            $table = "settings_website";
        }

        $ajaxAttributes = 'id="'.randomNumber().'" table="'.$table.'" column="value" xid="'.$rowa['item_id'].'" ';
        
        // TEXTBOX
        if($rowa['type'] == "Textbox"){
            echo '  <input class="ajax-input" type="text" settingtype="Textbox" '.$ajaxAttributes.' size="50" name="'.$rowa['name'].'" value="'.$rowa['value'].'" />';
        }
        
        
        // IMAGE
        if($rowa['type'] == "Image"){	
            echo '  <input class="ajax-image" type="text" '.$ajaxAttributes.' settingtype="Image" name="'.$rowa['name'].'" value="'.basename($rowa['value']).'" />';
            //echo '  <button type="button" onClick="SmallFileBrowser(\'../uploads/\',\''.$rowa['name'].'\')">Choose Image...</button>';
        }
        
        // TEXTAREA
        if($rowa['type'] == "Textarea"){
            echo '  <textarea class="ajax-textarea" settingtype="Textarea" '.$ajaxAttributes.' name="'.$rowa['name'].'">'.$rowa['value'].'</textarea>';
        }
              
        // PAGE CLEAN URL
        if($rowa['type'] == "page"){
        
            echo '  <select class="ajax-select" settingtype="page" '.$ajaxAttributes.' name="'.$rowa['name'].'">
                        <option value=""> -- Select Page -- </option>';
                        
                        $sel1 = "SELECT name FROM pages WHERE active='1' ORDER BY name ASC";
                        $res1 = doQuery($sel1);
                        $num1 = mysql_num_rows($res1);
                        $i1 = 0;
                        while($i1<$num1){
                                $rowa1 = mysql_fetch_array($res1);
                                echo '<option '.($rowa['value'] == $rowa1['name'] ? "SELECTED" : "").' value="'.$rowa1['name'].'">'.$rowa1['name'].'</option>';
                                $i1++;
                        }
            echo '  </select>';
               
        }
        
        // BOOLEAN					
        if($rowa['type'] == "Boolean")
        {					
            echo '  <input class="ajax-radio" type="radio" settingtype="Boolean" '.$ajaxAttributes.' style="display:inline-block;" name="'.$rowa['name'].'" value="1" '.($rowa['value'] == '1' ? "CHECKED" : "").' /> <span class="setting-radio-label">Yes <small><i>True</i></small></span>';
            echo '	<br>';
			echo '  <input class="ajax-radio" type="radio" settingtype="Boolean" '.$ajaxAttributes.' style="display:inline-block;" name="'.$rowa['name'].'" value="0" '.($rowa['value'] == '0' ? "CHECKED" : "").' /> <span class="setting-radio-label">No <small><i>False</i></small></span>';
        }					
        
        // TABLE ROW ID
        if($rowa['type'] == "table_row_id")
        {
            $flag = 0;
            if(strstr($rowa['table'],":")){
                    $tableArray = explode(":",$rowa['table']);
                    $rowa['table'] = $tableArray[0];						
                    $table = $tableArray[0];
                    $flag = 1;
            } else {
                    $table = $rowa['table'];
            }
                
                
            echo '  <select class="ajax-select" settingtype="table_row_id" '.$ajaxAttributes.' name="'.$rowa['name'].'">
                        <option value="0" '.($rowa['value'] == '0' ? " SELECTED " : "").'> -- Select Setting -- </option>';
                    
                    $sel1 = "SELECT * FROM `".$table."` WHERE active='1'";
                    $res1 = doQuery($sel1);
                    $num1 = mysql_num_rows($res1);
                    $i1 = 0;
                    while($i1<$num1){
                        $rowa1 = mysql_fetch_array($res1);
                        $option = $rowa1['name'];
                        if($flag == 1){ $option = $rowa1[$tableArray[1]]; }
                        echo '<option '.($rowa['value'] == $rowa1[0] ? "SELECTED" : "").' value="'.$rowa1[0].'">'.$option.'</option>';
                        $i1++;
                    }
                                                    
            echo '  </select>';
            
        }
        
    }

    // UPDATE A SETTING
    function updateSetting($row)
    {
    
        global $_SESSION;
        global $_SETTINGS;
        
        //$_POST = escape_smart_array($_POST);
        $value = $row['value'];
        
        if($row['type'] == 'Image'){
            $value = basename($value);
        }
        
        if($row['table'] == "settings_user")
        {
            // CHECK UPDATE USER SETTING
            $sel1 = "SELECT * FROM settings_user WHERE ".
                    "user_id='".$_SESSION["session"]->admin->user_id."' AND ".
                    "setting_id='".$row['id']."' AND ".
                    "active='1' LIMIT 1";
            $res1 = doQuery($sel1);		
            $num1 = mysql_num_rows($res1);
        
            // INSERT USER SETTING VALUE
            if($num1 < 1)
            {	
                $sel1 = "INSERT INTO settings_user SET ".
                        "setting_id='".$row['id']."',".
                        "user_id='".$_SESSION["session"]->admin->user_id."',".
                        "value='".escape_smart($value)."',".
                        "active='1'";
                $res1 = doQuery($sel1);		
            }
            // UPDATE USER SETTING VALUE
            else
            {	
                $sel1 = "UPDATE settings_user SET ".
                        "value='".escape_smart($value)."' ".
                        "WHERE ".
                        "setting_id='".$row['id']."' AND ".
                        "admin_user_id='".$_SESSION["session"]->admin->user_id."' AND ".						
                        "active='1'";
                $res1 = doQuery($sel1);					
            }									
        }
        // UPDATE WEBSITE SETTINGS
        else
        {			
            $sel1 = "UPDATE settings_website SET ". 
                    "value='".escape_smart($value)."' ".
                    "WHERE 1=1 AND ".
                    "website_id='".settingRequest('website_id')."' AND ".
                    "item_id='".$row['id']."' AND ".
                    "active='1'";
            $res1 = doQuery($sel1);		
        }
        echo $sel1;
    }
}
?>