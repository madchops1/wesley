<?
/*************************************************************************************************************************************
*
*	Wesley (TM), A Karl Steltenpohl Development LLC Product and Service
*  	Copyright (c) 2011 Karl Steltenpohl Development LLC. All Rights Reserved.
*	
*	This file is part of Karl Steltenpohl Development LLC's WESLEY (Website Enterprise Software).
*	Written By: Karl Steltenpohl
*	
*	Commercial License
*	http://wesley.wescms.com/license
*
*************************************************************************************************************************************/

// POWER TABLE
function powerPagesTable()
{
	global $_SETTINGS;	
	$Pages = new Pages();
	
	// DISPLAY POWER TOOLS
	$content = '	<div id="box1" class="box box-100">
						<div class="boxin">
							<div class="header">
								<h3>Pages</h3>
								<a class="button" id="newPage" href="">New Page</a>
							</div>
							<div id="box1-tabular" class="content"><!-- content box 1 for tab switching -->
								<!-- <form class="plain" action="" method="post" enctype="multipart/form-data"> -->
									<fieldset>
										<table cellspacing="0" id="pagestable">
											<thead>
												<tr>
													<td class=""><input type="checkbox" id="check-all" name="data-1-check-all" value="true"  /></td>
													<td class="tc small">Name</td>
													<td class="tc small">SEO Title</td>
													<td class="tc large">Parents</th>
													<td class="tc medium">Template</th>
													<td class="tc medium">Status</td>
													<td class="tc">Actions</td>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<td colspan="7">
														<!--
														<label>
															with selected do:
															<select name="data-1-groupaction" class="singleselect">
																<option value="delete">Delete</option>
																<option value="edit">edit</option>
															</select>
														</label>
														-->
														<input class="button altbutton ajax-delete-many" table="pages" columnid="page_id" type="submit" value="Delete" />
													</td>
												</tr>
											</tfoot>
											<tbody>';
											
											// HANDLE SEARCH KEYWORDS
											$searchSql = "";
											if (urlRequest('keywords') != "")
											{
												$searchSql = 	" AND ( ".
																" 	name LIKE '%".urlRequest('keywords')."%' OR ".
																"	seo_title LIKE '%".urlRequest('keywords')."%' OR ".
																"	keywords LIKE '%".urlRequest('keywords')."%' ". 
																" )";
											}
											
											// GET CURRENT PAGE
											$page = 1;
											$size = 50;
											if (isset($_GET['page']))
											{
												$page = (int) $_GET['page'];
											}
											
											$select = 	"SELECT * FROM pages ".
														"WHERE 1=1 ".
														" ".$searchSql." ".
														"AND website_id='".$_SETTINGS['website_id']."' ".
														"AND active='1'";
											$result = doQuery($select);
											$total_records = mysql_num_rows($result);
											
											$pagination = new Pagination();
											$pagination->setLink("index.php?module=".urlRequest('module')."&keywords=".urlRequest('keywords')."&page=%s");
											$pagination->setPage($page);
											$pagination->setSize($size);
											$pagination->setTotalRecords($total_records);
											
											$select .= $pagination->getLimitSql();
											$result = doQuery($select);
											$num = mysql_num_rows($result);
											
											$i = 0;
											while($row = mysql_fetch_array($result))
											{
												
												// RELATIONAL PARENTS MULTISELECT BOX
												$homePageId     = lookupDbValue('pages','page_id','1','home');
												$homePageName   = lookupDbValue('pages','name','1','home');
												
												$parentsMultiSelect = "		<select class='multiselect ajax-parents-multiselect' multiple='multiple' xid='".$row['page_id']."'>";
												//$parentsMultiSelect .= "             <option value='".$homePageId."'>Home</option>";
												
												$parentSelect =     "SELECT * FROM pages ".
																	"WHERE 1=1 ".
																	"AND website_id='".$_SETTINGS['website_id']."' ".
																	"AND page_id!='".$row['page_id']."' ".
																	"AND active='1'";
																	
												$parentResult =     doQuery($parentSelect);
												$parentNum    =     mysql_num_rows($parentResult);
												$parenti      =     0;
												while($parentRow = mysql_fetch_array($parentResult))
												{
													// CHECK IF SELECTED
													$checkSelect = "SELECT * FROM pages_parents WHERE page_id='".$row['page_id']."' AND parent_page_id='".$parentRow['page_id']."'";
													$checkResult = doQuery($checkSelect);
													if(mysql_num_rows($checkResult)){ $selected = " selected='selected' "; } else { $selected = ""; }
													$parentsMultiSelect .= "         <option value='".$parentRow['page_id']."' ".$selected." >".$parentRow['name']."</option>";
													$parenti++;
												}
												
												$parentsMultiSelect .= "	</select>";
											
												// NO PARENTS SELECT FOR HOMEPAGE // HOMEPAGE CANNOT HAVE PARENTS
												if($row['home'] == '1'){ $parentsMultiSelect = ""; }
												
											
												// TEMPLATE SELECTBOX - MUST HAVE UNIQUE ID
												$themesSelect = "		<select class='ajax-select singleselect' table='pages' column='template' columnid='page_id' id='".randomNumber()."' xid='".$row['page_id']."'>";
												// GET DIR ARRAY
												
												$dirs = $Pages->arrayTemplates();
												foreach($dirs as $dir)
												{
													$dirArray 		= explode(".",$dir);
													$optionName		= ucwords(str_replace("template_","",$dirArray[0]));
													$optionValue 	        = $dir;
													$themesSelect .= "      <option value='".$optionValue."' ".selected($optionValue,$row['template']).">".$optionName."</option>";
												}
													
												$themesSelect .= "		</select>";
											
												$siteSubDomain	= lookupDbValue('websites','subdomain',$_SETTINGS['website_id'],'website_id');
												$siteDomain		= lookupDbValue('websites','domain',$_SETTINGS['website_id'],'website_id');
												
												// DOMAIN
												if($siteDomain != "")
												{
													$editPageLink = "http://".$siteDomain."/".$Pages->formatCleanPageUrl($row['name'])."/session/".session_id()."/cms/1";
												} 
												// SUBDOMAIN
												else
												{
													$editPageLink = "http://".$siteSubDomain.".wescms.com/".$Pages->formatCleanPageUrl($row['name'])."/session/".session_id()."/cms/1";
												}
												
												$content .= '	<tr>
																	<td class=""><input type="checkbox" class="rowcheck" id="check-'.$i.'" name="check-'.$i.'" table="pages" xid="'.$row['page_id'].'" columnid="page_id" value="1" /></td>
																	<td class="tc small"><input type="text" class="ajax-input" table="pages" column="name" columnid="page_id" xid="'.$row['page_id'].'" value="'.$row['name'].'" /></td>
																	<td class="tc small"><input type="text" class="ajax-input" table="pages" column="seo_title" columnid="page_id" xid="'.$row['page_id'].'" value="'.$row['seo_title'].'" /></td>
																	<td class="tc large">'.$parentsMultiSelect.'</td>
																	<td class="tc medium">'.$themesSelect.'</td>
																	<td class="tc medium">'.statusField('pages','status',$row['page_id'],'page_id',$row['status']).'</td>
																	<td class="tc">
																		<a class="button" href="'.$editPageLink.'" target="_blank">Edit</a> 
																		<a class="button ajax-delete" table="pages" xid="'.$row['page_id'].'" columnid="page_id" href="">Delete</a>
																	</td>
																</tr>';
												$i++;
											}
	/*
												<tr class="first"><!-- .first for first row of the table (only if there is thead) -->
													<td class="tc"></td>
													<td class="tc"><span class="tag tag-gray">jpeg</span></td>
													<th><a href="#">On vacation with my 13.3î honey</a></th>
													<td>Lovely picture of me and my MacBook during sunset ƒ</td>
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
													<th><a href="#">On vacation with my 13.3î honey</a></th>
													<td>Lovely picture of me and my MacBook during sunset ƒ</td>
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

													<th><a href="#">On vacation with my 13.3î honey</a></th>
													<td>Lovely picture of me and my MacBook during sunset ƒ</td>
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
													<th><a href="#">On vacation with my 13.3î honey</a></th>
													<td>Lovely picture of me and my MacBook during sunset ƒ</td>

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
								<!-- </form> -->
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
															<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3î honey</a></h4>
															<p>Lovely picture of me and my MacBook during sunset ƒ Lovely picture of me and my MacBook during sunset ƒ Lovely picture of me and my MacBook during sunset ƒ</p>
															<p>Lovely picture of me and my MacBook during sunset ƒ</p>

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
															<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3î honey</a></h4>
															<p>Lovely picture of me and my MacBook during sunset ƒ Lovely picture of me and my MacBook during sunset</p>

															<p>Lovely picture of me and my MacBook during sunset ƒ</p>
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
															<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3î honey</a></h4>

															<p>Lovely picture of me and my MacBook during sunset ƒ Lovely picture of me and my MacBook during sunset ƒ Lovely picture of me and my MacBook during sunset ƒ</p>
															<p>Lovely picture of me and my MacBook during sunset ƒ</p>
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
															<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3î honey</a></h4>

															<p>Lovely picture of me and my MacBook during sunset ƒ Lovely picture of me and my MacBook during sunset ƒ Lovely picture of me and my MacBook during sunset ƒ</p>
															<p>Lovely picture of me and my MacBook during sunset ƒ</p>
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

// POWER FORM
function powerPagesForm()
{
	
	return true;
}
?>