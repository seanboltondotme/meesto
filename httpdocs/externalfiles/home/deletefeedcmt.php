<?php
include ('../../../externals/header/header-pb.php');

$fcid= escape_data($_GET['id']);

if ((mysql_result (mysql_query("SELECT COUNT(*) FROM feed_cmt WHERE fc_id='$fcid' AND u_id='$id' LIMIT 1"), 0)>0)||(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_cmt fc INNER JOIN feed f ON f.f_id=fc.f_id AND f.u_id='$id' WHERE fc.fc_id='$fcid' LIMIT 1"), 0)>0)) { //test for owner

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete Comment</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete a comment.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM feed_cmt WHERE fc_id='$fcid'");
	
	if (mysql_affected_rows()>0) {
		$deletenotifs = mysql_query("DELETE FROM notifications WHERE (type='feedcmt' OR type='feedcmtx') AND xref_id='$fcid'");
		$deletevis= mysql_query("DELETE FROM feed_cmt_vis WHERE fc_id='$fcid'");
		echo '<div align="center" class="p18">This comment was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.$(\'fcmtcid'.$fcid.'\').destroy();", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('home/deletefeedcmt.php', 'deleting a feed cmt', 'unable to delete fcid='.$fcid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/home/deletefeedcmt.php?id='.$fcid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to delete this comment?</div>
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