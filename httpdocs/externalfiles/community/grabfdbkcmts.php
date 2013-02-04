<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses2 = true;
}

if (!isset($fdbkid)) {
	$fdbkid = escape_data($_GET['id']);
}

if ($id>0) {//test vis

$msgcmts = mysql_query ("SELECT m.fdbkc_id, m.u_id, m.msg, u.defaultimg_url, DATE_FORMAT(m.time_stamp, '%b %D, %Y at %l:%i%p') AS time FROM feedback_cmts m INNER JOIN users u ON u.user_id=m.u_id WHERE m.fdbk_id='$fdbkid' ORDER BY m.time_stamp ASC");
while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
	echo '<div align="left" id="fdbkcid'.$msgcmt['fdbkc_id'].'" style="padding-top: 12px;"';
			if ($msgcmt['u_id']==$id) {
				echo ' onmouseover="$(\'btndeletemsg'.$msgcmt['fdbkc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'btndeletemsg'.$msgcmt['fdbkc_id'].'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');"';
			}
			echo '>
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="36px"><img src="'.$baseincpat.''.substr($msgcmt['defaultimg_url'], 0, -4).'m'.substr($msgcmt['defaultimg_url'], -4).'" /></td><td align="left" valign="top" width="315px" style="padding-left: 12px;">
			<div align="left" class="p18">'.nl2br($msgcmt['msg']).'</div>
			<div class="subtext">by '; loadpersonname($msgcmt['u_id']); echo' on '.$msgcmt['time'].'</div>
		</td><td align="left" valign="top" width="96px" style="padding-left: 16px;">';
			if ($msgcmt['u_id']==$id) {
				echo '<div id="btndeletemsg'.$msgcmt['fdbkc_id'].'" style="visibility: hidden; zoom: 1; opacity: 0;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/community/deletefdbkcmt.php?id='.$msgcmt['fdbkc_id'].'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>';
			}
		echo '</td></tr></table>
	</div>';
}

//writer
echo '<div align="left" style="margin-top: 12px; width: 400px; " class="cmtplaceholder" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'cmtwrite'.$fdbkid.'\', \'align\': \'left\'});newElem.inject($(\'fdbkcmts'.$fdbkid.'\'), \'bottom\');gotopage(\'cmtwrite'.$fdbkid.'\', \''.$baseincpat.'externalfiles/community/writefdbkcmt.php?id='.$fdbkid.'\');">
		click here to comment on this.
</div>';

} else { //unable to view
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must login to use this feature.
	</div>';
}

if (isset($minses2)) {
	session_write_close();
	exit();	
}
?>