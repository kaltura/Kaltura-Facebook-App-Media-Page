<?php
//Loads the thumbnail gallery for the category or playlist chosen in the admin console

set_time_limit(0);
require_once("config.php");
$pages = @unserialize(file_get_contents(PAGES));
$facePage = $pages[$_REQUEST['page']];
$id = $facePage['id'];
$partnerId = $facePage['partner'];
$admin = $facePage['admin'];

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

//Filters the entries so that they are ordered by descending creation order
//In other words, the newer videos show up on the front page
$filter = new KalturaMediaEntryFilter();
$filter->orderBy = "-createdAt";
$pager = new KalturaFilterPager();
//Displays 12 entries per page
$pageSize = 12;
$page = 1;
//Retrieves the correct page number
if(array_key_exists('pagenum', $_REQUEST))
	$page = $_REQUEST['pagenum'];
//If a search has been made, display only the entries that match the search terms
$search = trim($_REQUEST['search']);
function escapeChar($input)
{
	$input = '\\'.$input[0];
	return $input;
}
$search = preg_replace_callback('|[#-+]|','escapeChar',$search);
$search = preg_replace_callback('|[--/]|','escapeChar',$search);
$search = preg_replace_callback('|!|','escapeChar',$search);
$search = preg_replace_callback('|"|','escapeChar',$search);
$search = preg_replace_callback('|-|','escapeChar',$search);
$search = preg_replace_callback('|\\/|','escapeChar',$search);
$filter->freeText = $search;
$pager->pageSize = $pageSize;
$pager->pageIndex = $page;
if($facePage['type'] == 'cat') {
	$filter->categoriesIdsMatchAnd = $facePage['id'];
}
else {
	$playlist = $client->playlist->get($facePage['id']);
	$playlistContent = $playlist->playlistContent;
	@$xml = simplexml_load_string($playlistContent);
	if($xml === FALSE) {
		$filter->idIn = $playlistContent;
	}
	else {
		$xml->total_results = $pageSize;
		$playlistType = KalturaPlaylistType::DYNAMIC;
		$playlistContent = $xml->asXML();
		$playlist = $client->playlist->executefromcontent($playlistType, $playlistContent);
		$idIn = "";
		foreach($playlist as $playlistEntry) {
			$idIn .= $playlistEntry->id.',';
		}
		$filter->idIn = $idIn;
	}
}
$results = $client->media->listAction($filter, $pager);
$count = $results->totalCount;
	
//This function creates a set of links to other entry pages
function create_gallery_pager  ($pageNumber, $current_page, $pageSize, $count, $js_callback_paging_clicked) {
	$search = trim($_REQUEST['search']);
	$pageNumber = (int)$pageNumber;
	$b = (($pageNumber+1) * $pageSize) ;
	$b = min ( $b , $count ); // don't let the page-end be bigger than the total count
	$a = min($pageNumber * $pageSize + 1,$count - ($count % $pageSize) + 1);
	$veryLastPage = (int)($count / $pageSize);
	$veryLastPage += ($count % $pageSize == 0) ? 0 : 1;
	if($pageNumber == $veryLastPage) {
		$pageToGoTo = $pageNumber;
		$pageToGoTo += (($pageNumber + 1) * $pageSize > $count) ? 0 : 1;
	}
	else
		$pageToGoTo = $pageNumber + 1;
	if ($pageToGoTo == $current_page)
		$str = "[<a title='{$pageToGoTo}' href='javascript:{$js_callback_paging_clicked} ($pageToGoTo, \"$search\")'>{$a}-{$b}</a>] ";
	else
		$str =  "<a title='{$pageToGoTo}' href='javascript:{$js_callback_paging_clicked} ($pageToGoTo, \"$search\")'>{$a}-{$b}</a> ";
	return $str;
}
//The server may pull entries up to the hard limit. This number should not exceed 10000.
$hardLimit = 2000;
$pagerString = "";
$startPage = max(1, $page - 5);
$veryLastPage = (int)($count / $pageSize);
$veryLastPage += ($count % $pageSize == 0) ? 0 : 1;
$veryLastPage = min((int)($hardLimit / $pageSize), $veryLastPage);
$endPage = min($veryLastPage, $startPage + 10);
//Iterates to create several page links
for ($pageNumber = $startPage; $pageNumber < $endPage; ++$pageNumber) {
	$pagerString .= create_gallery_pager ($pageNumber , $page  , $pageSize , $count , "pagerClicked");
}

$beforePageString = "";
$afterPageString = "";
$prevPage = $page - 1;
if($page > 1) $beforePageString .= "<a title='{$prevPage}' href='javascript:pagerClicked ($prevPage, \"$search\")'>Previous</a> ";
// add page 0 if not in list
if($startPage == 1 && $count > 0) $beforePageString .= create_gallery_pager(0, $page, $pageSize, $count, "pagerClicked");
$nextPage = $page + 1;
if ($page < $veryLastPage) $afterPageString .= "<a title='{$nextPage}' href='javascript:pagerClicked ($nextPage, \"$search\")'>Next</a> ";
$pagerString = "<span style=\"color:#ccc;\">Total (" . $count . ") </span>" . $beforePageString . $pagerString . $afterPageString;

echo '<div id="pagerDiv">'.$pagerString.'</div>';
echo '<div id="gallery">';
//Loops through every entry on your current page
foreach ($results->objects as $result) {
	//Creates a thumbnail that can be clicked to view the content
	$name = $result->name;
	$type = $result->mediaType;
	$id = $result->id;
	$display =  $result->thumbnailUrl ? '<img width="120" height="68" src="'.$result->thumbnailUrl.'" title="'.$name.'" >' : '<div>'.$id.' '.$name.'</div>';
	$display .= '<img src="lib/play.png" id="play">';
	$thumbnail = '<a class="thumblink" id="'.$result->id.'" title="'.$name.'" >'.$display.'</a>';
	echo $thumbnail;
	//Only show 4 entry thumbnails per row
    if($count > 0 && ($count + 1) % 4 == 0)
    	echo '<div style="clear: both;"></div>';
}
echo '</div>';