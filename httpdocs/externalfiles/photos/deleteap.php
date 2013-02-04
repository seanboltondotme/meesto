<?php
include ('../../../externals/header/header-pb.php');

$apid = escape_data($_GET['id']);

if (isset($_GET['rel'])) {
	$rel = escape_data($_GET['rel']);	
} else {
	$rel = '';	
}

$apinfo = mysql_fetch_array(mysql_query ("SELECT pa.pa_id, pa.u_id, ap.url, ap.p_num FROM photo_albums pa INNER JOIN album_photos ap ON ap.pa_id=pa.pa_id AND ap.ap_id='$apid' LIMIT 1"), MYSQL_ASSOC);

if ($apinfo['u_id']==$id) {

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete Album Photo</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete an album photo.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM album_photos WHERE ap_id='$apid'");
	
	if (mysql_affected_rows()>0) {
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
			}
			//reorder pnums
			$paid = $apinfo['pa_id'];
			$spnum = $apinfo['p_num'];
			$photos = mysql_query ("SELECT ap_id FROM album_photos WHERE pa_id='$paid' AND p_num>'$spnum' ORDER BY p_num ASC");
			while ($photo = mysql_fetch_array ($photos, MYSQL_ASSOC)) {
				$roapid = $photo['ap_id'];
				$update = mysql_query ("UPDATE album_photos SET p_num='$spnum' WHERE ap_id='$roapid'");
				$spnum++;
			}
			//delete file
			$oldimg = '../../'.$apinfo['url'];
			unlink($oldimg);
			$oldimglt = '../../'.substr($apinfo['url'], 0, -4).'ltn'.substr($apinfo['url'], -4);
			unlink($oldimglt);
			$oldimgt = '../../'.substr($apinfo['url'], 0, -4).'tn'.substr($apinfo['url'], -4);
			unlink($oldimgt);
			
		echo '<div align="center" class="p18">Your photo was deleted.</div>
		<script type="text/javascript">';
			if ($rel=='editalbum') {
				echo 'setTimeout("parent.$(\'ap'.$apid.'\').destroy();", 0);
				setTimeout("parent.PopBox.close();", 1400);';
			} else {
				echo 'setTimeout("parent.location.href=\''.$baseincpat.'meefile.php?id='.$id.'&t=photos&aid='.$paid.'\';", 1400);';
			}
		echo '</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('photos/deleteap.php', 'deleting a mt cmt', 'unable to delete mtsid='.$apid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/photos/deleteap.php?id='.$apid; if($rel!=''){echo'&rel='.$rel;} echo'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to this photo?</div>
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