<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}

$t = escape_data($_GET['t']);
$mtsid = escape_data($_GET['mtsid']);

if (mysql_result (mysql_query("SELECT COUNT(*) FROM meefile_tab mt INNER JOIN meefile_tab_sec mts ON (mts.mts_id='$mtsid' AND mts.mt_id =mt.mt_id) WHERE mt.mt_id='$t' AND mt.u_id='$id' LIMIT 1"), 0)>0) { //test for owner
	
	if (isset($_GET['action']) && ($_GET['action'] == 'iframe')) {
	
	$mtsinfo = @mysql_fetch_array (@mysql_query ("SELECT title, content, show_date, allow_cmts FROM meefile_tab_sec WHERE mts_id='$mtsid' LIMIT 1"), MYSQL_ASSOC);
	
	$pjs = '<script type="text/javascript" src="'.$baseincpat.'externalfiles/attach/m-editmtsec.js"></script>';
	$fullmts = true;
	$ifname = 'mtsedit'.$mtsid;
	include ('../../../externals/header/header-iframe.php');
	
		if (isset($_POST['save'])) {
		//save
			
			$errors = NULL;
			
			if (isset($_POST['cst'.$mtsid]) && ($_POST['cst'.$mtsid] != 'enter name')) {
				$cst = escape_form_data($_POST['cst'.$mtsid]);
			} else {
				$cst = '';
			}
			
			if (isset($_POST['cs'.$mtsid]) && ($_POST['cs'.$mtsid] != 'type whatever you would like')) {
				$cs = escape_form_data($_POST['cs'.$mtsid]);
			} else {
				$cs = '';
			}
			
			if (isset($_POST['showts'.$mtsid])) {
				$showts = 'y';
			} else {
				$showts = '';
			}
			
			if (isset($_POST['allowc'.$mtsid])) {
				$allowc = 'y';
			} else {
				$allowc = '';
			}
			
			$atchmnt_ct = escape_data($_POST['atchmnt_ct']);
			
			if (empty($errors)) {
				if ($atchmnt_ct!=0) {
					$i = 1;
					while ($atchmnt_ct>=$i) {
						$type = escape_data($_POST['atch'.$i.'_type']);
						$atchtype = '';
							if ($type=='upld_p') {
								$atchtype = 'upld_p';
								$atchid = escape_data($_POST['atch'.$i.'_atchid']);
							} elseif ($type=='prevlnk_img') {
								$atchtype = 'lnk_img';
								$atchid = escape_data($_POST['atch'.$i.'_atchid']);
							} elseif ($type=='prevlnk_site') {
								$atchtype = 'lnk_site';
								$atchid = escape_data($_POST['atch'.$i.'_atchid']);
							} elseif ($type=='image') {
								$atchtype = 'lnk_img';
								$url = escape_data($_POST['atch'.$i.'_url']);
								$host = escape_data($_POST['atch'.$i.'_host']);
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
								$url = escape_data($_POST['atch'.$i.'_url']);
								$title = escape_data($_POST['atch'.$i.'_title']);
								$host = escape_data($_POST['atch'.$i.'_host']);
								$details = escape_form_data($_POST['atch'.$i.'_details']);
								$thumbsexist = escape_data($_POST['atch'.$i.'_thumbsexist']);
								$tnurl = escape_data($_POST['atch'.$i.'_tnurl']);
								if ($thumbsexist=='y') {
									$tnurl = escape_data($_POST['atch'.$i.'_tnurl']);
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
								$atchid = escape_data($_POST['atch'.$i.'_apid']);
							}
						$addatchmnt = mysql_query("INSERT INTO mts_atchmnts (mts_id, ref_id, ref_type, time_stamp) VALUES ('$mtsid', '$atchid', '$atchtype', NOW())");
						$i++;
					}
				}
				$update = mysql_query("UPDATE meefile_tab_sec SET title='$cst', content='$cs', show_date='$showts', allow_cmts='$allowc' WHERE mts_id='$mtsid'");
				echo '<script type="text/javascript">
						setTimeout("parent.gotopage(\'mts'.$mtsid.'\', \''.$baseincpat.'externalfiles/meefile/grabmtsec.php?id='.$id.'&t='.$t.'&mtsid='.$mtsid.'\');", \'0\');
					</script>';
			} else {
				echo '<script type="text/javascript">
						setTimeout("parent.gotopage(\'mts'.$mtsid.'\', \''.$baseincpat.'externalfiles/meefile/grabmtsec.php?id='.$id.'&t='.$t.'&mtsid='.$mtsid.'\');", \'3200\');
					</script>';
				echo 'An error occurred, sorry for the inconvenience. Please try again.<br />If this problem persists please let us know, thank you.<br />';
				reporterror('meefile/editbasic.php', 'editing basic info', $errors);
			}
			
		} else {
			
			echo '<form action="'.$baseincpat.'externalfiles/meefile/editmtsec.php?action=iframe&t='.$t.'&mtsid='.$mtsid.'" method="post">
			
			<div align="left" style="padding-bottom: 64px; padding-left: 2px;" onmouseover="$(\'editbtns\').set(\'tween\', {duration: \'short\'}).fade(\'show\');" onmouseout="$(\'editbtns\').set(\'tween\', {duration: \'short\'}).fade(\'hide\');">
				<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top" width="688px">
					<div align="left">
						<input type="text" name="cst'.$mtsid.'" size="64" maxlength="500" autocomplete="off" onfocus="if (trim(this.value) == \'enter name\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'enter name\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" style="font-size: 18px;"';
							if ($mtsinfo['title']!=''){echo'value="'.$mtsinfo ['title'].'"';}else{echo' class="inputplaceholder" value="enter name"';}
							echo '>
					</div><div align="left" style="padding-top: 12px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="center">
							<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if(document.getElementById(\'showts'.$mtsid.'\').checked == false){document.getElementById(\'showts'.$mtsid.'\').checked = true;}else{document.getElementById(\'showts'.$mtsid.'\').checked = false;}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="showts'.$mtsid.'" name="showts'.$mtsid.'" value="showts'.$mtsid.'" onclick="if(document.getElementById(\'showts'.$mtsid.'\').checked == false){document.getElementById(\'showts'.$mtsid.'\').checked = true;}else{document.getElementById(\'showts'.$mtsid.'\').checked = false;}"'; if ($mtsinfo['show_date']=='y'){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;" class="paragraph60">show timestamp</td></tr></table>
						</td><td align="left" valign="center" style="padding-left: 18px;">
							<table cellpadding="0" cellspacing="0" style="cursor: pointer;" onclick="if(document.getElementById(\'allowc'.$mtsid.'\').checked == false){document.getElementById(\'allowc'.$mtsid.'\').checked = true;}else{document.getElementById(\'allowc'.$mtsid.'\').checked = false;}"><tr><td align="left" valign="top" style="padding-top: 1px;"><input type="checkbox" id="allowc'.$mtsid.'" name="allowc'.$mtsid.'" value="allowc'.$mtsid.'" onclick="if(document.getElementById(\'allowc'.$mtsid.'\').checked == false){document.getElementById(\'allowc'.$mtsid.'\').checked = true;}else{document.getElementById(\'allowc'.$mtsid.'\').checked = false;}"'; if ($mtsinfo['allow_cmts']=='y'){echo' CHECKED';} echo'/></td><td align="left" style="padding-left: 4px;" class="paragraph60">allow comments</td></tr></table>
						</td></tr></table>
					</div><div align="left" style="padding-top: 12px;">
						<textarea name="cs'.$mtsid.'" cols="80" rows="6" onfocus="if (trim(this.value) == \'type whatever you would like\') {this.value=\'\';};this.className=\'inputfocus\';" onblur="if (trim(this.value) == \'\') {this.value=\'type whatever you would like\';this.className=\'inputplaceholder\';} else {this.className=\'inputplaceholderblur\';}" onkeyup="testtextlength(this, \'20000\', \'csovertxtalrt'.$mtsid.'\');"';
							if ($mtsinfo['content']!=''){echo'>'.$mtsinfo ['content'];}else{echo' class="inputplaceholder">type whatever you would like';}
						echo '</textarea>
						<div id="csovertxtalrt'.$mtsid.'" align="left" class="palert"></div>
					</div>
					
					<div id="btnattach" align="left" style="margin-top: 8px; margin-bottom: 8px;"><input type="button" align="center" valign="center" value="attach" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmtsec-attach.php?mtsid='.$mtsid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
					<div align="left" id="attachments">';
					//load previously added attachments
					$atchments = mysql_query("SELECT mtsa_id, ref_type, ref_id FROM mts_atchmnts WHERE mts_id='$mtsid' ORDER BY mtsa_id DESC");
					while ($atchment = mysql_fetch_array ($atchments, MYSQL_ASSOC)) {
						$mtsaid = $atchment['mtsa_id'];
						$ref_type = $atchment['ref_type'];
						$ref_id = $atchment['ref_id'];
						echo '<div align="left" id="mtsa'.$mtsaid.'" style="border-bottom: 2px solid #C5C5C5; padding-bottom: 12px; margin-top: 6px;">';
							if ($ref_type=='upld_p') {
								$photo = mysql_fetch_array(mysql_query("SELECT url FROM user_attachments WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
								echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
										<img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn" onclick="PopBox.fromElement(this , {url: \''.$photo['url'].'\'});$(\'pbox-loader\').set(\'styles\',{\'display\':\'none\'});"/>
									</td><td align="center" valign="top" style="padding-left: 6px;">Uploaded Photo</td>';
							} elseif ($ref_type=='lnk_site') {
								$atchinfo = mysql_fetch_array(mysql_query("SELECT url, host, tn_url, title, description FROM user_links WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
								echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
										<div align="center"><a href="'.$atchinfo['url'].'" target="_blank"><img src="'.$baseincpat.$atchinfo['tn_url'].'" class="pictn"/></a></div>
										<div align="center" class="subtext" style="font-size: 10px;">'.$atchinfo['host'].'</div>
									</td><td align="left" valign="top" style="padding-left: 8px;">
										<div align="left"><a href="'.$atchinfo['url'].'" target="_blank">'.$atchinfo['title'].'</a></div>
										'; if($atchinfo['description']!=''){echo'<div align="left" class="subtext">'.$atchinfo['description'].'</div>';} echo'
									</td>';
							} elseif ($ref_type=='lnk_img') {
								$atchinfo = mysql_fetch_array(mysql_query("SELECT url, host, tn_url FROM user_links WHERE ua_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
								echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
										<div align="center"><a href="'.$atchinfo['url'].'" target="_blank"><img src="'.$baseincpat.$atchinfo['tn_url'].'" class="pictn"/></a></div>
										<div align="center" class="subtext" style="font-size: 10px;">'.$atchinfo['host'].'</div>
									</td>';
							} elseif ($ref_type=='ap') {
								$photo = mysql_fetch_array(mysql_query("SELECT pa_id, url FROM album_photos WHERE ap_id='$ref_id' LIMIT 1"), MYSQL_ASSOC);
								echo '<table cellpadding="0" cellspacing="0"><tr><td align="left" valign="top">
										<img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'tn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn"/>
									</td><td align="center" valign="top" style="padding-left: 6px;">Album Photo</td>';
							}
						echo '<td align="left" valign="top" style="padding-left: 24px;"><input type="button" value="remove" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmtsec-deleteatchmnt.php?id='.$mtsaid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></td></tr></table>
						</div>';
					}
					echo '</div>
					
					<div align="center" style="padding-top: 8px;">
						<table cellpadding="0" cellspacing="0"><tr><td align="left">
							<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">loading...</td></tr></table></div>
						</td><td align="left">
							<div id="btnsbmt" align="left">
							<table cellpadding="0" cellspacing="0"><tr><td align="left">
								<input type="submit" id="submit" value="save" name="save" onclick="$(\'btnsbmt\').set(\'styles\',{\'display\':\'none\'});$(\'editbtns\').set(\'styles\',{\'display\':\'none\'});$(\'btnattach\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});"/>
							</td><td align="left" style="padding-left: 12px;">
								<input type="button" id="cancel" value="cancel" onclick="$(\'btnsbmt\').set(\'styles\',{\'display\':\'none\'});$(\'editbtns\').set(\'styles\',{\'display\':\'none\'});$(\'btnattach\').set(\'styles\',{\'display\':\'none\'});$(\'loader\').set(\'styles\',{\'display\':\'block\'});parent.gotopage(\'mts'.$mtsid.'\', \''.$baseincpat.'externalfiles/meefile/grabmtsec.php?id='.$id.'&t='.$t.'&mtsid='.$mtsid.'\');"/>
							</td></tr></table>
							</div>
						</td></tr></table>
					</div>
					
				</td><td align="right" valign="top" width="110px" style="padding-left: 24px;">
						<div align="left" id="editbtns" style="visibility: hidden; zoom: 1; opacity: 0;">
							<div><input type="button" align="center" valign="center" value="visibility" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/editmtsecvis.php?id='.$mtsid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
							<div style="padding-top: 12px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/meefile/deletemts.php?id='.$mtsid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
						</div>
				</td></tr></table>
			</div>
			
			<input type="hidden" id="atchmnt_ct" name="atchmnt_ct" value="0"/>
			
			</form>';
		
		}
			
	include ('../../../externals/header/footer-iframe.php');

	} else {
		echo '<iframe width="100%" height="200px" align="center" id="mtsedit'.$mtsid.'" scrolling="no" style="border: none;" frameborder="0" src="'.$baseincpat.'externalfiles/meefile/editmtsec.php?action=iframe&t='.$t.'&mtsid='.$mtsid.'"></iframe>';
	}

} else { //if not tab owner
	echo '<div class="container" align="left" valign="top" style="padding: 24px;">
		You must be the owner of this tab to use this feature.
	</div>';
}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>