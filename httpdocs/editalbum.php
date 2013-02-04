<?php
require_once('../externals/sessions/db_sessions.inc.php');
require_once ('../externals/general/includepaths.php');
require_once ('../externals/general/functions.php');

if ($_SESSION['user_id'] == NULL) {
	echo '<script type="text/javascript">
		window.location.href = \''.$baseincpat.'login.php?rel=\'+encodeURIComponent(window.location.pathname+window.location.search+window.location.hash);
	</script>
	<div align="left" valign="top" style="padding: 24px;">
		We were unable to redirect you. <form action="'.$baseincpat.'login.php?"><input type="submit" value="click here to login"/></form>
	</div>';
	exit();
}

$paid = escape_data($_GET['id']);
$id = $_SESSION['user_id'];

if (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_albums WHERE pa_id='$paid' AND u_id='$id' LIMIT 1"), 0)>0) {//test for admin
	$painfo = mysql_fetch_array (mysql_query ("SELECT name FROM photo_albums WHERE pa_id='$paid' LIMIT 1"), MYSQL_ASSOC);
	$paname = $painfo['name'];
	$title = 'Edit Album: "'.$paname.'"';
} else {
	$title = 'Edit Album: Not Visible';
}

if (isset($_GET['action'])) {
	$t = escape_data($_GET['action']);	
} else {
	$t = '';	
}

if ($t=='editp') {
	
	$pjs = '<script src="'.$baseincpat.'externalfiles/photos/tagger_edtalbmp.js" type="text/javascript" charset="utf-8"></script>';
	$pdrjs = 'tagger.initialize();';

} elseif (($t=='add')&&(!isset($_GET['method']))) {
	
$pjs = '<link href="'.$baseincpat.'externalfiles/swfupload/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="'.$baseincpat.'externalfiles/swfupload/swfupload.js"></script>
<script type="text/javascript" src="'.$baseincpat.'externalfiles/swfupload/handler_albmupldr.js"></script>';
$pdrjs = 'var swfu;
			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "externalfiles/photos/uploadhandler.php",
				post_params: {"paid": "'.$paid.'"},

				// File Upload Settings
				file_size_limit : "10 MB",
				file_types : "*.jpg;*.png",
				file_types_description : "JPG Images; PNG Image",

				// Event Handler Settings - these functions as defined in Handlers.js
				//  The handlers are not part of SWFUpload but are part of my website and control how
				//  my website reacts to the SWFUpload events.
				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : function(){window.location.replace(\''.$baseincpat.'editalbum.php?id='.$paid.'&action=add&method=flbk\');},
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_placeholder_id : "spanButtonPlaceholder",
				button_width: 120,
				button_height: 24,
				button_text : \'<span class="button">Select Photos</span>\',
				button_text_style : \'.button { font-family: Arial, Helvetica, sans-serif; font-size: 16pt; }\',
				button_text_top_padding: 2,
				button_text_left_padding: 10,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "externalfiles/swfupload/swfupload.swf",
				flash9_url : "externalfiles/swfupload/swfupload_fp9.swf",

				custom_settings : {
					upload_target : "divFileProgressContainer",
					thumbnail_height: 520,
					thumbnail_width: 660,
					thumbnail_quality: 80
				},
				
				// Debug Settings
				debug: false
			});';

} else {
	
//get current date
date_default_timezone_set('America/Los_Angeles');
$cyear=date("Y");
$cmonth=date("m");
$cday=date("d");

$pjs = '<link rel="stylesheet" href="'.$baseincpat.'externalfiles/cal/datepicker.css" type="text/css" media="screen" charset="utf-8" />
	<script src="'.$baseincpat.'externalfiles/cal/datepicker.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css" media="screen">
		.textboxlist-loading { background: url(\''.$baseincpat.'images/spinner.gif\') no-repeat 556px center; }
		.form_tags .textboxlist, #form_hiddenpeople .textboxlist { width: 580px; }
	</style>';
$pdrjs = 'new DatePicker(\'.start_date\', {
		format: \'m-d-Y\',
		inputOutputFormat: \'m-d-Y\',
		yearPicker: false,
		startDay: 0,
		pickerClass: \'datepicker_dashboard\'
	});';
	
}
include ('../externals/header/header.php');

echo '<div align="left" style="margin-left: 68px;">
	<div align="left" class="p24">Edit Photo Album "<span style="color: #36F;">'.$paname.'</span>"</div>
		
	<div align="left" valign="bottom" class="p24" style="border-bottom: 1px solid #C5C5C5; margin-left: 18px; padding-left: 8px; height: 22px;">
		<ul class="mftabul">
			<li class="mftabli'; if(!isset($t)||($t=='')){echo'On';} echo'" style="padding-right: 38px;"><div class="mftabliico"></div><a href="'.$baseincpat.'editalbum.php?id='.$paid.'">edit info</a></li>
			<li class="mftabli'; if($t=='editp'){echo'On';} echo'" style="padding-right: 38px;"><div class="mftabliico"></div><a href="'.$baseincpat.'editalbum.php?id='.$paid.'&action=editp">edit photos</a></li>
			<li class="mftabli'; if($t=='add'){echo'On';} echo'" style="padding-right: 38px;"><div class="mftabliico"></div><a href="'.$baseincpat.'editalbum.php?id='.$paid.'&action=add">add photos</a></li>
		</ul>
	</div>
</div>';

echo '<div align="left" style="padding-top: 24px; margin-left: 68px;">';
	
	if ($t=='add') {
		include ('externalfiles/photos/editalbum-add.php');
	} elseif ($t=='editp') {
		include ('externalfiles/photos/editalbum-editp.php');
	} else {
		include ('externalfiles/photos/editalbum-editi.php');
	}

echo '</div>';

include ('../externals/header/footer.php');
?>