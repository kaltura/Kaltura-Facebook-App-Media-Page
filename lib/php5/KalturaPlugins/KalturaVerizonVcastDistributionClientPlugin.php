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

class KalturaVerizonVcastDistributionProfileOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaVerizonVcastDistributionProviderOrderBy
{
}

class KalturaVerizonVcastDistributionProvider extends KalturaDistributionProvider
{

}

class KalturaVerizonVcastDistributionProfile extends KalturaConfigurableDistributionProfile
{
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
	public $ftpLogin = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $ftpPass = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $providerName = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $providerId = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $entitlement = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $priority = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $allowStreaming = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $streamingPriceCode = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $allowDownload = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $downloadPriceCode = null;


}

abstract class KalturaVerizonVcastDistributionProviderBaseFilter extends KalturaDistributionProviderFilter
{

}

class KalturaVerizonVcastDistributionProviderFilter extends KalturaVerizonVcastDistributionProviderBaseFilter
{

}

abstract class KalturaVerizonVcastDistributionProfileBaseFilter extends KalturaConfigurableDistributionProfileFilter
{

}

class KalturaVerizonVcastDistributionProfileFilter extends KalturaVerizonVcastDistributionProfileBaseFilter
{

}

class KalturaVerizonVcastDistributionClientPlugin extends KalturaClientPlugin
{
	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
	}

	/**
	 * @return KalturaVerizonVcastDistributionClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		return new KalturaVerizonVcastDistributionClientPlugin($client);
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
		return 'verizonVcastDistribution';
	}
}

