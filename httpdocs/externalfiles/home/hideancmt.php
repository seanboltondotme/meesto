<?php
include ('../../../externals/header/header-pb.php');

$aid= escape_data($_GET['id']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM hide_announcements WHERE ancmt_id='$aid' AND u_id='$id' LIMIT 1"), 0)==0) { //test for owner

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Hide Announcement</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to hide an announcement.</div>';

if (isset($_POST['delete'])) {
	
	$deletevis= mysql_query("INSERT INTO hide_announcements (ancmt_id, u_id, time_stamp) VALUES ('$aid', '$id', NOW())");
	
	if (mysql_affected_rows()>0) {
		echo '<div align="center" class="p18">This announcement has been hidden.</div>
		<script type="text/javascript">
			setTimeout("parent.$(\'ancmtid'.$aid.'\').getParent().destroy();", 0);
			setTimeout("parent.PopBox.close();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('home/hideancmt.php', 'deleting a feed cmt', 'unable to delete fcid='.$aid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/home/hideancmt.php?id='.$aid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to hide this announcement?</div>
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" class="end" value="hide this" name="delete" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 4px;">
				note: you will not see this announcement again
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
	</form>';
}

} else { //if not owner
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You\'ve already hidden this announcement.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>