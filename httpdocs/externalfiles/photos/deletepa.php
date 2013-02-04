<?php
include ('../../../externals/header/header-pb.php');

$paid = escape_data($_GET['id']);

$apinfo = mysql_fetch_array(mysql_query ("SELECT u_id, name FROM photo_albums WHERE pa_id='$paid' LIMIT 1"), MYSQL_ASSOC);

if ($apinfo['u_id']==$id) {

echo '<div align="left" class="p24" style="width: 600px; border-bottom: 1px solid #C5C5C5;">Delete Photo Album "'.ucwords($apinfo['name']).'"</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete a photo album.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM photo_albums WHERE pa_id='$paid'");
	
	if (mysql_affected_rows()>0) {
		$deletevis = mysql_query("DELETE FROM photo_album_vis WHERE pa_id='$paid'");
		$deleteties = mysql_query("DELETE FROM patoe_ties WHERE pa_id='$paid'");
		$fid = mysql_result(mysql_query ("SELECT f_id FROM feed WHERE u_id='$id' AND type='actvap' AND ref_id='$paid' LIMIT 1"), 0);
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
		$photos = mysql_query ("SELECT ap_id, url FROM album_photos WHERE pa_id='$paid'");
		while ($photo = mysql_fetch_array ($photos, MYSQL_ASSOC)) {
			$apid = $photo['ap_id'];
			$delete = mysql_query("DELETE FROM album_photos WHERE ap_id='$apid'");
			$deletevis = mysql_query("DELETE FROM album_photos_vis WHERE ap_id='$apid'");
			$deletecmts = mysql_query("DELETE FROM ap_cmts WHERE ap_id='$apid'");
			$deletenotifs = mysql_query("DELETE FROM notifications WHERE (type='apcmt' OR type='apcmtx') AND ref_id='$apid'");
			//delete tags
			$tags = mysql_query ("SELECT apt_id, u_id FROM ap_tags WHERE ap_id='$apid'");
			while ($tag = mysql_fetch_array ($tags, MYSQL_ASSOC)) {
				$taguid = $tag['u_id'];
				$aptid = $tag['apt_id'];
				$delete = mysql_query("DELETE FROM ap_tags WHERE apt_id='$aptid'");
				$deletenotifs = mysql_query("DELETE FROM notifications WHERE type='apt' AND xref_id='$aptid'");
					$fid = mysql_result(mysql_query ("SELECT f_id FROM feed WHERE u_id='$id' AND type='actvapt' AND ref_id='$apid' LIMIT 1"), 0);
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
			}
			//delete file
			$oldimg = '../../'.$photo['url'];
			unlink($oldimg);
			$oldimglt = '../../'.substr($photo['url'], 0, -4).'ltn'.substr($photo['url'], -4);
			unlink($oldimglt);
			$oldimgt = '../../'.substr($photo['url'], 0, -4).'tn'.substr($photo['url'], -4);
			unlink($oldimgt);
		}
		
				//delete directories via ftp
				require_once ('../../../externals/ftp/ftpconnect.php');
				if (ftp_login($conn_id, $ftp_user, $ftp_pass)) {
					if (ftp_rmdir($conn_id, "$ftp_basedir/users/$id/photos/$paid")) {
					} else {
						reporterror('externalfiles/event/removeevent.php', 'removing album', 'unable to remove directories paid='.$paid.'; failed at mkdir');
					}
				} else {
					reporterror('externalfiles/event/removeevent.php', 'removing album', 'unable to remove directories paid='.$paid.'; failed at login');
				}
				ftp_close($conn_id); //finish and close ftp
			
		echo '<div align="center" class="p18">Your photo album was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.location.href=\''.$baseincpat.'meefile.php?id='.$id.'&t=photos\';", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('photos/deletepa.php', 'deleting a mt cmt', 'unable to delete paid='.$paid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/photos/deletepa.php?id='.$paid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to this photo album?</div>
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="delete" name="delete" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 4px;">
				note: this will delete all photos in this album and you cannot undo this
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