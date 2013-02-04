<?php
require_once('../externals/sessions/db_sessions.inc.php');
$title = 'Verification';
include ('../externals/header/header.php');

//main content
echo '<div align="left" style="width: 900px;">
<div align="left" class="p24" style="margin-bottom: 4px; border-bottom: 1px solid #C5C5C5;">Meesto Verification</div>';

//main structure
echo '<div align="center" style="margin-top: 18px;">';

$type = strip_tags(escape_data($_GET['type']));
$aid = strip_tags(escape_data($_GET['aid']));
$a = strip_tags(escape_data($_GET['a']));

if ($type=='usr') {
	if (mysql_num_rows(mysql_query("SELECT user_id FROM users WHERE user_id='$aid' AND active='yes' LIMIT 1"))>0) {
		echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your email has already been activated.</td></tr></table>';
	} else {
		$activate = mysql_query("UPDATE users SET active='yes' WHERE user_id='$aid' AND active='$a'");
		if (mysql_affected_rows()>0) {
				if (mysql_num_rows(mysql_query("SELECT user_id FROM users WHERE user_id=$aid AND active_date IS NULL LIMIT 1"))>0) {
					$active = mysql_query("UPDATE users SET active_date=NOW() WHERE user_id=$aid");
				}
			echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your email has now been activated &mdash; thanks! :)</td></tr></table>';
		} else {
			echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">An error occurred: we were unable to process this activation.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
		reporterror('verif.php', 'verifiying user email', 'not able to activate user email uid='.$aid);
		echo '</td></tr></table>';	
		}
	}
		
} else {
	echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">An error occurred: we were unable to process this verification.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
	reporterror('verif.php', 'verifiying something', 'no type set aid='.$aid.' a='.$a);
	echo '</td></tr></table>';
}

//main structure
echo '</div>
</div>';

include ('../externals/header/footer.php');
?>
