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

$results = mysql_query ("SELECT ht_id, msg FROM help_threads WHERE msg LIKE '%$q%' LIMIT $s, 24");

$f_ct = 0;
while ($result = mysql_fetch_array ($results, MYSQL_ASSOC)) {
	$hid = $result['ht_id'];
	echo '<a href="'.$baseincpat.'help.php?htid='.$hid.'"><div align="left" class="p18" style="margin-top: 8px; padding-bottom: 4px; margin-bottom: 12px; border-bottom: 1px solid #C5C5C5;">
		<div align="left">'; if(strlen($result['msg'])>63){echo substr($result['msg'], 0, 63).'...'; }else{ echo $result['msg']; } echo'</div>
	</div></a>';
	$f_ct++;
}

if ($f_ct>0) {
echo '<div align="left">
	<div align="center" class="p18" style="padding-top: 8px; padding-bottom: 4px; border-bottom: 2px solid #C5C5C5; cursor: pointer;" onclick="gotopage(this.getParent(), \''.$baseincpat.'externalfiles/help/grab.php?q='.$q.'&s='.($s+24).'\');">show more</div>
</div>';
}

if ($f_ct==0) {
	echo '<div align="left">No '; if($s>0){echo'more ';} echo'matches were found for "'.$q.'"</div>';
}

if ($s==0) {
echo '<div align="center" style="margin-top: 24px;">
		<input type="button" value="ask a new question :)" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/help/ask.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/>
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>