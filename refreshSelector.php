<?php
//Refreshses the selector for choosing videos from your category or playlist
//This allows the selector to load quicker because the user is essentially searching for a specific entry

//Includes the client library and starts a Kaltura session to access the API
//More informatation about this process can be found at
//http://knowledge.kaltura.com/introduction-kaltura-client-libraries
require_once('lib/php5/KalturaClient.php');
$config = new KalturaConfiguration($_REQUEST['partnerId']);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
$client->setKs($_REQUEST['session']);

//$response is an array that tracks the videos to choose from in the selector and the total count
//Keeping track of the total count allows the selector to load multiple "pages" like a pager
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
//Loads the videos from a category
if($_REQUEST['content'][0] == 'cat') {
	$filter = new KalturaMediaEntryFilter();
	$filter->orderBy = '-createdAt';
	$filter->categoriesIdsMatchAnd = $_REQUEST['content'][1];
	//If one of the 3 selectors has a video selected, do not allow it to be selected again
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
//Otherwise, loads the videos from a playlist
else {
	$playlist = $client->playlist->get($_REQUEST['content'][1]);
	$playlistContent = $playlist->playlistContent;
	@$xml = simplexml_load_string($playlistContent);
	//If there is no valid XML in the playlistContent field, this a Manual Playlist
	if($xml === FALSE) {
		$filter = new KalturaMediaEntryFilter();
		$filter->orderBy = '-createdAt';
		$filter->idIn = $playlistContent;
		//If one of the 3 selectors has a video selected, do not allow it to be selected again
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
	//Otherwise, this is a Rule Based Playlist
	else {
		$xml->total_results = $_REQUEST['pageSize'];
		$results = $client->playlist->executefromcontent(KalturaPlaylistType::DYNAMIC, $xml->asXML());
		$idIn = "";
		//Grab all the entry id's in the playlist
		foreach($results as $result) {
			$idIn .= $result->id.',';
		}
		$filter = new KalturaMediaEntryFilter();
		$filter->orderBy = '-createdAt';
		$filter->idIn = $idIn;
		//If one of the 3 selectors has a video selected, do not allow it to be selected again
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