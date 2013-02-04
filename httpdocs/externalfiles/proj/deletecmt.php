<?php
include ('../../../externals/header/header-pb.php');

$cmctid = escape_data($_GET['id']);

if ((mysql_result (mysql_query("SELECT COUNT(*) FROM commprojcmt_threads WHERE cmct_id='$cmctid' AND u_id='$id' LIMIT 1"), 0)>0)||(mysql_result (mysql_query("SELECT COUNT(*) FROM commprojcmt_threads cpt INNER JOIN commproj_mem cpm ON cpt.cp_id=cpm.cp_id AND cpm.u_id='$id' AND cpm.type='a' WHERE cpt.cmct_id='$cmctid' LIMIT 1"), 0)>0)) { //test for owner

$cpinfo = mysql_fetch_array (mysql_query ("SELECT cp.type FROM commprojcmt_threads cpt INNER JOIN comm_projs cp ON cpt.cp_id=cp.cp_id WHERE cpt.cmct_id='$cmctid' LIMIT 1"), MYSQL_ASSOC);

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete Community '; if($cpinfo['type']=='bug'){echo'Bug';}else{echo'Project';} echo' Comment</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete an community '; if($cpinfo['type']=='bug'){echo'bug';}else{echo'project';} echo' comment.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM commprojcmt_threads WHERE cmct_id='$cmctid'");
	
	if (mysql_affected_rows()>0) {
		$deletenotifs = mysql_query("DELETE FROM notifications WHERE type='cprjmcmt' AND xref_id='$cmctid'");
		$msgs = mysql_query ("SELECT cmcc_id FROM commprojcmt_cmts WHERE cmct_id='$cmctid'");
		while ($msg = mysql_fetch_array ($msgs, MYSQL_ASSOC)) {
			$cmccid = $msg['cmcc_id'];
			$delete = mysql_query("DELETE FROM commprojcmt_cmts WHERE cmcc_id='$cmccid'");
			$deletenotifs = mysql_query("DELETE FROM notifications WHERE (type='cprjcmt' OR type='cprjcmtx') AND xref_id='$cmccid'");
		}
		echo '<div align="center" class="p18">This comment was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.location.reload();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('proj/deletecmt.php', 'deleting a event cmt', 'unable to delete fdbkcid='.$cmctid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400);
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/proj/deletecmt.php?id='.$cmctid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to delete this community '; if($cpinfo['type']=='bug'){echo'bug';}else{echo'project';} echo' comment?</div>
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