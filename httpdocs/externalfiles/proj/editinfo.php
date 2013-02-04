<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$cpid = escape_data($_GET['id']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM commproj_mem WHERE cp_id='$cpid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) {//test for admin

if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {

$ifname = 'editprojinfo'.$cpid;
include ('../../../externals/header/header-iframe.php');

$cpinfo = mysql_fetch_array (mysql_query ("SELECT type, timeline, about FROM comm_projs WHERE cp_id='$cpid' LIMIT 1"), MYSQL_ASSOC);

if (isset($_POST['save'])) {
//save
	
	$errors = NULL;
	
	if (isset($_POST['timeline']) && ($_POST['timeline'] != 'what is the estimated timeline for this project?')) {
		$timeline = escape_form_data($_POST['timeline']);
	} else {
		$timeline = '';
	}
	
	if (isset($_POST['about']) && ($_POST['about'] != 'describe what this project is about and why it is relevant (feel free to go beyond just that too)')) {
		$about = escape_form_data($_POST['about']);
	} else {
		$about = '';
	}
			
	if (empty($errors)) {
		$update = mysql_query("UPDATE comm_projs SET timeline='$timeline', about='$about' WHERE cp_id='$cpid'");
		//custom sections
		$customsecs = @mysql_query("SELECT cpie_id FROM commproj_info_ext WHERE cp_id='$cpid'");
			while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
				$cpieid = $customsec['cpie_id'];
				if (isset($_POST['cst'.$cpieid]) && ($_POST['cst'.$cpieid] != 'enter name')) {
					$type = escape_form_data($_POST['cst'.$cpieid]);
				} else {
					$type  = '';
				}
				if (isset($_POST['cs'.$cpieid]) && ($_POST['cs'.$cpieid] != 'type whatever you would like')) {
					$content = escape_form_data($_POST['cs'.$cpieid]);
				} else {
					$content  = '';
				}
				$update = @mysql_query("UPDATE commproj_info_ext SET type='$type', content='$content' WHERE cpie_id='$cpieid'");
			}
		echo '<script type="text/javascript">
				setTimeout("parent.$(\'infoeditbtn\').set(\'styles\',{\'display\':\'block\'});parent.gotopage(\'infomain\', \''.$baseincpat.'externalfiles/proj/grabinfo.php?id='.$cpid.'\');", \'0\');
			</script>';
	} else {
		echo '<script type="text/javascript">
				setTimeout("parent.$(\'infoeditbtn\').set(\'styles\',{\'display\':\'block\'});", \'3200\');
			</script>';
		echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('proj/editinfo.php', 'editing event info', $errors);
	}
	
} else {

echo '<form action="'.$baseincpat.'externalfiles/proj/editinfo.php?action=iframe&id='.$cpid.'" method="post">';
	
if($cpinfo['type']==''){
	echo '<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">timeline</td><td align="left" width="516px">
			<textarea name="timeline" cols="50" rows="1" onfocus="if (trim(this.value) == \'what is the estimated timeline for this project?\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'what is the estimated timeline for this project?\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'600\', \'tlovertxtalrt\');"';
				if ($cpinfo['timeline']!=''){echo'>'.$cpinfo['timeline'];}else{echo' class="inputplaceholder">what is the estimated timeline for this project?';}
			echo '</textarea>
			<div id="tlovertxtalrt" align="left" class="palert"></div>
		</td></tr></table>
	</div>';
}
	
	echo '<div align="left" style="padding-bottom: 20px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" class="subtext">about</td><td align="left" width="516px">
			<textarea name="about" cols="50" rows="7" onfocus="if (trim(this.value) == \'describe what this project is about and why it is relevant (feel free to go beyond just that too)\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'describe what this project is about and why it is relevant (feel free to go beyond just that too)\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'20000\', \'abtovertxtalrt\');"';
				if ($cpinfo['about']!=''){echo'>'.$cpinfo['about'];}else{echo' class="inputplaceholder">describe what this project is about and why it is relevant (feel free to go beyond just that too)';}
			echo '</textarea>
			<div id="abtovertxtalrt" align="left" class="palert"></div>
		</td></tr></table>
	</div>
	
	<div align="left" id="pinfocustsecs">';
	
		//get custom secs
		$customsecs = mysql_query("SELECT cpie_id, type, content FROM commproj_info_ext WHERE cp_id='$cpid' ORDER BY cpie_id ASC");
		while ($customsec = mysql_fetch_array ($customsecs, MYSQL_ASSOC)) {
			$cpieid = $customsec['cpie_id'];
			echo '<div align="left" id="csi'.$cpieid.'" style="padding-bottom: 20px;" onmouseover="$(\'csibtns'.$cpieid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'csibtns'.$cpieid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="160px" style="padding-left: 2px;">
				<input type="text" name="cst'.$cpieid.'" size="14" maxlength="40" autocomplete="off" onfocus="if (trim(this.value) == \'enter name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" ';
					if ($customsec['type']!=''){echo'value="'.$customsec['type'].'"';}else{echo' class="inputplaceholder" value="enter name"';}
					 echo'>
			</td><td align="left" width="406px">
				<textarea name="cs'.$cpieid.'" cols="44" rows="3" onfocus="if (trim(this.value) == \'type whatever you would like\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type whatever you would like\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'20000\', \'csovertxtalrt'.$cpieid.'\');"';
					if ($customsec['content']!=''){echo'>'.$customsec ['content'];}else{echo' class="inputplaceholder">type whatever you would like';}
				echo '</textarea>
				<div id="csovertxtalrt'.$cpieid.'" align="left" class="palert"></div>
			</td><td align="right" valign="top" width="110px">
				<div align="right" id="csibtns'.$cpieid.'" style="visibility: hidden; zoom: 1; opacity: 0;">
					<div><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/proj/deleteinfosec.php?id='.$cpieid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
				</div>
			</td></tr></table>
		</div>';
		}
	
	echo '</div>
	
	<div align="left" style="padding-bottom: 20px;">
		<input type="button" id="addnewcustsec" value="add custom info section" onclick="var newElem = new Element(\'div\', {\'align\': \'left\'});newElem.inject($(\'pinfocustsecs\'), \'bottom\');gotopage(newElem, \''.$baseincpat.'externalfiles/proj/addinfosec.php?id='.$cpid.'\');"/>
	</div>
	
	<div align="center" style="padding-top: 8px;">
		<table cellpadding="0" cellspacing="0"><tr><td align="left">
			<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
		</td><td align="left">
			<div id="submitbtns" align="left">
			<table cellpadding="0" cellspacing="0"><tr><td align="left">
				<input type="submit" id="submit" value="save" name="save" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
			</td><td align="left" style="padding-left: 12px;">
				<input type="button" id="cancel" value="cancel" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});parent.$(\'infoeditbtn\').set(\'styles\',{\'display\':\'block\'});parent.gotopage(\'infomain\', \''.$baseincpat.'externalfiles/proj/grabinfo.php?id='.$cpid.'\');"/>
			</td></tr></table>
			</div>
		</td></tr></table>
	</div>

</form>';
}

include ('../../../externals/header/footer-iframe.php');

} else {
	echo '<iframe width="100%" height="200px" align="center" id="editprojinfo'.$cpid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/proj/editinfo.php?action=iframe&id='.$cpid.'"></iframe>';
}

} else { //if not admin
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must an admin of this project to edit its info.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>