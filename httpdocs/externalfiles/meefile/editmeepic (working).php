<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

if (isset($_GET['choose'])) {
$choose = $_GET['choose'];
	
	$id = $_SESSION['user_id'];
	
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
						echo '<img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="shadowbox" onclick="gotopage(\'ap'.$photo['ap_id'].'\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=photos&action=set&apid='.$photo['ap_id']; echo'\', \'65\');" style="cursor: pointer;"/></td>';
						
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
				reporterror('meefile/editmeepic.php', 'viewing album photos', 'don\'t own photo photo album or may not exists paid='.$paid);
				echo '</td></tr></table>';
			}

		} elseif ($_GET['grab']=='uis') {
			
				echo '<table cellpadding="0" cellspacing="0">';
					$photos = @mysql_query ("SELECT ui_id, img_url FROM user_imgs WHERE u_id='$id' ORDER BY time_stamp DESC");
					while ($photo = @mysql_fetch_array ($photos, MYSQL_ASSOC)) {
						if ($i==0) {
							echo '<tr><td align="center" width="90px" id="ap'.$photo['ui_id'].'" style="padding-top: 16px;">';
						} else {
							echo '<td align="center" width="90px" id="ap'.$photo['ui_id'].'" style="padding-left: 18px; padding-top: 16px;">';	
						}
						echo '<img src="'.$baseincpat.substr($photo['img_url'], 0, strrpos($photo['img_url'], '.')).'tn'.substr($photo['img_url'], strrpos($photo['img_url'], '.')).'" class="shadowbox" onclick="gotopage(\'ap'.$photo['ui_id'].'\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=photos&action=setui&uiid='.$photo['ui_id']; echo'\', \'65\');" style="cursor: pointer;"/></td>';
						
						if ($i==3) {
							echo '</tr>';
							$i=-1;
						}
						$i++;
					}
					if ($i<4) {
						while ($i<4) {
						 echo'<td></td>';
						 $i++;
						}
						echo '</tr>';
					}
					//if no records
					if (@mysql_num_rows($photos) == 0) {
						echo '<tr><td align="left" class="paragraph80">you have not added any MeePics</td></tr>';
					}
				echo '</table>';

		} elseif ($_GET['action']=='set') {
			//set photo
			$apid = strip_tags(escape_data($_GET['apid']));
			if ($_GET['method']=='cncl') {
				$photo = @mysql_fetch_array (@mysql_query ("SELECT ap_id, url FROM album_photos WHERE ap_id='$apid' LIMIT 1"), MYSQL_ASSOC);
				echo '<img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="shadowbox" onclick="gotopage(\'ap'.$photo['ap_id'].'\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=photos&action=set&apid='.$photo['ap_id']; echo'\', \'65\');" style="cursor: pointer;"/>';
			} else {
				echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" calss="paragraph60">Are you sure?</td></tr><tr><td align="center" style="padding-top: 6px; padding-top: 4px;">
					<form action="'.$baseincpat.'externalfiles/meefile/editmeepic.php?action=setap&apid='.$apid.'" method="post">
						<table cellpadding="0" cellspacing="0"><tr><td align="left"><input type="submit" name="yes" value="yes"/></td><td align="left" style="padding-left: 4px;"><input type="button" class="endblur" name="no" value="no" onclick="gotopage(\'ap'.$apid.'\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=photos&action=set&method=cncl&apid='.$apid.'\', \'65\');"/></td></tr></table>
					</form>
				</td></tr></table>';
			}
			
		} else if ($_GET['action']=='setui') {
			//set photo
			$uiid = strip_tags(escape_data($_GET['uiid']));
			if ($_GET['method']=='cncl') {
				$photo = @mysql_fetch_array (@mysql_query ("SELECT img_url FROM user_imgs WHERE ui_id=$uiid LIMIT 1"), MYSQL_ASSOC);
				echo '<img src="'.$baseincpat.substr($photo['img_url'], 0, strrpos($photo['img_url'], '.')).'tn'.substr($photo['img_url'], strrpos($photo['img_url'], '.')).'" class="shadowbox" onclick="gotopage(\'ap'.$uiid.'\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=photos&action=setui&uiid='.$uiid; echo'\', \'65\');" style="cursor: pointer;"/>';
			} else {
				echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" calss="paragraph60">Are you sure?</td></tr><tr><td align="center" style="padding-top: 6px; padding-top: 4px;">
					<form action="'.$baseincpat.'externalfiles/meefile/editmeepic.php?action=setui&uiid='.$uiid; echo'" method="post">
						<table cellpadding="0" cellspacing="0"><tr><td align="left"><input type="submit" name="yes" value="yes"/><td align="left"><input type="button" class="endblur" name="no" value="no" onclick="gotopage(\'ap'.$uiid.'\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=photos&action=setui&method=cncl&uiid='.$uiid; echo'\', \'65\');"/></td></tr></table>
					</form>
				</td></tr></table>';
			}
			
		} else {
			//load album selector
			echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">My Photos |</td><td align="left" valign="center"
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmeepic.php\');">back to main</div>
				</td></tr></table>
			</div>
			<div id="pacatalog" style="overflow-y: scroll; overflow-x: hidden; height: 180px; width: 560px;">
				<table cellpadding="0" cellspacing="0">';
					$albums = mysql_query ("SELECT pa_id, name, cover_url, description, DATE_FORMAT(date, '%b %D, %Y') AS time FROM photo_albums WHERE u_id='$id' ORDER BY pa_id DESC");
					$i = 0;
					while ($album = @mysql_fetch_array ($albums, MYSQL_ASSOC)) {
						$paid = $album['pa_id'];
						$pcount = mysql_fetch_array(@mysql_query ("SELECT COUNT(*) FROM album_photos WHERE pa_id='$paid'"), MYSQL_NUM);
						echo '<tr><td align="left" style="border-bottom: 1px solid #ECECEC; padding-top: 12px; padding-bottom: 6px; cursor: pointer;" onclick="gotopage(\'pacatalog\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=photos&grab=aps&paid='.$paid; echo'\', \'182\');">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="90px"><img src="'.$baseincpat.$album['cover_url'].'" /></td><td align="left" valign="top" width="462px" style="padding-left: 8px; padding-top: 1px;">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p18">'.$album['name'].' <span class="subtext">('.$pcount[0].' Photo'; if($pcount[0]>1){echo's';} echo')</span></td></tr><tr><td align="left"><span class="subtext">'.$album['time'].'</span></td></tr><tr><td align="left" style="padding-left: 12px; padding-top: 6px;"><span class="paragraphA1">'; if(strlen($album['description'])>=40){echo substr($album['description'], 0, 40).'...';}else{echo $album['description'];} echo'</span></td></tr></table>
						</td></tr></table			
					</td></tr>';
					$i++;				
					}
					$uis = @mysql_query ("SELECT img_url FROM user_imgs WHERE u_id='$id' ORDER BY time_stamp DESC LIMIT 1");
					if (@mysql_num_rows($uis)>0) {
						$ui = @mysql_fetch_array ($uis, MYSQL_ASSOC);
						$uicount = @mysql_fetch_array(@mysql_query ("SELECT COUNT(*) FROM user_imgs WHERE u_id='$id'"), MYSQL_NUM);
						echo '<tr><td align="left" style="border-bottom: 1px solid #C5C5C5; padding-top: 4px;"></td></tr><tr><td align="left" style="border-bottom: 1px solid #ECECEC; padding-top: 12px; padding-bottom: 6px; cursor: pointer;" onclick="gotopage(\'pacatalog\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=photos&grab=uis\', \'182\');">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="90px"><img src="'.$baseincpat.substr($ui['img_url'], 0, strrpos($ui['img_url'], '.')).'tn'.substr($ui['img_url'], strrpos($ui['img_url'], '.')).'" /></td><td align="left" valign="top" width="354px" style="padding-left: 8px; padding-top: 1px;">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" class="crumb14">MeePics <span class="paragraph80">('.$uicount[0].' Photo'; if($uicount[0]>1){echo's';} echo')</span></td></tr><tr><td align="left" style="padding-left: 12px; padding-top: 6px;"><span class="paragraphA1">These are all of your MeePics.</span></td></tr></table>
							</td></tr></table>		
						</td></tr>';
					$i++;
					}
					//if no records
					if ($i==0) {
						echo '<tr><td align="left" class="paragraph80">you have not added any albums</td></tr>';
					}
				echo '</table></div>';
		}
		
	} elseif ($choose=='upload') {
			
			//load uploader
			echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">Upload |</td><td align="left" valign="center"
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmeepic.php\');">back to main</div>
				</td></tr></table>
			</div>
			<div style="overflow-y: hidden; overflow-x: hidden; height: 150px; width: 444px;">
					<form enctype="multipart/form-data" action="'.$baseincpat.'externalfiles/meefile/editmeepic.php?action=upload" method="post">
						<table cellpadding="0" cellspacing="0" width="300px">
							<tr><td align="left"><input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
							<input type="file" name="upload" id="upload" onchange="if (document.getElementById(\'upload\').value!=\'\'){ExtensionsOkay();}"/></td></tr>
							<tr><td align="center" class="paragraph80" style="padding-top: 6px;">you may upload a 4MB or smaller photo</td></tr>
							<tr><td align="center" style="padding-top: 12px;"><input type="submit" class="end" name="submit" id="submit" value="upload" style="display: none;" onclick="$(\'btm_btn\').destroy();this.style.display=\'none\';document.getElementById(\'uplmsg\').style.display=\'block\';parent.document.getElementById(\'pbox-loader\').style.display=\'block\';"/></td></tr>
							<tr><td align="center" id="uplmsg" style="display: none; padding-top: 8px;">
								<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top"><img src="'.$baseincpat.'images/spinner.gif"/></td><td align="left" valign="top" class="paragraph80" style="padding-left: 6px;"><span class="paragraph60">do not refresh the page</span><br />uploading may take a few minutes</td></tr></table>
							</td></tr><tr><td align="center" id="alrttype" class="paragraph60" style="display: none; padding-top: 8px;">you may only upload a .jpg, .png, or .gif file</td></tr>
							</table>
							</form>
				</div>';
	
	} elseif ($choose=='delete') {
			
		//verify delete
			echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">Delete |</td><td align="left" valign="center"
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmeepic.php\');">back to main</div>
				</td></tr></table>
			</div>
			<div style="overflow-y: hidden; overflow-x: hidden; height: 150px; width: 500px;">
					<form action="'.$baseincpat.'externalfiles/meefile/editmeepic.php?action=delete" method="post">
						<table cellpadding="0" cellspacing="0">
							<tr><td align="left" class="paragraph80" style="padding-top: 6px;">Are you sure you want to delete your MeePic?</td></tr>
							<tr><td align="center" style="padding-top: 12px;"><input type="submit" class="end" name="delete" id="delete" value="delete" onclick="$(\'btm_btn\').destroy();this.style.display=\'none\';document.getElementById(\'uplmsg\').style.display=\'block\';parent.document.getElementById(\'pbox-loader\').style.display=\'block\';"/></td></tr>
						</table>
					</form>
			</div>';	
		
	} else {
		//report error
	echo '<table cellpadding="0" cellspacing="0" width="400px"><tr><td align="left" class="paragraph60">An error occurred: no choice was made.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
	reporterror('meefile/editmeepic.php', 'selcting a photo', 'choose is not set');
	echo '</td></tr></table>';
	}

} else { //main else from choice

if($_GET['action']=='edtthumb'){
$pjs = '<script type="text/javascript" src="'.$baseincpat.'externalfiles/mts-more-Drag.Move.js" language="Javascript"></script>';
$pdrjs = 'var myDrag = new Drag.Move(\'etnptn\', {
	 	
		container: \'containme\',
		
		droppables: \'.droparea\',
	 
		onDrop: function(element, droppable, event){
			$(\'x_pos\').value = $(element).getPosition(\'containme\').x;
			$(\'y_pos\').value = $(element).getPosition(\'containme\').y;
		}
	 
	});';
} else {
$pjs = '<script type="text/javascript">
	function ExtensionsOkay() {
	var extension = new Array();
	
	var fieldvalue = document.getElementById(\'upload\').value.toLowerCase();
	
	extension[0] = ".png";
	extension[1] = ".gif";
	extension[2] = ".jpg";
	extension[3] = ".jpeg";
	
	var thisext = fieldvalue.substr(fieldvalue.lastIndexOf(\'.\'));
	for(var i = 0; i < extension.length; i++) {
		if(thisext == extension[i]) { document.getElementById(\'alrttype\').style.display=\'none\'; document.getElementById(\'submit\').style.display=\'block\'; return true; }
		}
	document.getElementById(\'submit\').style.display=\'none\';
	document.getElementById(\'alrttype\').style.display=\'block\';
	document.getElementById(\'upload\').value = null;
	return false;
	}
</script>';
}
include ('../../../externals/header/header-pb.php');

echo '<div align="left" class="p24" style="width: 460px; border-bottom: 1px solid #C5C5C5;">Edit MeePic</div>
<div align="left" class="subtext" style="padding-left: 20px; padding-top: 2px; padding-bottom: 18px;">Use this to edit your MeePic. <span style="font-size: 14px;">(This is publicly visible and will help people identify who you are.)</span></div>
<div align="center" id="mainarea">';

	if ($_GET['action']=='edtthumb') {
			
		//make thumbnail
		if (isset($_POST['save'])) {
			$uinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);	
			if ($uinfo['defaultimg_url']!='images/nophoto.png') {
				
				$x_pos = strip_tags(escape_data($_POST['x_pos']));
				$y_pos = strip_tags(escape_data($_POST['y_pos']));
				
				list($width, $height) = getimagesize($baseincpat.substr($uinfo['defaultimg_url'], 0, -4).'ptn'.substr($uinfo['defaultimg_url'], -4));
				
				$x = abs($x_pos-($width-50));
				$y = abs($y_pos-($height-50));
				
											//delete old photo
											$user = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
											if ($user['defaultimg_url']!='images/nophoto.png') {
												$oldimg = '../../'.$user['defaultimg_url'];
												unlink($oldimg);
												$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'m'.substr($user['defaultimg_url'], -4);
												unlink($oldimgt);
											}	
												
												//use ptn
												$filename = '../../'.substr($uinfo['defaultimg_url'], 0, -4).'ptn'.substr($uinfo['defaultimg_url'], -4);
												
												//get pfn
												$ppfn = substr($uinfo['defaultimg_url'], (strrpos($uinfo['defaultimg_url'], '/')+1));
												$pfn = substr($ppfn, 0, strrpos($ppfn, '.'));
												
												//make MeePic photo
												$fn = $pfn.'.jpg';
												
												list($width_orig, $height_orig) = getimagesize($filename);
													
												$image_l = imagecreatetruecolor(50, 50);
												$image = imagecreatefromjpeg($filename);
												imagecopyresampled($image_l, $image, 0, 0,  $x, $y, $width_orig, $height_orig, $width_orig, $height_orig);
												if (imagejpeg($image_l, "../../users/$id/meepics/$fn", 100)) {
												
													imagedestroy($image_l);
													imagedestroy($image);
													
													$update = @mysql_query ("UPDATE users SET durl_x='$x_pos', durl_y='$y_pos' WHERE user_id='$id' LIMIT 1");
													
													//use default for next one
													$filename = "../../users/$id/meepics/".$fn;
													
													//make mini photo
													$fn = $pfn.'m.jpg';
													$width = 36;
													$height = 36;
													
													list($width_orig, $height_orig) = getimagesize($filename);
													
													if (($width_orig > $width) || ($height_orig > $height)) {
														$ratio_orig = $width_orig/$height_orig;
														
														if ($width/$height > $ratio_orig) {
														   $width = $height*$ratio_orig;
														} else {
														   $height = $width/$ratio_orig;
														}
													}
														
													$image_l = imagecreatetruecolor($width, $height);
													$image = imagecreatefromjpeg($filename);
													imagecopyresampled($image_l, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
													imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
													
												echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your MeePic thumbnail has been saved.</td></tr></table>
												<script type="text/javascript">';
												if(isset($_GET['rel'])&&($_GET['rel']!='')){
													echo 'setTimeout("parent.loadurl(\''.$baseincpat.urldecode(trim($_GET['rel'])).'\');", 800);';
												} else {
													echo 'setTimeout("parent.location.reload();", 800);';
												}
													echo 'setTimeout("parent.PopBox.close();", 1400);
												</script>';
										} else {
											echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">A file system error occurred.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
											reporterror('meefile/editmeepic.php', 'saving MeePic thumbnail taken from ptn', 'not able to copy');
											echo '</td></tr></table>
											<script type="text/javascript">
												setTimeout("parent.PopBox.close();", 2800);
											</script>';
										}
										imagedestroy($image_l);
										imagedestroy($image);
			} else {
				echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">An error occured: you have not set a MeePic.<br />Set MeePic and try again.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
				reporterror('meefile/editmeepic.php', 'saving MeePic thumbnail taken from ptn', 'photo is set to default');
				echo '</td></tr></table>
				<script type="text/javascript">
					setTimeout("parent.PopBox.close();", 2800);
				</script>';
			}
			
		} else {
			$uinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url, durl_x, durl_y FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
			list($width, $height) = getimagesize($baseincpat.substr($uinfo['defaultimg_url'], 0, -4).'ptn'.substr($uinfo['defaultimg_url'], -4));
			echo '<div align="left" style="margin-bottom: 12px; margin-left: 16px;">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" class="p24" valign="center">Thumbnail |</td><td align="left" valign="center"
				<div align="left" id="btm_btn" class="blockbtn" style="width: 100px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmeepic.php\');">back to main</div>
				</td></tr></table>
			</div>
			<div align="center" style="padding-left: 32px; padding-top: 22px; height: 120px;">';
				if ($uinfo['defaultimg_url']!='images/nophoto.png') {
					echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
						<div id="droparea" style="overflow-y: hidden; overflow-x: hidden; height: 50px; width: 50px; position: relative;">
							<div id="containme" style="position: absolute; width: '.(50+(($width-50)*2)).'px; height: '.(50+(($height-50)*2)).'px; top: -'.($height-50).'px; left: -'.($width-50).'px;">
								<div id="etnptn" style="cursor: move; height: '.$height.'px; width: '.$width.'px; position: absolute; top: '.$uinfo['durl_y'].'px; left: '.$uinfo['durl_x'].'px;"><img src="'.$baseincpat.substr($uinfo['defaultimg_url'], 0, -4).'ptn'.substr($uinfo['defaultimg_url'], -4).'" /></div>
							</div>
						</div>
					</td><td align="left" valign="top" style="padding-left: 12px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph80">This is your photo used throughout meesto.<br /><span class="paragraphA1">Click and drag it to change it.</span></td></tr><tr><td align="left" style="padding-top: 18px; padding-left: 24px;">
							<form action="'.$baseincpat.'externalfiles/meefile/editmeepic.php?action=edtthumb" method="post">
								<input type="hidden" id="x_pos" name="x_pos" value="'.$uinfo['durl_x'].'"/>
								<input type="hidden" id="y_pos" name="y_pos" value="'.$uinfo['durl_y'].'"/>
								<input type="submit" class="end" name="save" id="save" value="save" onclick="$(\'btm_btn\').destroy();this.style.display=\'none\';parent.document.getElementById(\'pbox-loader\').style.display=\'block\';"/>
							</form>
						</td></tr></table>
					</td></tr></table>';
				} else {
					echo 'You have not set your MeePic yet.';	
				}
			echo '</div>';
		}
		
	} elseif ($_GET['action']=='upload') {
		
		if(isset($_FILES['upload'])) {
				$allowed = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/x-png');;
					if (in_array($_FILES['upload']['type'], $allowed)) {
						//set function
						function getSafeFileName($fileName) {
							$savepath = "../../users/$id/meepics/";
							
							$newFileName = $fileName;
							while (file_exists($absGalleryPath . $newFileName)) {
								$fext = substr($fileName, strrpos($fileName, '.'));
								$pfn = md5(uniqid(rand(), true));
								$newFileName = strtolower($pfn.$fext);
							}
							return $newFileName;	
						}
						
						//format image
						$filename = $_FILES['upload']['tmp_name'];
						$fext = strtolower(substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.')));
						$pfn = md5(uniqid(rand(), true));
						
						$tmpfn = getSafeFileName($pfn.'.jpg');
						
						$pfn = strtolower(substr($tmpfn, 0, strrpos($tmpfn, '.')));
						
									//make large photo
									$fn = $pfn.'l.jpg';
											
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
									if ($fext=='.png') {
										$image = imagecreatefrompng($filename);
									} elseif ($fext=='.gif') {
										$image = imagecreatefromgif($filename);
									} else {
										$image = imagecreatefromjpeg($filename);
									}
									
									imagecopyresampled($image_l, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
									
									if (imagejpeg($image_l, "../../users/$id/meepics/$fn", 100)) {
											imagedestroy($image_l);
											imagedestroy($image);
											unlink($filename);
											
										//delete old photo
										$user = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
										if ($user['defaultimg_url']!='images/nophoto.png') {
											$oldimg = '../../'.$user['defaultimg_url'];
											unlink($oldimg);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'m'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'p'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'ptn'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
										}
										$ffn = $pfn.'.jpg';
										$update = @mysql_query ("INSERT INTO user_imgs (u_id, url, time_stamp) VALUES ('$id', 'users/$id/meepics/$ffn', NOW())");
										$update = @mysql_query ("UPDATE users SET defaultimg_url='users/$id/meepics/$ffn' WHERE user_id='$id' LIMIT 1");
											
											//make profile photo
											$ogp = "../../users/$id/meepics/$fn";
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//make tn
											$fn = $pfn.'tn.jpg';
											
											$width = 90;
											$height = 80;
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//make prethumnnail (ptn) photo
											$fn = $pfn.'ptn.jpg';
											
											list($width_orig, $height_orig) = getimagesize($ogp);
											
											if ($width_orig>=$height_orig) {
												$height = 60;
												$width = $width_orig;
											} else {
												$width = 60;
												$height = $height_orig;
											}
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//use ptn for next one
											$ogp = "../../users/$id/meepics/".$fn;
											
											//make MeePic photo
											$fn = $pfn.'.jpg';
											
											list($width_orig, $height_orig) = getimagesize($ogp);
											
											if ($width_orig>=$height_orig) {
												$y = 5;
												$x = ($width_orig/2)-25;
											} else {
												$x = 5;
												$y = ($height_orig/2)-25;
											}
												
											$image_l = imagecreatetruecolor(50, 50);
											$image = imagecreatefromjpeg($ogp);
											imagecopyresampled($image_l, $image, 0, 0,  $x, $y, $width_orig, $height_orig, $width_orig, $height_orig);
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											$update = @mysql_query ("UPDATE users SET durl_x='$x', durl_y='$y' WHERE user_id='$id' LIMIT 1");
											
											//use default for next one
											$filename = "../../users/$id/meepics/".$fn;
											
											//make mini photo
											$fn = $pfn.'m.jpg';
											$width = 36;
											$height = 36;
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
										echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your MeePic has been saved.</td></tr></table>
										<script type="text/javascript">
											setTimeout("window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmeepic.php?action=edtthumb\');", 800);
										</script>';
									} else {
										echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">A file system error occurred.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
										reporterror('meefile/editmeepic.php', 'saving MeePic taken from upload', 'not able to copy');
										echo '</td></tr></table>
										<script type="text/javascript">
											setTimeout("parent.PopBox.close();", 2800);
										</script>';
									}
									imagedestroy($image_l);
									imagedestroy($image);
					} else {
						echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">An error occured: not correct file type.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
						reporterror('meefile/editmeepic.php', 'saving MeePic taken from upload', 'not correct file type');
						echo '</td></tr></table>
						<script type="text/javascript">
							setTimeout("parent.PopBox.close();", 2800);
						</script>';	
					}
			
		} else {
			echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">An error occurred: you are not logged in.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
		reporterror('meefile/editmeepic.php', 'editing MeePic', 'not file was sent');
		echo '</td></tr></table>';
		}
	} elseif (($_GET['action']=='setap')&&($_POST['yes'])) {
		
		//set ap photo
			$apid = strip_tags(escape_data($_GET['apid']));
			$photo = @mysql_fetch_array (@mysql_query ("SELECT pa_id, url FROM album_photos WHERE ap_id='$apid' LIMIT 1"), MYSQL_ASSOC);
			$paid = $photo['pa_id'];

			//test vis
			if ((mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos WHERE pa_id='$paid' AND u_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='pub' AND sub_type='y' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM ap_tags WHERE ap_id='$apid' AND u_id='$id' LIMIT 1"), 0)>0) || (((mysql_result(mysql_query("SELECT COUNT(*) FROM peep_streams ps INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='strm' AND ps.stream=apv.sub_type) WHERE ps.u_id='$uid' AND ps.p_id='$id' LIMIT 1"), 0)>0) || (mysql_result(mysql_query("SELECT COUNT(*) FROM mpc_mems mpcm INNER JOIN album_photos_vis apv ON (apv.ap_id='$apid'AND apv.type='chan' AND apv.ref_id=mpcm.mpc_id) WHERE mpcm.p_id='$id' LIMIT 1"), 0)>0)) && (mysql_result(mysql_query("SELECT COUNT(*) FROM album_photos_vis WHERE ap_id='$apid' AND type='user' AND ref_id='$id' LIMIT 1"), 0)<1))) {
						
						//set function
						function getSafeFileName($fileName) {
							$savepath = "../../users/$id/meepics/";
							
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
									$fn = $pfn.'l.jpg';
											
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
									
									if (imagejpeg($image_l, "../../users/$id/meepics/$fn", 100)) {
											imagedestroy($image_l);
											imagedestroy($image);
											
										//delete old photo
										$user = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
										if ($user['defaultimg_url']!='images/nophoto.png') {
											$oldimg = '../../'.$user['defaultimg_url'];
											unlink($oldimg);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'m'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'p'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'ptn'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
										}
										$ffn = $pfn.'.jpg';
										$update = @mysql_query ("INSERT INTO user_imgs (u_id, url, time_stamp) VALUES ('$id', 'users/$id/meepics/$ffn', NOW())");
										$update = @mysql_query ("UPDATE users SET defaultimg_url='users/$id/meepics/$ffn' WHERE user_id='$id' LIMIT 1");
											
											//make profile photo
											$ogp = "../../users/$id/meepics/$fn";
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//make tn
											$fn = $pfn.'tn.jpg';
											
											$width = 90;
											$height = 80;
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//make prethumnnail (ptn) photo
											$fn = $pfn.'ptn.jpg';
											
											list($width_orig, $height_orig) = getimagesize($ogp);
											
											if ($width_orig>=$height_orig) {
												$height = 60;
												$width = $width_orig;
											} else {
												$width = 60;
												$height = $height_orig;
											}
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//use ptn for next one
											$ogp = "../../users/$id/meepics/".$fn;
											
											//make MeePic photo
											$fn = $pfn.'.jpg';
											
											list($width_orig, $height_orig) = getimagesize($ogp);
											
											if ($width_orig>=$height_orig) {
												$y = 5;
												$x = ($width_orig/2)-25;
											} else {
												$x = 5;
												$y = ($height_orig/2)-25;
											}
												
											$image_l = imagecreatetruecolor(50, 50);
											$image = imagecreatefromjpeg($ogp);
											imagecopyresampled($image_l, $image, 0, 0,  $x, $y, $width_orig, $height_orig, $width_orig, $height_orig);
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											$update = @mysql_query ("UPDATE users SET durl_x='$x', durl_y='$y' WHERE user_id='$id' LIMIT 1");
											
											//use default for next one
											$filename = "../../users/$id/meepics/".$fn;
											
											//make mini photo
											$fn = $pfn.'m.jpg';
											$width = 36;
											$height = 36;
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
										echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your MeePic has been saved.</td></tr></table>
										<script type="text/javascript">
											setTimeout("window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmeepic.php?action=edtthumb\');", 800);
										</script>';
									} else {
										echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">A file system error occurred.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
										reporterror('meefile/editmeepic.php', 'saving MeePic taken from photo albums', 'not able to copy');
										echo '</td></tr></table>
										<script type="text/javascript">
											setTimeout("parent.PopBox.close();", 2800);
										</script>';
									}
									imagedestroy($image_l);
									imagedestroy($image);
					} else {
						echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">A file system error occurred: you don\'t own this photo.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
						reporterror('meefile/editmeepic.php', 'saving MeePic taken from album photos', 'did not own photo');
						echo '</td></tr></table>
						<script type="text/javascript">
							setTimeout("parent.PopBox.close();", 2800);
						</script>';
					}
									
	} elseif (($_GET['action']=='setui')&&($_POST['yes'])) {
		
		//set ap photo
			$uiid = strip_tags(escape_data($_GET['uiid']));
			
					$photo = @mysql_fetch_array ($photoq = @mysql_query ("SELECT img_url FROM user_imgs WHERE ui_id=$uiid AND u_id='$id' LIMIT 1"), MYSQL_ASSOC);
						
					if (mysql_num_rows($photoq)>0) {
						
						//format image
						$filename = '../../'.substr($photo['img_url'], 0, strrpos($photo['img_url'], '.')).'l'.substr($photo['img_url'], strrpos($photo['img_url'], '.'));
						$pfn = strtolower(substr($photo['img_url'], (strrpos($photo['img_url'], 'meepics/')+6)));
						$pfn = strtolower(substr($pfn, 0, strrpos($pfn, '.')));
						
									//make profile photo
									$ogp = $filename;
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

									if (imagejpeg($image_l, "../../users/$id/meepics/$fn", 100)) {
											imagedestroy($image_l);
											imagedestroy($image);
											
										//delete old photo
										$user = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
										if ($user['defaultimg_url']!='images/nophoto.png') {
											$oldimg = '../../'.$user['defaultimg_url'];
											unlink($oldimg);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'m'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'p'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'ptn'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
										}
										$ffn = $pfn.'.jpg';
										$update = @mysql_query ("UPDATE users SET defaultimg_url='users/$id/meepics/$ffn' WHERE user_id='$id' LIMIT 1");
										
											//make prethumnnail (ptn) photo
											$fn = $pfn.'ptn.jpg';
											
											list($width_orig, $height_orig) = getimagesize($ogp);
											
											if ($width_orig>=$height_orig) {
												$height = 60;
												$width = $width_orig;
											} else {
												$width = 60;
												$height = $height_orig;
											}
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//use ptn for next one
											$ogp = "../../users/$id/meepics/".$fn;
											
											//make MeePic photo
											$fn = $pfn.'.jpg';
											
											list($width_orig, $height_orig) = getimagesize($ogp);
											
											if ($width_orig>=$height_orig) {
												$y = 5;
												$x = ($width_orig/2)-25;
											} else {
												$x = 5;
												$y = ($height_orig/2)-25;
											}
												
											$image_l = imagecreatetruecolor(50, 50);
											$image = imagecreatefromjpeg($ogp);
											imagecopyresampled($image_l, $image, 0, 0,  $x, $y, $width_orig, $height_orig, $width_orig, $height_orig);
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											$update = @mysql_query ("UPDATE users SET durl_x='$x', durl_y='$y' WHERE user_id='$id' LIMIT 1");
											
											//use default for next one
											$filename = "../../users/$id/meepics/".$fn;
											
											//make mini photo
											$fn = $pfn.'m.jpg';
											$width = 36;
											$height = 36;
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
										echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your MeePic has been saved.</td></tr></table>
										<script type="text/javascript">
											setTimeout("window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmeepic.php?action=edtthumb\');", 800);
										</script>';
									} else {
										echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">A file system error occurred.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
										reporterror('meefile/editmeepic.php', 'saving MeePic taken from MeePics', 'not able to copy');
										echo '</td></tr></table>
										<script type="text/javascript">
											setTimeout("parent.PopBox.close();", 2800);
										</script>';
									}
									imagedestroy($image_l);
									imagedestroy($image);
					} else {
						echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">A file system error occurred: you don\'t own this photo.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
						reporterror('meefile/editmeepic.php', 'saving MeePic taken from MeePics', 'did not own photo');
						echo '</td></tr></table>
						<script type="text/javascript">
							setTimeout("parent.PopBox.close();", 2800);
						</script>';
					}
									
	} elseif ($_GET['action']=='relsetas') {
		
		//set ap photo
			$apid = strip_tags(escape_data($_GET['apid']));
			
			$photo = @mysql_fetch_array (@mysql_query ("SELECT url, pa_id FROM album_photos WHERE ap_id='$apid' LIMIT 1"), MYSQL_ASSOC);
			$paid = $photo['pa_id'];
						
				//get crop info
				$csx = strip_tags(escape_data($_GET['csx']));
				$csy = strip_tags(escape_data($_GET['csy']));
				$csh = strip_tags(escape_data($_GET['csh']));
				$csw = strip_tags(escape_data($_GET['csw']));
				
					//test crop size
					if (($csw>186)&&($csh>140)) {
				
						//set function
						function getSafeFileName($fileName) {
							$savepath = "../../users/$id/meepics/";
							
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
						
						$fext = strtolower(substr($filename, strrpos($filename, '.')));
						$pfn = md5(uniqid(rand(), true));
						
						$tmpfn = getSafeFileName($pfn.'.jpg');
						
						$pfn = strtolower(substr($tmpfn, 0, strrpos($tmpfn, '.')));
						
									//make large photo
									$fn = $pfn.'l.jpg';
									
									list($width_orig, $height_orig) = getimagesize($filename);
									
									$image_l = imagecreatetruecolor($csw, $csh);
									$bg = imagecolorallocate($image_l, 255, 255, 255);
									imagefill($image_l, 0, 0, $bg);
									$image = imagecreatefromjpeg($filename);
									
									imagecopyresampled($image_l, $image, 0, 0, $csx, $csy, $csw, $csh, $csw, $csh);
									
									if (imagejpeg($image_l, "../../users/$id/meepics/$fn", 100)) {
											imagedestroy($image_l);
											imagedestroy($image);
											
										//delete old photo
										$user = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
										if ($user['defaultimg_url']!='images/nophoto.png') {
											$oldimg = '../../'.$user['defaultimg_url'];
											unlink($oldimg);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'m'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'p'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
											$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'ptn'.substr($user['defaultimg_url'], -4);
											unlink($oldimgt);
										}
										$ffn = $pfn.'.jpg';
										$update = @mysql_query ("INSERT INTO user_imgs (u_id, url, time_stamp) VALUES ('$id', 'users/$id/meepics/$ffn', NOW())");
										$update = @mysql_query ("UPDATE users SET defaultimg_url='users/$id/meepics/$ffn' WHERE user_id='$id' LIMIT 1");
											
											//make profile photo
											$ogp = "../../users/$id/meepics/$fn";
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//make tn
											$fn = $pfn.'tn.jpg';
											
											$width = 90;
											$height = 80;
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//make prethumnnail (ptn) photo
											$fn = $pfn.'ptn.jpg';
											
											list($width_orig, $height_orig) = getimagesize($ogp);
											
											if ($width_orig>=$height_orig) {
												$height = 60;
												$width = $width_orig;
											} else {
												$width = 60;
												$height = $height_orig;
											}
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//use ptn for next one
											$ogp = "../../users/$id/meepics/".$fn;
											
											//make MeePic photo
											$fn = $pfn.'.jpg';
											
											list($width_orig, $height_orig) = getimagesize($ogp);
											
											if ($width_orig>=$height_orig) {
												$y = 5;
												$x = ($width_orig/2)-25;
											} else {
												$x = 5;
												$y = ($height_orig/2)-25;
											}
												
											$image_l = imagecreatetruecolor(50, 50);
											$image = imagecreatefromjpeg($ogp);
											imagecopyresampled($image_l, $image, 0, 0,  $x, $y, $width_orig, $height_orig, $width_orig, $height_orig);
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											$update = @mysql_query ("UPDATE users SET durl_x='$x', durl_y='$y' WHERE user_id='$id' LIMIT 1");
											
											//use default for next one
											$filename = "../../users/$id/meepics/".$fn;
											
											//make mini photo
											$fn = $pfn.'m.jpg';
											$width = 36;
											$height = 36;
											
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
											imagejpeg($image_l, "../../users/$id/meepics/$fn", 100);
											
										echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your MeePic has been saved.</td></tr></table>
										<script type="text/javascript">
											setTimeout("window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmeepic.php?action=edtthumb\');", 800);
										</script>';
									} else {
										echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">A file system error occurred.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
										reporterror('meefile/editmeepic.php', 'saving MeePic taken from upload', 'not able to copy');
										echo '</td></tr></table>
										<script type="text/javascript">
											setTimeout("parent.PopBox.close();", 2800);
										</script>';
									}
									imagedestroy($image_l);
									imagedestroy($image);
					} else {
						echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">An error occurred: crop area is too small.<tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
						reporterror('meefile/editmeepic.php', 'editing MeePic', 'crop area too small');
						echo '</td></tr></table>';
					}
									
	} elseif (($_GET['action']=='delete')&&($_POST['delete'])) {
			//delete old photo
			$user = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);
			if ($user['defaultimg_url']!='images/nophoto.png') {
				$oldimg = '../../'.$user['defaultimg_url'];
				unlink($oldimg);
				$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'m'.substr($user['defaultimg_url'], -4);
				unlink($oldimgt);
				$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'p'.substr($user['defaultimg_url'], -4);
				unlink($oldimgt);
				$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'ptn'.substr($user['defaultimg_url'], -4);
				unlink($oldimgt);
				$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'l'.substr($user['defaultimg_url'], -4);
				unlink($oldimgt);
				$oldimgt = '../../'.substr($user['defaultimg_url'], 0, -4).'tn'.substr($user['defaultimg_url'], -4);
				unlink($oldimgt);
			}
			$oldurl = $user['defaultimg_url'];
			$delete = @mysql_query ("DELETE FROM user_imgs WHERE u_id='$id' AND url='$oldurl'");
			$update = @mysql_query ("UPDATE users SET defaultimg_url='images/nophoto.png' WHERE user_id='$id' LIMIT 1");
			
			echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" class="paragraph60">Your MeePic has been deleted.</td></tr></table>
				<script type="text/javascript">
					setTimeout("parent.location.reload();", 1400);
				</script>';
	} else {
	
			echo '<div align="center">
			<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" style="padding-right: 4px;">
				<div class="blockbtn" style="width: 132px; height: 96px;" onclick="gotopage(\'mainarea\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=photos\');">
					<div align="left" class="p24">My Photos</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Choose a photo from your photos.</div>
				</div>
			</td><td align="left" valign="top" style="border-left: 1px solid #E4E4E4; padding-left: 4px; padding-right: 4px;">
				<div class="blockbtn" style="width: 132px; height: 96px;" onclick="gotopage(\'mainarea\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=upload\');">
					<div align="left" class="p24">Upload</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Choose a photo from your computer.</div>
				</div>
			</td><td align="left" valign="top" style="border-left: 1px solid #E4E4E4; padding-left: 4px; padding-right: 4px;">
				<div class="blockbtn" style="width: 132px; height: 96px;" onclick="window.location.replace(\''.$baseincpat.'externalfiles/meefile/editmeepic.php?action=edtthumb\');">
					<div align="left" class="p24">Thumbnail</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Edit your MeePic thumbnail.</div>
				</div>
			</td><td align="left" valign="top" style="border-left: 1px solid #E4E4E4; padding-left: 4px;">
				<div class="blockbtn" style="width: 132px; height: 96px;" onclick="gotopage(\'mainarea\', \''.$baseincpat.'externalfiles/meefile/editmeepic.php?choose=delete\');">
					<div align="left" class="p24">Delete</div>
					<div align="left" class="subtext" style="padding-top: 2px;">Remove your current MeePic.</div>
				</div>
			</td></tr></table>
			</div>';
	}

	echo '</div>';

include ('../../../externals/header/footer-pb.php');
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>