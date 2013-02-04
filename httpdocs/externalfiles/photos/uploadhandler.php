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
				$paid = escape_data($_POST['paid']);
		if (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_albums WHERE pa_id='$paid' AND u_id='$id' LIMIT 1"), 0)>0) {//test for admin
				$mimetest = getimagesize($_FILES["Filedata"]["tmp_name"]);
				$allowedmime = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/x-png');
				if (in_array($mimetest['mime'], $allowedmime)) {
						//set function
						function getSafeFileName($fileName) {
							$savepath = "../../users/$id/photos/$paid/";
							
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
									$fn = $pfn.'.jpg';
									
									if (move_uploaded_file($filename, "../../users/$id/photos/$paid/$fn")) {
										unlink($filename);
										$fullfn = "users/$id/photos/$paid/".$fn;
											//get last pnum
											$pcountq = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM album_photos WHERE pa_id='$paid'"), MYSQL_NUM);
											$pcount = $pcountq[0];
											$pcount++;
										$insert = mysql_query("INSERT INTO album_photos (pa_id, u_id, p_num, url, time_stamp) VALUES ('$paid', '$id', '$pcount', '$fullfn', NOW())");
										$apid = mysql_insert_id();
											//visibility
											$dvis = mysql_query("SELECT type, sub_type, ref_id FROM photo_album_vis WHERE pa_id='$paid'");
											while ($dvisinfo = mysql_fetch_array ($dvis, MYSQL_ASSOC)) {
												$type = $dvisinfo['type'];
												$subtype = $dvisinfo['sub_type'];
												$refid = $dvisinfo['ref_id'];
												if (($type=='pub')||($type=='strm')) {
													$addvis = mysql_query("INSERT INTO album_photos_vis (ap_id, type, sub_type, time_stamp) VALUES ('$apid', '$type', '$subtype', NOW())");
												} else {
													$addvis = mysql_query("INSERT INTO album_photos_vis (ap_id, type, ref_id, time_stamp) VALUES ('$apid', '$type', '$refid', NOW())");
												}
											}
											
											//make large tn
											$ogp = "../../users/$id/photos/$paid/$fn";
											$fn = $pfn.'ltn.jpg';
											
											$width = 330;
											$height = 260;
											
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
											imagejpeg($image_l, "../../users/$id/photos/$paid/$fn", 100);
											
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
											imagejpeg($image_l, "../../users/$id/photos/$paid/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
											//set default
											if (mysql_result(mysql_query("SELECT cover_url FROM photo_albums WHERE pa_id='$paid'"), 0)=='images/nophoto-pa.png') {
													$update = mysql_query("UPDATE photo_albums SET cover_url='users/$id/photos/$paid/$fn' WHERE pa_id='$paid'");
											}
											
										echo 'Complete';
									} else {
										echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">A file system error occurred.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
										reporterror('meefile/editmeepic.php', 'saving MeePic taken from upload', 'not able to copy');
										echo '</td></tr></table>
										<script type="text/javascript">
											setTimeout("parent.PopBox.close();", 2800);
										</script>';
									}
					} else {
						echo '<table cellpadding="0" cellspacing="0" width="350px"><tr><td align="left" class="paragraph60">An error occured: not correct file type.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
						reporterror('meefile/editmeepic.php', 'saving MeePic taken from upload', 'not correct file type');
						echo '</td></tr></table>
						<script type="text/javascript">
							setTimeout("parent.PopBox.close();", 2800);
						</script>';	
					}
		}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>