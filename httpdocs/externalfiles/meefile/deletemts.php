<?php
include ('../../../externals/header/header-pb.php');

$mtsid = escape_data($_GET['id']);

if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_tab_sec mts INNER JOIN meefile_tab mt ON mts.mt_id=mt.mt_id WHERE mts.mts_id='$mtsid' AND mt.u_id='$id' LIMIT 1"), 0)>0) { //test for owner

$mtsinfo = mysql_fetch_array (mysql_query ("SELECT title FROM meefile_tab_sec WHERE mts_id='$mtsid' LIMIT 1"), MYSQL_ASSOC);

if ($mtsinfo['title']!='') {
	$mtstitle = '"'.$mtsinfo['title'].'"';	
} else {
	$mtstitle = 'Meefile Tab Section';
}

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete '.ucwords($mtstitle).'</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete a Meefile tab section.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM meefile_tab_sec WHERE mts_id='$mtsid'");
	
	if (mysql_affected_rows()>0) {
			$deletevis = mysql_query("DELETE FROM meefile_tab_sec_vis WHERE mts_id='$mtsid'");
			$deletecmts = mysql_query("DELETE FROM meefile_tab_cmts WHERE mts_id='$mtsid'");
			$deletenotifs = mysql_query("DELETE FROM notifications WHERE (type='mtscmt' OR type='mtscmtx') AND ref_id='$mtsid'");
			$fid = mysql_result(mysql_query ("SELECT f_id FROM feed WHERE u_id='$id' AND type='actvmt' AND ref_type='mts' AND ref_id='$mtsid' LIMIT 1"), 0);
			if ($fid>0) {
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
		echo '<div align="center" class="p18">'.$mtstitle.' was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.$(\'mts'.$mtsid.'\').destroy();", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('meefile/deletemts.php', 'deleting a mt cmt', 'unable to delete mtsid='.$mtsid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/meefile/deletemts.php?id='.$mtsid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to delete '.$mtstitle.'?</div>
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