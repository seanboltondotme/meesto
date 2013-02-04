<?php
require_once('../externals/sessions/db_sessions.inc.php');
require_once ('../externals/general/includepaths.php');

if ($_SESSION['user_id'] == NULL) {
	echo '<script type="text/javascript">
		window.location.href = \''.$baseincpat.'login.php?rel=\'+encodeURIComponent(window.location.pathname+window.location.search+window.location.hash);
	</script>
	<div align="left" valign="top" style="padding: 24px;">
		We were unable to redirect you. <form action="'.$baseincpat.'login.php?"><input type="submit" value="click here to login"/></form>
	</div>';
	exit();
}

$title = 'View All Notifications';
$pdrjs = 'backcontrol.initialize(\''.$baseincpat.'externalfiles/notifications/graball.php?\');';
include ('../externals/header/header.php');

//main content
echo '<div align="left" style="width: 900px;">
<div align="left" class="p24" style="margin-bottom: 4px; border-bottom: 1px solid #C5C5C5;">View All Notifications</div>
<div align="left" id="maincontent" style="margin-left: 24px; margin-top: 10px;">';
	include ('externalfiles/notifications/graball.php');
echo '</div>
</div>';

include ('../externals/header/footer.php');
?>