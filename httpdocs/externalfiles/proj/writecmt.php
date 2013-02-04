<?php
require_once ('../../../externals/general/functions.php');

$cpid = escape_data($_GET['id']);

$ifname = 'projcmtwritecmt'.$cpid;
include ('../../../externals/header/header-iframe.php');

if ($id>0) {//test vis

if (isset($_POST['send'])) {
//save
	
	$errors = NULL;
	
	if (isset($_POST['msg']) && ($_POST['msg'] != 'type here to comment on this project...')) {
		$msg = escape_form_data($_POST['msg']);
	} else {
		$errors[] = 'no msg content';
	}
	
	if (empty($errors)) {
		$createthread = mysql_query("INSERT INTO commprojcmt_threads (cp_id, u_id, msg, time_stamp) VALUES ('$cpid', '$id', '$msg', NOW())");
		$cmctid = mysql_insert_id();
		
		//notifications (only to event owners)
			$cpinfo = mysql_fetch_array (mysql_query ("SELECT name, type FROM comm_projs WHERE cp_id='$cpid' LIMIT 1"), MYSQL_ASSOC);
		$msgcmts = mysql_query ("SELECT DISTINCT u_id FROM commproj_mem WHERE cp_id='$cpid' AND type='a' AND u_id!='$id'");
		while ($msgcmt = mysql_fetch_array ($msgcmts, MYSQL_ASSOC)) {
			$mcuid = $msgcmt['u_id'];
			$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, xref_id, time_stamp) VALUES ('$mcuid', 'cprjmcmt', '$id', '$cpid', '$cmctid', NOW())");
			$notifid = mysql_insert_id();
				//check to send email
				if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$mcuid' AND admn_projcmt='y' LIMIT 1"), 0)>0) {
					
						//set correct community task type
						if ($cpinfo['type']=='bug') {
							$cpt_name = 'Meesto Bug';
						} else {
							$cpt_name = 'Meesto Community Project';
						}
					
					//send email
					$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$mcuid' LIMIT 1"), 0);
								
					//params
					$subject = returnpersonnameasid($id, $mcuid).' commented on your '.$cpt_name;
					$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $mcuid).'</a> wrote <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a comment</a> on your '.$cpt_name.' "<a href="'.$baseincpat.'proj.php?id='.$cpid.'">'.$cpinfo['name'].'</a>"';
								
					include('../../../externals/general/emailer.php');
				}
		}
		
		echo '<script type="text/javascript">
				setTimeout("parent.gotopage(\'maincontent\', \''.$baseincpat.'externalfiles/proj/grabcmts.php?id='.$cpid.'\');", \'0\');
			</script>';
	} else {
		echo '<script type="text/javascript">
				setTimeout("parent.gotopage(\'maincontent\', \''.$baseincpat.'externalfiles/proj/grabcmts.php?id='.$cpid.'\');", \'3200\');
			</script>';
		echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('proj/writecmt.php', 'writting msg', $errors);
	}
	
} else {

$myinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);

echo '<form action="'.$baseincpat.'externalfiles/proj/writecmt.php?id='.$cpid.'" method="post">
	
<div align="left" style="padding-bottom: 20px;">

	<div align="left">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px"><img src="'.$baseincpat.''.$myinfo['defaultimg_url'].'" /></td><td align="left" valign="top" width="458px" style="padding-left: 12px;">
			<div align="left">
				<textarea name="msg" cols="50" rows="2" onfocus="if (trim(this.value) == \'type here to comment on this project...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type here to comment on this project...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'cmtovertxtalrt\');" class="inputplaceholder">type here to comment on this project...</textarea>
				<div id="cmtovertxtalrt" align="left" class="palert"></div>
			</div>
		</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">
			<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
			<div id="btngrp" align="left">
				<div id="btnsbmt" style="margin-top: 36px;"><input type="submit" id="submit" value="post" name="send" onclick="$(\'btngrp\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/></div>
			</div>
		</td></tr></table>
	</div>
</div>
</form>';
}

} else { //unable to view
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must login to use this feature.
	</div>';
}

include ('../../../externals/header/footer-iframe.php');
?>