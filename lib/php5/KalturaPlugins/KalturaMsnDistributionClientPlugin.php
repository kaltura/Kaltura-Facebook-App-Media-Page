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

class KalturaMsnDistributionProfileOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaMsnDistributionProviderOrderBy
{
}

class KalturaMsnDistributionProvider extends KalturaDistributionProvider
{

}

class KalturaMsnDistributionProfile extends KalturaConfigurableDistributionProfile
{
	/**
	 * 
	 *
	 * @var string
	 */
	public $username = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $password = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $domain = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $csId = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $source = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $sourceFriendlyName = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $pageGroup = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $sourceFlavorParamsId = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $wmvFlavorParamsId = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $flvFlavorParamsId = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $slFlavorParamsId = null;

	/**
	 * 
	 *
	 * @var int
	 */
	public $slHdFlavorParamsId = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $msnvideoCat = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $msnvideoTop = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $msnvideoTopCat = null;


}

abstract class KalturaMsnDistributionProviderBaseFilter extends KalturaDistributionProviderFilter
{

}

class KalturaMsnDistributionProviderFilter extends KalturaMsnDistributionProviderBaseFilter
{

}

abstract class KalturaMsnDistributionProfileBaseFilter extends KalturaConfigurableDistributionProfileFilter
{

}

class KalturaMsnDistributionProfileFilter extends KalturaMsnDistributionProfileBaseFilter
{

}

class KalturaMsnDistributionClientPlugin extends KalturaClientPlugin
{
	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
	}

	/**
	 * @return KalturaMsnDistributionClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		return new KalturaMsnDistributionClientPlugin($client);
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
		return 'msnDistribution';
	}
}

