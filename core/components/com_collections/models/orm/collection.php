<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

namespace Components\Collections\Models\Orm;

use Hubzero\Database\Relational;
use Component;
use Lang;
use Date;

require_once __DIR__ . DS . 'post.php';

/**
 * Collection model
 */
class Collection extends Relational
{
	/**
	 * Default order by for model
	 *
	 * @var string
	 */
	public $orderBy = 'created';

	/**
	 * Default order direction for select queries
	 *
	 * @var  string
	 */
	public $orderDir = 'desc';

	/**
	 * Fields and their validation criteria
	 *
	 * @var  array
	 */
	protected $rules = array(
		'title'       => 'notempty',
		'object_type' => 'notempty',
		'object_id'   => 'positive|nonzero'
	);

	/**
	 * Automatically fillable fields
	 *
	 * @var  array
	 **/
	public $always = array(
		'alias'
	);

	/**
	 * Automatic fields to populate every time a row is created
	 *
	 * @var  array
	 */
	public $initiate = array(
		'created',
		'created_by'
	);

	/**
	 * Sets up additional custom rules
	 *
	 * @return  void
	 */
	public function setup()
	{
		// alias rule to ensure unique alias
		$this->addRule('alias', function($data)
		{
			$exists = self::all()
				->whereEquals('alias', $data['alias'])
				->whereEquals('object_id', $data['object_id'])
				->whereEquals('object_type', $data['object_type'])
				->where('id', '!=', $data['id'])
				->where('state', '!=', self::STATE_DELETED)
				->total();

			if ($exists)
			{
				return Lang::txt('COM_COLLECTIONS_ERROR_UNABLE_TO_CREATE_ALIAS_EXISTS');
			}
			return false;
		});
	}

	/**
	 * Generates automatic owned by field value
	 *
	 * @param   array   $data  the data being saved
	 * @return  string
	 */
	public function automaticAlias($data)
	{
		$alias = (isset($data['alias']) && $data['alias'] ? $data['alias'] : $data['title']);
		$alias = strip_tags($alias);
		$alias = trim($alias);
		if (strlen($alias) > 250)
		{
			$alias = substr($alias . ' ', 0, 250);
			$alias = substr($alias, 0, strrpos($alias, ' '));
		}
		$alias = str_replace(' ', '-', $alias);

		return preg_replace("/[^a-zA-Z0-9\-]/", '', strtolower($alias));
	}

	/**
	 * Return a formatted timestamp for created date
	 *
	 * @param   string  $as  What data to return
	 * @return  string
	 */
	public function created($as='')
	{
		$as = strtolower($as);

		if ($as == 'date')
		{
			return Date::of($this->get('created'))->toLocal(Lang::txt('DATE_FORMAT_HZ1'));
		}

		if ($as == 'time')
		{
			return Date::of($this->get('created'))->toLocal(Lang::txt('TIME_FORMAT_HZ1'));
		}

		return $this->get('created');
	}

	/**
	 * Defines a belongs to one relationship between article and user
	 *
	 * @return  object
	 */
	public function creator()
	{
		return $this->belongsToOne('Hubzero\User\User', 'created_by');
	}

	/**
	 * Get a list of responses
	 *
	 * @return  object
	 */
	public function posts()
	{
		return $this->oneToMany(__NAMESPACE__ . '\\Post', 'collection_id');
	}

	/**
	 * Get a list of likes
	 *
	 * @return  object
	 */
	public function votes()
	{
		return $this->item()->votes();
	}

	/**
	 * Get a list of chosen responses
	 *
	 * @return  object
	 */
	public function item()
	{
		return Item::all()
			->whereEquals('type', 'collection')
			->whereEquals('object_id', $this->get('id'))
			->row();
	}

	/**
	 * Store the record
	 *
	 * @return  boolean  False if error, True on success
	 */
	public function save()
	{
		// Make sure state cascades to children
		if ($this->item()->get('id'))
		{
			$this->item()->set('state', $this->get('state'));

			if (!$this->item()->save())
			{
				$this->addError($this->item()->getError());
				return false;
			}
		}

		return parent::save();
	}

	/**
	 * Delete the record and all associated data
	 *
	 * @return  boolean  False if error, True on success
	 */
	public function destroy()
	{
		// Can't delete what doesn't exist
		if ($this->isNew())
		{
			return true;
		}

		// Remove posts
		foreach ($this->posts()->rows() as $post)
		{
			if (!$post->destroy())
			{
				$this->addError($post->getError());
				return false;
			}
		}

		if (!$this->item()->destroy())
		{
			$this->addError($this->item()->getError());
			return false;
		}

		// Attempt to delete the record
		return parent::destroy();
	}

	/**
	 * Get the URL for this collection
	 *
	 * @return  string
	 */
	public function link()
	{
		return 'index.php?option=com_collections&controller=collections&task=view&id=' . $this->get('id');
	}
}
