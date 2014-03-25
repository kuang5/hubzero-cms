<?php

use Hubzero\Content\Migration\Base;

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Migration script for ...
 **/
class Migration20140115171149PlgResourcesFindthistext extends Base
{
	/**
	 * Up
	 **/
	public function up($db)
	{
		$this->addPluginEntry('resources','findthistext');
	}

	/**
	 * Down
	 **/
	public function down($db)
	{
		$this->deletePluginEntry('resources','findthistext');
	}
}