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
					$uid = escape_data($_POST['uid']);
				$mimetest = getimagesize($_FILES["Filedata"]["tmp_name"]);
				$allowedmime = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png', 'image/x-png');
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
						$filename = $_FILES["Filedata"]['tmp_name'];
						$fext = strtolower(substr($_FILES["Filedata"]['name'], strrpos($_FILES["Filedata"]['name'], '.')));
						$pfn = md5(uniqid(rand(), true));
						
						$tmpfn = getSafeFileName($pfn.'.jpg');
						
						$pfn = strtolower(substr($tmpfn, 0, strrpos($tmpfn, '.')));
						
									//make large photo
									$fn = $pfn.'.jpg';
									
									if (move_uploaded_file($filename, "../../users/$id/attachments/$fn")) {
											unlink($filename);
											
										//add to attachment db
										$insert = mysql_query ("INSERT INTO user_attachments (u_id, type, url, time_stamp) VALUES ('$id', 'photo', 'users/$id/attachments/$fn', NOW())");
										$atchid = mysql_insert_id();
										
											//make profile photo
											$ogp = "../../users/$id/attachments/$fn";
											
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
											imagejpeg($image_l, "../../users/$id/attachments/$fn", 100);
											
											imagedestroy($image_l);
											imagedestroy($image);
											
										echo 'convomsgwrite'.$uid.'&users/'.$id.'/attachments/'.$fn.'&'.$atchid;
									} else {
										echo 'error';
										reporterror('meefile/writemsg-attach-uploadhandler.php', 'saving photo attachment taken from upload', 'not able to copy');
									}
									imagedestroy($image_l);
									imagedestroy($image);
					} else {
						echo 'error';
						reporterror('meefile/writemsg-attach-uploadhandler.php', 'saving  photo attachment taken from upload', 'not correct file type');
					}

if (isset($minses)) {
	session_write_close();
	exit();	
}
?>