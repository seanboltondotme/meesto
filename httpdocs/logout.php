<?php
require_once('../externals/sessions/db_sessions.inc.php');
session_destroy();

$title = 'Logout';
include ('../externals/header/header.php');

$uid = escape_data($_GET['id']);

//main structure
echo '<div align="left" class="p24" style="width: 600px; padding-top: 16px;">Logout</div>
<div align="center" style="padding-top: 16px; padding-bottom: 18px;">You are now logged out. Thank you for using Meesto! Have a great day!</div><div align="center">
	<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" class="paragraph60">
		<form action="'.$baseincpat.'login.php?"><input type="submit" value="login"/></form>
	</td><td align="center" valign="center" style="padding-left: 12px;">
		<a href="meefile.php?id=' . $uid .'">view your meefile</a>
	</td></tr></table>
</div>';

include ('../externals/header/footer.php');
?>
