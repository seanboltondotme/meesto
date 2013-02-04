<?php
if (!isset($id)) {
	require_once('../../../externals/sessions/db_sessions.inc.php');
	$id = $_SESSION['user_id'];
	require_once ('../../../externals/general/includepaths.php');
	require_once ('../../../externals/general/functions.php');
	$minses = true;
}
				
//swf upload params and settings
ini_set("html_errors", "0");
if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
	echo "ERROR:invalid upload";
	session_write_close();
	exit();
}
//end swf upload params and settings
				
				$mimetest = getimagesize($_FILES["Filedata"]["tmp_name"]);
				$allowedmime = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/x-png');
				if (in_array($mimetest['mime'], $allowedmime)) {
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
						$filename = $_FILES["Filedata"]['tmp_name'];
						$fext = strtolower(substr($_FILES["Filedata"]['name'], strrpos($_FILES["Filedata"]['name'], '.')));
						$pfn = md5(uniqid(rand(), true));
						
						$tmpfn = getSafeFileName($pfn.'.jpg');
						
						$pfn = strtolower(substr($tmpfn, 0, strrpos($tmpfn, '.')));
						
									//make large photo
									$fn = $pfn.'l.jpg';
									
									if (move_uploaded_file($filename, "../../users/$id/meepics/$fn")) {
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
										$update = mysql_query ("INSERT INTO user_imgs (u_id, img_url, time_stamp) VALUES ('$id', 'users/$id/meepics/$ffn', NOW())");
										$update = mysql_query ("UPDATE users SET defaultimg_url='users/$id/meepics/$ffn' WHERE user_id='$id' LIMIT 1");
											
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

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>