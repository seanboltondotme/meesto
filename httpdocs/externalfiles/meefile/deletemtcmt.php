<?php
include ('../../../externals/header/header-pb.php');

$mtcid = escape_data($_GET['id']);

if ((mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_tab_cmts WHERE mtc_id='$mtcid' AND u_id='$id' LIMIT 1"), 0)>0)||(mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_tab_cmts mtc INNER JOIN meefile_tab_sec mts ON mtc.mts_id=mts.mts_id INNER JOIN meefile_tab mt ON mts.mt_id=mt.mt_id WHERE mtc.mtc_id='$mtcid' AND mt.u_id='$id' LIMIT 1"), 0)>0)) { //test for owner

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete Comment</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete a comment.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM meefile_tab_cmts WHERE mtc_id='$mtcid'");
	
	if (mysql_affected_rows()>0) {
		$deletenotifs = mysql_query("DELETE FROM notifications WHERE (type='mtscmt' OR type='mtscmtx') AND xref_id='$mtcid'");
		echo '<div align="center" class="p18">This comment was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.$(\'mtcid'.$mtcid.'\').destroy();", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('meefile/deletemtcmt.php', 'deleting a mt cmt', 'unable to delete fdbkcid='.$mtcid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/meefile/deletemtcmt.php?id='.$mtcid.'" method="post">
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