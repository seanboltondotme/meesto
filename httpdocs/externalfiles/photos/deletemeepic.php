<?php
include ('../../../externals/header/header-pb.php');

$uiid = escape_data($_GET['id']);

$uiinfo = mysql_fetch_array(mysql_query ("SELECT u_id, img_url FROM user_imgs WHERE ui_id='$uiid' LIMIT 1"), MYSQL_ASSOC);

if ($uiinfo ['u_id']==$id) {

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Delete MeePic</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to delete a MeePic.</div>';

if (isset($_POST['delete'])) {
	
	$delete = mysql_query("DELETE FROM user_imgs WHERE ui_id='$uiid'");
	
	if (mysql_affected_rows()>0) {
			$deletecmts = mysql_query("DELETE FROM meepic_cmts WHERE ui_id='$uiid'");
			$deletenotifs = mysql_query("DELETE FROM notifications WHERE (type='uicmt' OR type='uicmtx') AND ref_id='$uiid'");
			//delete file
			$oldimg = '../../'.substr($uiinfo['img_url'], 0, -4).'l'.substr($uiinfo['img_url'], -4);
			unlink($oldimg);
			$oldimgt = '../../'.substr($uiinfo['img_url'], 0, -4).'tn'.substr($uiinfo['img_url'], -4);
			unlink($oldimgt);
			
		echo '<div align="center" class="p18">Your MeePic was deleted.</div>
		<script type="text/javascript">
			setTimeout("parent.location.href=\''.$baseincpat.'meefile.php?id='.$id.'&t=photos&view=meepics\';", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('photos/deletemeepic.php', 'deleting a mt cmt', 'unable to delete mtsid='.$uiid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/photos/deletemeepic.php?id='.$uiid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to this MeePic?</div>
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