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

class KalturaSynacorHboDistributionProfileOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaSynacorHboDistributionProviderOrderBy
{
}

class KalturaSynacorHboDistributionProvider extends KalturaDistributionProvider
{

}

class KalturaSynacorHboDistributionProfile extends KalturaConfigurableDistributionProfile
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
	public $feedTitle = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $feedSubtitle = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $feedLink = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $feedAuthorName = null;


}

abstract class KalturaSynacorHboDistributionProviderBaseFilter extends KalturaDistributionProviderFilter
{

}

class KalturaSynacorHboDistributionProviderFilter extends KalturaSynacorHboDistributionProviderBaseFilter
{

}

abstract class KalturaSynacorHboDistributionProfileBaseFilter extends KalturaConfigurableDistributionProfileFilter
{

}

class KalturaSynacorHboDistributionProfileFilter extends KalturaSynacorHboDistributionProfileBaseFilter
{

}


class KalturaSynacorHboService extends KalturaServiceBase
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
		$this->client->queueServiceActionCall('synacorhbodistribution_synacorhbo', 'getFeed', $kparams);
		$resultObject = $this->client->getServeUrl();
		return $resultObject;
	}
}
class KalturaSynacorHboDistributionClientPlugin extends KalturaClientPlugin
{
	/**
	 * @var KalturaSynacorHboService
	 */
	public $synacorHbo = null;

	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
		$this->synacorHbo = new KalturaSynacorHboService($client);
	}

	/**
	 * @return KalturaSynacorHboDistributionClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		return new KalturaSynacorHboDistributionClientPlugin($client);
	}

	/**
	 * @return array<KalturaServiceBase>
	 */
	public function getServices()
	{
		$services = array(
			'synacorHbo' => $this->synacorHbo,
		);
		return $services;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'synacorHboDistribution';
	}
}

