<?php
// ===================================================================================================
//                           _  __     _ _
//                          | |/ /__ _| | |_ _  _ _ _ __ _
//                          | ' </ _` | |  _| || | '_/ _` |
//                          |_|\_\__,_|_|\__|\_,_|_| \__,_|
//
// This file is part of the Kaltura Collaborative Media Suite which allows users
// to do with audio, video, and animation what Wiki platfroms allow them to do with
// text.
//
// Copyright (C) 2006-2011  Kaltura Inc.
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// @ignore
// ===================================================================================================

require_once(dirname(__FILE__) . "/../KalturaClientBase.php");
require_once(dirname(__FILE__) . "/../KalturaEnums.php");
require_once(dirname(__FILE__) . "/../KalturaTypes.php");
require_once(dirname(__FILE__) . "/KalturaContentDistributionClientPlugin.php");

class KalturaNdnDistributionProfileOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaNdnDistributionProviderOrderBy
{
}

class KalturaNdnDistributionProvider extends KalturaDistributionProvider
{

}

class KalturaNdnDistributionProfile extends KalturaConfigurableDistributionProfile
{
	/**
	 * 
	 *
	 * @var string
	 * @readonly
	 */
	public $feedUrl = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $channelTitle = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $channelLink = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $channelDescription = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $channelLanguage = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $channelCopyright = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $channelImageTitle = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $channelImageUrl = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $channelImageLink = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $itemMediaRating = null;


}

abstract class KalturaNdnDistributionProviderBaseFilter extends KalturaDistributionProviderFilter
{

}

class KalturaNdnDistributionProviderFilter extends KalturaNdnDistributionProviderBaseFilter
{

}

abstract class KalturaNdnDistributionProfileBaseFilter extends KalturaConfigurableDistributionProfileFilter
{

}

class KalturaNdnDistributionProfileFilter extends KalturaNdnDistributionProfileBaseFilter
{

}


class KalturaNdnService extends KalturaServiceBase
{
	function __construct(KalturaClient $client = null)
	{
		parent::__construct($client);
	}

	function getFeed($distributionProfileId, $hash)
	{
		$kparams = array();
		$this->client->addParam($kparams, "distributionProfileId", $distributionProfileId);
		$this->client->addParam($kparams, "hash", $hash);
		$this->client->queueServiceActionCall('ndndistribution_ndn', 'getFeed', $kparams);
		$resultObject = $this->client->getServeUrl();
		return $resultObject;
	}
}
class KalturaNdnDistributionClientPlugin extends KalturaClientPlugin
{
	/**
	 * @var KalturaNdnService
	 */
	public $ndn = null;

	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
		$this->ndn = new KalturaNdnService($client);
	}

	/**
	 * @return KalturaNdnDistributionClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		return new KalturaNdnDistributionClientPlugin($client);
	}

	/**
	 * @return array<KalturaServiceBase>
	 */
	public function getServices()
	{
		$services = array(
			'ndn' => $this->ndn,
		);
		return $services;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'ndnDistribution';
	}
}

