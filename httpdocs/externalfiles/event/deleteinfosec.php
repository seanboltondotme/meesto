<?php
include ('../../../externals/header/header-pb.php');

$eieid = escape_data($_GET['id']);

$eieinfo = mysql_fetch_array (mysql_query ("SELECT e_id FROM event_info_ext WHERE eie_id='$eieid' LIMIT 1"), MYSQL_ASSOC);

$eid = $eieinfo['e_id'];

if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) { //test for admin

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete Custom Info Section</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete a custom info section.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM event_info_ext WHERE eie_id='$eieid'");
	
	if (mysql_affected_rows()>0) {
		echo '<div align="center" class="p18">This custom info section was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.$(\'editeinfo'.$eid.'\').contentWindow.$(\'csi'.$eieid.'\').destroy();", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('event/deleteinfosec.php', 'deleting an event custom info section', 'unable to delete eieid='.$eieid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/event/deleteinfosec.php?id='.$eieid.'" method="post">
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
		You must an admin of this event to edit its info.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>