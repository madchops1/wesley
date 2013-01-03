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

report(urlRequest('msg'),urlRequest('msgType'));
?>
<!-- Main content Ð all content is within boxes. Feel free to add your boxes (see the examples on the bottom of this document) and your content within -->
<div class="box box-50 altbox"><!-- .altbox for alternative box's color -->
	<div class="boxin">
		<div class="header">
			<h3>My Websites</h3>
			<a class="button" href="#">Create/add new website</a>
		</div>
		<div class="content">
			<table cellspacing="0">
				<thead>
					<tr>
						<th>Name</th>
						<td>URL</td>
						<td class="tc"></td>
					</tr>
				</thead>
				<tbody>
				
					<?php 
					$i = 0;
					$myWebsites = $_SESSION["session"]->admin->getUsersWebsites();
					while($row = mysql_fetch_array($myWebsites)){
						
						if($row['domain'] != ''){
							$url = $row['domain'];
						} else {
							$url = $row['subdomain'].'.wescms.com';
						}
						
						echo '	<tr class="first">
									<th><a href="#">'.$row['name'].'</a></th>
									<td><a class="" href="#">http://'.$url.'</a></td>
									<td class="tc"><a class="button" href="#" target="_blank">Manage</a> </td>
								</tr>';
						$i++;
					}
					?>
				
					<?php /* ?>
					<tr>
						<th><a href="#">Simple dropdown menu using jQuery</a></th>
						<td class="tc"><a class="ico-comms" href="#">1</a></td>
						<td>10/24/2009</td>
						<td><a href="#">edit</a></td>

					</tr>
					<tr>
						<th><a href="#">Simple dropdown menu using jQuery</a></th>
						<td class="tc"><a class="ico-comms" href="#">45</a></td>
						<td>10/24/2009</td>
						<td><a href="#">edit</a></td>
					</tr>

					<tr>
						<th><a href="#">Simple dropdown menu using jQuery</a></th>
						<td class="tc"><a class="ico-comms" href="#">0</a></td>
						<td>10/24/2009</td>
						<td><a href="#">edit</a></td>
					</tr>
					<tr>

						<th><a href="#">Simple dropdown menu using jQuery</a></th>
						<td class="tc"><a class="ico-comms" href="#">9</a></td>
						<td>10/24/2009</td>
						<td><a href="#">edit</a></td>
					</tr>
					<tr>
						<th><a href="#">Simple dropdown menu using jQuery</a></th>

						<td class="tc"><a class="ico-comms" href="#">209</a></td>
						<td>10/24/2009</td>
						<td><a href="#">edit</a></td>
					</tr>
					<tr>
						<th><a href="#">Simple dropdown menu using jQuery</a></th>
						<td class="tc"><a class="ico-comms" href="#">0</a></td>

						<td>10/24/2009</td>
						<td><a href="#">edit</a></td>
					</tr>
					*/ ?>
				</tbody>
			</table>
		</div>
	</div>
</div>


<div class="box box-50">
	<div class="boxin">
		<div class="header">
			<h3>Getting Started</h3>
		</div>
		<div class="content">
			<iframe width="517" height="315" style='margin:10px 10px 10px 10px;' src="http://www.youtube.com/embed/P1oliPSnXSU" frameborder="0" allowfullscreen></iframe>
		</div>
	</div>
</div>

