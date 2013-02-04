<?php
if (mysql_result(mysql_query("SELECT COUNT(*) FROM photo_albums WHERE pa_id='$paid' AND u_id='$id' LIMIT 1"), 0)>0) {//test for admin

$uid = $id;

echo '<div align="left">
<div align="left" id="taggerinstructions" style="margin-bottom: 24px;">Tagging mode is activated.<br /><span class="subtext" style="font-size: 14px;">Click on the photo to move the tagger, start typing a name, select the name from the suggestion list, and the tag will be added.</span></div>';

	$photos = @mysql_query ("SELECT ap_id, url, caption FROM album_photos WHERE pa_id='$paid' ORDER BY p_num ASC");
	$i = 0;
	while ($photo = @mysql_fetch_array ($photos, MYSQL_ASSOC)) {
		$apid = $photo['ap_id'];
		echo '<div align="left" id="ap'.$apid.'" style="padding-bottom: 24px;">
			<table cellpadding="0" cellspacing="0"><tr><td align="center" valign="top" width="340px">';
				list($ap_width, $ap_height) = getimagesize(substr($photo['url'], 0, strrpos($photo['url'], '.')).'ltn'.substr($photo['url'], strrpos($photo['url'], '.')));
				$borderoffset = 2;
				echo '<div align="center" id="ap'.$apid.'_cont" style="width: '.($ap_width+($borderoffset*2)).'px; height: '.($ap_height+($borderoffset*2)).'px; position: relative;">
					<div align="left" style="position: absolute; top: 0px; left: 0px;"><img src="'.$baseincpat.substr($photo['url'], 0, strrpos($photo['url'], '.')).'ltn'.substr($photo['url'], strrpos($photo['url'], '.')).'" class="pictn"/></div>
				<div align="left" id="ap'.$apid.'_tagcont" style="position: absolute; top: 0px; left: 0px; width: '.$ap_width.'px; height: '.$ap_height.'px;">';
					$tags = mysql_query("SELECT apt_id, u_id, type, x, y FROM ap_tags WHERE ap_id='$apid' ORDER BY apt_id ASC");
					while ($tag = mysql_fetch_array ($tags, MYSQL_ASSOC)) {
						echo '<div style="top: '.floor($tag['y']/2).'px; left: '.floor($tag['x']/2).'px; width: 54px; height: 54px; position: absolute; z-index: 10; display: block;">
							<div align="left" id="'.$apid.'apt'.$tag['u_id'].'" style="border: 1px solid #36F; display: none;"><div align="left" style="width: 50px; height: 50px; border: 1px solid #fff;"></div></div>
							<div align="center" id="'.$apid.'apt'.$tag['u_id'].'_name" style="top: 54px; width: 104px; position: absolute; display: none; background-color: #fff; border: 1px solid #C5C5C5; padding: 2px;">'; loadpersonname($tag['u_id']); echo'</div>
						</div>';
					}
				echo '</div>
				<div align="left" id="ap'.$apid.'_taggerhit" style="position: absolute; top: 0px; left: 0px; width: '.$ap_width.'px; height: '.$ap_height.'px; cursor: crosshair; z-index: 100;" onclick="tagger.setObj(\'ap'.$apid.'\');tagger.movetag(event);"></div>
			</div>
			</td><td align="left" valign="top" style="padding-left: 24px; padding-top: 8px;">
				<div align="left" class="p24">Caption</div>
				<div align="left" id="apcptncont'.$apid.'" style="padding-top: 4px; padding-left: 26px; width: 392px;">
					<div align="left" class="cmtplaceholder" onclick="this.destroy(); var newElem = new Element(\'div\', {\'id\': \'apeditcptncont'.$apid.'\', \'align\': \'left\'});newElem.inject($(\'apcptncont'.$apid.'\'), \'bottom\');gotopage(\'apeditcptncont'.$apid.'\', \''.$baseincpat.'externalfiles/photos/editalbum-editp-caption.php?id='.$apid.'\');"  style="margin-bottom: 12px; width: 354px;';
							if ($photo['caption']!=''){echo' color: #000;">'.$photo['caption'];}else{echo'">type a caption for this photo here';}
						echo '</div>
				</div>
				<div align="left" class="p24">Tags</div>
				<div align="left" id="ap'.$apid.'_taglist" style="padding-top: 4px; padding-left: 28px;">';
					include('grabtags.php');
				echo '</div>
			</td><td align="left" valign="top" style="padding-left: 40px; padding-top: 12px;">
				<div align="left" style="padding-top: 6px;"><input type="button" value="set as MeePic" onclick="" style="padding-left: 8px; padding-right: 8px;"/></div>
				<div align="left" style="padding-top: 12px;"><input type="button" value="visibility" onclick="PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/editapvis.php?id='.$apid.'\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
				<div align="left" style="padding-top: 12px;"><input type="button" align="center" valign="center" value="delete" onclick="parent.PopBox.fromElement(this , {url: \''.$baseincpat.'externalfiles/photos/deleteap.php?id='.$apid.'&rel=editalbum\', size: {x: 660, y: 340}, handler:\'iframe\'});"/></div>
			</td></tr></table>
		</div>';
		$i++;
	}
	if ($i==0) {
		echo '<div align="left" style="padding-bottom: 12px;">none</div>';
	}

echo '</div>';

}
?>