<?php
//Loads the thumbnail for a video chosen in the admin console

//Includes the client library and starts a Kaltura session to access the API
//More informatation about this process can be found at
//http://knowledge.kaltura.com/introduction-kaltura-client-libraries
require_once('lib/php5/KalturaClient.php');
$config = new KalturaConfiguration($_REQUEST['partnerId']);
$config->serviceUrl = 'http://www.kaltura.com/';
$client = new KalturaClient($config);
$client->setKs($_REQUEST['session']);

if($_REQUEST['id'] != '')
	echo '<img class="thumbnail" src="'.$client->media->get($_REQUEST['id'])->thumbnailUrl.'"/>';