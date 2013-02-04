<?php
require_once('../../../externals/sessions/db_sessions.inc.php');
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');

echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="'.$baseincpat.'externalfiles/m-iframe.css" type="text/css" media="screen" charset="utf-8"/>';
if(isset($fullmts)){
	echo'<script src="'.$baseincpat.'externalfiles/mts.js" type="text/javascript" charset="utf-8"></script>';
} else {
	echo'<script src="'.$baseincpat.'externalfiles/mts-iframe.js" type="text/javascript" charset="utf-8"></script>';
}
echo'<script src="'.$baseincpat.'externalfiles/m-iframe.js" type="text/javascript" charset="utf-8"></script>
'; if(isset($pjs)){echo $pjs;} echo'
<script type="text/javascript">
window.addEvent(\'domready\', function() {
	resize_iframe(\''.$ifname.'\');
	setInterval("resize_iframe(\''.$ifname.'\');", 200);
	'; if(isset($pdrjs)){echo $pdrjs;} echo'
});		
</script>
</head>';

if (isset($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
	
	//main cont structure
	echo '<body class="body">
	<div id="'.$ifname.'content" class="container" align="left" valign="top">';

} else {
	$id = 0;
	
	//main cont structure
	echo '<body class="body">
	<div class="container" align="left" valign="top" style="padding: 24px;">
		You must be logged in to use this feature.
	</div>
	</body>
	</html>';
	
	session_write_close();
	exit();
}
?>