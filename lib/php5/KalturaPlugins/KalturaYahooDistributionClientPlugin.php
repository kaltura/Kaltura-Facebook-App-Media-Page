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

class KalturaYahooDistributionProcessFeedActionStatus
{
	const MANUAL = 0;
	const AUTOMATIC = 1;
}

class KalturaYahooDistributionProfileOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaYahooDistributionProviderOrderBy
{
}

class KalturaYahooDistributionProvider extends KalturaDistributionProvider
{

}

class KalturaYahooDistributionProfile extends KalturaConfigurableDistributionProfile
{
	/**
	 * 
	 *
	 * @var string
	 */
	public $ftpPath = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $ftpUsername = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $ftpPassword = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $ftpHost = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $contactTelephone = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $contactEmail = null;

	/**
	 * 
	 *
	 * @var KalturaYahooDistributionProcessFeedActionStatus
	 */
	public $processFeed = null;


}

abstract class KalturaYahooDistributionProviderBaseFilter extends KalturaDistributionProviderFilter
{

}

class KalturaYahooDistributionProviderFilter extends KalturaYahooDistributionProviderBaseFilter
{

}

abstract class KalturaYahooDistributionProfileBaseFilter extends KalturaConfigurableDistributionProfileFilter
{

}

class KalturaYahooDistributionProfileFilter extends KalturaYahooDistributionProfileBaseFilter
{

}

class KalturaYahooDistributionClientPlugin extends KalturaClientPlugin
{
	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
	}

	/**
	 * @return KalturaYahooDistributionClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		return new KalturaYahooDistributionClientPlugin($client);
	}

	/**
	 * @return array<KalturaServiceBase>
	 */
	public function getServices()
	{
		$services = array(
		);
		return $services;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'yahooDistribution';
	}
}

