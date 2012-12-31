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
if(isset($_POST['ajax']))
{
    if(is_file('../../../includes/config.php'))
    {
        require_once '../../../includes/config.php';
    } else {
        echo "THERE IS NO CONFIG!";
    }
    global $_SETTINGS;
    global $_SESSION;   
}

$Settings = new Settings();

// AJAX SAVE FIELD POST
if(isset($_POST['save']) == '1')
{
    //echo "made it this far";
    $Settings->updateSetting($_POST);
    echo "success";
    die();
    exit();
}

// ERROR/SUCCESS MSG
report(urlRequest('msg'),urlRequest('msgType'));

// SETTINGS TABLE
//if(urlRequest('section') == "")
//{
    echo '	<div id="settings-box" class="box box-100">
				<div class="boxin tabs">
					<div class="header">
						<h3>Settings</h3>
						<a class="button" href="#">New Setting</a>
						<ul>
							<li><a href="#tabs-general">General</a></li>';
							// MODULE TABS
							$moduleDir = $_SETTINGS['DOC_ROOT']."admin/modules/";
							$modules = arrayDirectories($moduleDir);              
							foreach($modules as $module){ if($module != "dashboard"){ echo '<li><a href="#tabs-'.$module.'">'.$module.'</a></li>'; }}
	echo '				</ul>
					</div>';
	
	// GENERAL TAB IS ALL SETTINGS WITH NO GROUP
	echo ' 			<div id="tabs-general" class="content">
						<form class="plain" action="" method="post" enctype="multipart/form-data">
							<fieldset>
								<table cellspacing="0">
									<thead>
										<tr>
											<th>Setting Name</th>
											<td>Description</td>
											<td style="width:300px;">Value</td>
										</tr>
									</thead>
									<!--
									<tfoot>
										<tr>
											<td colspan="6">
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
									-->
									<tbody>';
										$select = "SELECT * FROM settings WHERE active=1 AND groups='' ORDER BY type ASC";
										$result = doQuery($select);
										$i = 0;
										while($row = mysql_fetch_array($result))
										{    
											echo '  <tr class="'.($i==0 ? "first" : "").' ">
														<td style="font-weight:bold;">'.($row['user_friendly_name'] != "" ? $row['user_friendly_name'] : $row['name']).'</td>
														<td><strong style="color:#aaa; font-size:11px;">$_SETTINGS["'.strtolower(str_replace(" ","_",$row['name'])).'"]</strong><br> '.$row['description'].'</td>
														<td>';
														$Settings->displaySettingField($row);
											echo '      </td>
													</tr>';
											$i++;
										}
	echo '  	                    </tbody>
								</table>
							</fieldset>
						</form>
						<div class="pagination">
							<ul style="visibility:hidden;">
									<li><a href="#">previous</a></li>
									<li><a href="#">1</a></li>
									<li><a href="#">2</a></li>
									<li><strong>3</strong></li>
									<li><a href="#">4</a></li>
									<li><a href="#">5</a></li>
									<li><a href="#">next</a></li>
							</ul>
						</div>
					</div><!-- END BOX1 GENERAL -->';
	
	//debugArray($modules);	 
    // MODULE GROUP SETTINGS
    foreach($modules as $module)
    {
		if($module != 'dashboard')
		{
			echo '		<div id="tabs-'.$module.'" class="content">
							<form class="plain" action="" method="post" enctype="multipart/form-data">
								<fieldset>
									<table cellspacing="0">
										<thead>
											<tr>
												<th>Setting Name</th>
												<td>Description</td>
												<td style="width:300px;">Value</td>
											</tr>
										</thead>
										<!--
										<tfoot>
											<tr>
												<td colspan="6">
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
										-->
										<tbody>';
									
										$select = "SELECT * FROM settings WHERE active=1 ORDER BY type ASC";
										$result = doQuery($select);
										$i = 0;
										while($row = mysql_fetch_array($result))
										{
											if(strstr($row['groups'],$module))
											{
												echo '  <tr class="'.($i==0 ? "first" : "").' ">
															<td style="font-weight:bold;">'.($row['user_friendly_name'] != "" ? $row['user_friendly_name'] : $row['name']).'</td>
															<td><strong style="color:#aaa; font-size:11px;">$_SETTINGS["'.strtolower(str_replace(" ","_",$row['name'])).'"]</strong><br> '.$row['description'].'</td>
															<td>';
															$Settings->displaySettingField($row);
												echo '      </td>
														</tr>';
											}
											$i++;
										}
									
			echo '                     	</tbody>
									</table>
								</fieldset>
							</form>
							<div class="pagination">
								<ul style="visibility:hidden;">
									<li><a href="#">previous</a></li>
									<li><a href="#">1</a></li>
									<li><a href="#">2</a></li>
									<li><strong>3</strong></li>
									<li><a href="#">4</a></li>
									<li><a href="#">5</a></li>
									<li><a href="#">next</a></li>
								</ul>
							</div>
						</div>';
		}
    }                
             
    echo '    	</div>
			</div>';     
//}
?>