<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$eid = escape_data($_GET['id']);
if (mysql_result (mysql_query("SELECT COUNT(*) FROM event_owners WHERE e_id='$eid' AND u_id='$id' AND type='a' LIMIT 1"), 0)>0) { //test for admin

if (isset($_GET['choose'])) {
$choose = $_GET['choose'];
	
	if ($id>0) {}else{
		session_write_close();
		exit();		
	}
	
	if ($choose=='photos') {
		
		if ($_GET['grab']=='aps') {
			
			//load album photo selector
			$paid = strip_tags(escape_data($_GET['paid']));
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
						echo '<img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="shadowbox" onclick="gotopage(\'ap'.$photo['ap_id'].'\', \''.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'&choose=photos&action=set&apid='.$photo['ap_id']; echo'\', \'65\');" style="cursor: pointer;"/></td>';
						
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
						echo '<tr><td align="left" class="paragraph80">you have not added any photos</td></tr>';
					}
				echo '</table>';
			} else {
				//report error
				echo '<table cellpadding="0" cellspacing="0" width="500px"><tr><td align="left" class="paragraph60">An error occurred: you don\'t own this photo album. Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
				reporterror('event/editphoto.php', 'viewing album photos', 'don\'t own photo photo album or may not exists paid='.$paid);
				echo '</td></tr></table>';
			}
			
		} elseif ($_GET['action']=='set') {
			//set photo
			$apid = strip_tags(escape_data($_GET['apid']));
			if ($_GET['method']=='cncl') {
				$photo = @mysql_fetch_array (@mysql_query ("SELECT ap_id, url FROM album_photos WHERE ap_id='$apid' LIMIT 1"), MYSQL_ASSOC);
				echo '<img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="shadowbox" onclick="gotopage(\'ap'.$photo['ap_id'].'\', \''.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'&choose=photos&action=set&apid='.$photo['ap_id']; echo'\', \'65\');" style="cursor: pointer;"/>';
			} else {
				echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" calss="paragraph60">Are you sure?</td></tr><tr><td align="center" style="padding-top: 6px; padding-top: 4px;">
					<form action="'.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'&action=setap&apid='.$apid.'" method="post">
						<table cellpadding="0" cellspacing="0"><tr><td align="left"><input type="submit" name="yes" value="yes"/></td><td align="left" style="padding-left: 4px;"><input type="button" class="endblur" name="no" value="no" onclick="gotopage(\'ap'.$apid.'\', \''.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'&choose=photos&action=set&method=cncl&apid='.$apid.'\', \'65\');"/></td></tr></table>
					</form>
				</td></tr></table>';
			}
			
		} else {
			//load album selector
			echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">My Photos |</td><td align="left" valign="center">
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'\');">back to main</div>
				</td></tr></table>
			</div>
			<div id="pacatalog" style="overflow-y: scroll; overflow-x: hidden; height: 180px; width: 560px;">
				<table cellpadding="0" cellspacing="0">';
					$albums = mysql_query ("SELECT pa_id, name, cover_url, description, DATE_FORMAT(date, '%b %D, %Y') AS time FROM photo_albums WHERE u_id='$id' ORDER BY pa_id DESC");
					$i = 0;
					while ($album = @mysql_fetch_array ($albums, MYSQL_ASSOC)) {
						$paid = $album['pa_id'];
						$pcount = mysql_fetch_array(@mysql_query ("SELECT COUNT(*) FROM album_photos WHERE pa_id='$paid'"), MYSQL_NUM);
						echo '<tr><td align="left" style="border-bottom: 1px solid #ECECEC; padding-top: 12px; padding-bottom: 6px; cursor: pointer;" onclick="gotopage(\'pacatalog\', \''.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'&choose=photos&grab=aps&paid='.$paid; echo'\', \'182\');">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="90px"><img src="'.$baseincpat.$album['cover_url'].'" /></td><td align="left" valign="top" width="462px" style="padding-left: 8px; padding-top: 1px;">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p18">'.$album['name'].' <span class="subtext">('.$pcount[0].' Photo'; if($pcount[0]>1){echo's';} echo')</span></td></tr><tr><td align="left"><span class="subtext">'.$album['time'].'</span></td></tr><tr><td align="left" style="padding-left: 12px; padding-top: 6px;"><span class="subtext" style="font-size: 14px;">'; if(strlen($album['description'])>=40){echo substr($album['description'], 0, 40).'...';}else{echo $album['description'];} echo'</span></td></tr></table>
						</td></tr></table			
					</td></tr>';
					$i++;				
					}
					//if no records
					if ($i==0) {
						echo '<tr><td align="left" class="paragraph80">you have not added any albums</td></tr>';
					}
				echo '</table></div>';
		}
		
	} elseif ($choose=='delete') {
			
		//verify delete
			echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">Delete |</td><td align="left" valign="center">
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'\');">back to main</div>
				</td></tr></table>
			</div>
			<div style="overflow-y: hidden; overflow-x: hidden; height: 150px; width: 500px;">
					<form action="'.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'&action=delete" method="post">
						<table cellpadding="0" cellspacing="0">
							<tr><td align="left" class="paragraph80" style="padding-top: 6px;">Are you sure you want to delete your event photo?</td></tr>
							<tr><td align="center" style="padding-top: 12px;"><input type="submit" class="end" name="delete" id="delete" value="delete" onclick="$(\'btm_btn\').destroy();this.style.display=\'none\';document.getElementById(\'uplmsg\').style.display=\'block\';parent.document.getElementById(\'pbox-loader\').style.display=\'block\';"/></td></tr>
						</table>
					</form>
			</div>';	
		
	} else {
		//report error
	echo '<table cellpadding="0" cellspacing="0" width="400px"><tr><td align="left" class="paragraph60">An error occurred: no choice was made.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
	reporterror('event/editphoto.php', 'selcting a photo', 'choose is not set');
	echo '</td></tr></table>';
	}

} else { //main else from choice

if($_GET['action']=='upload'){
$pjs = '<link href="'.$baseincpat.'externalfiles/swfupload/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="'.$baseincpat.'externalfiles/swfupload/swfupload.js"></script>
<script type="text/javascript" src="'.$baseincpat.'externalfiles/swfupload/handlers.js"></script>
<script type="text/javascript">
		var swfu;
		window.onload = function () {
			swfu = new SWFUpload({
				// Backend Settings
				upload_url: "editphoto-uploadhandler.php",
				post_params: {"eid": "'.$eid.'"},

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
				upload_success_handler : function(){return true;},
				upload_complete_handler : function(){$(\'uploadermaincont\').set(\'html\', \'<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your event photo has been saved.</td></tr></table>\');setTimeout("parent.window.location.reload();", 800);},

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

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit Event Photo</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to edit your event photo.</div>
<div align="center" id="mainarea">';

	if ($_GET['action']=='upload') {
		
		echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="bottom" style="padding-top: 8px;">Upload |</td><td align="left" valign="top"
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/home/postfeed-attach.php\');">back to main</div>
				</td></tr></table>
				<div align="left">Please select a photo to upload and set as your event photo.</div>
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
		
	} elseif (($_GET['action']=='setap')&&($_POST['yes'])) {
		
		//set ap photo
			$apid = strip_tags(escape_data($_GET['apid']));
			$photo = @mysql_fetch_array (@mysql_query ("SELECT pa_id, url FROM album_photos WHERE ap_id='$apid' LIMIT 1"), MYSQL_ASSOC);
			$paid = $photo['pa_id'];

			//test vis
			if ((mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos WHERE pa_id='$paid' AND u_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE ap_id='$apid' AND u_id='$id' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='strm' AND ps.stream=apv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='chan' AND apv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
						
						//set function
						function getSafeFileName($fileName) {
							$savepath = "../../events/$eid/";
							
							$newFileName = $fileName;
							while (file_exists($absGalleryPath . $newFileName)) {
								$fext = substr($fileName, strrpos($fileName, '.'));
								$pfn = md5(uniqid(rand(), true));
								$newFileName = strtolower($pfn.$fext);
							}
							return $newFileName;	
						}
						
						//format image
						$filename = '../../'.$photo['url'];
						$pfn = md5(uniqid(rand(), true));
						
						$tmpfn = getSafeFileName($pfn.'.jpg');
						
						$pfn = strtolower(substr($tmpfn, 0, strrpos($tmpfn, '.')));
						
									//make large photo
									$fn = $pfn.'.jpg';
											
									$width = 660;
									$height = 520;
									
									list($width_orig, $height_orig) = getimagesize($filename);
									
									if (($width_orig > $width) || ($height_orig > $height)) {
										$ratio_orig = $width_orig/$height_orig;
										
										if ($width/$height > $ratio_orig) {
										   $width = $height*$ratio_orig;
										} else {
										   $height = $width/$ratio_orig;
										}
									} else {
										$width = $width_orig;
										$height = $height_orig;
									}
									
									$image_l = imagecreatetruecolor($width, $height);
									$bg = imagecolorallocate($image_l, 255, 255, 255);
									imagefill($image_l, 0, 0, $bg);
									$image = imagecreatefromjpeg($filename);
									
									imagecopyresampled($image_l, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
									
									if (imagejpeg($image_l, "../../events/$eid/$fn", 100)) {
											imagedestroy($image_l);
											imagedestroy($image);
											
										//delete old photo
										$user = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
										if ($user['defaultimg_url']!='images/nophoto_e.png') {
											$oldimg = '../../'.$user['defaultimg_url'];
											unlink($oldimg);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -5).substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
										}
										$ffn = $pfn.'p.jpg';
										$update = mysql_query ("UPDATE events SET defaultimg_url='events/$eid/$ffn' WHERE e_id='$eid' LIMIT 1");
											
											//make profile photo
											$ogp = "../../events/$eid/$fn";
											$fn = $pfn.'p.jpg';
											
											$width = 186;
											$height = 140;
											
											list($width_orig, $height_orig) = getimagesize($ogp);
											
											if (($width_orig > $width) || ($height_orig > $height)) {
												$ratio_orig = $width_orig/$height_orig;
												
												if ($width/$height > $ratio_orig) {
												   $width = $height*$ratio_orig;
												} else {
												   $height = $width/$ratio_orig;
												}
											} else {
												$width = $width_orig;
												$height = $height_orig;
											}
												
											$image_l = imagecreatetruecolor($width, $height);
											$image = imagecreatefromjpeg($ogp);
											
											imagecopyresampled($image_l, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
											imagejpeg($image_l, "../../events/$eid/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
										echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your event photo has been saved.</td></tr></table>
										<script type="text/javascript">
											setTimeout("parent.window.location.reload();", 800);
										</script>';
									} else {
										echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">A file system error occurred.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
										reporterror('event/editphoto.php', 'saving event photo taken from photo albums', 'not able to copy');
										echo '</td></tr></table>
										<script type="text/javascript">
											setTimeout("parent.PopBox.close();", 2800);
										</script>';
									}
									imagedestroy($image_l);
									imagedestroy($image);
					} else {
						echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">A file system error occurred: you don\'t own this photo.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
						reporterror('event/editphoto.php', 'saving event photo taken from album photos', 'did not own photo');
						echo '</td></tr></table>
						<script type="text/javascript">
							setTimeout("parent.PopBox.close();", 2800);
						</script>';
					}
									
	} elseif (($_GET['action']=='delete')&&($_POST['delete'])) {
			//delete old photo
			$user = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM events WHERE e_id='$eid' LIMIT 1"), MYSQL_ASSOC);
			if ($user['defaultimg_url']!='images/nophoto_e.png') {
				$oldimg = '../../'.$user['defaultimg_url'];
				unlink($oldimg);
				$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -5).substr($user['defaultimg_url'], -4);
				unlink($oldimgt);
			}
			$update = mysql_query ("UPDATE events SET defaultimg_url='images/nophoto_e.png' WHERE e_id='$eid' LIMIT 1");
			
			echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your event photo has been deleted.</td></tr></table>
				<script type="text/javascript">
					setTimeout("parent.location.reload();", 1400);
				</script>';
	} else {
	
			echo '<div align="center">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-right: 4px;">
				<div class="blockbtn" style="width: 160px; height: 96px;" onclick="gotopage(\'mainarea\', \''.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'&choose=photos\');">
					<div align="left" class="p24">My Photos</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Choose a photo from your photos.</div>
				</div>
			</td><td align="left" valign="top" style="border-left: 1px solid #E4E4E4; padding-left: 4px; padding-right: 4px;">
				<div class="blockbtn" style="width: 160px; height: 96px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'&action=upload\');">
					<div align="left" class="p24">Upload</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Choose a photo from your computer.</div>
				</div>
			</td><td align="left" valign="top" style="border-left: 1px solid #E4E4E4; padding-left: 4px;">
				<div class="blockbtn" style="width: 160px; height: 96px;" onclick="gotopage(\'mainarea\', \''.$baseincpat.'externalfiles/event/editphoto.php?id='.$eid.'&choose=delete\');">
					<div align="left" class="p24">Delete</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Remove your current event photo.</div>
				</div>
			</td></tr></table>
			</div>';
	}

	echo '</div>';

include ('../../../externals/header/footer-pb.php');
}

} else {
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You are unable to view this information.
	</div>';	
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>