<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$eid = escape_data($_GET['id']);

if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) { //test for admin

$insert = mysql_query ("INSERT INTO event_info_ext (e_id) VALUES ('$eid')");
$eieid = mysql_insert_id();
	
	echo '<div align="left" id="csi'.$eieid.'" style="padding-bottom: 20px;" onmouseover="$(\'csibtns'.$eieid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'csibtns'.$eieid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" style="padding-left: 2px;">
			<input type="text" name="cst'.$eieid.'" size="14" maxlength="40" autocomplete="off" onfocus="if (trim(this.value) == \'enter name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="enter name">
		</td><td align="left" width="406px">
			<textarea name="cs'.$eieid.'" cols="44" rows="3" onfocus="if (trim(this.value) == \'type whatever you would like\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type whatever you would like\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'csovertxtalrt'.$eieid.'\');" class="inputplaceholder">type whatever you would like</textarea>
			<div id="csovertxtalrt'.$eieid.'" align="left" class="palert"></div>
		</td><td align="right" valign="top" width="110px">
			<div align="right" id="csibtns'.$eieid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
				<div><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/deleteinfosec.php?id='.$eieid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
			</div>
		</td></tr></table>
	</div>';

} else { //if not event admin
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must an admin of this event to edit its info.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>