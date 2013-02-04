<?php
include ('../../../externals/header/header-pb.php');

$mcid = escape_data($_GET['id']);

$mcsecinfo = @mysql_fetch_array (@mysql_query ("SELECT u_id, type, sec FROM meefile_contact WHERE mc_id=$mcid LIMIT 1"), MYSQL_ASSOC);

$sec = $mcsecinfo ['sec'];
if ($sec=='im') {
	$cntntnm = 'IM name';
} elseif ($sec=='phone') {
	$cntntnm = 'phone number';
} elseif ($sec=='adrs') {
	$cntntnm = 'address';
} elseif ($sec=='web') {
	$cntntnm = 'website';
} else {
	$cntntnm = 'email';
}

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete "'.ucwords($mcsecinfo['type']).'" '.ucwords($cntntnm).'</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete a contact info section.</div>';

if ($mcsecinfo['u_id']==$id) { //test for owner

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM meefile_contact WHERE mc_id='$mcid'");
	
	if (mysql_affected_rows()>0) {
		$delete = mysql_query("DELETE FROM meefile_contact_vis WHERE mc_id='$mcid'");
		echo '<div align="center" class="p18">This contact info section was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.$(\'editic\').contentWindow.$(\'mc'.$mcid.'\').destroy();", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('meefile/deletecinfo.php', 'deleting an event custom info section', 'unable to delete eieid='.$mcid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/meefile/deletecinfo.php?id='.$mcid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to delete this custom info section?</div>
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="delete" name="delete" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 4px;">
				note: you cannot undo this
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
	</form>';
}

} else { //if not event admin
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You don\'t own this information.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>