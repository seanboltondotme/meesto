<?php
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');
$url = escape_data(urldecode($_GET['u']));

if ($url!='') {
	
	$response = array();
	
	//test for compete url : make complete
	if (substr($url, 0, 4)!='http') {
		$url = 'http://'.$url;
	}
	$response['url'] = $url;
	
	//video site api functions
	function get_youtube_thumb($videoid) {
		$thumbnail = false;
		if(($res = get_url("http://gdata.youtube.com/feeds/api/videos/$videoid"))) {
			$vrss = $res['content'];
			$previous = 0;
			if($vrss && preg_match_all('/<media:thumbnail url=["\'](.+?)["\'].*?width=["\'](\d+)["\']/',$vrss,$matches, PREG_SET_ORDER)) {
				foreach ($matches as $match) {
					if ($match[2] > $previous) {
						$thumbnail = $match[1];
						$previous = $match[2];
					}
				}
			}
		}
		return $thumbnail;
	}
	function get_google_thumb($videoid) {
		if(($res = get_url("http://video.google.com/videofeed?docid=$videoid"))) {
			$vrss = $res['content'];
			if($vrss) {
				preg_match('/<media:thumbnail url=["\'](.+?)["\']/',$vrss,$thumbnail_array);
				return $thumbnail_array[1];
			}
		}
		return false;
	}
	function get_vimeo_thumb($videoid) {
		if(($res = get_url("http://vimeo.com/api/clip/$videoid.xml"))) {
			$vrss = $res['content'];
			if($vrss) {
				preg_match('/<thumbnail_large>(.+)<\/thumbnail_large>/i',$vrss,$thumbnail_array);
				return $thumbnail_array[1];
			}
		}
		return false;
	}
	function get_metacafe_thumb($videoid) {
		if(($res = get_url("http://www.metacafe.com/api/item/$videoid"))) {
			$vrss = $res['content'];
			if($vrss) {
				preg_match('/<media:thumbnail url=["\'](.+?)["\']/',$vrss,$thumbnail_array);
				return $thumbnail_array[1];
			}
		}
		return false;
	}
	
	//set array
	$images = array();
	$thumbnails = array();
	
	//test for an http site
	if(ereg("^((http|https)://)?(((www\.)?[^ ]+\.[[:alpha:]])|([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+))([^ ]+)?$", $url) == true) {

		// make the cURL request to $url
			$userAgent = 'Google – Googlebot/2.1 ( http://www.googlebot.com/bot.html) ';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_AUTOREFERER, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$html= curl_exec($ch);
			$type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
			if (!$html) {
				echo "<br />cURL error number:" .curl_errno($ch);
				echo "<br />cURL error:" . curl_error($ch);
				exit;
			}
			curl_close($ch);
		
		if (preg_match('/^image/i', $type)) {
			$response['type'] = 'image';
			$chopped = parse_url($url);	     
			$response['host'] = $chopped['host'];
				//test for compete url : make complete
				if (substr($url, 0, 4)!='http') {
					$url = 'http://'.$response['host'].$url;
				}
			//test if allowed file type
			$mimetest = getimagesize($url);
			$allowedmime = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png',
'image/x-png');
			if (in_array($mimetest['mime'], $allowedmime)) {
				if (!in_array($url, $thumbnails)){
					list($width_orig, $height_orig) = getimagesize($url);
					$width = 90;
					$height = 80;	
						//resize if too big
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
					array_push($thumbnails, array($url, round($width), round($height)));
				}
			}
		} elseif (preg_match('/text\/html/i', $type)) {
			$response['type'] = 'html';
			$chopped = parse_url($url);	     
			$response['host'] = $chopped['host'];
			
			// parse the html into a DOMDocument
			$dom = new DOMDocument();
			@$dom->loadHTML($html);
			
			// grab all the on the page
			$xpath = new DOMXPath($dom);
			$metas = $xpath->evaluate("/html/head//meta");
			$meta_links = $xpath->evaluate("/html/head//link");
			$response['title'] = $dom->getElementsByTagName('title')->item(0)->nodeValue;
			
			//get description | check for default thumbnail
				for ($i = 0; $i < $metas->length; $i++) {
					$meta = $metas->item($i);
					$name = $meta->getAttribute('name');
					$content = $meta->getAttribute('content');
					if ($name=='description') {
						$response['description'] = $content;
					} elseif ($name=='thumbnail_url') {
						$images[] = $content;
					}
				}
				//tests for meta_links
				if (count($images)==0) {
					for ($i = 0; $i < $meta_links->length; $i++) {
						$meta_link = $meta_links->item($i);
						$rel = $meta_link->getAttribute('rel');
						$href = $meta_link->getAttribute('href');
						if ($rel=='image_src') {
							$images[] = $href;
						}
					}
				}
				//set blank description if none found
				if (!$response['description']) {
					$response['description'] = '';	
				}
			//end get meta data
			
			//if not images were found, get all images on page
			if (count($images)==0) {
				$doc_images = $xpath->evaluate("/html/body//img");
				for ($i = 0; $i < $doc_images->length; $i++) {
					$doc_image = $doc_images->item($i);
					$imgsrc = $doc_image->getAttribute('src');
							//test for compete url : make complete
							if (substr($imgsrc, 0, 4)!='http') {
								$imgsrc = 'http://'.$response['host'].$imgsrc;
							}
						//test if allowed file type and add if so
						$mimetest = getimagesize($imgsrc);
						$allowedmime = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/png',
		'image/x-png');
						if (in_array($mimetest['mime'], $allowedmime)) {
							if (!in_array($imgsrc, $thumbnails)){
								list($width_orig, $height_orig) = getimagesize($imgsrc);
								$width = 90;
								$height = 80;			
								//test to make sure acceptable size – that it is not too small
								if (($width_orig>=$width)||($height_orig>=$height)) {
									//resize if too big
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
									array_push($thumbnails, array($imgsrc, round($width), round($height)));
								}
							}
						}
				}
			}
			
			
			
		} //end if content type
		
	} //end if http
} //end if url is blank

$response['thumbnails'] = $thumbnails;

header('Content-type: application/json');
echo json_encode($response);

exit();
?>
