<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

// No direct access
defined('_HZEXEC_') or die();

$this->css()
     ->js('curation.js');

Toolbar::title(Lang::txt('COM_PUBLICATIONS_PUBLICATION') . ' ' . Lang::txt('COM_PUBLICATIONS_MASTER_TYPE') . ' - ' . $this->row->type . ': ' . Lang::txt('COM_PUBLICATIONS_FIELD_CURATION_ADD_BLOCK'), 'publications');
Toolbar::save('saveblock');
Toolbar::cancel();

$params = new \Hubzero\Config\Registry($this->row->params);
$manifest  = $this->curation->_manifest;
$curParams = $manifest->params;
$blocks    = $manifest->blocks;

$blockSelection = array('active' => array());
$masterBlocks = array();
foreach ($this->blocks as $b)
{
	$masterBlocks[$b->block] = $b;
}
foreach ($blocks as $blockId => $block)
{
	$blockSelection['active'][] = $block->name;
}

?>

<form action="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller); ?>" method="post" id="item-form" name="adminForm">
	<p><a class="button" href="<?php echo Route::url('index.php?option=' . $this->option . '&controller=' . $this->controller . '&task=edit&id=' . $this->row->id); ?>"><?php echo Lang::txt('COM_PUBLICATIONS_MTYPE_BACK') . ' ' . $this->row->type . ' ' . Lang::txt('COM_PUBLICATIONS_MASTER_TYPE'); ?></a></p>

	<fieldset class="adminform">
		<legend><span><?php echo Lang::txt('COM_PUBLICATIONS_FIELD_CURATION_ADD_BLOCK'); ?></span></legend>

		<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $this->option; ?>" />
		<input type="hidden" name="controller" value="<?php echo $this->controller; ?>" />
		<input type="hidden" name="task" value="saveblock" />

		<div class="input-wrap">
			<label for="field-newblock"><?php echo Lang::txt('COM_PUBLICATIONS_CURATION_SELECT_BLOCK'); ?>:</label>
			<select name="newblock" id="field-newblock">
			<?php
			foreach ($this->blocks as $sBlock)
			{
				if (!in_array($sBlock->block, $blockSelection['active']) || $sBlock->maximum > 1)
				{
					?>
					<option value="<?php echo $sBlock->block; ?>"><?php echo $sBlock->block; ?></option>
					<?php
				}
			}
			?>
			</select>
		</div>
		<div class="input-wrap">
			<label for="field-order"><?php echo Lang::txt('COM_PUBLICATIONS_CURATION_INSERT_BLOCK_BEFORE'); ?>:</label>
			<select name="before" id="field-order">
				<?php foreach ($blocks as $blockId => $block) { ?>
					<option value="<?php echo $blockId; ?>"><?php echo $block->name; ?></option>
				<?php } ?>
			</select>
		</div>
	</fieldset>

	<?php echo Html::input('token'); ?>
</form>
