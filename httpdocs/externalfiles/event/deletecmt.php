<?php
include ('../../../externals/header/header-pb.php');

$ectid = escape_data($_GET['id']);

if ((mysql_result (mysql_query("SELECT COUNT(*) FROM eventcmt_threads WHERE ect_id='$ectid' AND u_id='$id' LIMIT 1"), 0)>0)||(mysql_result (mysql_query("SELECT COUNT(*) FROM eventcmt_threads ect INNER JOIN event_owners eo ON ect.e_id=eo.e_id AND eo.u_id='$id' AND eo.type='a' WHERE ect.ect_id='$ectid' LIMIT 1"), 0)>0)) { //test for owner

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete Event Comment</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete an event comment.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM eventcmt_threads WHERE ect_id='$ectid'");
	
	if (mysql_affected_rows()>0) {
		$deletenotifs = mysql_query("DELETE FROM notifications WHERE type='evntmcmt' AND xref_id='$ectid'");
		$msgs = mysql_query ("SELECT ecc_id FROM eventcmt_cmts WHERE ect_id='$ectid'");
		while ($msg = mysql_fetch_array ($msgs, MYSQL_ASSOC)) {
			$eccid = $msg['ecc_id'];
			$delete = mysql_query("DELETE FROM eventcmt_cmts WHERE ecc_id='$eccid'");
			$deletenotifs = mysql_query("DELETE FROM notifications WHERE (type='evntcmt' OR type='evntcmtx') AND xref_id='$eccid'");
		}
		echo '<div align="center" class="p18">This comment was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.location.reload();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('event/deletecmt.php', 'deleting a event cmt', 'unable to delete ectid='.$ectid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/event/deletecmt.php?id='.$ectid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to delete this event comment?</div>
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

} else { //if not owner
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must own this information to be able to delete it.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>