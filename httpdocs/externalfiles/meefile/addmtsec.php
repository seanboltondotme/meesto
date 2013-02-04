<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$mtid = escape_data($_GET['t']);

if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_tab WHERE mt_id='$mtid' AND u_id='$id' LIMIT 1"), 0)>0) { //test for owner

	if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {
	
	$mtsid = escape_data($_GET['mtsid']);
	
	$pjs = '<script type="text/javascript" src="'.$baseincpat.'externalfiles/attach/m-editmtsec.js"></script>';
	$fullmts = true;
	$ifname = 'mtsedit'.$mtsid;
	include ('../../../externals/header/header-iframe.php');
			
			echo '<form action="'.$baseincpat.'externalfiles/meefile/editmtsec.php?action=iframe&t='.$mtid.'&mtsid='.$mtsid.'" method="post">
			
			<div align="left" style="padding-bottom: 64px; padding-left: 2px;" onmouseover="$(\'editbtns\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'editbtns\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="688px">
					<div align="left">
						<input type="text" name="cst'.$mtsid.'" size="64" maxlength="500" autocomplete="off" onfocus="if (trim(this.value) == \'enter name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" style="font-size: 18px;" value="enter name">
					</div><div align="left" style="padding-top: 12px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if(document.getElementById(\'showts'.$mtsid.'\').checked == false){document.getElementById(\'showts'.$mtsid.'\').checked = true;}else{document.getElementById(\'showts'.$mtsid.'\').checked = false;}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="showts'.$mtsid.'" name="showts'.$mtsid.'" value="showts'.$mtsid.'" onclick="if(document.getElementById(\'showts'.$mtsid.'\').checked == false){document.getElementById(\'showts'.$mtsid.'\').checked = true;}else{document.getElementById(\'showts'.$mtsid.'\').checked = false;}" CHECKED/></td><td align="left" style="padding-left: 4px;" class="paragraph60">show timestamp</td></tr></table>
						</td><td align="left" valign="center" style="padding-left: 18px;">
							<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if(document.getElementById(\'allowc'.$mtsid.'\').checked == false){document.getElementById(\'allowc'.$mtsid.'\').checked = true;}else{document.getElementById(\'allowc'.$mtsid.'\').checked = false;}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="allowc'.$mtsid.'" name="allowc'.$mtsid.'" value="allowc'.$mtsid.'" onclick="if(document.getElementById(\'allowc'.$mtsid.'\').checked == false){document.getElementById(\'allowc'.$mtsid.'\').checked = true;}else{document.getElementById(\'allowc'.$mtsid.'\').checked = false;}" CHECKED/></td><td align="left" style="padding-left: 4px;" class="paragraph60">allow comments</td></tr></table>
						</td></tr></table>
					</div><div align="left" style="padding-top: 12px;">
						<textarea name="cs'.$mtsid.'" cols="80" rows="6" onfocus="if (trim(this.value) == \'type whatever you would like\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type whatever you would like\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'20000\', \'csovertxtalrt'.$mtsid.'\');" class="inputplaceholder">type whatever you would like</textarea>
						<div id="csovertxtalrt'.$mtsid.'" align="left" class="palert"></div>
					</div>
					
					<div id="btnattach" align="left" style="margin-top: 8px; margin-bottom: 8px;"><input type="button" align="center" valign="center" value="attach" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
					<div align="left" id="attachments"></div>
					
					<div align="center" style="padding-top: 8px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left">
							<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
						</td><td align="left">
							<div id="btnsbmt" align="left">
							<table cellpadding="0" cellspacing="0"><tr><td align="left">
								<input type="submit" id="submit" value="save" name="save" onclick="$(\'btnsbmt\').set(\'styles\',{\'display\':\'none\'});$(\'editbtns\').set(\'styles\',{\'display\':\'none\'});$(\'btnattach\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
							</td><td align="left" style="padding-left: 12px;">
								<input type="button" id="cancel" value="cancel" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletemts.php?id='.$mtsid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
							</td></tr></table>
							</div>
						</td></tr></table>
					</div>
					
				</td><td align="right" valign="top" width="110px" style="padding-left: 24px;">
						<div align="left" id="editbtns" style="visibility: hidden; zoom: 1; opacity: 0;">
							<div><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmtsecvis.php?id='.$mtsid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
							<div style="padding-top: 12px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletemts.php?id='.$mtsid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
						</div>
				</td></tr></table>
			</div>
			
			<input type="hidden" id="atchmnt_ct" name="atchmnt_ct" value="0"/>
			
		</form>';
			
	include ('../../../externals/header/footer-iframe.php');

	} else {
		//create new
		$insert = mysql_query ("INSERT INTO meefile_tab_sec (mt_id, time_stamp) VALUES ('$mtid', NOW())");
		$mtsid = mysql_insert_id();
		
		//set default vis
		if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_tab_vis WHERE mt_id='$mtid' AND type='pub' LIMIT 1"), 0)>0) {
			$addvis = mysql_query("INSERT INTO meefile_tab_sec_vis (mts_id, type, sub_type, time_stamp) VALUES ('$mtsid', 'pub', 'y', NOW())");
		}
		$plstrm = mysql_query("SELECT sub_type FROM meefile_tab_vis WHERE mt_id='$mtid' AND type='strm'");
		while ($plstrminfo = mysql_fetch_array ($plstrm, MYSQL_ASSOC)) {
			$streamvis = $plstrminfo['sub_type'];
			$addvis = mysql_query("INSERT INTO meefile_tab_sec_vis (mts_id, type, sub_type, time_stamp) VALUES ('$mtsid', 'strm', '$streamvis', NOW())");
		}
		$plchan = mysql_query("SELECT ref_id FROM meefile_tab_vis WHERE mt_id='$mtid' AND type='chan'");
		while ($plchaninfo = mysql_fetch_array ($plchan, MYSQL_ASSOC)) {
			$chanvis = $plchaninfo['ref_id'];
			$addvis = mysql_query("INSERT INTO meefile_tab_sec_vis (mts_id, type, ref_id, time_stamp) VALUES ('$mtsid', 'chan', '$chanvis', NOW())");
		}
		$prsns = mysql_query("SELECT ref_id FROM meefile_tab_vis WHERE mt_id='$mtid' AND type='user'");
		while ($prsn = mysql_fetch_array ($prsns, MYSQL_ASSOC)) {
			$uid = $prsn['ref_id'];
			$addvis = mysql_query("INSERT INTO meefile_tab_sec_vis (mts_id, type, ref_id, time_stamp) VALUES ('$mtsid', 'user', '$uid', NOW())");
		}
		
		// tests for and make activity post
		if (mysql_result(mysql_query ("SELECT COUNT(*) FROM user_activityposts WHERE u_id='$id' AND mtabs='y' LIMIT 1"), 0)>0) {
			$createpost = mysql_query("INSERT INTO feed (u_id, type, ref_id, ref_type, time_stamp) VALUES ('$id', 'actvmt', '$mtsid', 'mts', NOW())");
		}
		
		echo '<div align="left" id="mts'.$mtsid.'">
		<iframe width="100%" height="200px" align="center" id="mtsedit'.$mtsid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/meefile/addmtsec.php?action=iframe&t='.$mtid.'&mtsid='.$mtsid.'"></iframe>
		</div>';
	}

} else { //if not tab owner
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must be the owner of this tab to use this feature.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>