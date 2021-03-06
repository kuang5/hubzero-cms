<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

// No direct access
defined('_HZEXEC_') or die();

$this->js('managers.js');

$roles = $this->course->offering(0)->roles(array('alias' => '!student'));
$offerings = $this->course->offerings();
?>
<?php if ($this->getError()) { ?>
	<dl id="system-message">
		<dt><?php echo Lang::txt('ERROR'); ?></dt>
		<dd class="error"><?php echo implode('<br />', $this->getErrors()); ?></dd>
	</dl>
<?php } ?>
<div id="groups">
	<form action="<?php echo Route::url('index.php?option=' . $this->option  . '&controller=' . $this->controller); ?>" method="post">
		<table>
			<tbody>
				<tr>
					<td>
						<label>
							<input type="text" name="usernames" value="" />
							<?php echo Lang::txt('COM_COURSES_ENTER_USERS'); ?>
						</label>
					</td>
					<td>
						<select name="role">
						<?php foreach ($roles as $role) { ?>
							<option value="<?php echo $role->id; ?>"><?php echo $this->escape(stripslashes($role->title)); ?></option>
						<?php } ?>
						<?php
						foreach ($offerings as $offering)
						{
							$oroles = $offering->roles(array('offering_id' => $offering->get('id')));
							if (!$oroles || !count($oroles))
							{
								continue;
							}
						?>
							<optgroup label="<?php echo Lang::txt('Offering:') . ' ' . $this->escape($offering->get('title')); ?>">
							<?php foreach ($oroles as $role) { ?>
								<option value="<?php echo $role->id; ?>"><?php echo $this->escape(stripslashes($role->title)); ?></option>
							<?php } ?>
							</optgroup>
						<?php } ?>
						</select>
					</td>
					<td>
						<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
						<input type="hidden" name="controller" value="<?php echo $this->controller; ?>">
						<input type="hidden" name="tmpl" value="component" />
						<input type="hidden" name="id" value="<?php echo $this->course->get('id'); ?>" />
						<input type="hidden" name="task" value="add" />

						<input type="submit" value="<?php echo Lang::txt('COM_COURSES_ADD_USER'); ?>" />
					</td>
				</tr>
			</tbody>
		</table>

		<?php echo Html::input('token'); ?>
	</form>
	<form action="<?php echo Route::url('index.php?option=' . $this->option  . '&controller=' . $this->controller); ?>" method="post" id="adminForm">
		<table class="paramlist admintable">
			<thead>
				<tr>
					<th colspan="4">
						<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
						<input type="hidden" name="controller" value="<?php echo $this->controller; ?>">
						<input type="hidden" name="tmpl" value="component" />
						<input type="hidden" name="id" value="<?php echo $this->course->get('id'); ?>" />
						<input type="hidden" name="task" id="task" value="remove" />

						<input type="submit" name="action" value="<?php echo Lang::txt('COM_COURSES_REMOVE_USER'); ?>" />
					</th>
				</tr>
			</thead>
			<tbody>
<?php
		$managers = $this->course->managers(array(), true);
		if (count($managers) > 0)
		{
			$i = 0;
			foreach ($managers as $manager)
			{
				$u = User::getInstance($manager->get('user_id'));
				if (!is_object($u))
				{
					continue;
				}
?>
				<tr>
					<td>
						<input type="hidden" name="entries[<?php echo $i; ?>][course_id]" value="<?php echo $manager->get('course_id'); ?>" />
						<input type="hidden" name="entries[<?php echo $i; ?>][offering_id]" value="<?php echo $manager->get('offering_id', 0); ?>" />
						<input type="hidden" name="entries[<?php echo $i; ?>][section_id]" value="<?php echo $manager->get('section_id', 0); ?>" />
						<input type="hidden" name="entries[<?php echo $i; ?>][user_id]" value="<?php echo $u->get('id'); ?>" />
						<input type="checkbox" name="entries[<?php echo $i; ?>][select]" value="<?php echo $u->get('id'); ?>" />

						<!-- <input type="checkbox" name="users[]" value="<?php echo $u->get('id'); ?>" /> -->
					</td>
					<td class="paramlist_key">
						<a href="<?php echo Route::url('index.php?option=com_members&controller=members&task=edit&id=' . $u->get('id')); ?>" target="_parent">
							<?php echo $u->get('name') ? $this->escape($u->get('name')) . ' (' . $this->escape($u->get('username')) . ')' : Lang::txt('COM_COURSES_UNKNOWN'); ?>
						</a>
					</td>
					<td class="paramlist_value">
						<a href="mailto:<?php echo $this->escape($u->get('email')); ?>"><?php echo $this->escape($u->get('email')); ?></a>
					</td>
					<td>
						<select name="entries[<?php echo $i; ?>][role_id]" class="entry-role">
						<?php foreach ($roles as $role) { ?>
							<option value="<?php echo $role->id; ?>"<?php if ($manager->get('role_id') == $role->id) { echo ' selected="selected"'; } ?>><?php echo $this->escape(stripslashes($role->title)); ?></option>
						<?php } ?>
						<?php
						foreach ($offerings as $offering)
						{
							$oroles = $offering->roles(array('offering_id' => $offering->get('id')));
							if (!$oroles || !count($oroles))
							{
								continue;
							}
						?>
							<optgroup label="<?php echo Lang::txt('Offering:') . ' ' . $this->escape($offering->get('title')); ?>">
							<?php foreach ($oroles as $role) { ?>
								<option value="<?php echo $role->id; ?>"<?php if ($manager->get('role_id') == $role->id) { echo ' selected="selected"'; } ?>><?php echo $this->escape(stripslashes($role->title)); ?></option>
							<?php } ?>
							</optgroup>
						<?php } ?>
						</select>
					</td>
				</tr>
<?php
				$i++;
			}
		}
?>
			</tbody>
		</table>

		<?php echo Html::input('token'); ?>
	</form>
</div>