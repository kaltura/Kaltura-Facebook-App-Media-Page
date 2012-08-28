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

class KalturaHuluDistributionProfileOrderBy
{
	const CREATED_AT_ASC = "+createdAt";
	const CREATED_AT_DESC = "-createdAt";
	const UPDATED_AT_ASC = "+updatedAt";
	const UPDATED_AT_DESC = "-updatedAt";
}

class KalturaHuluDistributionProviderOrderBy
{
}

class KalturaHuluDistributionProvider extends KalturaDistributionProvider
{

}

class KalturaHuluDistributionProfile extends KalturaConfigurableDistributionProfile
{
	/**
	 * 
	 *
	 * @var string
	 */
	public $sftpHost = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $sftpLogin = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $sftpPass = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $seriesChannel = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $seriesPrimaryCategory = null;

	/**
	 * 
	 *
	 * @var array of KalturaString
	 */
	public $seriesAdditionalCategories;

	/**
	 * 
	 *
	 * @var string
	 */
	public $seasonNumber = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $seasonSynopsis = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $seasonTuneInInformation = null;

	/**
	 * 
	 *
	 * @var string
	 */
	public $videoMediaType = null;

	/**
	 * 
	 *
	 * @var bool
	 */
	public $disableEpisodeNumberCustomValidation = null;


}

abstract class KalturaHuluDistributionProviderBaseFilter extends KalturaDistributionProviderFilter
{

}

class KalturaHuluDistributionProviderFilter extends KalturaHuluDistributionProviderBaseFilter
{

}

abstract class KalturaHuluDistributionProfileBaseFilter extends KalturaConfigurableDistributionProfileFilter
{

}

class KalturaHuluDistributionProfileFilter extends KalturaHuluDistributionProfileBaseFilter
{

}

class KalturaHuluDistributionClientPlugin extends KalturaClientPlugin
{
	protected function __construct(KalturaClient $client)
	{
		parent::__construct($client);
	}

	/**
	 * @return KalturaHuluDistributionClientPlugin
	 */
	public static function get(KalturaClient $client)
	{
		return new KalturaHuluDistributionClientPlugin($client);
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
		return 'huluDistribution';
	}
}

