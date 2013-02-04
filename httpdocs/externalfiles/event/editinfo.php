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

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {

$ifname = 'editeinfo'.$eid;
include ('../../../externals/header/header-iframe.php');

$einfo = mysql_fetch_array (mysql_query ("SELECT location, about, ntk, wtb FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);

if (isset($_POST['save'])) {
//save
	
	$errors = NULL;
	
	if (isset($_POST['location']) && ($_POST['location'] != 'where will it take place? (where is this event located?)')) {
		$location = escape_form_data($_POST['location']);
	} else {
		$location = '';
	}
	
	if (isset($_POST['about']) && ($_POST['about'] != 'what is this event?')) {
		$about = escape_form_data($_POST['about']);
	} else {
		$about = '';
	}
	
	if (isset($_POST['ntk']) && ($_POST['ntk'] != 'what do peeple attending this event need to know?')) {
		$ntk = escape_form_data($_POST['ntk']);
	} else {
		$ntk = '';
	}
	
	if (isset($_POST['wtb']) && ($_POST['wtb'] != 'what should peeple attending this event bring?')) {
		$wtb = escape_form_data($_POST['wtb']);
	} else {
		$wtb = '';
	}
			
	if (empty($errors)) {
		$update = mysql_query("UPDATE events SET location='$location', about='$about', ntk='$ntk', wtb='$wtb' WHERE e_id='$eid'");
		//custom sections
			$customsecs = @mysql_query("SELECT eie_id FROM event_info_ext WHERE e_id='$eid'");
			while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
				$eieid = $customsec['eie_id'];
				if (isset($_POST['cst'.$eieid]) && ($_POST['cst'.$eieid] != 'enter name')) {
					$type = escape_form_data($_POST['cst'.$eieid]);
				} else {
					$type  = '';
				}
				if (isset($_POST['cs'.$eieid]) && ($_POST['cs'.$eieid] != 'type whatever you would like')) {
					$content = escape_form_data($_POST['cs'.$eieid]);
				} else {
					$content  = '';
				}
				$update = @mysql_query("UPDATE event_info_ext SET type='$type', content='$content' WHERE eie_id='$eieid'");
			}
		echo '<script type="text/javascript">
				setTimeout("parent.$(\'infoeditbtn\').set(\'styles\',{\'display\':\'block\'});parent.gotopage(\'infomain\', \''.$baseincpat.'externalfiles/event/grabinfo.php?id='.$eid.'\');", \'0\');
			</script>';
	} else {
		echo '<script type="text/javascript">
				setTimeout("parent.$(\'infoeditbtn\').set(\'styles\',{\'display\':\'block\'});", \'3200\');
			</script>';
		echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('event/editinfo.php', 'editing event info', $errors);
	}
	
} else {

echo '<form action="'.$baseincpat.'externalfiles/event/editinfo.php?action=iframe&id='.$eid.'" method="post">
	
	<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">where</td><td align="left" width="516px">
			<textarea name="location" cols="50" rows="2" onfocus="if (trim(this.value) == \'where will it take place? (where is this event located?)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'where will it take place? (where is this event located?)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'600\', \'locovertxtalrt\');"';
				if ($einfo['location']!=''){echo'>'.$einfo ['location'];}else{echo' class="inputplaceholder">where will it take place? (where is this event located?)';}
			echo '</textarea>
			<div id="locovertxtalrt" align="left" class="palert"></div>
		</td></tr></table>
	</div>
	
	<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">about</td><td align="left" width="516px">
			<textarea name="about" cols="50" rows="5" onfocus="if (trim(this.value) == \'what is this event?\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'what is this event?\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'abtovertxtalrt\');"';
				if ($einfo['about']!=''){echo'>'.$einfo ['about'];}else{echo' class="inputplaceholder">what is this event?';}
			echo '</textarea>
			<div id="abtovertxtalrt" align="left" class="palert"></div>
		</td></tr></table>
	</div>
	
	<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">need to know</td><td align="left" width="516px">
			<textarea name="ntk" cols="50" rows="3" onfocus="if (trim(this.value) == \'what do peeple attending this event need to know?\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'what do peeple attending this event need to know?\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'abtovertxtalrt\');"';
				if ($einfo['ntk']!=''){echo'>'.$einfo ['ntk'];}else{echo' class="inputplaceholder">what do peeple attending this event need to know?';}
			echo '</textarea>
			<div id="abtovertxtalrt" align="left" class="palert"></div>
		</td></tr></table>
	</div>
	
	<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">what to bring</td><td align="left" width="516px">
			<textarea name="wtb" cols="50" rows="3" onfocus="if (trim(this.value) == \'what should peeple attending this event bring?\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'what should peeple attending this event bring?\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'abtovertxtalrt\');"';
				if ($einfo['wtb']!=''){echo'>'.$einfo ['wtb'];}else{echo' class="inputplaceholder">what should peeple attending this event bring?';}
			echo '</textarea>
			<div id="abtovertxtalrt" align="left" class="palert"></div>
		</td></tr></table>
	</div>
	
	
	<div align="left" id="pinfocustsecs">';
	
		//get custom secs
		$customsecs = mysql_query("SELECT eie_id, type, content FROM event_info_ext WHERE e_id='$eid' ORDER BY eie_id ASC");
		while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
			$eieid = $customsec['eie_id'];
			echo '<div align="left" id="csi'.$eieid.'" style="padding-bottom: 20px;" onmouseover="$(\'csibtns'.$eieid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'csibtns'.$eieid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" style="padding-left: 2px;">
				<input type="text" name="cst'.$eieid.'" size="14" maxlength="40" autocomplete="off" onfocus="if (trim(this.value) == \'enter name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($customsec['type']!=''){echo'value="'.$customsec['type'].'"';}else{echo' class="inputplaceholder" value="enter name"';}
					 echo'>
			</td><td align="left" width="406px">
				<textarea name="cs'.$eieid.'" cols="44" rows="3" onfocus="if (trim(this.value) == \'type whatever you would like\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type whatever you would like\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'csovertxtalrt'.$eieid.'\');"';
					if ($customsec['content']!=''){echo'>'.$customsec ['content'];}else{echo' class="inputplaceholder">type whatever you would like';}
				echo '</textarea>
				<div id="csovertxtalrt'.$eieid.'" align="left" class="palert"></div>
			</td><td align="right" valign="top" width="110px">
				<div align="right" id="csibtns'.$eieid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
					<div><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/event/deleteinfosec.php?id='.$eieid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
				</div>
			</td></tr></table>
		</div>';
		}
	
	echo '</div>
	
	<div align="left" style="padding-bottom: 20px;">
		<input type="button" id="addnewcustsec" value="add custom info section" onclick="var newElem = new Element(\'div\', {\'align\': \'left\'});newElem.inject($(\'pinfocustsecs\'), \'bottom\');gotopage(newElem, \''.$baseincpat.'externalfiles/event/addinfosec.php?id='.$eid.'\');"/>
	</div>
	
	<div align="center" style="padding-top: 8px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left">
			<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		</td><td align="left">
			<div id="submitbtns" align="left">
			<table cellpadding="0" cellspacing="0"><tr><td align="left">
				<input type="submit" id="submit" value="save" name="save" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
			</td><td align="left" style="padding-left: 12px;">
				<input type="button" id="cancel" value="cancel" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});parent.$(\'infoeditbtn\').set(\'styles\',{\'display\':\'block\'});parent.gotopage(\'infomain\', \''.$baseincpat.'externalfiles/event/grabinfo.php?id='.$eid.'\');"/>
			</td></tr></table>
			</div>
		</td></tr></table>
	</div>

</form>';
}

include ('../../../externals/header/footer-iframe.php');

} else {
	echo '<iframe width="100%" height="200px" align="center" id="editeinfo'.$eid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/event/editinfo.php?action=iframe&id='.$eid.'"></iframe>';
}

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