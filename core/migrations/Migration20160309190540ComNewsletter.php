<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

use Hubzero\Content\Migration\Base;

/**
 * Migration script for enabling digest plugins
 * Specifically the event, feedaggregator, and resource plugins
 **/
class Migration20160309190540ComNewsletter extends Base
{
	/**
	 * Up
	 **/
	public function up()
	{
		$elements = array('event', 'feedaggregator', 'resource');
		foreach ($elements as $element)
		{
			$this->addPluginEntry('newsletter', $element);
		}
	}

	/**
	 * Down
	 **/
	public function down()
	{
		$elements = array('event', 'feedaggregator', 'resource');
		foreach ($elements as $element)
		{
			$this->deletePluginEntry('newsletter', $element);
		}
	}
}
