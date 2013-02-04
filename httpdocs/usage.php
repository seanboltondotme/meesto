<?php
require_once('../externals/sessions/db_sessions.inc.php');
require_once ('../externals/general/includepaths.php');

//insert test for active session !imporant

$title = 'Knowledge For Usage';
include ('../externals/header/header.php');

//main content
echo '<div align="left" style="width: 900px;">
<div align="center" class="p24" style="font-size: 42px;">Knowledge For Usage</div>';
	include('externalfiles/usage/body.php');
echo '</div>';

include ('../externals/header/footer.php');
?>