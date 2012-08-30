<?php
require_once('config.php');
//Includes the client library and starts a Kaltura session to access the API
//More informatation about this process can be found at
//http://knowledge.kaltura.com/introduction-kaltura-client-libraries
require_once('lib/php5/KalturaClient.php');
$config = new KalturaConfiguration($_REQUEST['partnerId']);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
$client->setKs($_REQUEST['session']);

$videos = explode(',', $_REQUEST['options'][3]);
foreach($videos as $index => $video) {
	if(trim($video) == '') {
		unset($videos[$index]);
	}
}
if(count($videos) < 3) {
	echo 'low';
	die();
}
$pages = @file_get_contents(PAGES);
if($pages != '') {
	$pages = unserialize($pages);	
}
else {
	$pages = array();
}
$page = array();
if(array_key_exists($_REQUEST['page'], $pages)) {
	$page = $pages[$_REQUEST['page']];
}
$page['player'] = $_REQUEST['options'][0];
$page['type'] = $_REQUEST['options'][1];
$page['id'] = $_REQUEST['options'][2];
$page['videos'] = substr($_REQUEST['options'][3], 0, -1);
$page['admin'] = $client->partner->get($_REQUEST['partnerId'])->adminSecret;
$page['partner'] = $_REQUEST['partnerId'];
$pages[$_REQUEST['page']] = $page;
$pages = serialize($pages);
file_put_contents(PAGES, '<?php'."\n".$pages);
echo 'success';