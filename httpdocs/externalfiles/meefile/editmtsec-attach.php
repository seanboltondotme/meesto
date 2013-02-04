<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$mtsid = escape_data($_GET['mtsid']);

if (isset($_GET['choose'])) {
$choose = escape_data($_GET['choose']);

	if ($id>0) {}else{
		session_write_close();
		exit();		
	}

	if ($choose=='photos') {
		
		if ($_GET['grab']=='aps') {
			
			//load album photo selector
			$paid = escape_data($_GET['paid']);
			//test owner
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos WHERE pa_id='$paid' AND u_id='$id' LIMIT 1"), 0)>0) {
				echo '<table cellpadding="0" cellspacing="0">';
					$photos = @mysql_query ("SELECT ap_id, url FROM album_photos WHERE pa_id='$paid' ORDER BY p_num ASC");
					while ($photo = @mysql_fetch_array ($photos, MYSQL_ASSOC)) {
						if ($i==0) {
							echo '<tr><td align="center" width="90px" id="ap'.$photo['ap_id'].'" style="padding-top: 16px;">';
						} else {
							echo '<td align="center" width="90px" id="ap'.$photo['ap_id'].'" style="padding-left: 18px; padding-top: 16px;">';	
						}
						echo '<img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="shadowbox" onclick="parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});parent.$(\'mtsedit'.$mtsid.'\').contentWindow.attachments.ap(\''.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'\', \''.$photo['ap_id'].'\');" style="cursor: pointer;"/></td>';
						
						if ($i==4) {
							echo '</tr>';
							$i=-1;
						}
						$i++;
					}
					if ($i<5) {
						while ($i<5) {
						 echo'<td width="90px"></td>';
						 $i++;
						}
						echo '</tr>';
					}
					//if no records
					if (@mysql_num_rows($photos) == 0) {
						echo '<tr><td align="left">you have not added any photos</td></tr>';
					}
				echo '</table>';
			} else {
				//report error
				echo '<table cellpadding="0" cellspacing="0" width="500px"><tr><td align="left" class="paragraph60">An error occurred: you don\'t own this photo album. Sorry for the inconvenience, please try again.</td></tr><tr><td align="left">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
				reporterror('profile/meefile/editmtsec-attach.php', 'viewing album photos', 'don\'t own photo photo album or may not exists paid='.$paid);
				echo '</td></tr></table>';
			}
			
		} else {
			//load album selector
			echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">My Photos |</td><td align="left" valign="center"
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'\');">back to main</div>
				</td></tr></table>
			</div>
			<div id="pacatalog" style="overflow-y: scroll; overflow-x: hidden; height: 180px; width: 560px;">
				<table cellpadding="0" cellspacing="0">';
					$albums = mysql_query ("SELECT pa_id, name, cover_url, description, DATE_FORMAT(date, '%b %D, %Y') AS time FROM photo_albums WHERE u_id='$id' ORDER BY pa_id DESC");
					$i = 0;
					while ($album = @mysql_fetch_array ($albums, MYSQL_ASSOC)) {
						$paid = $album['pa_id'];
						$pcount = mysql_fetch_array(@mysql_query ("SELECT COUNT(*) FROM album_photos WHERE pa_id='$paid'"), MYSQL_NUM);
						echo '<tr><td align="left" style="border-bottom: 1px solid #ECECEC; padding-top: 12px; padding-bottom: 6px; cursor: pointer;" onclick="gotopage(\'pacatalog\', \''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'&choose=photos&grab=aps&paid='.$paid; echo'\', \'182\');">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="90px"><img src="'.$baseincpat.$album['cover_url'].'" /></td><td align="left" valign="top" width="462px" style="padding-left: 8px; padding-top: 1px;">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p18">'.$album['name'].' <span class="subtext">('.$pcount[0].' Photo'; if($pcount[0]>1){echo's';} echo')</span></td></tr><tr><td align="left"><span class="subtext">'.$album['time'].'</span></td></tr><tr><td align="left" style="padding-left: 12px; padding-top: 6px;"><span class="subtext" style="font-size: 14px;">'; if(strlen($album['description'])>=40){echo substr($album['description'], 0, 40).'...';}else{echo $album['description'];} echo'</span></td></tr></table>
						</td></tr></table>		
					</td></tr>';
					$i++;				
					}
					//if no records
					if ($i==0) {
						echo '<tr><td align="left">you have not added any albums</td></tr>';
					}
				echo '</table></div>';
		}
		
	} elseif ($choose=='upload') {
			
			//load uploader
			echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">Upload |</td><td align="left" valign="center"
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'\');">back to main</div>
				</td></tr></table>
			</div>
			<div align="left" style="width: 640px; margin-top: 6px;">An error occurred with our normal uploader. Sorry about that. <span style="font-size: 14px;">';
			reporterror('meefile/editmtsec-attach.php', 'loading swfuploader', 'unable to load, displayed error msg');
			echo '</span></div><div align="left" style="width: 600px; margin-top: 12px; margin-bottom: 12px;"><span style="font-size: 14px;">Please <a href="http://www.mozilla.com/plugincheck/" target = "_new">check to make sure you have the most recent version of flash installed</a> and try again.</span><br /><span style="font-size: 14px;">Unfortunately, we do not have fallback option for this.</div>';
	
	} elseif ($choose=='link') {
		
		echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">New Link |</td><td align="left" valign="center"
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'\');">back to main</div>
				</td></tr></table>
				<div align="left">You can attach a link to a website, photo, or video.</div>
			</div>
			<div align="center" style="margin-top: 32px; margin-bottom: 32px;">
					<div align="center"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center" style="font-size: 13px; ">
						<div align="left" id="url_cont">
							<input type="text" id="url" name="url" size="50" maxlength="1000" autocomplete="off" onfocus="if (trim(this.value) == \'copy and paste a link to a website, photo, or video here...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'copy and paste a link to a website, photo, or video here...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" class="inputplaceholder" value="copy and paste a link to a website, photo, or video here..."/>
						</div>
					</td><td align="left" valign="top"  style="padding-left: 4px;">
						<div align="center" id="sbmtbtns">
							<div align="center">
								<input type="button" id="submit" value="attach" name="attach" onclick="$(\'sbmtbtns\').set(\'styles\',{\'display\':\'none\'});$(\'url_cont\').fade(\'hide\');parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});parent.$(\'mtsedit'.$mtsid.'\').contentWindow.getlinkdata.get($(\'url\').value, \'mtsedit'.$mtsid.'\');""/>
							</div>
						</div>
					</td></tr></table></div>
					<div align="center" id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">getting link information...<br /><span class="subtext" style="font-size: 14px;">this might be a minute or so</span></td></tr></table></div>
			</div>';
		
} elseif ($choose=='prev') {
		
		if (isset($_GET['method'])&&($_GET['method']!='')) {
			$method = escape_data($_GET['method']);
		} else {
			$method = '';
		}
		
		if ($method=='upld') {
				echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">Previous Uploads |</td><td align="left" valign="center"
					<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'\');">back to main</div>
					</td></tr></table>
				</div>
				<div id="pacatalog" style="overflow-y: scroll; overflow-x: hidden; height: 180px; width: 560px;">
					<table cellpadding="0" cellspacing="0">';
						$atchmnts = mysql_query ("SELECT ua_id, url, DATE_FORMAT(time_stamp, '%b %D, %Y') AS time FROM user_attachments WHERE u_id='$id' AND type='photo' ORDER BY ua_id DESC");
						$i = 0;
						while ($atchmnt = @mysql_fetch_array ($atchmnts, MYSQL_ASSOC)) {
							$uaid = $atchmnt['ua_id'];
							echo '<tr><td align="left" style="border-bottom: 1px solid #ECECEC; padding-top: 12px; padding-bottom: 6px; cursor: pointer;" onclick="parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});parent.$(\'mtsedit'.$mtsid.'\').contentWindow.attachments.upload(\''.$baseincpat.substr($atchmnt['url'], 0, strrpos($atchmnt['url'], '.')).'tn'.substr($atchmnt['url'], strrpos($atchmnt['url'], '.')).'\', \''.$uaid.'\');">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="90px"><img src="'.$baseincpat.substr($atchmnt['url'], 0, strrpos($atchmnt['url'], '.')).'tn'.substr($atchmnt['url'], strrpos($atchmnt['url'], '.')).'"/></td><td align="left" valign="top" width="462px" style="padding-left: 8px; padding-top: 1px;">
								<span class="subtext" style="font-size: 14px;">'.$atchmnt['time'].'</span>
								</td></tr></table>';		
							echo '</td></tr>';
						$i++;				
						}
						//if no records
						if ($i==0) {
							echo '<tr><td align="left">you have not added links</td></tr>';
						}
					echo '</table></div>';
		} elseif ($method=='link') {
			//load album selector
				echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">Previous Links |</td><td align="left" valign="center"
					<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'\');">back to main</div>
					</td></tr></table>
				</div>
				<div id="pacatalog" style="overflow-y: scroll; overflow-x: hidden; height: 180px; width: 560px;">
					<table cellpadding="0" cellspacing="0">';
						$links = mysql_query ("SELECT ua_id, type, host, url, tn_url, title, description, DATE_FORMAT(time_stamp, '%b %D, %Y') AS time FROM user_links WHERE u_id='$id' ORDER BY ua_id DESC");
						$i = 0;
						while ($link = @mysql_fetch_array ($links, MYSQL_ASSOC)) {
							$uaid = $link['ua_id'];
							echo '<tr><td align="left" style="border-bottom: 1px solid #ECECEC; padding-top: 12px; padding-bottom: 6px; cursor: pointer;" onclick="parent.$(\'pbox-loader\').set(\'styles\',{\'display\':\'block\'});parent.$(\'mtsedit'.$mtsid.'\').contentWindow.attachments.lnk(\''.$link['type'].'\', \''.$link['host'].'\', \''.$baseincpat.$link['tn_url'].'\', \''.$link['title'].'\', \''.$link['description'].'\', \''.$uaid.'\');">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="90px"><img src="'.$baseincpat.$link['tn_url'].'" /></td><td align="left" valign="top" width="462px" style="padding-left: 8px; padding-top: 1px;">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p18">'.$link['title'].'</td></tr><tr><td align="left"><span class="subtext" style="font-size: 14px;">'.$link['url'].'</span></td></tr><tr><td align="left" style="padding-left: 12px; padding-top: 6px;"><span class="subtext" style="font-size: 14px;">'; if(strlen($link['description'])>=40){echo substr($link['description'], 0, 40).'...';}else{echo $album['description'];} echo'</span></td></tr><tr><td align="left"><span class="subtext" style="font-size: 14px;">'.$link['time'].'</span></td></tr</table>
								</td></tr></table>';		
							echo '</td></tr>';
						$i++;				
						}
						//if no records
						if ($i==0) {
							echo '<tr><td align="left">you have not added links</td></tr>';
						}
					echo '</table></div>';
		} else {
			//previous item picker
			echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">Previous |</td><td align="left" valign="center"
					<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'\');">back to main</div>
					</td></tr></table>
			</div>
			<div align="center">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-right: 4px;">
				<div class="blockbtn" style="width: 210px; height: 96px;" onclick="gotopage(\'mainarea\', \''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'&choose=prev&method=upld\');">
					<div align="left" class="p24">Previous Uploads</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Attach a previously uploaded photo from your computer.</div>
				</div>
			</td><td align="left" valign="top" style="border-left: 1px solid #E4E4E4; padding-left: 4px; padding-right: 4px;">
				<div class="blockbtn" style="width: 210px; height: 96px;" onclick="gotopage(\'mainarea\', \''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'&choose=prev&method=link\');">
					<div align="left" class="p24">Previous Links</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Attach a previously used link to a website, photo, or video.</div>
				</div>
			</td></tr></table>
			</div>';
		}
		
	} else {
		//report error
	echo '<table cellpadding="0" cellspacing="0" width="400px"><tr><td align="left" class="paragraph60">An error occurred: no choice was made.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
	reporterror('profile/meefile/editmtsec-attach.php', 'attaching to message - making selection', 'choose is not set');
	echo '</td></tr></table>';
	}

} else {
	
if($_GET['action']=='upload'){
$pjs = '<link href="'.$baseincpat.'externalfiles/swfupload/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="'.$baseincpat.'externalfiles/swfupload/swfupload.js"></script>
<script type="text/javascript" src="'.$baseincpat.'externalfiles/swfupload/handlers_attachments.js"></script>
<script type="text/javascript">
		var swfu;
		window.onload = function () {
			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "editmtsec-attach-uploadhandler.php",
				post_params: {"mtsid": "'.$mtsid.'"},

				// File Upload Settings
				file_size_limit : "10 MB",
				file_types : "*.jpg;*.png",
				file_types_description : "JPG Images; PNG Image",
				file_upload_limit : 1,

				// Event Handler Settings - these functions as defined in Handlers.js
				//  The handlers are not part of SWFUpload but are part of my website and control how
				//  my website reacts to the SWFUpload events.
				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : function(){$(\'uploadermaincont\').set(\'html\', \'<table cellpadding="0" cellspacing="0"><tr><td align="left">The uploader failed to load.<br />Please <a href="http://www.mozilla.com/plugincheck/" target = "_new">check to make sure you have the most recent version of flash installed</a> and try again.</span></td></tr></table>\');},
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : function(){$(\'uploadermaincont\').set(\'html\', \'<table cellpadding="0" cellspacing="0"><tr><td align="left">Your photo has been uploaded.</td></tr></table>\');},

				// Button Settings
				button_placeholder_id : "spanButtonPlaceholder",
				button_width: 100,
				button_height: 24,
				button_text : \'<span class="button">Select Photo</span>\',
				button_text_style : \'.button { font-family: Arial, Helvetica, sans-serif; font-size: 16pt; }\',
				button_text_top_padding: 2,
				button_text_left_padding: 4,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "../swfupload/swfupload.swf",
				flash9_url : "../swfupload/swfupload_fp9.swf",

				custom_settings : {
					upload_target : "divFileProgressContainer",
					thumbnail_height: 520,
					thumbnail_width: 660,
					thumbnail_quality: 80
				},
				
				// Debug Settings
				debug: false
			});
		};
	</script>';
}
include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Attach To Meefile Tab Section</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to attach something to your Meefile tab section.</div>
<div align="center" id="mainarea">';

if ($_GET['action']=='upload') {
		
		echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="bottom" style="padding-top: 8px;">Upload |</td><td align="left" valign="top"
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'\');">back to main</div>
				</td></tr></table>
				<div align="left">Please select a photo to upload and attach to your feed post.</div>
			</div>
			<div align="center" id="uploadermaincont" style="overflow-y: hidden; overflow-x: hidden; height: 150px; width: 600px;">
				<form>
					<div id="uploaderbtn" style="cursor: pointer; height: 24px; width: 100px; font-size: 16px; padding-left: 18px; padding-top: none; padding-right: 18px; padding-bottom: none; background-color: #D9D9D9; border: 1px solid #000; -moz-border-radius: 4px; -webkit-border-radius: 4px; -opera-border-radius: 4px; -khtml-border-radius: 4px; border-radius: 4px;">
						<span id="spanButtonPlaceholder"></span>
					</div>
				</form>
				<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">uploading...</td></tr><tr><td></td><td align="left" valign"center" style="padding-left: 2px;">do not close or refresh this page</td></tr></table></div>
				<div id="divFileProgressContainer" style="height: 75px;"></div>
			</div>';
		
} else {
	
	echo '<div align="center">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-right: 4px;">
				<div class="blockbtn" style="width: 132px; height: 96px;" onclick="gotopage(\'mainarea\', \''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'&choose=photos\');">
					<div align="left" class="p24">My Photos</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Attach a photo from your photos.</div>
				</div>
			</td><td align="left" valign="top" style="border-left: 1px solid #E4E4E4; padding-left: 4px; padding-right: 4px;">
				<div class="blockbtn" style="width: 132px; height: 96px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'&action=upload\');">
					<div align="left" class="p24">Upload</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Attach a photo from your computer.</div>
				</div>
			</td><td align="left" valign="top" style="border-left: 1px solid #E4E4E4; padding-left: 4px; padding-right: 4px;">
				<div class="blockbtn" style="width: 132px; height: 96px;" onclick="gotopage(\'mainarea\', \''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'&choose=link\');">
					<div align="left" class="p24">New Link</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Attach a link to a website, photo, or video.</div>
				</div>
			</td><td align="left" valign="top" style="border-left: 1px solid #E4E4E4; padding-left: 4px;">
				<div class="blockbtn" style="width: 132px; height: 96px;" onclick="gotopage(\'mainarea\', \''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'&choose=prev\');">
					<div align="left" class="p24">Previous</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Attach a previous attachment you\'ve used.</div>
				</div>
			</td></tr></table>
			</div>';
}

include ('../../../externals/header/footer-pb.php');
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>