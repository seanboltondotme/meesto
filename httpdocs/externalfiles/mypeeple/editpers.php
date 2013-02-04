<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$pid = escape_data($_GET['pid']);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$pid' LIMIT 1"), 0)>0) {
	
	if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {
	
	$ifname = 'mtsedit'.$pid;
	$pjs = '<style type="text/css" media="screen">
	.blockbtn-sel {
		padding-top: 2px;
		padding-left: 4px;
		padding-bottom: 2px;
		padding-right: 4px;
		cursor: pointer;
		background-color: #fff;
		border: 1px solid #FFF;
		-moz-border-radius: 6px;
		-webkit-border-radius: 6px;
		-opera-border-radius: 6px;
		-khtml-border-radius: 6px;
		border-radius: 6px;
	}
	
	.blockbtn-sel:hover {
		padding-top: 2px;
		padding-left: 4px;
		padding-bottom: 2px;
		padding-right: 4px;
		cursor: pointer;
		background-color: #F6F6F6;
		border: 1px solid #fff;
		-moz-border-radius: 6px;
		-webkit-border-radius: 6px;
		-opera-border-radius: 6px;
		-khtml-border-radius: 6px;
		border-radius: 6px;
	}
	</style>';
	include ('../../../externals/header/header-iframe.php');
	
		if (isset($_POST['save'])) {
		//save
			
			$errors = NULL;
			
			if (isset($_POST['cst'.$pid]) && ($_POST['cst'.$pid] != 'enter name')) {
				$cst = escape_form_data($_POST['cst'.$pid]);
			} else {
				$cst = '';
			}
			
			if (isset($_POST['cs'.$pid]) && ($_POST['cs'.$pid] != 'type whatever you would like')) {
				$cs = escape_form_data($_POST['cs'.$pid]);
			} else {
				$cs = '';
			}
			
			if (isset($_POST['showts'.$pid])) {
				$showts = 'y';
			} else {
				$showts = '';
			}
			
			if (isset($_POST['allowc'.$pid])) {
				$allowc = 'y';
			} else {
				$allowc = '';
			}
			
			if (empty($errors)) {
				$update = mysql_query("UPDATE meefile_tab_sec SET title='$cst', content='$cs', show_date='$showts', allow_cmts='$allowc' WHERE mts_id='$pid'");
				echo '<script type="text/javascript">
						setTimeout("parent.gotopage(\'mts'.$pid.'\', \''.$baseincpat.'externalfiles/meefile/grabmtsec.php?id='.$id.'&pid='.$pid.'\');", \'0\');
					</script>';
			} else {
				echo '<script type="text/javascript">
						setTimeout("parent.gotopage(\'mts'.$pid.'\', \''.$baseincpat.'externalfiles/meefile/grabmtsec.php?id='.$id.'&pid='.$pid.'\');", \'3200\');
					</script>';
				echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
				reporterror('meefile/editbasic.php', 'editing basic info', $errors);
			}
			
		} else {
			
			$uinfo = mysql_fetch_array (mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$pid' LIMIT 1"), MYSQL_ASSOC);
			
			$plstrm = mysql_query("SELECT stream FROM peep_streams WHERE u_id='$id' AND p_id='$pid'");
			$plstrms = array();
			while ($plstrminfo = mysql_fetch_array ($plstrm, MYSQL_ASSOC)) {
				array_push($plstrms, $plstrminfo['stream']);
			}
			
			echo '<form action="'.$baseincpat.'externalfiles/mypeeple/editpers.php?action=iframe&pid='.$pid.'" method="post">
			
			<div align="left" onmouseover="$(\'editbtns\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'editbtns\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
				<table cellpadding="0" cellspacing="0" width="488px"><tr><td align="left" valign="top" width="50px"><img src="'.$baseincpat.''.$uinfo['defaultimg_url'].'" /></td><td align="left" valign="top" width="320px" style="padding-left: 8px;">
				
					<div align="left">
						<input type="text" name="cst'.$pid.'" size="30" maxlength="210" autocomplete="off" onfocus="if (trim(this.value) == \'enter name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" style="font-size: 18px;" value="'; loadpersonnameclean($pid); echo'" />
					</div>
					
					<div align="left" style="margin-left: 12px; margin-top: 6px;">	
						<div align="left" class="p18">Streams</div>
						<div align="left" style="margin-left: 6px;">
							
							<div align="left" class="blockbtn-sel" style="width: 200px; margin-bottom: 2px;" onclick="if($(\'streamvis[frnd]\').get(\'checked\') == false){$(\'streamvis[frnd]\').set(\'checked\',true);}else{$(\'streamvis[frnd]\').set(\'checked\',false);}">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[frnd]" name="streamvis[frnd]" value="frnd" onclick="if($(\'streamvis[frnd]\').get(\'checked\') == false){$(\'streamvis[frnd]\').set(\'checked\',true);}else{$(\'streamvis[frnd]\').set(\'checked\',false);}"'; if(in_array('frnd', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" valign="center" style="padding-left: 8px; width: 110px;">friends</td></tr></table>
							</div>
							
							<div align="left" class="blockbtn-sel" style="width: 200px; margin-bottom: 2px;" onclick="if($(\'streamvis[fam]\').get(\'checked\') == false){$(\'streamvis[fam]\').set(\'checked\',true);}else{$(\'streamvis[fam]\').set(\'checked\',false);}">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[fam]" name="streamvis[fam]" value="fam" onclick="if($(\'streamvis[fam]\').get(\'checked\') == false){$(\'streamvis[fam]\').set(\'checked\',true);}else{$(\'streamvis[fam]\').set(\'checked\',false);}"'; if(in_array('fam', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" valign="center" style="padding-left: 8px; width: 110px;">family</td></tr></table>
							</div>
							
							<div align="left" class="blockbtn-sel" style="width: 200px; margin-bottom: 2px;" onclick="if($(\'streamvis[prof]\').get(\'checked\') == false){$(\'streamvis[prof]\').set(\'checked\',true);}else{$(\'streamvis[prof]\').set(\'checked\',false);}">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[prof]" name="streamvis[prof]" value="prof" onclick="if($(\'streamvis[prof]\').get(\'checked\') == false){$(\'streamvis[prof]\').set(\'checked\',true);}else{$(\'streamvis[prof]\').set(\'checked\',false);}"'; if(in_array('prof', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" valign="center" style="padding-left: 8px; width: 110px;">professional</td></tr></table>
							</div>
							
							<div align="left" class="blockbtn-sel" style="width: 200px; margin-bottom: 2px;" onclick="if($(\'streamvis[edu]\').get(\'checked\') == false){$(\'streamvis[edu]\').set(\'checked\',true);}else{$(\'streamvis[edu]\').set(\'checked\',false);}">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[edu]" name="streamvis[edu]" value="edu" onclick="if($(\'streamvis[edu]\').get(\'checked\') == false){$(\'streamvis[edu]\').set(\'checked\',true);}else{$(\'streamvis[edu]\').set(\'checked\',false);}"'; if(in_array('edu', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" valign="center" style="padding-left: 8px; width: 110px;">education</td></tr></table>
							</div>
							
							<div align="left" class="blockbtn-sel" style="width: 200px; margin-bottom: 4px;" onclick="if($(\'streamvis[aqu]\').get(\'checked\') == false){$(\'streamvis[aqu]\').set(\'checked\',true);}else{$(\'streamvis[aqu]\').set(\'checked\',false);}">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center"><input type="checkbox" id="streamvis[aqu]" name="streamvis[aqu]" value="aqu" onclick="if($(\'streamvis[aqu]\').get(\'checked\') == false){$(\'streamvis[aqu]\').set(\'checked\',true);}else{$(\'streamvis[aqu]\').set(\'checked\',false);}"'; if(in_array('aqu', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" valign="center" style="padding-left: 8px; width: 110px;">just met mee</td></tr></table>
							</div>
							
						</div>
					</div>
					
					<div align="center" style="padding-top: 8px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left">
							<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
						</td><td align="left">
							<div id="submitbtns" align="left">
							<table cellpadding="0" cellspacing="0"><tr><td align="left">
								<input type="submit" id="submit" value="save" name="save" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'editbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
							</td><td align="left" style="padding-left: 12px;">
								<input type="button" id="cancel" value="cancel" onclick="$(\'submitbtns\').set(\'styles\',{\'display\':\'none\'});$(\'editbtns\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});parent.gotopage(\'mts'.$pid.'\', \''.$baseincpat.'externalfiles/meefile/grabmtsec.php?id='.$id.'&pid='.$pid.'\');"/>
							</td></tr></table>
							</div>
						</td></tr></table>
					</div>
					
				</td><td align="right" valign="top" width="98px" style="padding-left: 12px;">
						<div align="left" id="editbtns" style="visibility: hidden; zoom: 1; opacity: 0;">
							<div><input type="button" align="center" valign="center" value="delete" onclick=""/></div>
						</div>
				</td></tr></table>
			</div>
			
			</form>';
		
		}
			
	include ('../../../externals/header/footer-iframe.php');

	} else {
		echo '<iframe width="100%" height="200px" align="center" id="mtsedit'.$pid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/mypeeple/editpers.php?action=iframe&pid='.$pid.'"></iframe>';
	}

} else { //if not tab owner
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You connect with this person to use this feature.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>