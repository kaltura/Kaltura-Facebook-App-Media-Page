<?php
//This script displays the list of partners to choose from on the account
//and then generates a Kaltura session for the appropriate partner

//Includes the client library and starts a Kaltura session to access the API
//More informatation about this process can be found at
//http://knowledge.kaltura.com/introduction-kaltura-client-libraries
require_once('lib/php5/KalturaClient.php');
$partnerId = $_REQUEST['partnerId'];
if($partnerId != 0)
	properKS($partnerId);
else {
	$config = new KalturaConfiguration($partnerId);
	$config->serviceUrl = 'http://www.kaltura.com/';
	$client = new KalturaClient($config);
	$loginId = $_REQUEST['email'];
	$password = $_REQUEST['password'];
	//Attempts to login with the given information
	try {
		//Use user->loginByLoginId rather than adminUser->login which has been deprecated
		$ks = $client->user->loginByLoginId($loginId, $password);
		$client->setKs($ks);
		$filter = null;
		$pager = null;
		$results = $client->partner->listPartnersForUser();
		//If there is only one partner on the account, log in immediately
		if($results->totalCount == 1)
			properKS($results->objects[0]->id);
		//Otherwise, display the list of partners on the account
		else {
			$ret = array();
			$ret[] = $results->totalCount;
			foreach($results->objects as $partner)
				$ret[] = array($partner->id, $partner->name);
			echo json_encode($ret);
		}
	}
	//If the login attempt fails, throw an error
	catch(Exception $ex) {
		if(strpos($ex->getMessage(), 'Unknown') === false)
			echo 'loginfail';
	}	
}

//Once a partner is selected, generate a Kaltura Session
function properKS($partnerId) {
	$config = new KalturaConfiguration($partnerId);
	$config->serviceUrl = 'http://www.kaltura.com/';
	$client = new KalturaClient($config);
	$loginId = $_REQUEST['email'];
	$password = $_REQUEST['password'];
	$expiry = null;
	$privileges = null;
	//Use user->loginByLoginId rather than adminUser->login which has been deprecated
	$ks = $client->user->loginByLoginId($loginId, $password, $partnerId, $expiry, $privileges);
	$ret = array();
	$ret[] = 1;
	$ret[] = $ks;
	$ret[] = $partnerId;
	echo json_encode($ret);
}