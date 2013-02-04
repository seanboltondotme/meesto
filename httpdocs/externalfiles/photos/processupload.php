<?php
require_once('../../../externals/sessions/db_sessions.inc.php');
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');

if (isset($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
} else {
	$id = 0;
}

$paid = escape_data($_GET['id']);

//test ownership
if (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_albums WHERE pa_id='$paid' AND u_id='$id' LIMIT 1"), 0)>0) {

//This variable specifies relative path to the folder, where the gallery with uploaded files is located.
//Do not forget about the slash in the end of the folder name.
$galleryPath = "../../users/$id/photos/$paid/";

$absGalleryPath = realpath($galleryPath) . "/";
$absThumbnailsPath = realpath($galleryPath) . "/";

function saveUploadedFiles()
{
	$id = $_SESSION['user_id'];
	
	global $paid, $absGalleryPath, $absThumbnailsPath;
	
	//Get total number of uploaded files (all files are uploaded in a single package).
	$fileCount = $_POST ["FileCount"];
	
	//Iterate through uploaded data and save the original file, thumbnail, and description.
		//get last pnum
		$pcountq = @mysql_fetch_array(@mysql_query ("SELECT COUNT(*) FROM album_photos WHERE pa_id='$paid'"), MYSQL_NUM);
		$pcount = $pcountq[0];
		$pcount++;
	for ($i = 1; $i <= $fileCount; $i++)
	{
		//Get the first thumbnail
		$thumbnail1Field = "Thumbnail1_" . $i;
		if (!$_FILES[$thumbnail1Field]['size'])
		{
			return;	
		}
		$fileName = basename($_POST["FileName_" . $i]);
		$u_fext = strtolower(substr($fileName, strrpos($fileName, '.')));
		
		$fext = '.jpg';
		$pfn = md5(uniqid(rand(), true));
		
		$fileName = getSafeFileName($pfn.'.jpg');
		
		$fn = strtolower(substr($fileName, 0, strrpos($fileName, '.')));
		
		if (move_uploaded_file($_FILES[$thumbnail1Field]['tmp_name'], $absGalleryPath . $fn.$fext)) {
			$fullfn = "users/$id/photos/$paid/" . $fn.$fext;
			$insert = @mysql_query("INSERT INTO album_photos (pa_id, u_id, p_num, url, time_stamp) VALUES ('$paid', '$id', '$pcount', '$fullfn', NOW())");
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
		}
		
		//Get the second thumbnail
		$thumbnail2Field = "Thumbnail2_" . $i;
		if (!$_FILES[$thumbnail2Field]['size'])
		{
			return;	
		}
		move_uploaded_file($_FILES[$thumbnail2Field]['tmp_name'], $absGalleryPath . $fn.'ltn'.$fext);
		
		//Get the third thumbnail
		$thumbnail3Field = "Thumbnail3_" . $i;
		if (!$_FILES[$thumbnail3Field]['size'])
		{
			return;	
		}
		move_uploaded_file($_FILES[$thumbnail3Field]['tmp_name'], $absGalleryPath . $fn.'tn'.$fext);
		
			//test for album cover | if not then set
				if (mysql_result(mysql_query("SELECT cover_url FROM photo_albums WHERE pa_id='$paid' LIMIT 1"), 0)=='images/nophoto-pa.png') {		
					$cfn = 'users/'.$id.'/photos/'.$paid.'/'.$fn.'tn'.$fext;
					$update = mysql_query ("UPDATE photo_albums SET cover_url='$cfn' WHERE pa_id='$paid' LIMIT 1");
				}
				
		$pcount++;
	}

	
}

//This method verifies whether file with such name already exists 
//and if so, construct safe filename name (to avoid collision).	
function getSafeFileName($fileName)
{
	global $absGalleryPath;
	
	$newFileName = $fileName;
	while (file_exists($absGalleryPath . $newFileName))
	{
		$fext = substr($fileName, strrpos($fileName, '.'));
		$pfn = md5(uniqid(rand(), true));
		$newFileName = strtolower($pfn.$fext);
	}
	return $newFileName;	
}

saveUploadedFiles();

} else {
	//report error
	echo '<table cellpadding="0" cellspacing="0" width="500px"><tr><td align="left" class="paragraph60">An error occurred: you are not an admin or contributor for this photo album.<br />Sorry for the inconvenience, please try again.</td></tr><tr><td align="left" class="paragraph80">If this problem persists please let us know, thank you.</td></tr><tr><td align="center" class="paragraphA1" style="padding-top: 6px;">';
	reporterror('photos/processupload.php', 'recieving uploaded files', 'don\'t own photo album paid='.$paid);
	echo '</td></tr></table>';
}

session_write_close();
exit();
?>