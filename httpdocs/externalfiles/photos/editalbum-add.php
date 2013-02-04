<?php
if (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_albums WHERE pa_id='$paid' AND u_id='$id' LIMIT 1"), 0)>0) {//test for admin

if (isset($_GET['method'])&&($_GET['method']=='flbk')) {
	echo '<div align="left" style="margin-left: 48px; margin-bottom: 24px;">
				<div align="left">An error occurred with our normal uploader. Sorry about that. <span style="font-size: 14px;">';
				reporterror('editalbum-add.php', 'loading swfuploader', 'unable to load, displayed fallback option');
				echo '</span></div><div align="left"><span style="font-size: 14px;">Please <a href="http://www.mozilla.com/plugincheck/" target = "_new">check to make sure you have the most recent version of flash installed</a> and try again.</span><br /><span style="font-size: 14px;">If you\'d like, <span class="palert">this is our fallback option.</span></span></div>
			</div>
			<div align="left" style="margin-left: 48px; margin-bottom: 24px;">Use this to select and upload photos to your photo album. You can rotate the photos before you upload them. <br /><span class="subtext" style="font-size: 14px;">(Note: it may take several seconds for the uploader to appear.)</div><div align="center">';
	
	require_once "externalfiles/ImageUploaderPHP/ImageUploader.class.php";

	$imageUploader = new ImageUploader("ImageUploader1", 800, 400);

    //configure Image Uploader
	require_once ('../externals/general/AurigmaLicense.php');
	
	$imageUploader->setAction("./externalfiles/photos/processupload.php?id=".$paid);
	$imageUploader->setRedirectUrl("./editalbum.php?id=".$paid."&action=edit");
	
	$imageUploader->setShowDebugWindow(true);
	$imageUploader->setPaneLayout("TwoPanes");
	$imageUploader->setBackgroundColor("#ffffff");
	$imageUploader->setFileMask("*.jpg;*.jpeg;*.jpe;*.gif;*.png");
	$imageUploader->setFolderPaneShowDescriptions(false);
	$imageUploader->setButtonSendText("Upload");
	$imageUploader->setPreviewThumbnailBorderColor("#ffffff");
	$imageUploader->setPreviewThumbnailBorderHoverColor("#36f");
	$imageUploader->setUploadView("AdvancedDetails");
	$imageUploader->setAllowAutoRotate(true);
	$imageUploader->setUploadSourceFile(false);
	$imageUploader->setMinImageWidth(330);
	$imageUploader->setMinImageHeight(260);
	
	$imageUploader->setMaxFileCount("6");
	$imageUploader->setMessageMaxFileCountExceededText("You can't upload more than 6 photos at a time.");
	
	$imageUploader->setProgressDialogTitleText("Meesto | Uploading Photos To Album");
	$imageUploader->setMessageBoxTitleText("Meesto | Photo Uploader");
		
	$imageUploader->setUploadThumbnail1FitMode("Fit");
	$imageUploader->setUploadThumbnail1Width(660);
	$imageUploader->setUploadThumbnail1Height(520);
	$imageUploader->setUploadThumbnail1JpegQuality(80);
	$imageUploader->setUploadThumbnail1CopyExif(true);
	
	$imageUploader->setUploadThumbnail2FitMode("Fit");
	$imageUploader->setUploadThumbnail2Width(330);
	$imageUploader->setUploadThumbnail2Height(260);
	$imageUploader->setUploadThumbnail2JpegQuality(80);
	$imageUploader->setUploadThumbnail2CopyExif(true);
	
	$imageUploader->setUploadThumbnail3FitMode("Fit");
	$imageUploader->setUploadThumbnail3Width(90);
	$imageUploader->setUploadThumbnail3Height(80);
	$imageUploader->setUploadThumbnail3JpegQuality(80);
	$imageUploader->setUploadThumbnail3CopyExif(true);
	
    $imageUploader->render();
	
	echo '</div><div align="left" style="margin-left: 48px; margin-top: 32px;">If the uploader is not working correctly, please make sure you have the newest version of Java installed on your computer. <a href="http://www.java.com/getjava">Click here to download Java.</a></div>';
} else {

	echo '<div align="left" style="margin-left: 48px; margin-bottom: 24px;">Select the photos you would like to upload and add to this album.</div>
	<div align="center" style="margin-top: 24px;">
				<form>
					<div id="uploaderbtn" style="cursor: pointer; height: 24px; width: 120px; font-size: 16px; padding-left: 18px; padding-top: none; padding-right: 18px; padding-bottom: none; background-color: #D9D9D9; border: 1px solid #000; -moz-border-radius: 4px; -webkit-border-radius: 4px; -opera-border-radius: 4px; -khtml-border-radius: 4px; border-radius: 4px;">
						<span id="spanButtonPlaceholder"></span>
					</div>
				</form>
				<div id="loader" style="display: none;"><table cellpadding="0" cellspacing="0"><tr><td align="left" valign"center"><img src="'.$baseincpat.'images/spinner.gif" /></td><td align="left" valign"center" style="padding-left: 2px;">uploading...</td></tr><tr><td></td><td align="left" valign"center" style="padding-left: 2px;">do not close or refresh this page</td></tr></table></div>
				<div id="divFileProgressContainer" style="height: 75px;"></div>
			</div>';	
	
}

}
?>