<?php
set_time_limit(0);
require_once('config.php');
$pages = @unserialize(file_get_contents(PAGES));
$page = $pages[$_REQUEST['page']];
$videos = explode(',', $page['videos']);
$entryId = $videos[mt_rand(0, 2)];
$id = $page['id'];
$partnerId = $page['partner'];
$admin = $page['admin'];

//Includes the client library and starts a Kaltura session to access the API
//More informatation about this process can be found at
//http://knowledge.kaltura.com/introduction-kaltura-client-libraries
require_once('lib/php5/KalturaClient.php');
$config = new KalturaConfiguration($partnerId);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
global $USER_ID;
$ks = $client->session->start($admin, 'MediaPage', KalturaSessionType::ADMIN, $partnerId);
$client->setKs($ks);

$response = array();
$response[0] = '';
$filter = new KalturaMediaEntryFilter();
$filter->idIn = $page['videos'];
$pager = new KalturaFilterPager();
$pager->pageSize = 3;
$pager->pageIndex = 1;
$entries = $client->media->listAction($filter, $pager)->objects;
foreach($entries as $entry) {
	$display =  $entry->thumbnailUrl ? '<img width="120" height="68" id="'.$entry->id.'" src="'.$entry->thumbnailUrl.'" title="'.$entry->name.'" >' : '<div>'.$entry->id.' '.$entry->name.'</div>';
	if($entry->id == $entryId) {
		$display .= '<img src="lib/play.png" id="play" style="display: block;">';
	}
	else {
		$display .= '<img src="lib/play.png" id="play">';
	}
	$response[0] .= '<a class="thumblink" id="'.$entry->id.'" title="'.$entry->name.'" >'.$display.'</a>';
	//$response[0] .= '<div class="featuredThumbs" id="'.$entry->id.'"><img src="'.$entry->thumbnailUrl.'"></div>';
}
$response[0] .= '<div style="clear: both;"></div>';
$response[1] = $entryId;
echo json_encode($response);