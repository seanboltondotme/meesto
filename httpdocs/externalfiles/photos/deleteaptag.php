<?php
include ('../../../externals/header/header-pb.php');

$aptid = escape_data($_GET['id']);
$aptinfo = mysql_fetch_array(mysql_query("SELECT ap.u_id, ap.ap_id, ap.pa_id FROM album_photos ap INNER JOIN ap_tags apt ON apt.apt_id='$aptid' AND apt.ap_id=ap.ap_id LIMIT 1"), MYSQL_ASSOC);
$uid = $aptinfo['u_id'];
$apid = $aptinfo['ap_id'];
$paid = $aptinfo['pa_id'];

if (($uid==$id)||(mysql_result (mysql_query("SELECT COUNT(*) FROM ap_tags WHERE apt_id='$aptid' AND u_id='$id' LIMIT 1"), 0)>0)) { //test for owner

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete Photo Tag</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete a photo tag.</div>';

if (isset($_POST['delete'])) {
	$taguid = mysql_result(mysql_query("SELECT u_id FROM ap_tags WHERE apt_id='$aptid' LIMIT 1"), 0);
	$delete = mysql_query("DELETE FROM ap_tags WHERE apt_id='$aptid'");
	
	if (mysql_affected_rows()>0) {
		$deletenotifs = mysql_query("DELETE FROM notifications WHERE type='apt' AND xref_id='$aptid'");
		$fid = mysql_result(mysql_query ("SELECT f_id FROM feed WHERE u_id='$id' AND type='actvapt' AND ref_id='$paid' LIMIT 1"), 0);
		if (mysql_result(mysql_query ("SELECT ref_type FROM feed WHERE f_id='$fid'"), 0)>1) {
			$createpost = mysql_query("UPDATE feed SET ref_type=ref_type-1 WHERE f_id='$fid'");
		} else {
			$delete = mysql_query("DELETE FROM feed WHERE f_id='$fid'");
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
		}
		echo '<div align="center" class="p18">This photo tag was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.$(\''.$apid.'apt'.$taguid.'\').getParent().destroy();", 0);
			setTimeout("parent.gotopage(\'ap'.$apid.'_taglist\', \''.$baseincpat.'externalfiles/photos/grabtags.php?apid='.$apid.'\');", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('photos/deleteaptag.php', 'deleting a ap cmt', 'unable to delete apcid='.$aptid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/photos/deleteaptag.php?id='.$aptid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to delete this photo tag?</div>
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