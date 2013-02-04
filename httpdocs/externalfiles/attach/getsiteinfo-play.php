<?php
require_once ('../../../externals/general/includepaths.php');
require_once ('../../../externals/general/functions.php');
$target_url = escape_data(urldecode($_GET['u']));

function storeLink($url,$gathered_from) {
	return;
}

$userAgent = 'Google – Googlebot/2.1 ( http://www.googlebot.com/bot.html) ';

// make the cURL request to $target_url
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
curl_setopt($ch, CURLOPT_URL,$target_url);
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

// parse the html into a DOMDocument
$dom = new DOMDocument();
@$dom->loadHTML($html);

// grab all the on the page
$xpath = new DOMXPath($dom);
$metas = $xpath->evaluate("/html//meta");
$hrefs = $xpath->evaluate("/html/body//img");
$title = $dom->getElementsByTagName('title')->item(0)->nodeValue;

echo '<p>oh hai!</p>';

echo '<p>title: '.$title.'</p>';

echo '<p>type: '.$type.'</p>';

echo '<p>meta:</p>';
for ($i = 0; $i < $metas->length; $i++) {
	$meta = $metas->item($i);
	$name = $meta->getAttribute('name');
	$content = $meta->getAttribute('content');
	echo "<br />meta: name: $name content: $content";
}


echo '<p>imgs:</p>';
for ($i = 0; $i < $hrefs->length; $i++) {
	$href = $hrefs->item($i);
	$url = $href->getAttribute('src');
	echo "<br />Link: $url";
}

exit();
?>
