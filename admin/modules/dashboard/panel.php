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
<!-- Main content � all content is within boxes. Feel free to add your boxes (see the examples on the bottom of this document) and your content within -->
<div class="box box-50 altbox"><!-- .altbox for alternative box's color -->
	<div class="boxin">
		<div class="header">
			<h3>Articles</h3>
			<a class="button" href="#">write new&nbsp;�</a><!-- Action button in the header of the box -->

			<ul><!-- Tabs in the box's header -->
				<li><a href="#" class="active">published</a></li><!-- .active for active tab -->
				<li><a href="#">comments</a></li>
				<li><a href="#">drafts</a></li>
			</ul>
		</div>
		<div class="content">

			<table cellspacing="0">
				<thead>
					<tr>
						<th>Article</th>
						<td class="tc">Comments</td>
						<td class="tc">Pub. date</td>
						<td></td>

					</tr>
				</thead>
				<tbody>
					<tr class="first"><!-- .first for first row of the table (only if there is thead) -->
						<th><a href="#">Simple dropdown menu using jQuery</a></th>
						<td class="tc"><a class="ico-comms" href="#">1</a></td><!-- a.ico-comms for comment-like backgrounds -->
						<td>10/24/2009</td>

						<td><a href="#">edit</a></td>
					</tr>
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
				</tbody>
			</table>
		</div>
	</div>
</div>


<div class="box box-50"><!-- box 50% width -->
	<div class="boxin">
		<div class="header">
			<h3>Pages</h3>
			<a class="button" href="#">create&nbsp;�</a>
			<ul>
				<li><a href="#" class="active">published</a></li>

				<li><a href="#">drafts</a></li>
			</ul>
		</div>
		<div class="content">
			<ul class="simple"><!-- ul.simple for simple listings of pages, categories, etc. -->
				<li>
					<strong><a href="#">Simple dropdown menu using jQuery</a></strong>
					<span><a href="#">edit</a></span>

				</li>
				<li>
					<strong><a href="#">Simple dropdown menu using jQuery</a></strong>
					<span><a href="#">edit</a></span>
				</li>
				<li>
					<strong><a href="#">Simple dropdown menu using jQuery</a></strong>

					<span><a href="#">edit</a></span>
				</li>
				<li>
					<strong><a href="#">Simple dropdown menu using jQuery</a></strong>
					<span><a href="#">edit</a></span>
				</li>
				<li>

					<strong><a href="#">Simple dropdown menu using jQuery</a></strong>
					<span><a href="#">edit</a></span>
				</li>
				<li>
					<strong><a href="#">Simple dropdown menu using jQuery</a></strong>
					<span><a href="#">edit</a></span>
				</li>

				<li>
					<strong><a href="#">Simple dropdown menu using jQuery</a></strong>
					<span><a href="#">edit</a></span>
				</li>
			</ul>
		</div>
	</div>
</div>


<div id="box1" class="box box-100"><!-- box full-width -->
	<div class="boxin">
		<div class="header">
			<h3>Tabular data (eg. files) � TABS ARE WORKING</h3>
			<a class="button" href="#">upload file&nbsp;�</a>
			<ul>
				<li><a rel="box1-tabular" href="#" class="active">list view</a></li><!-- insert ID of content related to this tab into the rel attribute of this tab -->

				<li><a rel="box1-grid" href="#">grid view</a></li><!-- insert ID of content related to this tab into the rel attribute of this tab -->
			</ul>
		</div>
		<div id="box1-tabular" class="content"><!-- content box 1 for tab switching -->
			<form class="plain" action="" method="post" enctype="multipart/form-data">
				<fieldset>
					<table cellspacing="0">
						<thead><!-- universal table heading -->

							<tr>
								<td class="tc"><input type="checkbox" id="data-1-check-all" name="data-1-check-all" value="true" /></td>
								<td class="tc">Type</td>
								<th>File</th>
								<td>Description</td>
								<td class="tc">Pub. date</td>
								<td class="tc">Actions</td>

							</tr>
						</thead>
						<tfoot><!-- table foot - what to do with selected items -->
							<tr>
								<td colspan="6"><!-- do not forget to set appropriate colspan if you will edit this table -->
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
						<tbody>

							<tr class="first"><!-- .first for first row of the table (only if there is thead) -->
								<td class="tc"><input type="checkbox" id="data-1-check-1" name="data-1-check-1" value="true" /></td>
								<td class="tc"><span class="tag tag-gray">jpeg</span></td>
								<th><a href="#">On vacation with my 13.3� honey</a></th>
								<td>Lovely picture of me and my MacBook during sunset �</td>
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
								<th><a href="#">On vacation with my 13.3� honey</a></th>
								<td>Lovely picture of me and my MacBook during sunset �</td>
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

								<th><a href="#">On vacation with my 13.3� honey</a></th>
								<td>Lovely picture of me and my MacBook during sunset �</td>
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
								<th><a href="#">On vacation with my 13.3� honey</a></th>
								<td>Lovely picture of me and my MacBook during sunset �</td>

								<td class="tc">715&nbsp;KB</td>
								<td class="tc">
									<ul class="actions">
										<li><a class="ico" href="#" title="edit"><img src="images/led-ico/pencil.png" alt="edit" /></a></li>
										<li><a class="ico" href="#" title="delete"><img src="images/led-ico/delete.png" alt="delete" /></a></li>
									</ul>
								</td>
							</tr>

						</tbody>
					</table>
				</fieldset>
			</form>
			<div class="pagination"><!-- pagination underneath the box's content -->
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
		</div><!-- .content#box-1-holder -->
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
										<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3� honey</a></h4>
										<p>Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset �</p>
										<p>Lovely picture of me and my MacBook during sunset �</p>

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
										<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3� honey</a></h4>
										<p>Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset</p>

										<p>Lovely picture of me and my MacBook during sunset �</p>
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
										<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3� honey</a></h4>

										<p>Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset �</p>
										<p>Lovely picture of me and my MacBook during sunset �</p>
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
										<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3� honey</a></h4>

										<p>Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset �</p>
										<p>Lovely picture of me and my MacBook during sunset �</p>
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
	</div>
</div>

<div class="box box-100">

	<div class="boxin">
		<div class="header">
			<h3>Grid data (eg. files)</h3>
			<a class="button" href="#">upload file&nbsp;�</a>
			<ul>
				<li><a href="#">list view</a></li>
				<li><a href="#" class="active">grid view</a></li>

			</ul>
		</div>
		<div class="content">
			<form class="plain" action="" method="post" enctype="multipart/form-data">
				<fieldset>
					<div class="grid"><!-- grid view -->
						<div class="line">
							<div class="item">
								<div class="inner">

									<a class="thumb" href="#"><img src="_tmp/grid-img.jpg" alt="" /></a>
									<div class="data">
										<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3� honey</a></h4>
										<p>Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset �</p>
										<p>Lovely picture of me and my MacBook during sunset �</p>
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
										<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3� honey</a></h4>
										<p>Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset</p>
										<p>Lovely picture of me and my MacBook during sunset �</p>
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
										<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3� honey</a></h4>
										<p>Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset �</p>
										<p>Lovely picture of me and my MacBook during sunset �</p>

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
										<h4><span class="tag tag-gray">jpeg</span> <a href="#">On vacation with my 13.3� honey</a></h4>
										<p>Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset � Lovely picture of me and my MacBook during sunset �</p>

										<p>Lovely picture of me and my MacBook during sunset �</p>
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
		</div>
	</div>
</div>

<div class="box box-50">
	<div class="boxin">
		<div class="header">
			<h3>Forms, basic layout</h3>

		</div>
		<form class="basic" action="" method="post" enctype="multipart/form-data"><!-- Default basic forms -->
			<div class="inner-form">
				<!-- error and information messages -->
				<div class="msg msg-ok"><p>I'm a <strong>success</strong> message and I'm proud of it!</p></div>
				<div class="msg msg-error"><p>I'm an <strong>error</strong> message and I'm proud of it!</p></div>

				<div class="msg msg-warn"><p>I'm a <strong>warning</strong> message and I'm proud of it!</p></div>
				<div class="msg msg-info"><p>I'm an <strong>info</strong> message and I'm proud of it!</p></div>
					<dl>
						<dt><label for="some1">Input field:</label></dt>

						<dd>
							<input class="txt" type="text" id="some1" name="some1" />
							<small>A little description.</small>
						</dd>
					
						<dt><label class="error" for="some3">Input field:</label></dt>
						<dd>
							<input class="txt error" type="text" id="some3" name="some3" value="error" /><!-- class error for wrong filled inputs -->
							<small>An error description.</small>

						</dd>
					
						<dt class="ttop"><label for="some2">Textarea:</label></dt><!-- .ttop for vertical-align: top -->
						<dd><textarea name="some2" id="some2" cols="20" rows="5"></textarea></dd>
					
						<dt><label for="some4">File input:</label></dt>
						<dd>
							<input class="file" type="file" id="some4" name="some4" />
							<span class="loading">uploading�</span><!-- OR <img src="images/upload.gif" alt="" /> --><!-- uploading animation -->

						</dd>
					
						<dt><label for="some10">Select box:</label></dt>
						<dd>
							<select id="some10" name="some10">
								<option value="val1">select �</option>
								<option value="val1">� something</option>
							</select>

						</dd>
					
						<dt></dt>
						<dd>
							<label class="check"><input class="check" type="checkbox" id="some5" name="some5" value="true" checked="checked" />Checkbox 1</label>
							<label class="check"><input class="check" type="checkbox" id="some6" name="some6" value="true" />Checkbox 2</label>
						</dd>
					
						<dt></dt>
						<dd>

							<label class="radio"><input class="radio" type="radio" id="some7" name="some78" value="true" />Radio 1</label>
							<label class="radio"><input class="radio" type="radio" id="some8" name="some78" value="true" />Radio 2</label>
						</dd>
					
						<dt></dt>
						<dd>
							<input class="button" type="submit" value="Submit emphasized" />
							<input class="button altbutton" type="submit" value="Submit" />
						</dd>

					</dl>
			</div>
		</form>
	</div>
</div>

<div class="box box-50 altbox">
	<div class="boxin">
		<div class="header">
			<h3>Forms with fieldsets, plain layout</h3>

		</div>
		<form class="fields" action="" method="post" enctype="multipart/form-data"><!-- Forms (plain layout, cleaner) -->
			<fieldset>
				<legend><strong>Legend</strong></legend>
				<div class="msg msg-ok">
					<p>I'm a <strong>success</strong> message and I'm proud of it!</p>

				</div>
				<label for="some21">Input field:</label>
				<input class="txt" type="text" id="some21" name="some21" size="30" />
				<small>A little description.</small>
					
				<label class="error" for="some23">Input field:</label>
				<input class="txt error" type="text" id="some23" name="some23" size="30" value="error" />
				<small>An error description.</small>

				
				<label for="some22">Textarea:</label>
				<textarea name="some22" id="some22" cols="40" rows="5"></textarea>
				
				<label for="some24">File input:</label>
				<input class="file" type="file" id="some24" name="some24" /> <span class="loading">uploading�</span><!-- OR <img src="images/upload.gif" alt="" /> -->
					
				<label for="some210">Select box:</label>
				<select id="some210" name="some210">

					<option value="val1">select �</option>
					<option value="val1">� something</option>
				</select>
				
				<div class="sep">
					<label class="check"><input class="check" type="checkbox" id="some25" name="some25" value="true" checked="checked" />Checkbox 1</label>
					<label class="check"><input class="check" type="checkbox" id="some26" name="some26" value="true" />Checkbox 2</label>
				</div>

				
				<div class="sep">
					<label class="radio"><input class="radio" type="radio" id="some27" name="some278" value="true" />Radio 1</label>
					<label class="radio"><input class="radio" type="radio" id="some28" name="some278" value="true" />Radio 2</label>
				</div>									
				
				<div class="sep">
					<input class="button" type="submit" value="Submit" />
					<input class="button altbutton" type="submit" value="Submit emphasized" />
				</div>

			</fieldset>
		</form>
	</div>
</div>

<div class="box box-100">
	<div class="boxin">
		<div class="header">
			<h3>Calendar with events</h3>

		</div>
		<div class="content">
			<table class="calendar" cellspacing="0">
				<thead>
					<tr>
						<th class="tc month" colspan="7">
							<a href="#" title="Go to October 2009"><img src="images/cal-left.png" alt="Go to October 2009" /></a>
							December 2009
							<a href="#" title="Go to January 2010"><img src="images/cal-right.png" alt="Go to January 2010" /></a>

						</th>
					</tr>
					<tr>
						<th class="tc">Monday</th>
						<th class="tc">Tuesday</th>
						<th class="tc">Wednesday</th>
						<th class="tc">Thursday</th>

						<th class="tc">Friday</th>
						<th class="tc">Saturday</th>
						<th class="tc">Sunday</th>
					</tr>
				</thead>
				<tbody>
					<tr class="first"><!-- .first for first row of the table (only if there is thead) -->

						<td class="inactive"><strong>28</strong></td><!-- inactive days (month past or month after) -->
						<td class="inactive"><strong>29</strong></td>
						<td class="inactive">
							<strong>30</strong>
							<div class="items"><!-- spots indicating due items or something like that -->
								<a href="#" title="Your due item"><img src="images/cal-due.png" alt="Your due item" /></a>
								<a href="#" title="Your due item"><img src="images/cal-due.png" alt="Your due item" /></a>

								<a href="#" title="Your due item"><img src="images/cal-due.png" alt="Your due item" /></a>
							</div>
						</td>
						<td><strong>1</strong></td>
						<td>
							<strong>2</strong>
							<div class="items"><!-- spots indicating items to do or something -->
								<a href="#" title="Your item"><img src="images/cal-item.png" alt="Your item" /></a>

								<a href="#" title="Your item"><img src="images/cal-item.png" alt="Your item" /></a>
							</div>
						</td>
						<td><strong>3</strong></td>
						<td>
							<strong>4</strong>
							<div class="items">
								<a href="#" title="Your item"><img src="images/cal-item.png" alt="Your item" /></a>

								<a href="#" title="Your item"><img src="images/cal-item.png" alt="Your item" /></a>
								<a href="#" title="Your item"><img src="images/cal-item.png" alt="Your item" /></a>
							</div>
						</td>
					</tr>
					<tr>
						<td><strong>5</strong></td>
						<td>

							<strong>6</strong>
							<div class="items">
								<a href="#" title="Your item"><img src="images/cal-item.png" alt="Your item" /></a>
								<a href="#" title="Your item"><img src="images/cal-item.png" alt="Your item" /></a>
							</div>
						</td>
						<td><strong>7</strong></td>
						<td><strong>8</strong></td>

						<td><strong>9</strong></td>
						<td><strong>10</strong></td>
						<td><strong>11</strong></td>
					</tr>
					<tr>
						<td><strong>12</strong></td>
						<td><strong>13</strong></td>

						<td><strong>14</strong></td>
						<td><strong>15</strong></td>
						<td><strong>16</strong></td>
						<td><strong>17</strong></td>
						<td><strong>18</strong></td>
					</tr>

					<tr>
						<td><strong>19</strong></td>
						<td><strong>20</strong></td>
						<td><strong>21</strong></td>
						<td><strong>22</strong></td>
						<td><strong>23</strong></td>

						<td><strong>24</strong></td>
						<td><strong>25</strong></td>
					</tr>
					<tr>
						<td><strong>26</strong></td>
						<td><strong>27</strong></td>
						<td><strong>28</strong></td>

						<td><strong>29</strong></td>
						<td><strong>30</strong></td>
						<td><strong>31</strong></td>
						<td class="inactive"><strong>1</strong></td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>
</div>

<!-- Examples of box sizes and placing of boxes
<div class="box box-100">
	<div class="boxin">
		<div class="header">
			<h3>Title of box</h3>
			<a class="button" href="#">Call to action</a>
			<ul>
				<li><a href="#" class="active">list view</a></li>
				<li><a href="#">grid view</a></li>
			</ul>
		</div>
		<div class="content">
			Content of the box
		</div>
	</div>
</div>

<div class="box box-75">
	<div class="boxin">
		box sample
	</div>
</div>

<div class="box box-25">
	<div class="boxin">
		box sample
	</div>
</div>

<div class="box box-50">
	<div class="boxin">
		box sample
	</div>
</div>

<div class="box box-25">
	<div class="boxin">
		box sample
	</div>
</div>

<div class="box box-25">
	<div class="boxin">
		box sample
	</div>
</div>

<div class="box box-25">
	<div class="boxin">
		box sample
	</div>
</div>

<div class="box box-25">
	<div class="boxin">
		box sample
	</div>
</div>

<div class="box box-25">
	<div class="boxin">
		box sample
	</div>
</div>

<div class="box box-25">
	<div class="boxin">
		box sample
	</div>
</div>
-->