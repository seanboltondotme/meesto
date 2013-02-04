<?php
require_once('../../../externals/sessions/db_sessions.inc.php');
$id = $_SESSION['user_id'];
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');
$ifname = 'postfeed'.$id;
$fullmts = true;
$pjs = '<script type="text/javascript" src="'.$baseincpat.'externalfiles/attach/m.js"></script>';
include ('../../../externals/header/header-iframe.php');

if (isset($_GET['mf'])&&($_GET['mf']=='y')) {
	$ismeefile = true;
} else {
	$ismeefile = false;
}

if (isset($_POST['share'])) {
//save
	
	$errors = NULL;
	//need to do error checking to make sure can't make a blank post !important
	
	if (isset($_POST['msg']) && ($_POST['msg'] != "share what's on your mind...")) {
		$msg = escape_form_data($_POST['msg']);
	} else {
		$msg = '';
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
				$createpost = mysql_query("INSERT INTO feed (u_id, type, msg, ref_id, ref_type, time_stamp) VALUES ('$id', 'stndrd', '$msg', '$atchid', '$atchtype', NOW())");
			}
		} else {
			$createpost = mysql_query("INSERT INTO feed (u_id, type, msg, time_stamp) VALUES ('$id', 'stndrd', '$msg', NOW())");
		}
		$fid = mysql_insert_id();
		
		if (isset($_POST['publicvis'])) {
			if (mysql_result (mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='pub' AND sub_type IS NOT NULL LIMIT 1"), 0)<1) {
				$addvis = mysql_query("INSERT INTO feed_vis (f_id, type, sub_type, time_stamp) VALUES ('$fid', 'pub', 'y', NOW())");
			}
		}
		
		if (isset($_POST['streamvis'])) {
			foreach ($_POST['streamvis'] as $streamvis) {
				$streamvis = escape_data($streamvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='strm' AND sub_type='$streamvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO feed_vis (f_id, type, sub_type, time_stamp) VALUES ('$fid', 'strm', '$streamvis', NOW())");
				}
			}
		}
		
		if (isset($_POST['chanvis'])) {
			foreach ($_POST['chanvis'] as $chanvis) {
				$chanvis = escape_data($chanvis);
				if (mysql_result (mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='chan' AND ref_id='$chanvis' LIMIT 1"), 0)<1) {
					$addvis = mysql_query("INSERT INTO feed_vis (f_id, type, ref_id, time_stamp) VALUES ('$fid', 'chan', '$chanvis', NOW())");
				}
			}
		}
		
		$peeple = explode(",", $_POST['peeplenames']);
		if (isset($_POST['peeplenames'])) {
			foreach ($peeple as $visuid) {
				$visuid = escape_data($visuid);
				if (($visuid!=0)&&(mysql_result (mysql_query("SELECT COUNT(*) FROM feed_vis WHERE f_id='$fid' AND type='user' AND ref_id='$visuid' LIMIT 1"), 0)<1)) {
					$addvis = mysql_query("INSERT INTO feed_vis (f_id, type, ref_id, time_stamp) VALUES ('$fid', 'user', '$visuid', NOW())");
				}
			}
		}
		
		echo '<div align="center" id="sbmtstat" class="p18">Your post has been created.</div>
			<script type="text/javascript">
				setTimeout("parent.gotopage(\'maincontent\', \''.$baseincpat.'externalfiles/';
				 if($ismeefile){
					 echo 'meefile/grabfeed.php?id='.$id;
				 } else {
					 echo 'home/grabfeed.php?';
				 }
				 echo '\'+parent.backcontrol.getState());", \'0\');
				setTimeout("$(\'sbmtstat\').destroy();", \'2400\');
			</script>';
	} else {
		echo '<script type="text/javascript">
				setTimeout("$(\'sbmtstat\').destroy();", \'3200\');
			</script>';
		echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
		reporterror('home/postfeed.php', 'writting msg', $errors);
	}
	
}

$myinfo = @mysql_fetch_array (@mysql_query ("SELECT defaultimg_url FROM users WHERE user_id='$id' LIMIT 1"), MYSQL_ASSOC);

//load in default vis
	if (mysql_result (mysql_query("SELECT COUNT(*) FROM defvis_feed WHERE u_id='$id' AND type='pub' LIMIT 1"), 0)>0) {
		$ispub = true;
	} else {
		$ispub = false;
	}
	$plstrm = mysql_query("SELECT sub_type FROM defvis_feed WHERE u_id='$id' AND type='strm'");
	$plstrms = array();
	while ($plstrminfo = mysql_fetch_array ($plstrm, MYSQL_ASSOC)) {
		array_push($plstrms, $plstrminfo['sub_type']);
	}
	$plchan = mysql_query("SELECT ref_id FROM defvis_feed WHERE u_id='$id' AND type='chan'");
	$plchans = array();
	while ($plchaninfo = mysql_fetch_array ($plchan, MYSQL_ASSOC)) {
		array_push($plchans, $plchaninfo['ref_id']);
	}

echo '<form action="'.$baseincpat.'externalfiles/home/postfeed.php?'; if($ismeefile){echo'mf=y';} echo'" method="post">
	
<div align="left" style="padding-bottom: 20px;">

	<div align="left">
		<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="520px">
				<div align="left">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="50px"><img src="'.$baseincpat.''.$myinfo['defaultimg_url'].'" /></td><td align="left" valign="top" width="520px" style="padding-left: 12px;">
						<textarea name="msg" cols="58" rows="2" onfocus="if (trim(this.value) == \'share what&rsquo;s on your mind...\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'share what&rsquo;s on your mind...\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'10000\', \'cmtovertxtalrt\');" class="inputplaceholder">share what&rsquo;s on your mind...</textarea>
						<div id="cmtovertxtalrt" align="left" class="palert"></div>
					</td></tr></table>
				</div>
				<div align="left" style="font-size: 13px; padding-top: 4px;">
					<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">visible to:</td><td align="left" valign="center" style="padding-left: 8px;">
						<div align="left" id="pubbtn"'; if(!$ispub){echo'style="display: none;"';} echo'>
							<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'publicvis\').get(\'checked\') == false){$(\'publicvis\').set(\'checked\',true);}else{$(\'publicvis\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="publicvis" name="publicvis" value="y" onclick="if($(\'publicvis\').get(\'checked\') == false){$(\'publicvis\').set(\'checked\',true);}else{$(\'publicvis\').set(\'checked\',false);}"'; if($ispub){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">Make this public. <span class="paragraphA1">(This means everyone on the internet can view it.)</span></td></tr></table>
						</div>
						<div align="left" id="strmbtns"'; if($ispub){echo'style="display: none;"';} echo'>
							<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
								<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[mb]\').get(\'checked\') == false){$(\'streamvis[mb]\').set(\'checked\',true);}else{$(\'streamvis[mb]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[mb]" name="streamvis[mb]" value="mb" onclick="if($(\'streamvis[mb]\').get(\'checked\') == false){$(\'streamvis[mb]\').set(\'checked\',true);}else{$(\'streamvis[mb]\').set(\'checked\',false);}"'; if(in_array('mb', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">my bubble</td></tr></table>
							</td><td align="left" valign="center" style="padding-left: 12px;">
								<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[frnd]\').get(\'checked\') == false){$(\'streamvis[frnd]\').set(\'checked\',true);}else{$(\'streamvis[frnd]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[frnd]" name="streamvis[frnd]" value="frnd" onclick="if($(\'streamvis[frnd]\').get(\'checked\') == false){$(\'streamvis[frnd]\').set(\'checked\',true);}else{$(\'streamvis[frnd]\').set(\'checked\',false);}"'; if(in_array('frnd', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">friends</td></tr></table>
							</td><td align="left" valign="center" style="padding-left: 12px;">
								<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[fam]\').get(\'checked\') == false){$(\'streamvis[fam]\').set(\'checked\',true);}else{$(\'streamvis[fam]\').set(\'checked\',false);}""><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[fam]" name="streamvis[fam]" value="fam" onclick="if($(\'streamvis[fam]\').get(\'checked\') == false){$(\'streamvis[fam]\').set(\'checked\',true);}else{$(\'streamvis[fam]\').set(\'checked\',false);}"'; if(in_array('fam', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">family</td></tr></table>
							</td><td align="left" valign="center" style="padding-left: 12px;">
								<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[prof]\').get(\'checked\') == false){$(\'streamvis[prof]\').set(\'checked\',true);}else{$(\'streamvis[prof]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[prof]" name="streamvis[prof]" value="prof" onclick="if($(\'streamvis[prof]\').get(\'checked\') == false){$(\'streamvis[prof]\').set(\'checked\',true);}else{$(\'streamvis[prof]\').set(\'checked\',false);}"'; if(in_array('prof', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">professional</td></tr></table>
							</td><td align="left" valign="center" style="padding-left: 12px;">
								<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[edu]\').get(\'checked\') == false){$(\'streamvis[edu]\').set(\'checked\',true);}else{$(\'streamvis[edu]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[edu]" name="streamvis[edu]" value="edu" onclick="if($(\'streamvis[edu]\').get(\'checked\') == false){$(\'streamvis[edu]\').set(\'checked\',true);}else{$(\'streamvis[edu]\').set(\'checked\',false);}"'; if(in_array('edu', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">education</td></tr></table>
							</td><td align="left" valign="center" style="padding-left: 12px;">
								<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if($(\'streamvis[aqu]\').get(\'checked\') == false){$(\'streamvis[aqu]\').set(\'checked\',true);}else{$(\'streamvis[aqu]\').set(\'checked\',false);}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="streamvis[aqu]" name="streamvis[aqu]" value="aqu" onclick="if($(\'streamvis[aqu]\').get(\'checked\') == false){$(\'streamvis[aqu]\').set(\'checked\',true);}else{$(\'streamvis[aqu]\').set(\'checked\',false);}"'; if(in_array('aqu', $plstrms)){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;">just met mee</td></tr></table>
							</td></tr></table>
						</div>
						<div align="left" style="display: none;">';
						//get channels
						$channels = @mysql_query("SELECT mpc_id, name FROM my_peeple_channels WHERE u_id='$id' ORDER BY name ASC");
						while ($channel = @mysql_fetch_array ($channels, MYSQL_ASSOC)) {
							echo '<input type="checkbox" id="chanvis['.$channel['mpc_id'].']" name="chanvis['.$channel['mpc_id'].']" value="'.$channel['mpc_id'].'"'; if(in_array($channel['mpc_id'], $plchans)){echo' CHECKED';} echo'/>';
						}
						echo '<input type="text" name="peeplenames" value="';
							//load added people
							$prsns = mysql_query("SELECT ref_id FROM defvis_feed WHERE u_id='$id' AND type='user'");
							$prsn_ct = 0;
							while ($prsn = mysql_fetch_array ($prsns, MYSQL_ASSOC)) {
								if ($prsn_ct>0) {
									echo ',';	
								}
								echo $prsn['ref_id'];
								$prsn_ct++;
							}
						echo '" id="form_peeplenames_input"/>
						</div>
					</td></tr></table>
				</div>
				
				
				<div align="left" id="attachments"></div>
		</td><td align="left" valign="top" width="110px" style="padding-left: 16px;">
			<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
			<div id="btngrp" align="left">
				<div id="btnattach"><input type="button" align="center" valign="center" value="attach" style="padding-left: 15px; padding-right: 15px;" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/postfeed-attach.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
				<div align="btnvis" style="margin-top: 8px;"><input type="button" id="visibility" value="visibility" style="padding-left: 10px; padding-right: 10px;" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/home/editpostvis.php\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
				<div id="btnsbmt" style="margin-top: 8px;"><input type="submit" id="submit" value="share" name="share" onclick="$(\'btngrp\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/></div>
			</div>
		</td></tr></table>
	</div>
</div>
</form>';

include ('../../../externals/header/footer-iframe.php');
?>