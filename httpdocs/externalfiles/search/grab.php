<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (isset($_GET['s'])&&is_numeric($_GET['s'])) {
	$s = escape_data($_GET['s']);
} else {
	$s = 0;	
}

if (!isset($q)) {
	$q = escape_data($_GET['q']);
}

$results = mysql_query ("SELECT user_id, first_name, defaultimg_url FROM users WHERE first_name LIKE '$q' OR last_name LIKE '$q' OR CONCAT_WS(' ',first_name, last_name) LIKE '$q' OR middle_name LIKE '$q' OR CONCAT_WS(' ',first_name, middle_name, last_name) LIKE '$q' OR full_name LIKE '$q' LIMIT $s, 24");

$f_ct = 0;
while ($result = mysql_fetch_array ($results, MYSQL_ASSOC)) {
	$uid = $result['user_id'];
	if ($fst_fid==0) {
		$fst_fid = $fid;
	}
	echo '<div align="left" style="margin-bottom: 18px;" onmouseover="$(\'useraddbtnarea'.$uid.'\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'useraddbtnarea'.$uid.'\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px" style="padding-top: 2px;"><a href="'.$baseincpat.'meefile.php?id='.$uid.'"><img src="'.$baseincpat.''.$result['defaultimg_url'].'" /></a></td><td align="left" valign="top" width="698px" style="padding-left: 12px;">
			<table cellpadding="0" cellspacing="0" width="698px"><tr><td align="left" valign="top" class="p18" style="padding-top: 2px;">'; loadpersonname($uid); echo '</td><td align="right" valign="top" style="padding-left: 12px;">
					<div id="useraddbtnarea'.$uid.'" align="right" style="visibility: hidden; zoom: 1; opacity: 0;">';
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM requests WHERE type='peepcnct' AND ((u_id='$id' AND s_id='$uid') OR (u_id='$uid' AND s_id='$id')) LIMIT 1"), 0)>0) {
							echo '<div align="right">pending connection</div>';
						} elseif (($id!=$uid)&&(mysql_result (mysql_query ("SELECT COUNT(*) FROM my_peeple WHERE u_id='$id' AND p_id='$uid' LIMIT 1"), 0)==0)) {
							echo '<input type="button" value="connect with '.$result['first_name'].'" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/user/add.php?id='.$uid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>';
						}
					echo '</div>
				</td></tr></table>
			</td></tr></table>
	</div>';
	$f_ct++;
}

if ($f_ct>0) {
echo '<div align="left">
	<div align="center" class="p18" style="padding-top: 8px; padding-bottom: 4px; border-bottom: 2px solid #C5C5C5; cursor: pointer;" onclick="gotopage(this.getParent(), \''.$baseincpat.'externalfiles/search/grab.php?q='.$q.'&s='.($s+24).'\');">show more</div>
</div>';
}

if ($f_ct==0) {
	echo '<div align="left">No '; if($s>0){echo'more ';} echo'matches were found.</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>