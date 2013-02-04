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
echo'<script src="'.$baseincpat.'externalfiles/m-pb.js" type="text/javascript" charset="utf-8"></script>
'; if(isset($pjs)){echo $pjs;} echo'
<script type="text/javascript">
window.addEvent(\'domready\', function() {
	parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'none\'});
	resize_pb();
	resizepb = setInterval("resize_pb();", 100);
	'; if(isset($pdrjs)){echo $pdrjs;} echo'
});
</script>
</head>';

//nlr - no login required

	$id = $_SESSION['user_id'];
	
	//main cont structure
	echo '<body class="body">
	<div id="pbcontent" class="container" align="left" valign="top">';
?>