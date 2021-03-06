<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

namespace Modules\MyTickets;

use Hubzero\Module\Module;
use User;

/**
 * Module class for displaying a user's support tickets
 */
class Helper extends Module
{
	/**
	 * Display module content
	 *
	 * @return  void
	 */
	public function display()
	{
		$database = \App::get('db');

		$this->moduleclass = $this->params->get('moduleclass');
		$limit = intval($this->params->get('limit', 10));

		// Find the user's most recent support tickets
		$database->setQuery(
			"(
				SELECT id, summary, category, open, status, severity, owner, created, login, name,
					(SELECT COUNT(*) FROM #__support_comments as sc WHERE sc.ticket=st.id AND sc.access=0) as comments
				FROM #__support_tickets as st
				WHERE st.login=" . $database->quote(User::get('username')) . " AND st.open=1 AND type=0
				ORDER BY created DESC
				LIMIT $limit
			)
			UNION
			(
				SELECT id, summary, category, open, status, severity, owner, created, login, name,
					(SELECT COUNT(*) FROM #__support_comments as sc WHERE sc.ticket=st.id AND sc.access=0) as comments
				FROM #__support_tickets as st
				WHERE st.owner=" . $database->quote(User::get('id')) . " AND st.open=1 AND type=0
				ORDER BY created DESC
				LIMIT $limit
			)"
		);
		$this->rows = $database->loadObjectList();
		if ($database->getErrorNum())
		{
			$this->setError($database->stderr());
			$this->rows = array();
		}

		$rows1 = array();
		$rows2 = array();

		if ($this->rows)
		{
			foreach ($this->rows as $row)
			{
				if ($row->owner == User::get('id'))
				{
					$rows2[] = $row;
				}
				else
				{
					$rows1[] = $row;
				}
			}
		}

		$this->rows1 = $rows1;
		$this->rows2 = $rows2;

		$xgroups = \Hubzero\User\Helper::getGroups(User::get('id'), 'members', 1);

		$groups = '';
		if ($xgroups)
		{
			$g = array();
			foreach ($xgroups as $xgroup)
			{
				$g[] = $database->quote($xgroup->gidNumber);
			}
			$groups = implode(",", $g);
		}

		$this->rows3 = null;
		if ($groups)
		{
			// Find support tickets on the user's contributions
			$database->setQuery(
				"SELECT id, summary, category, open, status, severity, owner, created, login, name,
					(SELECT COUNT(*) FROM `#__support_comments` as sc WHERE sc.ticket=st.id AND sc.access=0) as comments
				FROM `#__support_tickets` as st
				WHERE st.open=1 AND type=0 AND st.group_id IN ($groups)
				ORDER BY created DESC
				LIMIT $limit"
			);
			$this->rows3 = $database->loadObjectList();
			if ($database->getErrorNum())
			{
				$this->setError($database->stderr());
				$this->rows3 = null;
			}
		}

		require $this->getLayoutPath();
	}
}
