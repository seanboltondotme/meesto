<?php
require_once ('../../../externals/general/includepaths.php');

$pjs = '<script type="text/javascript" src="'.$baseincpat.'externalfiles/attach/m.js"></script>
<link rel="stylesheet" href="'.$baseincpat.'externalfiles/autocompleter/TextboxList.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.css" type="text/css" media="screen" charset="utf-8" />
	<script src="'.$baseincpat.'externalfiles/autocompleter/GrowingInput.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.js" type="text/javascript" charset="utf-8"></script>		
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.js" type="text/javascript" charset="utf-8"></script>
	<script src="'.$baseincpat.'externalfiles/autocompleter/TextboxList.Autocomplete.Binary.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css" media="screen">
			.postoptlink {
			color: #36F;
			line-height: 20px;
			cursor: pointer;
			}
			
			.postoptlink a {
			color: #36F;
			line-height: 20px;
			text-decoration: none;
			}
			
			.postoptlink a:hover {
			color: #36F;
			line-height: 20px;
			text-decoration: underline;
			}
			
			.postoptlink a:visited {
			color: #36F;
			line-height: 20px;
			text-decoration: none;
			}
			
		.postoptlinkmrkr {
			background-color: #36F;
			height: 6px;
			width: 6px;
		}
		.textboxlist-loading { background: url(\''.$baseincpat.'images/spinner.gif\') no-repeat 416px center; }
		.form_tags .textboxlist, #form_peeple .textboxlist { width: 440px; }
	</style>';
$pdrjs = 'var t4 = new TextboxList(\'form_peeplenames_input\', {unique: true, onFocus: function(){$(\'sugspcr\').set(\'styles\',{\'display\':\'block\'});}, onBlur: function(){$(\'sugspcr\').set(\'styles\',{\'display\':\'none\'});}, plugins: {autocomplete: {placeholder: \'start typing the name of one of your peeple to receive suggestions\'}}});
			t4.container.addClass(\'textboxlist-loading\');	
			new Request.JSON({url: \''.$baseincpat.'externalfiles/autocompleter/grabmypeeple.php\', onSuccess: function(r){
				t4.plugins[\'autocomplete\'].setValues(r);
				t4.container.removeClass(\'textboxlist-loading\');
			}}).send();
			$(\'peepinputcont\').set(\'styles\',{\'display\':\'none\'});';
			
$fullmts = true;
require_once ('../../../externals/general/functions.php');

$uid = escape_data($_GET['id']);

$ifname = 'convomsgwrite'.$uid;
include ('../../../externals/header/header-iframe.php');

$uinfo = @mysql_fetch_array (@mysql_query ("SELECT first_name FROM users WHERE user_id='$uid' LIMIT 1"), MYSQL_ASSOC);
$fn = $uinfo['first_name'];

if (isset($_POST['send'])) {
//save
	
	$errors = NULL;
	
	if (isset($_POST['msg']) && ($_POST['msg'] != 'type here to talk to '.$fn.'...')) {
		$msg = escape_form_data($_POST['msg']);
	} else {
		$errors[] = 'no msg content';
	}
	
	if (isset($_POST['type']) && ($_POST['type'] != '')) {
		$type = escape_data($_POST['type']);
	} else {
		$type = '';
	}
	
	if (empty($errors)) {
		if ($type!='') {
			$atchtype = '';
				if ($type=='upld_p') {
					$atchtype = 'upld_p';
					$atchid = escape_data($_POST['atchid']);
				} elseif ($type=='prevlnk_img') {
					$atchtype = 'lnk_img';
					$atchid = escape_data($_POST['atchid']);
				} elseif ($type=='prevlnk_site') {
					$atchtype = 'lnk_site';
					$atchid = escape_data($_POST['atchid']);
				} elseif ($type=='image') {
					$atchtype = 'lnk_img';
					$url = escape_data($_POST['url']);
					$host = escape_data($_POST['host']);
					//test image type
					$mimetest = getimagesize($url);
					$allowedmime = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png',
		'image/x-png');
					if (in_array($mimetest['mime'], $allowedmime)) {
						//set function
						function getSafeFileName($fileName) {
							$savepath = "../../users/$id/attachments/";
							
							$newFileName = $fileName;
							while (file_exists($absGalleryPath . $newFileName)) {
								$fext = substr($fileName, strrpos($fileName, '.'));
								$pfn = md5(uniqid(rand(), true));
								$newFileName = strtolower($pfn.$fext);
							}
							return $newFileName;	
						}
						
						//format image
						$filename = $url;
						$fext = strtolower(substr($url, strrpos($url, '.')));
						$pfn = md5(uniqid(rand(), true));
						
						$tmpfn = getSafeFileName($pfn.'.jpg');
						
						$pfn = strtolower(substr($tmpfn, 0, strrpos($tmpfn, '.')));
						
									//make tn
									$fn = $pfn.'.jpg';
											
									$width = 90;
									$height = 80;
									
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
									
									if (imagejpeg($image_l, "../../users/$id/attachments/$fn", 100)) {
											imagedestroy($image_l);
											imagedestroy($image);
											$thumburl = "users/$id/attachments/$fn";	
									} else {
										$thumburl = '';	
									}
					$addlink = mysql_query("INSERT INTO user_links (u_id, type, url, host, tn_url, time_stamp) VALUES ('$id', 'img', '$url', '$host', '$thumburl', NOW())");
					$atchid = mysql_insert_id();
					}
				} elseif ($type=='html') {
					$atchtype = 'lnk_site';
					$url = escape_data($_POST['url']);
					$title = escape_data($_POST['title']);
					$host = escape_data($_POST['host']);
					$details = escape_form_data($_POST['details']);
					$thumbsexist = escape_data($_POST['thumbsexist']);
					$tnurl = escape_data($_POST['tnurl']);
					if ($thumbsexist=='y') {
						$tnurl = escape_data($_POST['tnurl']);
						//test image type
						$mimetest = getimagesize($tnurl);
						$allowedmime = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png',
			'image/x-png');
						if (in_array($mimetest['mime'], $allowedmime)) {
							//set function
							function getSafeFileName($fileName) {
								$savepath = "../../users/$id/attachments/";
								
								$newFileName = $fileName;
								while (file_exists($absGalleryPath . $newFileName)) {
									$fext = substr($fileName, strrpos($fileName, '.'));
									$pfn = md5(uniqid(rand(), true));
									$newFileName = strtolower($pfn.$fext);
								}
								return $newFileName;	
							}
							
							//format image
							$filename = $tnurl;
							$fext = strtolower(substr($tnurl, strrpos($tnurl, '.')));
							$pfn = md5(uniqid(rand(), true));
							
							$tmpfn = getSafeFileName($pfn.'.jpg');
							
							$pfn = strtolower(substr($tmpfn, 0, strrpos($tmpfn, '.')));
							
										//make tn
										$fn = $pfn.'.jpg';
												
										$width = 90;
										$height = 80;
										
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
										
										if (imagejpeg($image_l, "../../users/$id/attachments/$fn", 100)) {
												imagedestroy($image_l);
												imagedestroy($image);
												$thumburl = "users/$id/attachments/$fn";	
										} else {
											$thumburl = '';	
										}
								} else {
									$thumburl = '';	
								}
						} else {
							$thumburl = '';	
						}
					$addlink = mysql_query("INSERT INTO user_links (u_id, type, url, title, host, description, tn_url, time_stamp) VALUES ('$id', 'site', '$url', '$title', '$host', '$details', '$thumburl', NOW())");
					$atchid = mysql_insert_id();
				} elseif ($type=='ap') {
					$atchtype = 'ap';
					$atchid = escape_data($_POST['apid']);
				}
			if ($atchtype!='') {
				$createthread = mysql_query("INSERT INTO msg_threads (msg, ref_id, ref_type, time_stamp) VALUES ('$msg', '$atchid', '$atchtype', NOW())");
			}
		} else {
			$createthread = mysql_query("INSERT INTO msg_threads (msg, time_stamp) VALUES ('$msg', NOW())");
		}
		$tid = mysql_insert_id();
		
		//make sender
		$sndr = mysql_query("INSERT INTO msg_owners (t_id, u_id, type, time_stamp) VALUES ('$tid', '$id', 's', NOW())");
		
		//make receiver and nofiy them
		$sndr = mysql_query("INSERT INTO msg_owners (t_id, u_id, type, time_stamp) VALUES ('$tid', '$uid', 'r', NOW())");
		$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$uid', 'msg', '$id', '$tid', NOW())");
		$notifid = mysql_insert_id();
			//check to send email
			if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$uid' AND msg='y' LIMIT 1"), 0)>0) {				
				//send email
				$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$uid' LIMIT 1"), 0);
									
				//params
				$subject = returnpersonnameasid($id, $uid).' sent you a message';
				$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $uid).'</a> sent you <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a message</a>.<br /><br />"'.escape_emailcont_data($_POST['msg']).'"';
									
				include('../../../externals/general/emailer.php');
			}
		
		//get other recievers and nofiy them
		$peeple = explode(",", $_POST['peeplenames']);
		if (isset($_POST['peeplenames'])&&($_POST['peeplenames']!=0)) {
			foreach ($peeple as $ruid) {
				$ruid = escape_data($ruid);
				if ($ruid!=$uid) {
					$sndr = mysql_query("INSERT INTO msg_owners (t_id, u_id, type, time_stamp) VALUES ('$tid', '$ruid', 'r', NOW())");
					//make notif
					$notif = mysql_query("INSERT INTO notifications (u_id, type, s_id, ref_id, time_stamp) VALUES ('$ruid', 'msg', '$id', '$tid', NOW())");
					$notifid = mysql_insert_id();
						//check to send email
						if (mysql_result(mysql_query("SELECT COUNT(*) FROM user_e_notif WHERE u_id='$ruid' AND msg='y' LIMIT 1"), 0)>0) {				
							//send email
							$to = mysql_result(mysql_query("SELECT email FROM users WHERE user_id='$ruid' LIMIT 1"), 0);
												
							//params
							$subject = returnpersonnameasid($id, $ruid).' sent you a message';
							$emailercontent = '<a href="'.$baseincpat.'meefile.php?id='.$id.'">'.returnpersonnameasid($id, $ruid).'</a> sent you <a href="'.$baseincpat.'enotif.php?id=n'.$notifid.'">a message</a>.<br /><br />"'.escape_emailcont_data($_POST['msg']).'"';
												
							include('../../../externals/general/emailer.php');
						}
				}
			}
		}
		
		echo '<script type="text/javascript">
				setTimeout("parent.gotopage(\'maincontent\', \''.$baseincpat.'externalfiles/meefile/grabmsgs.php?id='.$uid.'\');", \'0\');
			</script>';
	} else {
		echo '<script type="text/javascript">
				setTimeout("parent.gotopage(\'maincontent\', \''.$baseincpat.'externalfiles/meefile/grabmsgs.php?id='.$uid.'\');", \'3200\');
			</script>';
		echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('meefile/writemsg.php', 'writting msg', $errors);
	}
	
} else {

$myinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);

echo '<form action="'.$baseincpat.'externalfiles/meefile/writemsg.php?id='.$uid.'" method="post">
	
<div align="left" style="padding-bottom: 20px;">

	<div align="left">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px"><img src="'.$baseincpat.''.$myinfo['defaultimg_url'].'" /></td><td align="left" valign="top" width="458px" style="padding-left: 12px;">
			<div align="left" id="peepinputcont" style="padding-bottom: 12px;">
				<div align="left" style="padding-bottom: 2px;">this message will be sent to '.$fn.' and...</div>
				<div id="form_peeple">
					<input type="text" name="peeplenames" value="" id="form_peeplenames_input"/>
				</div>
			</div>
			<div align="left">
				<textarea name="msg" cols="50" rows="2" onfocus="if (trim(this.value) == \'type here to talk to '.$fn.'...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type here to talk to '.$fn.'...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'msgovertxtalrt\');" class="inputplaceholder">type here to talk to '.$fn.'...</textarea>
				<div id="msgovertxtalrt" align="left" class="palert"></div>
			</div><div align="left" style="padding-top: 2px; font-size: 13px;"><table cellpadding="0" cellspacing="0" class="postoptlink" onclick="this.parentNode.destroy(); $(\'peepinputcont\').set(\'styles\',{\'display\':\'block\'});"><tr><td align="left" valign="center"><div align="left" class="postoptlinkmrkr"></div></td><td align="left" valign="center" style="padding-left: 4px;">click here to send this message to more than just '.$fn.'</td></tr></table></div>
			</div>
		</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">
			<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
			<div id="btngrp" align="left">
				<div id="btnattach"><input type="button" align="center" valign="center" value="attach" style="padding-left: 15px; padding-right: 15px;" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/writemsg-attach.php?uid='.$uid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
				<div id="btnsbmt" style="margin-top: 12px;"><input type="submit" id="submit" value="send" name="send" onclick="$(\'btngrp\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/></div>
			</div>
		</td></tr></table>
	</div>
	
	<div align="left" id="attachments"></div>
	
	<div align="left" id="sugspcr" style="height: 120px; display: none;"></div>

</div>
</form>';
}

include ('../../../externals/header/footer-iframe.php');
?>