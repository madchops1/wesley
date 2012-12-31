<?
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

?>
<li><a class="Search <? if($_REQUEST['ADDNEW'] == "" AND $_REQUEST['LAYOUT'] == "" AND $_REQUEST['xid'] == ""){ ?> active <? } ?>" href="?VIEW=<?=$_GET['VIEW']?>">Pages</a></li>
<li><a class="Add <? if($_REQUEST['ADDNEW'] == '1'){ ?> active <? } ?>" href="?VIEW=<?=$_GET['VIEW']?>&ADDNEW=1">New Page</a></li>
<li><a class="Layout <? if($_REQUEST['LAYOUT'] == '1'){ ?> active <? } ?>" href="?VIEW=<?=$_GET['VIEW']?>&LAYOUT=1">Other Content</a></li>

<?
if(checkActiveModule('0000005')){
	?>
	<li><a class="Permissions" href="?VIEW=useraccounts&PERMISSIONS=1">Permissions</a></li>
	<?
}
?>

<li><a class="Info" href="">Help</a></li>