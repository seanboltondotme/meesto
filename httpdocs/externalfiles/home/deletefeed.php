<?php
include ('../../../externals/header/header-pb.php');

$fid = escape_data($_GET['id']);

if (mysql_result (mysql_query("SELECT COUNT(*) FROM feed WHERE f_id='$fid' AND u_id='$id' LIMIT 1"), 0)>0) { //test for owner

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete Feed Post</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete a feed post.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM feed WHERE f_id='$fid'");
	
	if (mysql_affected_rows()>0) {
		$msgs = mysql_query ("SELECT fc_id FROM feed_cmt WHERE f_id='$fid'");
		while ($msg = mysql_fetch_array ($msgs, MYSQL_ASSOC)) {
			$fcid = $msg['fc_id'];
			$delete = mysql_query("DELETE FROM feed_cmt WHERE fc_id='$fcid'");
			$deletenotifs = mysql_query("DELETE FROM notifications WHERE (type='feedcmt' OR type='feedcmtx') AND xref_id='$fcid'");
			$deletevis= mysql_query("DELETE FROM feed_cmt_vis WHERE fc_id='$fcid'");
		}
		$emos = mysql_query ("SELECT fe_id FROM feed_emo WHERE f_id='$fid'");
		while ($emo = mysql_fetch_array ($emos, MYSQL_ASSOC)) {
			$feid = $emo['fe_id'];
			$delete = mysql_query("DELETE FROM feed_emo WHERE fe_id='$feid'");
			$deletenotifs = mysql_query("DELETE FROM notifications WHERE (type='feedeml' OR type='feedemlx' OR type='feedemd' OR type='feedemdx') AND xref_id='$feid'");
		}
		echo '<div align="center" class="p18">This feed post was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.$(\'fid'.$fid.'p2\').destroy();", 0);
			setTimeout("parent.$(\'fid'.$fid.'\').destroy();", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('home/deletefeed.php', 'deleting a feed', 'unable to delete fid='.$fid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/home/deletefeed.php?id='.$fid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to delete this feed post?</div>
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