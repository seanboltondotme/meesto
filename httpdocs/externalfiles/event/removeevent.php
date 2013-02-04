<?php
include ('../../../externals/header/header-pb.php');

$eid = escape_data($_GET['id']);

$einfo = mysql_fetch_array (mysql_query ("SELECT name, defaultimg_url FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);

if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' LIMIT 1"), 0)>0) { //test for own

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Remove Event</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to remove "'.$einfo['name'].'" from your calendar.</div>';

if (isset($_POST['remove'])) {
	
	$errors = NULL;
	
	if (isset($_POST['fulldlt']) && ($_POST['fulldlt'] == 'y')) {
		$fulldlt = true;
	} else {
		$fulldlt = false;
	}
	
	if (empty($errors)) {
		if (($fulldlt)&&(mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0)) {
			$delete = mysql_query("DELETE FROM event_owners WHERE e_id='$eid'");
			$delete = mysql_query("DELETE FROM events WHERE e_id='$eid'");
			$delete = mysql_query("DELETE FROM event_info_ext WHERE e_id='$eid'");
			$delete = mysql_query("DELETE FROM requests WHERE type='invtevnt' AND ref_id='$eid'");
			$delete = mysql_query("DELETE FROM notifications WHERE (type='evntcmt' OR type='evntcmtx' OR type='evntmcmt') AND ref_id='$eid'");
			$msgs = mysql_query ("SELECT ect_id FROM eventcmt_threads WHERE e_id='$eid'");
			while ($msg = mysql_fetch_array ($msgs, MYSQL_ASSOC)) {
				$ectid = $msg['ect_id'];
				$delete = mysql_query("DELETE FROM eventcmt_threads WHERE ect_id='$ectid'");
				$delete = mysql_query("DELETE FROM eventcmt_cmts WHERE ect_id='$ectid'");
			}
			$fid = mysql_result(mysql_query ("SELECT f_id FROM feed WHERE u_id='$id' AND type='actvcev' AND ref_id='$eid' LIMIT 1"), 0);
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
				//delete photo;
				if ($einfo['defaultimg_url']!='images/nophoto_e.png') {
					$oldimgp = '../../'.$einfo['defaultimg_url'];
					unlink($oldimgp);
					$oldimg = '../../'.substr($einfo['defaultimg_url'], 0, -5).substr($einfo['defaultimg_url'], -4);
					unlink($oldimg);
					$oldimgt = '../../'.substr($einfo['defaultimg_url'], 0, -5).'tn'.substr($einfo['defaultimg_url'], -4);
					unlink($oldimgt);
				}
				//delete directories via ftp
				require_once ('../../../externals/ftp/ftpconnect.php');
				if (ftp_login($conn_id, $ftp_user, $ftp_pass)) {
					if (ftp_rmdir($conn_id, "$ftp_basedir/events/$eid")) {
					} else {
						reporterror('externalfiles/event/removeevent.php', 'removing event', 'unable to remove directories eid='.$eid.'; failed at mkdir');
					}
				} else {
					reporterror('externalfiles/event/removeevent.php', 'removing event', 'unable to remove directories eid='.$eid.'; failed at login');
				}
				ftp_close($conn_id); //finish and close ftp
			echo '<div align="center" class="p18">This event has been completely deleted.</div>';
		} else {
			$delete = mysql_query("DELETE FROM event_owners WHERE e_id='$eid' AND u_id='$id'");
			echo '<div align="center" class="p18">This event was remove from your calendar.</div>';
		}
		echo '
			<script type="text/javascript">
				setTimeout("parent.location.href=\''.$baseincpat.'cal.php?\';", 1400);
			</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('event/removeevent.php', 'removing event', 'unable to remove eid='.$eid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/event/removeevent.php?id='.$eid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to remove this event from your calendar?</div>';
	//test if admin
	if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {
		echo '<div align="center" style="padding-top: 12px; padding-bottom: 12px;"><table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'fulldlt\').get(\'checked\') == false){$(\'fulldlt\').set(\'checked\',true);}else{$(\'fulldlt\').set(\'checked\',false);}""><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="fulldlt" name="fulldlt" value="y" onclick="if($(\'fulldlt\').get(\'checked\') == false){$(\'fulldlt\').set(\'checked\',true);}else{$(\'fulldlt\').set(\'checked\',false);}"/></td><td align="left" style="padding-left: 4px;">delete this entire event &mdash; all data related to this event will be deleted</td></tr></table></div>';
	}
		echo '<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="remove" name="remove" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 4px;">
				note: you cannot undo this
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
	</form>';
}

} else { //if not own
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must add the event before you can remove it.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>