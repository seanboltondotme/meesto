<?php
include ('../../../externals/header/header-pb.php');

$mpcid = escape_data($_GET['id']);
$chaninfo = mysql_fetch_array (mysql_query ("SELECT u_id, name, description FROM my_peeple_channels WHERE mpc_id='$mpcid' LIMIT 1"), MYSQL_ASSOC);

if ($chaninfo['u_id']==$id) {

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete "'.ucwords($chaninfo['name']).'" Channel</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete a Channel.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM my_peeple_channels WHERE mpc_id='$mpcid'");
	
	if (mysql_affected_rows()>0) {
		$delete = mysql_query("DELETE FROM mpc_mems WHERE mpc_id='$mpcid'");
		//delete from vis databases
		$delete = mysql_query("DELETE FROM album_photos_vis WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM defvis_apt WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM defvis_feed WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM feed_cmt_vis WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM feed_vis WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM mc_vis WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM meefile_contact_vis WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM meefile_infosec_vis WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM meefile_pers_ext_vis WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM meefile_sec_vis WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM meefile_tab_sec_vis WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM meefile_tab_vis WHERE type='chan' AND ref_id='$mpcid'");
		$delete = mysql_query("DELETE FROM photo_album_vis WHERE type='chan' AND ref_id='$mpcid'");
		
		echo '<div align="center" class="p18">Your "'.$chaninfo['name'].'" Channel was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.location.replace(\''.$baseincpat.'mypeeple.php\');", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('mypeeple/deletechannel.php', 'deleting a mt cmt', 'unable to delete mpcid='.$mpcid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/mypeeple/deletechannel.php?id='.$mpcid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to delete your "'.$chaninfo['name'].'" Channel?</div>
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="delete" name="delete" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 4px;">
				note: this will delete all information related to this Channel and you cannot undo this
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