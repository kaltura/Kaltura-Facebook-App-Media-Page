<?php
//Includes the client library and starts a Kaltura session to access the API
//More informatation about this process can be found at
//http://knowledge.kaltura.com/introduction-kaltura-client-libraries
require_once('lib/php5/KalturaClient.php');
$config = new KalturaConfiguration($_REQUEST['partnerId']);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
$client->setKs($_REQUEST['session']);

$response = array('videos' => array(), 'count' => 0);
$search = trim($_REQUEST['search']);
function escapeChar($input) {
	$input = '\\'.$input[0];
	return $input;
}
$search = preg_replace_callback('|[#-+]|','escapeChar',$search);
$search = preg_replace_callback('|[--/]|','escapeChar',$search);
$search = preg_replace_callback('|!|','escapeChar',$search);
$search = preg_replace_callback('|"|','escapeChar',$search);
$search = preg_replace_callback('|-|','escapeChar',$search);
$search = preg_replace_callback('|\\/|','escapeChar',$search);
if($_REQUEST['content'][0] == 'cat') {
	$filter = new KalturaMediaEntryFilter();
	$filter->orderBy = '-createdAt';
	$filter->categoriesIdsMatchAnd = $_REQUEST['content'][1];
	$filter->idNotIn = $_REQUEST['ignore'];
	$filter->freeText = $search;
	$pager = new KalturaFilterPager();
	$pager->pageSize = $_REQUEST['pageSize'];
	$pager->pageIndex = $_REQUEST['page'];
	$results = $client->media->listAction($filter, $pager);
	foreach($results->objects as $entry) {
		$response['videos'][] = array('id' => $entry->id, 'text' => $entry->id.': '.$entry->name);
	}
	$response['count'] = $results->totalCount;
}
else {
	$playlist = $client->playlist->get($_REQUEST['content'][1]);
	$playlistContent = $playlist->playlistContent;
	@$xml = simplexml_load_string($playlistContent);
	if($xml === FALSE) {
		$filter = new KalturaMediaEntryFilter();
		$filter->orderBy = '-createdAt';
		$filter->idIn = $playlistContent;
		$filter->idNotIn = $_REQUEST['ignore'];
		$filter->freeText = $search;
		$pager = new KalturaFilterPager();
		$pager->pageSize = $_REQUEST['pageSize'];
		$pager->pageIndex = $_REQUEST['page'];
		$results = $client->media->listAction($filter, $pager);
		foreach($results->objects as $entry) {
			$response['videos'][] = array('id' => $entry->id, 'text' => $entry->id.': '.$entry->name);
		}
		$response['count'] = $results->totalCount;
	}
	else {
		$xml->total_results = 10;
		$playlistType = KalturaPlaylistType::DYNAMIC;
		$playlistContent = $xml->asXML();
		$results = $client->playlist->executefromcontent($playlistType, $playlistContent);
		$idIn = "";
		foreach($results as $result) {
			$idIn .= $result->id.',';
		}
		$filter = new KalturaMediaEntryFilter();
		$filter->orderBy = '-createdAt';
		$filter->idIn = $idIn;
		$filter->idNotIn = $_REQUEST['ignore'];
		$filter->freeText = $search;
		$pager = new KalturaFilterPager();
		$pager->pageSize = $_REQUEST['pageSize'];
		$pager->pageIndex = $_REQUEST['page'];
		$results = $client->media->listAction($filter, $pager);
		foreach($results->objects as $entry) {
			$response['videos'][] = array('id' => $entry->id, 'text' => $entry->id.': '.$entry->name);
		}
		$response['count'] = $results->totalCount;
	}
}
echo json_encode($response);