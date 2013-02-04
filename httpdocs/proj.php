<?php
require_once('../externals/sessions/db_sessions.inc.php');
require_once ('../externals/general/includepaths.php');
require_once ('../externals/general/functions.php');

$cpid = escape_data($_GET['id']);
$cpinfo = mysql_fetch_array (mysql_query ("SELECT type, stat, timeline, name, about, u_id FROM comm_projs WHERE cp_id='$cpid' LIMIT 1"), MYSQL_ASSOC);

if($cpinfo['type']=='bug'){
	$title = 'Bug: '.$cpinfo['name'];
} else {
	$title = 'Project: '.$cpinfo['name'];
}
include ('../externals/header/header.php');

//main content
echo '<div align="left" valign="top" style="margin-left: 72px; width: 928px; margin-bottom: 6px;"><span style="font-size: 42px;">'.$cpinfo['name'].' </span><span class="subtext">| a Meesto '; if($cpinfo['type']=='bug'){echo'Bug';}else{echo'Community Project';} echo'</span></div>
<div align="left" style="margin-left: 72px; margin-top: 4px; margin-bottom: 16px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">';
		if (mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' LIMIT 1"), 0)>0) { echo'<input type="button" value="unsupport" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/proj/unsupport.php?id='.$cpid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>'; }else{ echo'<input type="button" value="support" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/proj/support.php?id='.$cpid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>'; }
	echo '</td><td align="left" valign="center" style="padding-left: 12px;">
		<input type="button" value="invite" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/proj/invite.php?id='.$cpid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
	</td></tr></table>
</div> 
<div align="left" style="margin-left: 72px; width: 928px;">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
		<div style="border-right: 2px solid #C5C5C5; padding-bottom: 84px;">';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {//test for admin
				echo '<div align="left" style="margin-left: 12px; margin-bottom: 12px;">
					<input type="button" value="edit team" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/proj/editteam.php?id='.$cpid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
				</div>';
			}
			echo '<div align="left" id="teamlistarea" style="width: 184px;">';
				include('externalfiles/proj/grabattendeesidelist.php');
			echo '</div>
		</div>
	</td><td align="left" valign="top" width="716px" style="padding-left: 18px;">
		<div align="left" class="p24"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {echo' onmouseover="$(\'infoeditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'infoeditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';}
			echo '>
			<table cellpadding="0" cellspacing="0" width="690px"><tr><td align="left" valign="top">Information</td><td align="right" valign="bottom">';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {echo'<div id="infoeditbtn" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="edit info" onclick="$(\'infoeditbtn\').set(\'styles\',{\'display\':\'none\'});gotopage(\'infomain\', \''.$baseincpat.'externalfiles/proj/editinfo.php?id='.$cpid.'\');"/></div>';}
			echo '</td></tr></table>
		</div>
		<div align="left" id="infomain" class="paragraph" style="padding-left: 30px; padding-top: 18px;"';
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {echo' onmouseover="$(\'infoeditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'infoeditbtn\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';}
			echo '>';
			include ('externalfiles/proj/grabinfo.php');
		echo '</div>
		<div align="left" style="padding-top: 36px;"><span class="p24">Comments </span><span class="subtext">(This is visible to all Meesto Peeple &mdash; it\'s bascially public.)</span></div>
		<div id="maincontent" style="width: 690px; margin-top: 12px; padding-left: 30px;">';
			include ('externalfiles/proj/grabcmts.php');
		echo '</div>
	</td></tr></table>
</div>';

include ('../externals/header/footer.php');
?>
