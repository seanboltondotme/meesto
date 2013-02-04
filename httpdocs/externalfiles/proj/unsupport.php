<?php
include ('../../../externals/header/header-pb.php');

$cpid = escape_data($_GET['id']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' LIMIT 1"), 0)>0) {//test vis

$cpinfo = mysql_fetch_array (mysql_query ("SELECT name FROM comm_projs WHERE cp_id='$cpid' LIMIT 1"), MYSQL_ASSOC);

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Unsupport "'.ucwords($cpinfo['name']).'"</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 26px;">Use this to remove your support of a Meesto Community Project.</div>';

if (isset($_POST['unsupport'])) {
	
	$delete = mysql_query("DELETE FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id'");
	
	if (mysql_affected_rows()>0) {
		echo '<div align="center" class="p18">You are no longer a supporter of this project.</div>
		<script type="text/javascript">
			setTimeout("parent.location.reload();", 1400);
		</script>';
	} else {
		echo '<div align="center" class="p18">An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('proj/unsupport.php', 'unsupporting a project', 'unable to unsupport cpid='.$cpid);
		echo '</div>
		<script type="text/javascript">
			setTimeout("parent.PopBox.close();", 2400); 
		</script>';
	}

} else {
	
	echo '<form action="'.$baseincpat.'externalfiles/proj/unsupport.php?id='.$cpid.'" method="post">
		<div align="left" class="p18" style="padding-left: 16px; padding-bottom: 6px;">Are you sure you want to unsupport this project?</div>
		<div align="center" id="sbmtbtns">
			<div align="center" style="padding-top: 16px;">
				<input type="submit" id="submit" value="unsupport" name="unsupport" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});""/>
			</div><div align="center" class="subtext" style="padding-top: 4px;">
				note: you cannot undo this
			</div>
		</div>
		<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
	</form>';
}

} else { //if not vis
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must be a supporter of the project to unsupport it.
	</div>';
}

include ('../../../externals/header/footer-pb.php');
?>